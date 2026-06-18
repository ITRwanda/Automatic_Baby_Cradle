<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaregiverController extends Controller
{
    /**
     * Caregiver dashboard.
     * Shows only devices assigned to the logged-in caregiver.
     */
    public function dashboard()
    {
        $caregiver = Auth::user();
        $devices = Device::query()
            ->where('user_id', $caregiver->id)
            ->get();

        return view('member.dashboard', [
            'devices' => $devices,
            'notifications' => [],
        ]);
    }

    /**
     * Caregiver reports.
     * Shows incident activities for devices assigned to this caregiver.
     */
    public function reports(Request $request)
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

        $activities = $query->orderByDesc('created_at')->get();

        return view('member.reports', compact('activities'));
    }

    /**
     * Placeholder notifications page.
     */
    public function notifications()
    {
        $notifications = []; // later: fetch from notifications table
        return view('member.notifications', compact('notifications'));
    }

    /**
     * Caregiver assigns/unassigns are NOT allowed.
     * Device assignment should be controlled by family_parent.
     */
    public function assignDevice(Request $request)
    {
        return redirect()->back()->with('error', 'Unauthorized action for caregiver.');
    }

    public function unassignDevice(Request $request)
    {
        return redirect()->back()->with('error', 'Unauthorized action for caregiver.');
    }
}

