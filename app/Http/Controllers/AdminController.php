<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show Admin Dashboard
     */
    public function dashboard()
    {
        $families = Family::with('members', 'devices')->get();
        $devices = Device::all();
        $users = User::all();
        $reports = Device::with('family')->get(); // or your actual Report model

        return view('admin.dashboard', compact('families', 'devices', 'users', 'reports'));
    }



    /**
     * Register a new device with unique token
     */
    public function registerDevice(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        $device = Device::create([
            'device_name' => $request->device_name,
            'device_token' => Str::uuid(), // auto-generate unique token
        ]);

        return redirect()->back()->with('success', 'Device registered with token: ' . $device->device_token);
    }
    public function families()
    {
        $families = Family::with('members')->get();
        return view('admin.families', compact('families'));
    }

    public function devices()
    {
        $devices = Device::all();
        return view('admin.devices', compact('devices'));
    }

    /**
     * Create a new family
     */
    public function createFamily(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string|max:255',
        ]);

        Family::create([
            'family_name' => $request->family_name,
        ]);

        return redirect()->back()->with('success', 'Family created successfully');
    }

    /**
     * Assign device to a family
     */
    public function assignDevice(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'family_id' => 'required|exists:families,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        $device->family_id = $request->family_id;
        $device->save();

        return redirect()->back()->with('success', 'Device assigned successfully');
    }

    /**
     * View reports across all devices
     */
    public function reports()
    {
        $devices = Device::with('family')->get();
        // Example: later you can attach IoT activity logs here
        return view('admin.reports', compact('devices'));
    }
}
