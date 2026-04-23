<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reinitialisation de votre mot de passe - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        .email-wrapper {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #1e293b;
            color: white;
            padding: 32px 24px;
            text-align: center;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .greeting strong {
            color: #1e293b;
        }
        .new-password-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-family: monospace;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .warning-box {
            background: #fef3c7;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            color: #92400e;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .warning-box strong {
            color: #92400e;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <div class="logo">{{ config('app.name') }}</div>
            </div>

            <div class="content">
                <div class="greeting">
                    Bonjour <strong>{{ $user->name }}</strong>,
                </div>

                <p>Une demande de reinitialisation de mot de passe a ete effectuee pour votre compte.</p>

                <p>Votre nouveau mot de passe temporaire est :</p>

                <div class="new-password-box">
                    {{ $password }}
                </div>

                <div class="text-center">
                    <a href="{{ $loginUrl }}" class="button">Se connecter avec ce nouveau mot de passe</a>
                </div>

                <div class="warning-box">
                    <strong>Important :</strong> Ce mot de passe est temporaire. Nous vous recommandons de le modifier immediatement apres votre connexion.
                </div>

                <p>Si vous n'etes pas a l'origine de cette demande, veuillez contacter immediatement l'administrateur.</p>

                <p>Cordialement,<br><strong>L'equipe {{ config('app.name') }}</strong></p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits reserves.</p>
                <p>Cet email a ete envoye automatiquement, merci de ne pas y repondre.</p>
            </div>
        </div>
    </div>
</body>
</html>
