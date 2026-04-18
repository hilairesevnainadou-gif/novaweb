<?php
// app/Http/Controllers/Webhook/StripeWebhookController.php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Vérifier le type d'événement
        switch ($payload['type']) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($payload);
                break;
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($payload);
                break;
            default:
                Log::info('Unhandled Stripe webhook event', ['type' => $payload['type']]);
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSucceeded($payload)
    {
        // Logique pour gérer le paiement réussi
        Log::info('Payment succeeded', ['data' => $payload['data']['object']]);
    }

    private function handleSubscriptionCreated($payload)
    {
        // Logique pour gérer la création d'abonnement
        Log::info('Subscription created', ['data' => $payload['data']['object']]);
    }
}
