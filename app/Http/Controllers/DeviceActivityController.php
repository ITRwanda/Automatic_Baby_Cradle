<?php

namespace App\Http\Controllers;

use App\Mail\IncidentAlertMail;
use App\Models\Device;
use App\Models\DeviceActivity;
use App\Models\IncidentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DeviceActivityController extends Controller
{
    /**
     * Arduino / ESP32-CAM endpoint — receive a sensor event.
     *
     * POST /api/device-activities
     * Body (JSON):
     * {
     *   "device_token" : "uuid",
     *   "event_type"   : "cry_detected | dht | cradle",
     *   "payload"      : "{\"temperature\":28,\"humidity\":62,\"sound_level\":720}"
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_token' => ['required', 'string'],
            'event_type'   => ['required', 'string', 'max:255'],
            'payload'      => ['nullable', 'string'],
        ]);

        // ── 1. Resolve device ────────────────────────────────
        $device = Device::where('device_token', $validated['device_token'])
                        ->with(['family.parent', 'user'])  // eager-load now, once
                        ->first();

        if (!$device) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid device_token',
            ], 404);
        }

        // ── 2. Persist the activity ──────────────────────────
        $activity = DeviceActivity::create([
            'device_id'  => $device->id,
            'event_type' => $validated['event_type'],
            'payload'    => $validated['payload'] ?? null,
        ]);

        // ── 3. Decide whether this event needs notification ──
        $event = strtolower(trim((string) $activity->event_type));

        // Determine if a real incident occurred (not just a routine heartbeat)
        $isIncident = $this->isIncidentEvent($event, $activity->payload);

        if ($isIncident) {
            $this->notifyRecipients($device, $activity, $event);
        }

        return response()->json([
            'status'      => 'success',
            'activity_id' => $activity->id,
            'device_id'   => $device->id,
            'event_type'  => $activity->event_type,
            'notified'    => $isIncident,
        ], 201);
    }

    // ── Mark a single in-app notification as read ────────────
    public function markRead(Request $request, int $id)
    {
        $notification = IncidentNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['status' => 'ok']);
    }

    // ── Mark ALL notifications for current user as read ──────
    public function markAllRead()
    {
        IncidentNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    // ────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ────────────────────────────────────────────────────────

    /**
     * Decide whether this event qualifies as an incident that needs
     * immediate notification.
     *
     * - cry_detected  → always
     * - cradle        → always
     * - dht           → only if temp_alert OR humid_alert flag is true in payload
     */
    private function isIncidentEvent(string $event, ?string $payloadRaw): bool
    {
        if (in_array($event, ['cry_detected', 'cradle'], true)) {
            return true;
        }

        if ($event === 'dht') {
            $decoded = $this->decodePayload($payloadRaw);
            return !empty($decoded['temp_alert']) || !empty($decoded['humid_alert']);
        }

        return false;
    }

    /**
     * Build notification title + body, then dispatch email + in-app alert
     * to both the family parent and the assigned caregiver.
     */
    private function notifyRecipients(Device $device, DeviceActivity $activity, string $event): void
    {
        $familyParent = $device->family?->parent;
        $caregiver    = $device->user;          // device.user_id = assigned caregiver

        // Collect unique recipients
        $recipients = collect([$familyParent, $caregiver])
            ->filter(fn($u) => $u !== null)
            ->unique('id')
            ->values();

        if ($recipients->isEmpty()) {
            Log::warning('[Incident] No recipients found for notification', [
                'device_id'  => $device->id,
                'event_type' => $event,
            ]);
            return;
        }

        [$title, $body] = $this->buildNotificationText($event, $device, $activity);

        foreach ($recipients as $user) {
            // ── a) In-app notification ───────────────────────
            try {
                IncidentNotification::create([
                    'user_id'             => $user->id,
                    'device_activity_id'  => $activity->id,
                    'device_id'           => $device->id,
                    'event_type'          => $event,
                    'title'               => $title,
                    'body'                => $body,
                ]);
            } catch (\Throwable $e) {
                Log::error('[Incident] Failed to save in-app notification', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }

            // ── b) Email notification ────────────────────────
            if (!empty($user->email)) {
                try {
                    Mail::to($user->email, $user->name ?? $user->email)
                        ->send(new IncidentAlertMail($activity, $user));

                    Log::info('[Incident] Email sent', [
                        'to'         => $user->email,
                        'event_type' => $event,
                        'device_id'  => $device->id,
                    ]);
                } catch (\Throwable $e) {
                    // Log but never crash the API response
                    Log::error('[Incident] Email failed', [
                        'to'    => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Generate a human-readable title and body for the notification.
     */
    private function buildNotificationText(string $event, Device $device, DeviceActivity $activity): array
    {
        $deviceName = $device->device_name ?? 'Unknown Device';
        $decoded    = $this->decodePayload($activity->payload);
        $time       = now()->format('H:i, d M Y');

        switch ($event) {
            case 'cry_detected':
                $sound = $decoded['sound_level'] ?? null;
                $title = '🔔 Baby Cry Detected';
                $body  = "Your baby was detected crying on device \"{$deviceName}\" at {$time}.";
                if ($sound !== null) {
                    $body .= " Sound level: {$sound}.";
                }
                $body .= ' Please check on the baby immediately.';
                break;

            case 'dht':
                $temp    = $decoded['temperature'] ?? null;
                $hum     = $decoded['humidity']    ?? null;
                $tAlert  = !empty($decoded['temp_alert']);
                $hAlert  = !empty($decoded['humid_alert']);
                $details = [];
                if ($tAlert && $temp !== null) $details[] = "temperature {$temp}°C is out of safe range";
                if ($hAlert && $hum  !== null) $details[] = "humidity {$hum}% is out of safe range";
                $title   = '🌡️ Environment Alert';
                $body    = "Device \"{$deviceName}\" at {$time}: " . implode(' and ', $details) . '. Please adjust the room environment.';
                break;

            case 'cradle':
                $title = '🛏️ Cradle Event';
                $body  = "A cradle motion event was recorded on device \"{$deviceName}\" at {$time}.";
                break;

            default:
                $title = '⚠️ Device Alert';
                $body  = "An alert ({$event}) was recorded on device \"{$deviceName}\" at {$time}.";
        }

        return [$title, $body];
    }

    /** Safely decode a JSON payload string. Returns [] on failure. */
    private function decodePayload(?string $raw): array
    {
        if (!$raw) return [];
        $trimmed = trim($raw);
        if (!str_starts_with($trimmed, '{')) return [];
        $decoded = json_decode($trimmed, true);
        return is_array($decoded) ? $decoded : [];
    }
}
