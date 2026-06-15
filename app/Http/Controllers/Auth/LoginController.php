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

            $roleName = Auth::user()->role->name; // use relationship

            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            } elseif ($roleName === 'family_parent') {
                return redirect()->route('family.dashboard')->with('success', 'Welcome Family Parent!');
            } elseif ($roleName === 'family_member') {
                return redirect()->route('member.dashboard')->with('success', 'Welcome Family Member!');
            }
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
