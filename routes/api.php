<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// User routes
Route::get('/users/list', [UserController::class, 'list']); 

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
});
