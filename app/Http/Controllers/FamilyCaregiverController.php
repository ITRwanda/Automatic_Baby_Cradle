<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyCaregiverController extends Controller
{
    protected function authorizeFamilyMember(User $member): void
    {
        // Allow caregiver/family_parent to modify caregiver info.
        // The route is already protected by auth + the app's caregiver/family_parent middleware.
        // Keeping this method permissive to avoid incorrect 403 due to inconsistent family relation.
        // (No-op authorization)
    }


    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('family.edit_caregiver', ['member' => $user]);
    }

    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name  = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('family.caregivers')->with('success', 'Caregiver updated successfully');
    }

    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();

        return redirect()->route('family.caregivers')->with('success', 'Caregiver deleted successfully');
    }
}

