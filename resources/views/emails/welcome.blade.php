{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - {{ config('app.name') }}</title>
    <style>
        /* ── Reset ── */
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1e293b; background: #f1f5f9; margin: 0; padding: 0; }

        /* ── Wrapper ── */
        .wrap    { max-width: 600px; margin: 32px auto; padding: 0 16px; }
        .card    { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }

        /* ── Header ── */
        .header  { background: #0f172a; padding: 32px 24px; text-align: center; }
        .header .brand { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }
        .header .subtitle { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 4px; }
        .header-accent { display: inline-block; width: 40px; height: 3px; background: #2563eb; border-radius: 2px; margin: 12px auto 0; }

        /* ── Body ── */
        .body    { padding: 32px 28px; }
        .body p  { margin: 0 0 16px; font-size: 14px; color: #334155; }
        .body h2 { margin: 0 0 20px; font-size: 18px; color: #0f172a; }

        /* ── Info box ── */
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #64748b; }
        .info-value { color: #0f172a; font-family: monospace; font-size: 14px; }

        /* ── Button ── */
        .btn-wrap { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; padding: 13px 30px; background: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; letter-spacing: 0.3px; }

        /* ── Alert ── */
        .alert { border-radius: 8px; padding: 14px 16px; margin: 20px 0; font-size: 13px; }
        .alert-warning { background: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; }
        .alert-info    { background: #eff6ff; border-left: 4px solid #2563eb; color: #1e40af; }
        .alert-error   { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
        .alert strong  { display: block; margin-bottom: 4px; }

        /* ── Divider ── */
        hr { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

        /* ── Footer ── */
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center; font-size: 12px; color: #94a3b8; }
        .footer p { margin: 3px 0; }
        .footer strong { color: #64748b; }

        @media (max-width: 600px) {
            .body { padding: 24px 18px; }
            .info-row { flex-direction: column; gap: 3px; }
        }
    </style>
</head>
<body>
<div class="wrap">
<div class="card">

    <div class="header">
        <div class="brand">{{ config('app.name') }}</div>
        <div class="subtitle">Panneau d'administration</div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        <h2>Bienvenue, {{ $user->name }} !</h2>

        <p>Votre compte administrateur a été créé avec succès. Voici vos identifiants de connexion :</p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mot de passe temporaire</span>
                <span class="info-value"><strong>{{ $password }}</strong></span>
            </div>
        </div>

        <div class="btn-wrap">
            <a href="{{ $loginUrl }}" class="btn">Se connecter à mon compte</a>
        </div>

        <div class="alert alert-warning">
            <strong>Important</strong>
            Ce mot de passe est temporaire. Modifiez-le dès votre première connexion dans les paramètres de votre profil.
        </div>

        <hr>

        <p>Une fois connecté(e), vous pourrez mettre à jour votre profil, modifier votre mot de passe et accéder aux fonctionnalités selon vos permissions.</p>

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
