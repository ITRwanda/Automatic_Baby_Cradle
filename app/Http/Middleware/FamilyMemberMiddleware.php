<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FamilyMemberMiddleware
{
    // Deprecated: member role has been replaced by caregiver.
    public function handle($request, Closure $next)
    {
        return redirect()->route('login')->with('error', 'This route is no longer available.');
    }
}


