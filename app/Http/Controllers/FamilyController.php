<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FamilyController extends Controller {
    public function dashboard() {
        $family = auth()->user()->family;
        $devices = $family->devices;
        $members = $family->members;
        return view('family.dashboard', compact('devices','members'));
    }

    public function addMember(Request $request) {
        $family = auth()->user()->family;
        if ($family->members()->count() >= 3) {
            return back()->with('error','Maximum 3 members allowed');
        }
        $family->members()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'family_member',
        ]);
        return back()->with('success','Member added successfully');
    }
}
