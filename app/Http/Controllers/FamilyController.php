<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    /**
     * Show Family Dashboard
     */
    public function dashboard()
    {
        $family = Auth::user()->family; // assuming User belongsTo Family
        $devices = $family ? $family->devices : [];
        $members = $family ? $family->members : [];

        return view('family.dashboard', compact('devices', 'members'));
    }

    /**
     * Add a new member to the family
     */
    public function addMember(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        $family = Auth::user()->family;

        $memberRoleId = \App\Models\Role::where('name', 'family_member')->value('id');

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->input('password') ?: 'password123'),
            'role_id'   => $memberRoleId,
            'family_id' => $family?->id,
        ]);

        return redirect()->back()->with('success', 'Family member added successfully');

    }

    /**
     * Assign role to a family member
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->back()->with('success', 'Role assigned successfully');
    }

    /**
     * View family devices
     */
    public function devices()
    {
        $family = Auth::user()->family;
        $devices = $family ? $family->devices : [];

        return view('family.devices', compact('devices'));
    }

    /**
     * View family reports
     */
    public function reports()
    {
        $family = Auth::user()->family;
        $devices = $family ? $family->devices : [];

        return view('family.reports', compact('devices'));
    }
}
