{{-- resources/views/emails/password-reset-invitation.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1e293b; background: #f1f5f9; margin: 0; padding: 0; }
        .wrap    { max-width: 600px; margin: 32px auto; padding: 0 16px; }
        .card    { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header  { background: #0f172a; padding: 32px 24px; text-align: center; }
        .header .brand { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }
        .header .subtitle { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 4px; }
        .header-accent { display: inline-block; width: 40px; height: 3px; background: #2563eb; border-radius: 2px; margin: 12px auto 0; }
        .body    { padding: 32px 28px; }
        .body p  { margin: 0 0 16px; font-size: 14px; color: #334155; }
        .body h2 { margin: 0 0 20px; font-size: 18px; color: #0f172a; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 20px; margin: 20px 0; font-size: 13px; }
        .btn-wrap { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; padding: 13px 30px; background: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; letter-spacing: 0.3px; }
        .alert { border-radius: 8px; padding: 14px 16px; margin: 20px 0; font-size: 13px; }
        .alert-warning { background: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; }
        .alert strong  { display: block; margin-bottom: 4px; }
        .url-fallback { font-size: 11px; color: #94a3b8; word-break: break-all; margin-top: 12px; }
        hr { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center; font-size: 12px; color: #94a3b8; }
        .footer p { margin: 3px 0; }
        .footer strong { color: #64748b; }
        @media (max-width: 600px) {
            .body { padding: 24px 18px; }
        }
    </style>
</head>
<body>
<div class="wrap">
<div class="card">

    <div class="header">
        <div class="brand">{{ config('app.name') }}</div>
        <div class="subtitle">Réinitialisation de mot de passe</div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        <h2>Bonjour {{ $user->name }},</h2>

        <p>Une demande de réinitialisation de mot de passe a été effectuée pour votre compte.</p>

        <div class="info-box">
            <strong>Email associé :</strong> {{ $user->email }}
        </div>

        <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>

        <div class="btn-wrap">
            <a href="{{ $invitationUrl }}" class="btn">Réinitialiser mon mot de passe</a>
        </div>

        <div class="alert alert-warning">
            <strong>Lien valable 72 heures</strong>
            Passé ce délai, vous devrez soumettre une nouvelle demande de réinitialisation.
        </div>

        <p class="url-fallback">
            Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
            {{ $invitationUrl }}
        </p>

        <hr>

        <p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email — votre mot de passe actuel reste inchangé.</p>

        <p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
    </div>

    <div class="footer">
        <p><strong>{{ config('app.name') }}</strong></p>
        <p>Cet email a été envoyé automatiquement — merci de ne pas y répondre.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
    </div>

</div>
</div>
</body>
</html>
