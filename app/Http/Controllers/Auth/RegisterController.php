<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $registerAs = $request->input('register_as');

        // Only Admin is allowed to self-register in this system.
        // Family Parent + Family Members must be created/managed by Admin.
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'register_as' => 'nullable|in:admin',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            return back()->with('error', 'Required role is missing. Please seed roles first.');
        }

        try {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $role->id,
                'family_id' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Registration failed. Please try again.');
        }

        return redirect()->route('login')->with('success', 'Admin account created successfully. You can log in now.');
    }

}

