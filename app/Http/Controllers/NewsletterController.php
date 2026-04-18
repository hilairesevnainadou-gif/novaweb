<?php
// app/Http/Controllers/NewsletterController.php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Valider l'email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez entrer une adresse email valide.'
                ], 422);
            }

            return back()->withErrors(['email' => 'Email invalide'])->withInput();
        }

        $email = $request->email;

        try {
            // Vérifier si l'email existe déjà
            $existing = Newsletter::where('email', $email)->first();

            if ($existing) {
                if (!$existing->is_active) {
                    // Réactiver l'abonnement
                    $existing->update([
                        'is_active' => true,
                        'unsubscribed_at' => null,
                        'subscribed_at' => now(),
                        'unsubscribe_token' => Str::random(64) // Nouveau token
                    ]);

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Votre abonnement a été réactivé avec succès !'
                        ]);
                    }

                    return back()->with('success', 'Votre abonnement a été réactivé avec succès !');
                }

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cet email est déjà inscrit à notre newsletter.'
                    ], 422);
                }

                return back()->withErrors(['email' => 'Cet email est déjà inscrit'])->withInput();
            }

            // Créer un nouvel abonné
            Newsletter::create([
                'email' => $email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_active' => true,
                'subscribed_at' => now()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Merci pour votre inscription ! Vous recevrez bientôt nos actualités.'
                ]);
            }

            return back()->with('success', 'Merci pour votre inscription !');

        } catch (\Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer plus tard.']);
        }
    }

    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('unsubscribe_token', $token)->first();

        if ($newsletter) {
            $newsletter->update([
                'is_active' => false,
                'unsubscribed_at' => now()
            ]);

            return view('newsletter.unsubscribe', [
                'success' => true,
                'email' => $newsletter->email
            ]);
        }

        return view('newsletter.unsubscribe', [
            'success' => false,
            'message' => 'Token de désabonnement invalide ou déjà utilisé.'
        ]);
    }

    // Méthode alternative pour désabonnement par email (pour compatibilité)
    public function unsubscribeByEmail($email)
    {
        $newsletter = Newsletter::where('email', $email)->first();

        if ($newsletter) {
            $newsletter->update([
                'is_active' => false,
                'unsubscribed_at' => now()
            ]);

            return view('newsletter.unsubscribe', [
                'success' => true,
                'email' => $newsletter->email
            ]);
        }

        return view('newsletter.unsubscribe', [
            'success' => false,
            'message' => 'Email non trouvé dans notre liste d\'abonnés.'
        ]);
    }
}
