<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceActivityController;

// Arduino / ESP32 device endpoint — no auth required
Route::post('/device-activities', [DeviceActivityController::class, 'store']);

// In-app notification read status (auth required)
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{id}/read', [DeviceActivityController::class, 'markRead'])
         ->name('notifications.markRead');
});

