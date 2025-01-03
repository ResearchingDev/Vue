<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// User routes
Route::post('/users/list', [UserController::class, 'list']);

Route::post('/admin/users', [UserController::class, 'store'])->name('user.store');
Route::get('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/admin/users/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');




