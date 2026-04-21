<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Mail\ContactMail;

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
                'application-web' => 'Application Web',
                'application-mobile' => 'Application Mobile',
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

            // Envoi de l'email (avec gestion d'erreur silencieuse)
            $this->sendEmailNotification($contact, $serviceLabel);

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

    /**
     * Envoi de l'email de notification
     */
    private function sendEmailNotification($contact, $serviceLabel)
    {
        try {
            $adminEmail = config('mail.from.address');

            if (!$adminEmail) {
                Log::warning('Email admin non configuré');
                return false;
            }

            // Vérifier si le mailer est configuré
            $mailer = config('mail.default');

            if ($mailer === 'log') {
                // Mode log, simuler l'envoi
                Log::info('Email notification (mode log):', [
                    'to' => $adminEmail,
                    'subject' => 'Nouvelle demande de contact - ' . $contact->name,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'service' => $serviceLabel
                ]);
                return true;
            }

            // Construction du message
            $emailBody = $this->buildEmailBody($contact, $serviceLabel);

            // Envoi avec Mail::send
            Mail::send([], [], function($message) use ($adminEmail, $contact, $emailBody) {
                $message->to($adminEmail)
                        ->subject('Nouvelle demande de contact - ' . $contact->name)
                        ->replyTo($contact->email, $contact->name)
                        ->setBody($emailBody, 'text/html');
            });

            Log::info('Email envoyé avec succès vers: ' . $adminEmail);
            return true;

        } catch (\Exception $e) {
            Log::error("Erreur d'envoi d'email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Construction du corps de l'email
     */
    private function buildEmailBody($contact, $serviceLabel)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px; }
                .info-row { margin-bottom: 15px; padding: 10px; background: white; border-radius: 8px; }
                .label { font-weight: bold; color: #4f46e5; width: 120px; display: inline-block; }
                .value { color: #333; }
                .message-box { background: white; padding: 15px; border-radius: 8px; margin-top: 10px; border-left: 4px solid #6366f1; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; }
                hr { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>📩 Nouvelle demande de contact</h2>
                </div>
                <div class="content">
                    <div class="info-row">
                        <span class="label">👤 Nom :</span>
                        <span class="value">' . htmlspecialchars($contact->name) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">📧 Email :</span>
                        <span class="value"><a href="mailto:' . htmlspecialchars($contact->email) . '">' . htmlspecialchars($contact->email) . '</a></span>
                    </div>
                    <div class="info-row">
                        <span class="label">📞 Téléphone :</span>
                        <span class="value">' . htmlspecialchars($contact->phone ?: 'Non renseigné') . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">🛠️ Service :</span>
                        <span class="value">' . htmlspecialchars($serviceLabel) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">📅 Date :</span>
                        <span class="value">' . $contact->created_at->format('d/m/Y H:i:s') . '</span>
                    </div>
                    <hr>
                    <div class="info-row">
                        <span class="label">💬 Message :</span>
                    </div>
                    <div class="message-box">
                        ' . nl2br(htmlspecialchars($contact->message)) . '
                    </div>
                </div>
                <div class="footer">
                    <p>Cet email a été envoyé depuis le formulaire de contact de votre site web.</p>
                    <p>© ' . date('Y') . ' Nova Tech - Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
