<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TripPlayBackController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth']], static function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/vehicle-status', [DashboardController::class, 'vehicleLastStatus'])->name('dashboard.vehicleStatus');
});

Route::group(['middleware' => ['user-auth']], static function () {
    Route::get('/trip-playback/{id}', [TripPlayBackController::class, 'index'])->name('trip.playback.index');
});

Route::resource('role', RoleController::class);
Route::resource('permission', PermissionController::class);
