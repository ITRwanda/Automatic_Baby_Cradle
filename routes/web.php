<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication routes (Laravel Breeze/Jetstream handles login/register)
// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Admin routes
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/family/create', [AdminController::class, 'createFamily'])->name('admin.createFamily');
    Route::post('/admin/device/register', [AdminController::class, 'registerDevice'])->name('admin.registerDevice');
    Route::post('/admin/device/assign', [AdminController::class, 'assignDevice'])->name('admin.assignDevice');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
});

// Family Parent routes
Route::middleware(['auth','family_parent'])->group(function () {
    Route::get('/family/dashboard', [FamilyController::class, 'dashboard'])->name('family.dashboard');
    Route::post('/family/member/add', [FamilyController::class, 'addMember'])->name('family.addMember');
    Route::get('/family/reports', [FamilyController::class, 'reports'])->name('family.reports');
});

// Family Member routes
Route::middleware(['auth','family_member'])->group(function () {
    Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/member/reports', [MemberController::class, 'reports'])->name('member.reports');
});

// Profile settings (shared)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/settings', function () {
        return view('profile.settings');
    })->name('profile.settings');
});
