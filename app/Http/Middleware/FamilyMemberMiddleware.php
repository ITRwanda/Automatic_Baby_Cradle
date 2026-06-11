<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FamilyMemberMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role && Auth::user()->role->name === 'family_member') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}

