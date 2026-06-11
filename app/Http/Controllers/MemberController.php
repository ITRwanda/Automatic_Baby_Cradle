<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller {
    public function dashboard() {
        $notifications = auth()->user()->family->notifications ?? [];
        return view('member.dashboard', compact('notifications'));
    }
}

