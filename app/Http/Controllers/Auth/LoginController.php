<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role; // may be null if role_id is invalid
            $roleName = $role?->name;

            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            }

            if ($roleName === 'family_parent') {
                return redirect()->route('family.dashboard')->with('success', 'Welcome Family Parent!');
            }

            if ($roleName === 'family_member') {
                return redirect()->route('member.dashboard')->with('success', 'Welcome Family Member!');
            }

            // If auth succeeded but role isn't mapped, try to redirect by common role slugs.
            // (Prevents lockout when roles exist in DB but redirect mapping is incomplete.)
            if ($roleName === 'caregiver') {
                return redirect()->route('caregiver.dashboard')->with('success', 'Welcome Caregiver!');
            }

            // Fallback message
            return back()->with('error', "Your account role '{$roleName}' is not recognized. Please contact admin.");

        }

        return back()->with('error', 'Invalid credentials provided.');

    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
