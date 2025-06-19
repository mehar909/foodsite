<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\MenuItemController;
use App\Http\Controllers\API\OrderController;
//test route
Route::get('/test', function () {
    return response()->json(['API is working']);
});

//customer routes
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/customer/logout', [CustomerAuthController::class, 'logout']);

//admin routes
Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminAuthController::class, 'logout']);

//menuitem routes
//public routes
Route::get('/menu', [MenuItemController::class, 'index']);
//admin routes
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/menu', [MenuItemController::class, 'store']);
    Route::delete('/menu/{id}', [MenuItemController::class, 'destroy']);
    Route::put('/menu/{id}', [MenuItemController::class, 'toggleAvailability']);

//order routes
// Customer routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'place']);
    Route::get('/orders', [OrderController::class, 'myOrders']);
    Route::put('/orders/{id}', [OrderController::class, 'cancel']);

});

// Admin routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'allOrders']);
    Route::put('/admin/orders/{id}', [OrderController::class, 'updateStatus']);
});
});