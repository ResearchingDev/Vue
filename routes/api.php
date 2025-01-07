<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserController;
use App\Http\Controllers\API\Admin\ClientController;
use App\Http\Controllers\Client\UserRolesController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


// User routes
Route::get('/users/list', [UserController::class, 'list']); 



Route::post('/admin/users', [UserController::class, 'store'])->name('user.store');
Route::get('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/admin/users/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

Route::post('/users/list', [UserController::class, 'list']);

Route::get('/users/{id}', [UserController::class, 'profile']);
Route::post('/users/save_users/{id}', [UserController::class, 'save_user']);
Route::get('/clients/list', [ClientController::class, 'list']); 

// Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
//     Route::post('/users', [UserController::class, 'store'])->name('user.store');
// });
Route::apiResource('clients', ClientController::class);
Route::post('/clients/save/{id}', [ClientController::class, 'update']);

Route::post('/client/add_role', [UserRolesController::class, 'store']);
