<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\InvitationController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Route de déconnexion personnalisée (sans CSRF pour éviter l'erreur)
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/mentions-legales', [PageController::class, 'mentionsLegales'])->name('mentions.legales');
Route::get('/politique-confidentialite', [PageController::class, 'politiqueConfidentialite'])->name('politique.confidentialite');
Route::get('/services', [PageController::class, 'services'])->name('services');

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/portfolio', [\App\Http\Controllers\PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [\App\Http\Controllers\PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Routes d'Authentification Supplémentaires
|--------------------------------------------------------------------------
*/

// Désactiver l'inscription publique - rediriger vers login avec message
Route::get('/register', function () {
    return redirect()->route('login')->with('error', 'Les inscriptions ne sont pas ouvertes au public. Veuillez contacter un administrateur.');
})->name('register');

Route::post('/register', function () {
    return redirect()->route('login')->with('error', 'Les inscriptions ne sont pas ouvertes au public.');
});

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

// Routes d'invitation
Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');

/*
|--------------------------------------------------------------------------
| Dashboard — redirection unique vers l'espace admin
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes Administration
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';