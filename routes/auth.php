<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes d'Authentification Supplémentaires
|--------------------------------------------------------------------------
*/

// Routes pour l'authentification sociale
Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->name('auth.social.redirect');

    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
        ->name('auth.social.callback');
});

// Routes pour la vérification d'email personnalisée
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
});
