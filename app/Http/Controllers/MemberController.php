<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Show Member Dashboard
     */
    public function dashboard()
    {
        $family = Auth::user()->family;
        $devices = $family ? $family->devices : [];
        $notifications = []; // later: fetch from a notifications table

        return view('member.dashboard', compact('devices', 'notifications'));
    }

    /**
     * View member incident reports.
     * Must match admin mega report but restricted to this member's assigned devices.
     */
    public function reports(Request $request)
    {
        $member = Auth::user();

        $query = \App\Models\DeviceActivity::query()->with(['device.family']);

        $query->whereHas('device', function ($q) use ($member) {
            $q->where('user_id', $member->id);
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
     * View Member Notifications
     */
    public function notifications()
    {
        $notifications = []; // placeholder, later connect to DB
        return view('member.notifications', compact('notifications'));
    }
}
