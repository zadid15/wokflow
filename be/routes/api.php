<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\CookingCategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\StoveController;
use App\Http\Controllers\WokController;
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

    // Master Data Routes
    Route::get('/business-categories', [BusinessCategoryController::class, 'index']);
    Route::get('/cooking-categories', [CookingCategoryController::class, 'index']);
    Route::get('/menus', [MenuController::class, 'index']);
    Route::get('/attributes', [AttributeController::class, 'index']);
    Route::get('/stoves', [StoveController::class, 'index']);
    Route::get('/woks', [WokController::class, 'index']);

    Route::middleware('admin')->group(function () {
        Route::post('/business-categories', [BusinessCategoryController::class, 'store']);
        Route::put('/business-categories/{id}', [BusinessCategoryController::class, 'update']);
        Route::delete('/business-categories/{id}', [BusinessCategoryController::class, 'destroy']);

        Route::post('/cooking-categories', [CookingCategoryController::class, 'store']);
        Route::put('/cooking-categories/{id}', [CookingCategoryController::class, 'update']);
        Route::delete('/cooking-categories/{id}', [CookingCategoryController::class, 'destroy']);

        Route::post('/menus', [MenuController::class, 'store']);
        Route::put('/menus/{id}', [MenuController::class, 'update']);
        Route::delete('/menus/{id}', [MenuController::class, 'destroy']);

        Route::post('/attributes', [AttributeController::class, 'store']);
        Route::put('/attributes/{id}', [AttributeController::class, 'update']);
        Route::delete('/attributes/{id}', [AttributeController::class, 'destroy']);

        Route::post('/stoves', [StoveController::class, 'store']);
        Route::put('/stoves/{id}', [StoveController::class, 'update']);
        Route::delete('/stoves/{id}', [StoveController::class, 'destroy']);

        Route::post('/woks', [WokController::class, 'store']);
        Route::put('/woks/{id}', [WokController::class, 'update']);
        Route::delete('/woks/{id}', [WokController::class, 'destroy']);
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
