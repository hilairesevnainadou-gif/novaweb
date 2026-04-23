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
        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .info-box strong {
            color: #475569;
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
        .url-fallback {
            font-size: 12px;
            color: #64748b;
            word-break: break-all;
            margin-top: 15px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
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

                <div class="info-box">
                    <strong>Email :</strong> {{ $user->email }}
                </div>

                <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>

                <div class="text-center">
                    <a href="{{ $invitationUrl }}" class="button">Reinitialiser mon mot de passe</a>
                </div>

                <div class="warning-box">
                    <strong>Important :</strong> Ce lien est valable 72 heures. Passe ce delai, vous devrez faire une nouvelle demande.
                </div>

                <div class="url-fallback">
                    Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                    {{ $invitationUrl }}
                </div>

                <p>Si vous n'etes pas a l'origine de cette demande, veuillez ignorer cet email. Votre mot de passe actuel reste valable.</p>

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
