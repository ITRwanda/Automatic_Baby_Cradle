<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
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
Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/families', [AdminController::class, 'families'])->name('admin.families');
    Route::post('/family/create', [AdminController::class, 'createFamily'])->name('admin.createFamily');
    Route::get('/devices', [AdminController::class, 'devices'])->name('admin.devices');
    Route::post('/device/register', [AdminController::class, 'registerDevice'])->name('admin.registerDevice');
    Route::post('/device/assign', [AdminController::class, 'assignDevice'])->name('admin.assignDevice');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});


// Family Parent routes
Route::prefix('family')->middleware(['auth','family_parent'])->group(function () {
    Route::get('/dashboard', [FamilyController::class, 'dashboard'])->name('family.dashboard');
    Route::post('/member/add', [FamilyController::class, 'addMember'])->name('family.addMember');
    Route::post('/member/role', [FamilyController::class, 'assignRole'])->name('family.assignRole');
    Route::get('/devices', [FamilyController::class, 'devices'])->name('family.devices');
    Route::get('/reports', [FamilyController::class, 'reports'])->name('family.reports');
});


// Family Member routes
Route::prefix('member')->middleware(['auth','family_member'])->group(function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/reports', [MemberController::class, 'reports'])->name('member.reports');
    Route::get('/notifications', [MemberController::class, 'notifications'])->name('member.notifications');
});


// Profile settings (shared)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.settings');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
});

