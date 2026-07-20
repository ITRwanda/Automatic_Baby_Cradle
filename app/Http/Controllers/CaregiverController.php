<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceActivity;
use App\Models\IncidentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class CaregiverController extends Controller
{
    /**
     * Caregiver dashboard.
     * Shows all devices assigned to this caregiver via the pivot table.
     */
    public function dashboard()
    {
        $caregiver = Auth::user();

        // Devices assigned via many-to-many pivot
        $devices = $caregiver->assignedDevices()->with('family')->get();

        // In-app notifications
        $notifications = IncidentNotification::where('user_id', $caregiver->id)
            ->with('device')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $unreadCount = IncidentNotification::where('user_id', $caregiver->id)
            ->whereNull('read_at')
            ->count();

        return view('member.dashboard', compact('devices', 'notifications', 'unreadCount'));
    }

    /**
     * Caregiver reports — scoped to devices assigned via pivot.
     */
    public function reports(Request $request)
    {
        $activities = $this->caregiverReportsData($request);
        return view('member.reports', compact('activities'));
    }

    protected function caregiverReportsData(Request $request)
    {
        $caregiver = Auth::user();

        // Get IDs of devices assigned to this caregiver via pivot
        $deviceIds = $caregiver->assignedDevices()->pluck('devices.id');

        $query = DeviceActivity::query()
            ->with(['device.family', 'device.caregivers'])
            ->whereIn('device_id', $deviceIds);

        $q = trim((string) $request->input('q'));
        if ($q !== '') {
            $query->whereHas('device', function ($sub) use ($q) {
                $sub->where('device_name', 'LIKE', "%{$q}%")
                    ->orWhere('device_token', 'LIKE', "%{$q}%");
            });
        }

        $from = $request->input('from');
        if (!empty($from)) {
            $query->whereDate('created_at', '>=', $from);
        }

        $to = $request->input('to');
        if (!empty($to)) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Export CSV
     */
    public function exportCaregiverReportsCsv(Request $request)
    {
        $activities = $this->caregiverReportsData($request);

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="caregiver_incident_report.csv"',
        ];

        $stream = function () use ($activities) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Time', 'Device Name', 'Device Token', 'Family', 'Event Type', 'Payload']);
            foreach ($activities as $a) {
                fputcsv($out, [
                    $a->created_at?->format('Y-m-d H:i:s'),
                    $a->device->device_name ?? '—',
                    $a->device->device_token ?? '—',
                    optional($a->device->family)->family_name ?? 'Unassigned',
                    $a->event_type ?? '',
                    $a->payload ?? '',
                ]);
            }
            fclose($out);
        };

        return Response::stream($stream, 200, $headers);
    }

    /**
     * Export PDF
     */
    public function exportCaregiverReportsPdf(Request $request)
    {
        $activities = $this->caregiverReportsData($request);

        return View::make('member.exports.member_report_print', [
            'activities'  => $activities,
            'reportTitle' => 'Caregiver Incident Report',
            'filters'     => [
                'q'    => $request->input('q'),
                'from' => $request->input('from'),
                'to'   => $request->input('to'),
            ],
        ]);
    }

    /**
     * Notifications page — paginated.
     */
    public function notifications()
    {
        $caregiver = Auth::user();

        $notifications = IncidentNotification::where('user_id', $caregiver->id)
            ->with('device')
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = IncidentNotification::where('user_id', $caregiver->id)
            ->whereNull('read_at')
            ->count();

        return view('member.notifications', compact('notifications', 'unreadCount'));
    }

    public function assignDevice(Request $request)
    {
        return redirect()->back()->with('error', 'Device assignment is managed by the family parent.');
    }

    public function unassignDevice(Request $request)
    {
        return redirect()->back()->with('error', 'Device assignment is managed by the family parent.');
    }
}
