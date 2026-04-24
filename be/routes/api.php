<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/users/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/profile', [AuthController::class, 'profile']);
    Route::post('/users/logout', [AuthController::class, 'logout']);
    Route::put('/users', [AuthController::class, 'updateProfile']);
    Route::put('/users/password', [AuthController::class, 'updatePassword']);

    // Order Management Routes
    Route::get('/orders', [OrderController::class, 'index']); // All roles
    Route::middleware('role:admin,cashier')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
    });
    Route::middleware('admin')->group(function () {
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    });

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
