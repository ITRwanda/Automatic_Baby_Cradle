<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;


class FamilyController extends Controller
{
    /**
     * Show Family Dashboard
     */
    public function dashboard()
    {
        $user   = Auth::user();
        $family = $user->family;

        $devices = $family ? $family->devices()->get() : collect();
        $members = $family ? $family->members()->get() : collect();

        // Live in-app notifications for this family parent
        $notifications = \App\Models\IncidentNotification::where('user_id', $user->id)
            ->with('device')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $unreadCount = \App\Models\IncidentNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // Legacy alias
        $alerts = $notifications;

        return view('family.dashboard', compact('devices', 'members', 'alerts', 'notifications', 'unreadCount'));
    }

    /**
     * Add a new member to the family
     */
    public function caregivers()
    {

        $family = Auth::user()->family;
        $members = $family ? $family->members()->get() : collect();
        $devices = $family ? $family->devices()->get() : collect();

        // Keep variable names consistent with existing Blade(s)
        return view('family.caregivers', compact('members', 'devices'));

    }

    // Backward-compatible alias (was member listing)
    // NOTE: this method must not be duplicated; see bottom-of-file.
    public function members()
    {
        return $this->caregivers();
    }






    /**
     * Assign a device to a family caregiver.
     */
    public function assignDeviceToCaregiver(Request $request)
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
     * Add a new caregiver to the family
     */
    public function addCaregiver(Request $request)
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

        $memberRoleId = \App\Models\Role::where('name', 'caregiver')->value('id');

        if (!$memberRoleId) {
            return redirect()->back()->with('error', 'Role "caregiver" not found. Please seed the roles table first.');
        }


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
     * View family (parent/member) incident reports.
     * Must match admin mega report but restricted to this family's devices.
     */
    public function reports(Request $request)
    {
        $activities = $this->familyReportsData($request);

        return view('family.reports', compact('activities'));
    }

    protected function familyReportsData(Request $request)
    {
        $family = Auth::user()->family;
        if (!$family) {
            return collect();
        }

        $query = \App\Models\DeviceActivity::query()->with(['device.family']);

        $query->whereHas('device', function ($q) use ($family) {
            $q->where('family_id', $family->id);
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
     * Family report export CSV
     */
    public function exportFamilyReportsCsv(Request $request)
    {
        $activities = $this->familyReportsData($request);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="family_incident_report.csv"',
        ];

        $stream = function () use ($activities) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Time',
                'Device Name',
                'Device Token',
                'Family',
                'Event Type',
                'Payload',
            ]);

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
     * Family report export PDF (print-view HTML)
     */
    public function exportFamilyReportsPdf(Request $request)
    {
        $activities = $this->familyReportsData($request);

        return View::make('family.exports.family_report_print', [
            'activities' => $activities,
            'reportTitle' => 'Family Incident Report',
            'filters' => [
                'q' => $request->input('q'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
            ],
        ]);
    }



    /**
     * Unassign a device from a family member (set devices.user_id = null)
     */
    public function unassignDeviceFromCaregiver(Request $request)
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

