<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\ClientController;
use App\Http\Controllers\API\Client\UserController;
use App\Http\Controllers\API\Client\UserRolesController;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/users/save_users/{id}', [UserController::class, 'update']);
});
// Admin routes
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::apiResource('client', ClientController::class);
    Route::post('/client/list', [ClientController::class, 'list']);
    Route::post('/client/{id}', [ClientController::class, 'update']);
});
// Client routes
Route::middleware(['auth:sanctum'])->prefix('client')->group(function () {
    // Non-RESTful route
    Route::get('users/user_roles', [UserController::class, 'roles'])->name('user.roles');
    // RESTful resource routes
    Route::resource('users', UserController::class); // Resource Controller
    // Additional custom routes for users
    Route::post('users/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::post('users/list', [UserController::class, 'list'])->name('user.list');
    // Role Routes
    Route::post('roles/list', [UserRolesController::class, 'list']);
    Route::get('/roles/menus', [UserRolesController::class, 'modules_list']);
    Route::resource('roles', UserRolesController::class); // Resource Controller
});
