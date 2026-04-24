{{-- resources/views/emails/contact-form.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCopy ? 'Copie de votre message' : 'Nouveau message de contact' }} - {{ config('app.name') }}</title>
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
        .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; gap: 12px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #64748b; min-width: 130px; flex-shrink: 0; }
        .info-value { color: #0f172a; }
        .message-box { background: #eff6ff; border-left: 4px solid #2563eb; border-radius: 0 8px 8px 0; padding: 16px 20px; margin: 20px 0; font-size: 14px; color: #1e293b; }
        .message-box .msg-label { font-weight: 700; color: #2563eb; margin-bottom: 8px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .tech-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin: 16px 0; font-size: 12px; color: #64748b; }
        .alert { border-radius: 8px; padding: 14px 16px; margin: 20px 0; font-size: 13px; }
        .alert-info { background: #eff6ff; border-left: 4px solid #2563eb; color: #1e40af; }
        .alert strong { display: block; margin-bottom: 4px; }
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
        <div class="brand">{{ config('app.name') }}</div>
        <div class="subtitle">
            @if($isCopy) Copie de votre message envoyé
            @else Nouveau message de contact reçu
            @endif
        </div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        @if($isCopy)
            <h2>Bonjour {{ $data['name'] }},</h2>
            <p>Nous accusons bien réception de votre message. Voici le récapitulatif de votre demande :</p>
        @else
            <h2>Nouveau message de contact</h2>
            <p>Vous avez reçu un nouveau message via le formulaire de contact :</p>
        @endif

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Nom complet</span>
                <span class="info-value">{{ $data['name'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $data['email'] }}</span>
            </div>
            @if($data['phone'])
            <div class="info-row">
                <span class="info-label">Téléphone</span>
                <span class="info-value">{{ $data['phone'] }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Service souhaité</span>
                <span class="info-value">{{ $data['service_label'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Reçu le</span>
                <span class="info-value">{{ $data['received_at'] }}</span>
            </div>
        </div>

        <div class="message-box">
            <div class="msg-label">Message</div>
            {{ $data['message'] }}
        </div>

        @if(!$isCopy)
        <div class="tech-box">
            <strong>Informations techniques</strong><br>
            Adresse IP : {{ $data['ip_address'] }}<br>
            Navigateur : {{ Str::limit($data['user_agent'], 100) }}
        </div>
        @endif

        @if($isCopy)
        <div class="alert alert-info">
            <strong>Votre demande est bien enregistrée</strong>
            Nous traiterons votre demande dans les plus brefs délais. Vous recevrez une réponse sous 24h ouvrées.
        </div>
        @endif

        <hr>

        <p style="text-align:center; font-size:13px; color:#64748b;">
            {{ config('app.name') }} &mdash; {{ config('app.company_address', 'Abomey-Calavi, Bénin') }}<br>
            {{ config('app.company_phone', '+229 66 18 55 95') }} &mdash; {{ config('app.url') }}
        </p>
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
