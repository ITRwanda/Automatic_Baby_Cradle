<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FamilyMemberMiddleware {
    public function handle($request, Closure $next) {
        if (Auth::check() && Auth::user()->role === 'family_member') {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}

