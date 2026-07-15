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
     */
    public function dashboard()
    {
        $caregiver = Auth::user();

        $devices = Device::query()
            ->where('user_id', $caregiver->id)
            ->get();

        // Live in-app notifications
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
     * Caregiver reports.
     */
    public function reports(Request $request)
    {
        $activities = $this->caregiverReportsData($request);

        return view('member.reports', compact('activities'));
    }

    protected function caregiverReportsData(Request $request)
    {
        $caregiver = Auth::user();

        $query = DeviceActivity::query()->with(['device.family']);

        $query->whereHas('device', function ($q) use ($caregiver) {
            $q->where('user_id', $caregiver->id);
        });

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
     * Caregiver/member report export CSV
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
     * Caregiver/member report export PDF (print-view HTML)
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
     * Notifications page — paginated, with unread count.
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
        return redirect()->back()->with('error', 'Unauthorized action for caregiver.');
    }

    public function unassignDevice(Request $request)
    {
        return redirect()->back()->with('error', 'Unauthorized action for caregiver.');
    }
}
