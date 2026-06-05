<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller {
    public function registerDevice(Request $request) {
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        $device = Device::create([
            'device_name' => $request->device_name,
            'device_token' => Str::uuid(), // auto-generate unique token
        ]);

        return redirect()->back()->with('success', 'Device registered with token: ' . $device->device_token);
    }
}

