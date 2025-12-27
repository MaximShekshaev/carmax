<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\RentalConditionController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\AdminCarController;
use App\Http\Controllers\Api\ReviewController as PublicReviewController;
use App\Http\Controllers\Api\Admin\AdminReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\AdminRentalConditionController;
use App\Http\Controllers\Api\Admin\AdminFaqController;

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
Route::get('/reviews', [PublicReviewController::class, 'index']);
Route::post('/reviews', [PublicReviewController::class, 'store']);


// Ответы на частые вопросы
Route::get('/faqs', [FaqController::class, 'index']);


// Маршрут для создания аренды
Route::post('/rentals', [RentalController::class, 'store']);


// Маршрут для получения аренд пользователя
Route::get('/rentals', [RentalController::class, 'index']);
Route::post('/rentals', [RentalController::class, 'store']);
Route::delete('/rentals/{id}', [RentalController::class, 'destroy']);

// Маршрут для админ входа

Route::prefix('admin')->group(function () {

    // Логин админа
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Админские маршруты
    Route::middleware('simple.admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::put('/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);

    Route::get('/cars', [AdminCarController::class, 'index']);
    Route::put('/cars/{id}', [AdminCarController::class, 'update']);
    Route::delete('/cars/{id}', [AdminCarController::class, 'destroy']);
    Route::post('/cars', [AdminCarController::class, 'store']);

    Route::get('/reviews', [AdminReviewController::class, 'index']);
        Route::patch('/reviews/{id}/toggle', [AdminReviewController::class, 'toggle']);
        Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);
   

        Route::get('/rental-conditions', [AdminRentalConditionController::class, 'index']);
    Route::post('/rental-conditions', [AdminRentalConditionController::class, 'store']);
    Route::put('/rental-conditions/{id}', [AdminRentalConditionController::class, 'update']);
    Route::delete('/rental-conditions/{id}', [AdminRentalConditionController::class, 'destroy']);

    Route::get('/faqs', [AdminFaqController::class, 'index']);
    Route::post('/faqs', [AdminFaqController::class, 'store']);
    Route::put('/faqs/{id}', [AdminFaqController::class, 'update']);
    Route::delete('/faqs/{id}', [AdminFaqController::class, 'destroy']);
    });
});