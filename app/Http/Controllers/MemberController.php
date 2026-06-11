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
     * View Member Reports
     */
    public function reports()
    {
        $family = Auth::user()->family;
        $devices = $family ? $family->devices : [];

        return view('member.reports', compact('devices'));
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
