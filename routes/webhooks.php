<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Http\Controllers\Webhook\GithubWebhookController;

/*
|--------------------------------------------------------------------------
| Routes pour Webhooks
|--------------------------------------------------------------------------
*/

Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])
    ->name('webhook.stripe');

Route::post('/webhook/github', [GithubWebhookController::class, 'handle'])
    ->name('webhook.github');
