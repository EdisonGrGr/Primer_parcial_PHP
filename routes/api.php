<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('cars', CarController::class)->names([
    'index' => 'api.cars.index',
    'store' => 'api.cars.store',
    'show' => 'api.cars.show',
    'update' => 'api.cars.update',
    'destroy' => 'api.cars.destroy',
]);

Route::get('/categories/active', [CategoryController::class, 'active'])
    ->name('api.categories.active');

Route::get('/categories/active/with-available-cars', [CategoryController::class, 'activeWithAvailableCars'])
    ->name('api.categories.active-available');

Route::get('/categories/active/paginated', [CategoryController::class, 'activePaginated'])
    ->name('api.categories.active-paginated');


Route::apiResource('categories', CategoryController::class)->names([
    'index' => 'api.categories.index',
    'store' => 'api.categories.store',
    'show' => 'api.categories.show',
    'update' => 'api.categories.update',
    'destroy' => 'api.categories.destroy',
]);