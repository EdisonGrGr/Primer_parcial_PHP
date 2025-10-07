<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Resource routes for Cars
Route::apiResource('cars', CarController::class);

Route::get('/categories/active', [CategoryController::class, 'active'])
    ->name('categories.active');

Route::get('/categories/active/with-available-cars', [CategoryController::class, 'activeWithAvailableCars'])
    ->name('categories.active-available');

Route::get('/categories/active/paginated', [CategoryController::class, 'activePaginated'])
    ->name('categories.active-paginated');

// API Resource routes for Categories
Route::apiResource('categories', CategoryController::class);