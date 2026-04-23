<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/users/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/profile', [AuthController::class, 'profile']);
    Route::put('/users', [AuthController::class, 'updateProfile']);
    Route::put('/users/password', [AuthController::class, 'updatePassword']);

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}/deactivate', [UserController::class, 'deactivate']);
        Route::patch('/users/{id}/activate', [UserController::class, 'activate']);
        Route::get('/users/role/{role}', [UserController::class, 'getByRole']);
    });
});
