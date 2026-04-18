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
        .new-password {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .warning {
            background-color: #fef3c7;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            color: #92400e;
            margin: 20px 0;
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
            <div class="logo">NovaTech</div>
        </div>

        <div class="content">
            <h2>Bonjour {{ $user->name }},</h2>

            <p>Une demande de réinitialisation de mot de passe a été effectuée pour votre compte.</p>

            <p>Votre nouveau mot de passe temporaire est :</p>

            <div class="new-password">
                {{ $password }}
            </div>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Se connecter avec ce nouveau mot de passe</a>
            </div>

            <div class="warning">
                <strong>Important :</strong> Ce mot de passe est temporaire. Nous vous recommandons de le modifier immédiatement après votre connexion.
            </div>

            <p>Si vous n'êtes pas à l'origine de cette demande, veuillez contacter immédiatement l'administrateur.</p>

            <p>Cordialement,<br><strong>L'équipe NovaTech</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} NovaTech. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
