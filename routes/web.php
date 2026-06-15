<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication routes (custom auth for this project)
use App\Http\Controllers\Auth\RegisterController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


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

    // Device management (admin)
    Route::post('/device/update/{device_id}', [AdminController::class, 'updateDevice'])->name('admin.updateDevice');
    Route::post('/device/delete/{device_id}', [AdminController::class, 'deleteDevice'])->name('admin.deleteDevice');

    // Family management (admin)
    Route::post('/family/update/{family_id}', [AdminController::class, 'updateFamily'])->name('admin.updateFamily');
    Route::post('/family/delete/{family_id}', [AdminController::class, 'deleteFamily'])->name('admin.deleteFamily');

    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // New reports
    Route::get('/device/reports', [AdminController::class, 'deviceReports'])->name('admin.deviceReports');
    Route::get('/family/reports', [AdminController::class, 'familyReports'])->name('admin.familyReports');

    // Mega / general incident report
    Route::get('/mega/reports', [AdminController::class, 'megaReports'])->name('admin.megaReports');

    // Device unassign (unsign)
    Route::post('/device/unassign', [AdminController::class, 'unassignDevice'])->name('admin.unassignDevice');

    // Admin: assign/unassign device to a family_parent user (device.user_id)
    Route::post('/device/assign-to-family-parent', [AdminController::class, 'assignDeviceToFamilyParent'])->name('admin.assignDeviceToFamilyParent');
    Route::post('/device/unassign-from-family-parent', [AdminController::class, 'unassignDeviceFromFamilyParent'])->name('admin.unassignDeviceFromFamilyParent');
});





// Family Parent routes
Route::prefix('family')->middleware(['auth','family_parent'])->group(function () {
    Route::get('/dashboard', [FamilyController::class, 'dashboard'])->name('family.dashboard');
    Route::get('/members', [FamilyController::class, 'members'])->name('family.members');
    Route::get('/roles', [FamilyController::class, 'roles'])->name('family.roles');
    Route::post('/device/assign-to-member', [FamilyController::class, 'assignDeviceToMember'])->name('family.assignDeviceToMember');
    Route::post('/device/unassign-from-member', [FamilyController::class, 'unassignDeviceFromMember'])->name('family.unassignDeviceFromMember');



    Route::get('/member/add', function () {
        // Family parent can access add-member form via members page.
        return redirect()->route('family.members');
    })->name('family.memberAddForm');

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

