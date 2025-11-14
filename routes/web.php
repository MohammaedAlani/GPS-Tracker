<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::resource('role', RoleController::class);
Route::resource('permission', PermissionController::class);
