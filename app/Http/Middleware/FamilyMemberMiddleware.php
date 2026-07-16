<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FamilyMemberMiddleware
{
    /**
     * Allow caregiver OR family_parent — both may access member.* routes.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role) {
            $roleName = Auth::user()->role->name;
            if (in_array($roleName, ['caregiver', 'family_parent', 'admin'], true)) {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
