<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use App\Models\Device;
use App\Models\DeviceActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    /**
     * Show Admin Dashboard
     */
    public function dashboard()
    {
        return view('admin.dashboard', [
            'families'       => Family::with('members', 'devices')->get(),
            'devices'        => Device::with('family.members')->get(),
            'users'          => User::all(),
            'families_total' => Family::count(),
            'devices_total'  => Device::count(),
            'users_total'    => User::count(),
            'reports_total'  => Device::whereNotNull('family_id')->count(),
        ]);
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
        // Ensure we return *all* families even if relationships are empty.
        $families = Family::with(['members', 'devices', 'parent'])->get();
        $parents = User::whereHas('role', fn($q) => $q->where('name', 'family_parent'))->get();

        // Must include NULL-able family_id so the UI can calculate unassigned devices.
        $allDevices = Device::select(['id', 'device_name', 'device_token', 'family_id'])->get();

        return view('admin.families', compact('families', 'parents', 'allDevices'));
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
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|unique:users,email',
            'parent_password' => 'required|string|min:6|confirmed',
        ], [
            'parent_password.confirmed' => 'The family parent password confirmation does not match.',
        ]);

        $familyParentRoleId = \App\Models\Role::where('name', 'family_parent')->value('id');
        if (!$familyParentRoleId) {
            return redirect()->back()->with('error', 'Role "family_parent" not found.');
        }

        try {
            \DB::transaction(function () use ($request, $familyParentRoleId) {
                $parent = \App\Models\User::create([
                    'name' => $request->parent_name,
                    'email' => $request->parent_email,
                    'password' => bcrypt($request->parent_password),
                    'role_id' => $familyParentRoleId,
                ]);

                \App\Models\Family::create([
                    'family_name' => $request->family_name,
                    'parent_id' => $parent->id,
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Family creation DB failed: ' . $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Family creation failed: ' . $e->getMessage());
        }


        return redirect()->back()->with('success', 'Family created successfully. Family parent account created.');
    }



    /**
     * Update an existing family
     */
    public function updateFamily(Request $request, $family_id)
    {
        $request->validate([
            'family_name' => 'required|string|max:255',

            // Parent account editable fields (optional where password is optional)
            'parent_name' => 'nullable|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_password' => 'nullable|string|min:6|confirmed',
        ]);

        $family = Family::findOrFail($family_id);

        // IMPORTANT: Admin cannot change the family parent.
        // Only family_name and parent account details are editable.
        $family->family_name = $request->family_name;
        $family->save();

        $parent = \App\Models\User::findOrFail($family->parent_id);


        if ($request->filled('parent_name')) {
            $parent->name = $request->parent_name;
        }

        if ($request->filled('parent_email')) {
            // Prevent changing to an email used by another user.
            $email = $request->parent_email;
            $emailOwnerId = \App\Models\User::where('email', $email)->where('id', '!=', $parent->id)->value('id');
            if ($emailOwnerId) {
                return redirect()->back()->with('error', 'Parent email is already in use by another account.');
            }
            $parent->email = $email;
        }

        if ($request->filled('parent_password')) {
            $parent->password = bcrypt($request->parent_password);
        }

        $parent->save();

        return redirect()->back()->with('success', 'Family updated successfully');
    }

    /**
     * Delete a family
     */
    public function deleteFamily(Request $request, $family_id)
    {
        $family = Family::with(['devices', 'members'])->findOrFail($family_id);

        // Cascades will delete devices (DB foreign key). Members are users; family_id has onDelete cascade too.
        $family->delete();

        return redirect()->back()->with('success', 'Family deleted successfully');
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
     * Update a device (name only; token preserved)
     */
    public function updateDevice(Request $request, $device_id)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        $device = Device::findOrFail($device_id);
        $device->device_name = $request->device_name;
        $device->save();

        return redirect()->back()->with('success', 'Device updated successfully');
    }

    /**
     * Delete a device
     */
    public function deleteDevice(Request $request, $device_id)
    {
        $device = Device::findOrFail($device_id);
        $device->delete();

        return redirect()->back()->with('success', 'Device deleted successfully');
    }

    /**
     * Unassign device from a family (set family_id = null)
     */
    public function unassignDevice(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        $device->family_id = null;
        $device->save();

        return redirect()->back()->with('success', 'Device unassigned successfully');
    }

    /**
     * Admin: assign device to the family_parent user (stored in devices.user_id)
     */
    public function assignDeviceToFamilyParent(Request $request)
    {

        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'family_id' => 'required|exists:families,id',
        ]);

        $family = Family::with('parent')->findOrFail($request->family_id);
        $device = Device::findOrFail($request->device_id);

        // Ensure device belongs to the family we are assigning.
        if ((int)$device->family_id !== (int)$family->id) {
            return redirect()->back()->with('error', 'Device does not belong to this family.');
        }

        $parent = $family->parent;
        if (!$parent) {
            return redirect()->back()->with('error', 'Family parent not found.');
        }

        // Assigning to family_parent means the device is assigned to this family.
        // Keep member assignment as-is (or clear if you want a fresh assignment flow).
        $device->family_id = $family->id;
        $device->save();


        return redirect()->back()->with('success', 'Device assigned successfully');

    }

    /**
     * Admin: unassign device from family_parent (stored in devices.user_id)
     */
    public function unassignDeviceFromFamilyParent(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'family_id' => 'required|exists:families,id',
        ]);

        $family = Family::with('parent')->findOrFail($request->family_id);
        $device = Device::findOrFail($request->device_id);

        if ((int)$device->family_id !== (int)$family->id) {
            return redirect()->back()->with('error', 'Device does not belong to this family.');
        }

        // Unassigning from family_parent should also unassign the member.
        $device->family_id = null;
        $device->user_id = null;
        $device->save();

        return redirect()->back()->with('success', 'Device unassigned successfully');


    }


    /**
     * Legacy: View reports across all devices
     */

    public function reports()
    {
        $devices = Device::with('family')->get();
        $families = Family::with('members')->get();

        return view('admin.reports', compact('devices', 'families'));
    }

    /**
     * Device reports with filters + actions
     */
    public function deviceReports(Request $request)
    {
        $query = Device::with('family');

        $familyId = $request->input('family_id');
        if (!empty($familyId)) {
            $query->where('family_id', $familyId);
        }

        $q = trim((string) $request->input('q'));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
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

        $devices = $query->orderByDesc('created_at')->get();
        $families = Family::orderBy('family_name')->get(['id', 'family_name']);

        return view('admin.device_reports', compact('devices', 'families'));
    }

    /**
     * Family reports with filters + actions
     */
    public function familyReports(Request $request)
    {
        $query = Family::with(['members', 'devices', 'parent']);

        $q = trim((string) $request->input('q'));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('family_name', 'LIKE', "%{$q}%")
                    ->orWhereHas('parent', function ($p) use ($q) {
                        $p->where('name', 'LIKE', "%{$q}%")
                          ->orWhere('email', 'LIKE', "%{$q}%");
                    });
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

        $families = $query->orderByDesc('created_at')->get();
        $parents = User::whereHas('role', fn($q) => $q->where('name', 'family_parent'))->get();

        return view('admin.family_reports', compact('families', 'parents'));
    }

    /**
     * Mega / General Incident Report (Admin)
     * Returns device activities joined with device + family info.
     */
    public function megaReports(Request $request)
    {
        $query = DeviceActivity::query()->with(['device.family']);

        $familyId = $request->input('family_id');
        if (!empty($familyId)) {
            $query->whereHas('device', function ($q) use ($familyId) {
                $q->where('family_id', $familyId);
            });
        }

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
        $families = Family::orderBy('family_name')->get(['id', 'family_name']);

        return view('admin.mega_report', compact('activities', 'families'));
    }

}

