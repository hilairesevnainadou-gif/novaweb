<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'required|string',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => "L'email est obligatoire.",
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'service.required' => 'Veuillez sélectionner un service.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Veuillez corriger les erreurs du formulaire.'
            ], 422);
        }

        try {
            // Options de service
            $serviceOptions = [
                'site-vitrine' => 'Site Vitrine',
                'ecommerce' => 'Site E-commerce / Vente en ligne',
                'refonte' => 'Refonte de site existant',
                'autre' => 'Autre projet',
            ];

            $serviceLabel = $serviceOptions[$request->service] ?? $request->service;

            // Sauvegarder dans la base de données
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?? '',
                'subject' => $serviceLabel,
                'message' => $request->message,
                'is_read' => false
            ]);

            // Envoi de l'email SANS AUTHENTIFICATION
            try {
                $adminEmail = config('mail.from.address');

                // Configuration spéciale pour serveur sans auth
                config([
                    'mail.mailers.smtp.username' => null,
                    'mail.mailers.smtp.password' => null,
                    'mail.mailers.smtp.encryption' => null,
                    'mail.mailers.smtp.auth_mode' => null,
                ]);

                $emailData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone ?? 'Non renseigné',
                    'service' => $serviceLabel,
                    'message' => $request->message,
                    'created_at' => now()->format('d/m/Y H:i:s')
                ];

                $emailBody = "Nouvelle demande de contact\n";
                $emailBody .= "========================\n\n";
                $emailBody .= "Nom: {$emailData['name']}\n";
                $emailBody .= "Email: {$emailData['email']}\n";
                $emailBody .= "Téléphone: {$emailData['phone']}\n";
                $emailBody .= "Service: {$emailData['service']}\n";
                $emailBody .= "Message:\n{$emailData['message']}\n\n";
                $emailBody .= "Reçu le: {$emailData['created_at']}\n";

                Mail::raw($emailBody, function($message) use ($adminEmail, $emailData) {
                    $message->to($adminEmail)
                            ->subject('Nouvelle demande de contact - ' . $emailData['name'])
                            ->replyTo($emailData['email'], $emailData['name']);
                });

                Log::info('Email envoyé sans authentification avec succès');

            } catch (\Exception $mailError) {
                Log::warning("Erreur d'envoi d'email (mode sans auth): " . $mailError->getMessage());
                // Ne pas bloquer - on continue
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les 24h.'
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur d'enregistrement: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur technique est survenue. Veuillez nous appeler directement ou réessayer plus tard.'
            ], 500);
        }
    }
}
