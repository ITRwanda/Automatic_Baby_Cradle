<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DeviceActivityController extends Controller
{
    /**
     * Arduino/ESP8266 endpoint to push an activity.
     *
     * Expected JSON body:
     * {
     *   "device_token": "...",
     *   "event_type": "cry_detected|dht|...",
     *   "payload": "optional plain text (or JSON string)"
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_token' => ['required', 'string'],
            'event_type' => ['required', 'string', 'max:255'],
            'payload' => ['nullable', 'string'],
        ]);

        $device = Device::where('device_token', $validated['device_token'])->first();

        if (!$device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid device_token',
            ], 404);
        }

        $activity = DeviceActivity::create([
            'device_id' => $device->id,
            'event_type' => $validated['event_type'],
            'payload' => $validated['payload'] ?? null,
        ]);

        // Notify family parent + caregiver on baby crying / cradle events
        // (email only, only when recipient has an email)
        try {
            $event = (string) $activity->event_type;
            $shouldNotify = in_array($event, ['cry_detected', 'cradle'], true);

            if ($shouldNotify) {
                $deviceWithRels = $device->load(['family.parent', 'user']);

                $recipients = collect([
                    $deviceWithRels->family?->parent,
                    $deviceWithRels->user, // caregiver assigned to the device
                ])->filter(fn($u) => $u && !empty($u->email));

                // If both map to same user, make unique by id
                $recipients = $recipients->unique('id')->values();

                if ($recipients->count() === 0) {
                    \Log::warning('DeviceActivity: no recipients email found', [
                        'device_token' => $validated['device_token'],
                        'event_type' => $event,
                        'family_parent_email' => $deviceWithRels->family?->parent?->email,
                        'caregiver_email' => $deviceWithRels->user?->email,
                    ]);
                }

                if ($recipients->count() > 0) {
                    $deviceName = $deviceWithRels->device_name ?? 'Unknown Device';
                    $deviceToken = $deviceWithRels->device_token ?? 'Unknown Token';

                    $assignedCaregiver = $deviceWithRels->user;
                    $caregiverName = $assignedCaregiver->name ?? $assignedCaregiver->email ?? 'Not assigned';
                    $caregiverEmail = $assignedCaregiver->email ?? 'N/A';

                    $mailBody =
                        "Hello!\n\n" .
                        "You have received an important baby-care alert from IoTBabyCradle.\n\n" .
                        "=== Alert Details ===\n" .
                        "Event: {$event}\n" .
                        "Device Name: {$deviceName}\n" .
                        "Device Token: {$deviceToken}\n" .
                        "Assigned Caregiver: {$caregiverName}\n" .
                        "Caregiver Email: {$caregiverEmail}\n" .
                        "Time: " . now()->toDateTimeString() . "\n\n" .
                        "Origin: IoTBabyCradle (automated notification)\n\n" .
                        "Next steps: Please review the baby’s status and contact the assigned caregiver if immediate action is needed.";

                    try {
                        Mail::raw($mailBody, function ($m) use ($recipients, $event) {
                            $m->subject("IoTBabyCradle Alert — {$event}");
                            foreach ($recipients as $recipient) {
                                $m->to($recipient->email, $recipient->name ?? $recipient->email);
                            }
                        });
                    } catch (\Throwable $mailException) {
                        \Log::error('DeviceActivity: failed to send mail', [
                            'device_token' => $validated['device_token'],
                            'event_type' => $event,
                            'recipients' => $recipients->pluck('email')->all(),
                            'error' => $mailException->getMessage(),
                        ]);
                        throw $mailException; // will be caught by outer catch
                    }
                }
            }
        } catch (\Throwable $e) {
            // Do not break API if notifications fail
            \Log::error('DeviceActivity: notification flow failed', [
                'device_token' => $validated['device_token'] ?? null,
                'event_type' => $activity->event_type ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'activity_id' => $activity->id,
            'device_id' => $device->id,
            'event_type' => $activity->event_type,
        ], 201);

    }
}

