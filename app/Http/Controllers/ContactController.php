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

            // Envoi avec Mail::html
            Mail::html($emailBody, function($message) use ($adminEmail, $contact) {
                $message->to($adminEmail)
                        ->subject('Nouvelle demande de contact - ' . $contact->name)
                        ->replyTo($contact->email, $contact->name);
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
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #1a1a2e; }
                .wrapper { max-width: 620px; margin: 40px auto; background: #ffffff; border: 1px solid #dde3ec; border-radius: 4px; overflow: hidden; }
                .header { background-color: #1e2a4a; padding: 32px 40px; }
                .header h1 { color: #ffffff; font-size: 18px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
                .header p { color: #9daec8; font-size: 13px; margin-top: 4px; }
                .badge { display: inline-block; background-color: #4f46e5; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 10px; border-radius: 2px; margin-top: 12px; }
                .body { padding: 36px 40px; }
                .section-title { font-size: 11px; font-weight: 700; color: #6b7280; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
                .info-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
                .info-table tr td { padding: 10px 0; vertical-align: top; font-size: 14px; border-bottom: 1px solid #f3f4f6; }
                .info-table tr:last-child td { border-bottom: none; }
                .info-table td.label { color: #6b7280; font-weight: 600; width: 140px; padding-right: 16px; }
                .info-table td.value { color: #111827; }
                .info-table td.value a { color: #4f46e5; text-decoration: none; }
                .message-block { background-color: #f9fafb; border: 1px solid #e5e7eb; border-left: 3px solid #1e2a4a; border-radius: 2px; padding: 20px 24px; font-size: 14px; line-height: 1.75; color: #374151; }
                .footer { background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
                .footer strong { color: #6b7280; }
            </style>
        </head>
        <body>
            <div class="wrapper">
                <div class="header">
                    <h1>Nouvelle demande de contact</h1>
                    <p>Reçue via le formulaire de contact du site</p>
                    <span class="badge">À traiter</span>
                </div>
                <div class="body">
                    <p class="section-title">Informations du contact</p>
                    <table class="info-table">
                        <tr>
                            <td class="label">Nom</td>
                            <td class="value">' . htmlspecialchars($contact->name) . '</td>
                        </tr>
                        <tr>
                            <td class="label">Adresse e-mail</td>
                            <td class="value"><a href="mailto:' . htmlspecialchars($contact->email) . '">' . htmlspecialchars($contact->email) . '</a></td>
                        </tr>
                        <tr>
                            <td class="label">Téléphone</td>
                            <td class="value">' . htmlspecialchars($contact->phone ?: 'Non renseigné') . '</td>
                        </tr>
                        <tr>
                            <td class="label">Service demandé</td>
                            <td class="value">' . htmlspecialchars($serviceLabel) . '</td>
                        </tr>
                        <tr>
                            <td class="label">Date de réception</td>
                            <td class="value">' . $contact->created_at->format('d/m/Y à H:i') . '</td>
                        </tr>
                    </table>

                    <p class="section-title">Message</p>
                    <div class="message-block">
                        ' . nl2br(htmlspecialchars($contact->message)) . '
                    </div>
                </div>
                <div class="footer">
                    <p>Ce message a été transmis automatiquement depuis le formulaire de contact.</p>
                    <p style="margin-top:6px;"><strong>Nova Tech</strong> &mdash; &copy; ' . date('Y') . ' &mdash; Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
