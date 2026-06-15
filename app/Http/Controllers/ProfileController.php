<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile settings form
     */
    public function edit()
    {
        return view('profile.settings');
    }

    /**
     * Update basic profile information (name, email)
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password securely
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Password updated successfully.');
    }

    /**
     * Deactivate account (soft delete or flag)
     */
    public function deactivate(Request $request)
    {
        $user = Auth::user();
        $user->update(['active' => false]); // assumes 'active' column exists

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deactivated.');
    }
}
