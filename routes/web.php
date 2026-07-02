<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FamilyController;

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
    Route::get('/mega/reports/export/csv', [AdminController::class, 'exportMegaReportsCsv'])->name('admin.megaReports.exportCsv');
    Route::get('/mega/reports/export/pdf', [AdminController::class, 'exportMegaReportsPdf'])->name('admin.megaReports.exportPdf');


    // Device unassign (unsign)
    Route::post('/device/unassign', [AdminController::class, 'unassignDevice'])->name('admin.unassignDevice');

    // Admin: assign/unassign device to a family_parent user (device.user_id)
    Route::post('/device/assign-to-family-parent', [AdminController::class, 'assignDeviceToFamilyParent'])->name('admin.assignDeviceToFamilyParent');
    Route::post('/device/unassign-from-family-parent', [AdminController::class, 'unassignDeviceFromFamilyParent'])->name('admin.unassignDeviceFromFamilyParent');
});





// Family Parent routes
Route::prefix('family')->middleware(['auth','family_parent'])->group(function () {
    Route::get('/dashboard', [FamilyController::class, 'dashboard'])->name('family.dashboard');
    Route::get('/caregivers', [FamilyController::class, 'caregivers'])->name('family.caregivers');
    Route::get('/members', [FamilyController::class, 'members'])->name('family.members'); // backward compatible
    Route::get('/roles', [FamilyController::class, 'roles'])->name('family.roles');

    // Caregiver edit/delete
    Route::get('/caregiver/{user_id}/edit', [\App\Http\Controllers\FamilyCaregiverController::class, 'edit'])->name('family.editCaregiver');
    Route::post('/caregiver/{user_id}', [\App\Http\Controllers\FamilyCaregiverController::class, 'update'])->name('family.updateCaregiver');
    Route::delete('/caregiver/{user_id}', [\App\Http\Controllers\FamilyCaregiverController::class, 'delete'])->name('family.deleteCaregiver');


    Route::post('/device/assign-to-caregiver', [FamilyController::class, 'assignDeviceToCaregiver'])->name('family.assignDeviceToCaregiver');
    Route::post('/device/unassign-from-caregiver', [FamilyController::class, 'unassignDeviceFromCaregiver'])->name('family.unassignDeviceFromCaregiver');





    Route::get('/caregiver/add', function () {
        return redirect()->route('family.caregivers');
    })->name('family.caregiverAddForm');

    // Legacy compatibility: some templates might link to the old add path.
    // Route should be GET (redirect) not POST.
    Route::get('/member/add', function () {
        return redirect()->route('family.caregivers');
    })->name('family.memberAddForm');



    Route::post('/caregiver/add', [FamilyController::class, 'addCaregiver'])->name('family.addCaregiver');
    Route::post('/member/role', [FamilyController::class, 'assignRole'])->name('family.assignRole');
    // Legacy compatibility: keep old POST target but route name expected by existing Blade is `family.addMember`.
    Route::post('/member/add', [FamilyController::class, 'addCaregiver'])->name('family.addMember');






    Route::get('/devices', [FamilyController::class, 'devices'])->name('family.devices');
    Route::get('/reports', [FamilyController::class, 'reports'])->name('family.reports');
    Route::get('/reports/export/csv', [FamilyController::class, 'exportFamilyReportsCsv'])->name('family.reports.exportCsv');
    Route::get('/reports/export/pdf', [FamilyController::class, 'exportFamilyReportsPdf'])->name('family.reports.exportPdf');
});



// Caregiver routes
Route::prefix('caregiver')->middleware(['auth','caregiver'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CaregiverController::class, 'dashboard'])->name('caregiver.dashboard');
Route::get('/reports', [\App\Http\Controllers\CaregiverController::class, 'reports'])->name('caregiver.reports');
    Route::get('/reports/export/csv', [\App\Http\Controllers\CaregiverController::class, 'exportCaregiverReportsCsv'])->name('caregiver.reports.exportCsv');
    Route::get('/reports/export/pdf', [\App\Http\Controllers\CaregiverController::class, 'exportCaregiverReportsPdf'])->name('caregiver.reports.exportPdf');
    Route::get('/notifications', [\App\Http\Controllers\CaregiverController::class, 'notifications'])->name('caregiver.notifications');


    // Device assignment should be done by family_parent only.
    // Keep endpoints here but controller blocks caregiver actions.
    Route::post('/assign-device-to-caregiver', [\App\Http\Controllers\CaregiverController::class, 'assignDevice'])->name('caregiver.assignDevice');
    Route::post('/unassign-device-from-caregiver', [\App\Http\Controllers\CaregiverController::class, 'unassignDevice'])->name('caregiver.unassignDevice');
});





// Member routes (used by existing member.* links)
// NOTE: this is distinct from `family.*` and `caregiver.*`
Route::prefix('member')->middleware(['auth','family_member'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CaregiverController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/reports', [\App\Http\Controllers\CaregiverController::class, 'reports'])->name('member.reports');
    Route::get('/notifications', [\App\Http\Controllers\CaregiverController::class, 'notifications'])->name('member.notifications');
});




// Profile settings (shared)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.settings');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
});


