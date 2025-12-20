<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\RentalConditionController;
use App\Http\Controllers\Api\ReviewController;

// Маршруты для машин
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// Маршруты для авторизации
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Маршрут для условий
Route::get('/rental-conditions', [RentalConditionController::class, 'index']);


// отзывы
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
