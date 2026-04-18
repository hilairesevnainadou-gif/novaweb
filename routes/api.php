<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioApiController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\ContactApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API publiques
Route::prefix('v1')->group(function () {
    Route::get('/portfolio', [PortfolioApiController::class, 'index']);
    Route::get('/portfolio/{slug}', [PortfolioApiController::class, 'show']);
    Route::get('/blog', [BlogApiController::class, 'index']);
    Route::get('/blog/{slug}', [BlogApiController::class, 'show']);
    Route::post('/contact', [ContactApiController::class, 'submit']);
});

// Routes API protégées pour l'admin
Route::middleware(['auth:sanctum', 'permission:dashboard.view'])->prefix('v1/admin')->group(function () {
    Route::get('/stats', [App\Http\Controllers\Api\AdminStatsController::class, 'index']);
});
