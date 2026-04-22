<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe - NovaTech</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .content {
            padding: 30px 0;
        }
        .info-box {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .warning {
            background-color: #fef3c7;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            color: #92400e;
            margin: 20px 0;
        }
        .url-fallback {
            font-size: 12px;
            color: #6b7280;
            word-break: break-all;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
        </div>

        <div class="content">
            <h2>Bonjour {{ $user->name }},</h2>

            <p>Une demande de réinitialisation de mot de passe a été effectuée pour votre compte.</p>

            <div class="info-box">
                <strong>Email :</strong> {{ $user->email }}
            </div>

            <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>

            <div style="text-align: center;">
                <a href="{{ $invitationUrl }}" class="button">
                    Réinitialiser mon mot de passe
                </a>
            </div>

            <div class="warning">
                <strong>Important :</strong> Ce lien est valable 72 heures. Passé ce délai, vous devrez faire une nouvelle demande.
            </div>

            <p class="url-fallback">
                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                {{ $invitationUrl }}
            </p>

            <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet email. Votre mot de passe actuel reste valable.</p>

            <p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
