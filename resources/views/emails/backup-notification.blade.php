{{-- resources/views/emails/backup-notification.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de sauvegarde - {{ $company_name }}</title>
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
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 4px 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #64748b; }
        .info-value { color: #0f172a; }
        .alert { border-radius: 8px; padding: 14px 16px; margin: 20px 0; font-size: 13px; }
        .alert-success { background: #f0fdf4; border-left: 4px solid #10b981; color: #065f46; }
        .alert strong  { display: block; margin-bottom: 4px; }
        hr { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
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
        <div class="brand">{{ $company_name }}</div>
        <div class="subtitle">Rapport de sauvegarde — {{ $backup_type }}</div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        <h2>Sauvegarde effectuée avec succès</h2>

        <div class="alert alert-success">
            <strong>Sauvegarde {{ strtolower($backup_type) }} réussie</strong>
            Le fichier de sauvegarde a été généré et est joint à cet email.
        </div>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Date</span>
                <span class="info-value">{{ $backup_date }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Type</span>
                <span class="info-value">{{ $backup_type }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fichier</span>
                <span class="info-value">{{ $backup_filename }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Taille</span>
                <span class="info-value">{{ $backup_size }}</span>
            </div>
        </div>

        <p>Le fichier de sauvegarde est disponible en pièce jointe de cet email. Conservez-le en lieu sûr.</p>

        <hr>

        <p>Cordialement,<br><strong>L'équipe {{ $company_name }}</strong></p>
    </div>

    <div class="footer">
        <p><strong>{{ $company_name }}</strong></p>
        <p>Cet email a été envoyé automatiquement — merci de ne pas y répondre.</p>
        <p>&copy; {{ date('Y') }} {{ $company_name }}. Tous droits réservés.</p>
    </div>

</div>
</div>
</body>
</html>
