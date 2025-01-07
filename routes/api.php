<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserController;
use App\Http\Controllers\API\Admin\ClientController;
use App\Http\Controllers\Client\UserRolesController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// User routes
Route::get('/users/list', [UserController::class, 'list']);
Route::get('/clients/list', [ClientController::class, 'list']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
});
Route::apiResource('clients', ClientController::class);

Route::post('/client/add_role', [UserRolesController::class, 'store']);
Route::get('/client', [UserRolesController::class, 'list']);

Route::get('/modules_list', [UserRolesController::class, 'modules_list']);
Route::get('/client/roles/{id}', [UserRolesController::class, 'edit']);
