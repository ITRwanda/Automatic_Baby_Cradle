<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceActivityController;

Route::post('/device-activities', [DeviceActivityController::class, 'store']);

