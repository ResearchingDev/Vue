<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserController;
use App\Http\Controllers\API\Admin\ClientController;
use App\Http\Controllers\Client\UserRolesController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/users/save_users/{id}', [UserController::class, 'update']);
});
// User routes
Route::middleware(['auth:sanctum'])->prefix('client')->group(function () {
    Route::prefix(prefix: 'users')->name('user.')->group(function () {
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
    });
    Route::get('users/{id}', [UserController::class, 'profile']);
    Route::post('users/list', [UserController::class, 'list']);
    Route::get('users/user_roles/{id}', [UserController::class, 'roles']);

});

Route::get('/clients/list', [ClientController::class, 'list']);
Route::apiResource('clients', ClientController::class);
Route::post('/clients/save/{id}', [ClientController::class, 'update']);

Route::post('/client/add_role', [UserRolesController::class, 'store']);
Route::get('/client/{id}', [UserRolesController::class, 'list']);

Route::get('/modules_list', [UserRolesController::class, 'modules_list']);
Route::get('/client/roles/{id}', [UserRolesController::class, 'edit']);
Route::delete('/client/roles/delete/{id}', [UserRolesController::class, 'destroy']);
Route::post('/client/roles/update/{id}', [UserRolesController::class, 'update']);
