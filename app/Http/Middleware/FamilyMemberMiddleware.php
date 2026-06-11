<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FamilyMemberMiddleware {
    public function handle($request, Closure $next) {
        if (Auth::check() && Auth::user()->role === 'family_member') {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}

