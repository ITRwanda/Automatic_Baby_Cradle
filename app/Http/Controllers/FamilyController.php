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

        $devices = $family ? $family->devices()->get() : collect();
        $members = $family ? $family->members()->get() : collect();

        // Alerts are device-level; aggregate them for the family dashboard.
        $alerts = $devices->flatMap(function ($device) {
            return $device->alerts ?? collect();
        });

        return view('family.dashboard', compact('devices', 'members', 'alerts'));
    }

    /**
     * Add a new member to the family
     */
    public function members()
    {
        $family = Auth::user()->family;
        $members = $family ? $family->members()->get() : collect();
        $devices = $family ? $family->devices()->get() : collect();

        return view('family.members', compact('members', 'devices'));
    }

    /**
     * Assign a device to a family member.
     */
    public function assignDeviceToMember(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required|exists:devices,id',
        ]);

        $family = Auth::user()->family;
        if (!$family) {
            return redirect()->back()->with('error', 'Family not found.');
        }

        $member = User::where('family_id', $family->id)->where('id', $request->user_id)->first();
        if (!$member) {
            return redirect()->back()->with('error', 'Invalid member for this family.');
        }

        $device = Device::where('family_id', $family->id)->where('id', $request->device_id)->first();
        if (!$device) {
            return redirect()->back()->with('error', 'Invalid device for this family.');
        }

        // Assign device to this member.
        $device->family_id = $family->id;
        $device->user_id = $member->id;
        $device->save();


        return redirect()->back()->with('success', 'Device assignment updated.');

    }


    public function roles()
    {
        $family = Auth::user()->family;
        $members = $family ? $family->members()->get() : collect();
        $roles = \App\Models\Role::all();

        return view('family.roles', compact('members', 'roles'));
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

        // Limit how many family members a family can have.
        // Family parent can add family members using the add-member form.
        $maxFamilyMembers = 3;

        $memberRoleId = \App\Models\Role::where('name', 'family_member')->value('id');

        $existingFamilyMembersCount = User::where('family_id', $family?->id)
            ->where('role_id', $memberRoleId)
            ->count();

        if ($existingFamilyMembersCount >= $maxFamilyMembers) {
            return redirect()->back()->with('error', "A family can only have up to {$maxFamilyMembers} family members.");
        }


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

    /**
     * Unassign a device from a family member (set devices.user_id = null)
     */
    public function unassignDeviceFromMember(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $family = Auth::user()->family;
        if (!$family) {
            return redirect()->back()->with('error', 'Family not found.');
        }

        $device = Device::where('family_id', $family->id)->where('id', $request->device_id)->first();
        if (!$device) {
            return redirect()->back()->with('error', 'Invalid device for this family.');
        }

        $device->user_id = null;
        $device->save();

        return redirect()->back()->with('success', 'Device unassigned successfully.');
    }
}

