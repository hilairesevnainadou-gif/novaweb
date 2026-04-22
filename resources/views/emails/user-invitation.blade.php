<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitation NovaTech</title>
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
        .content { padding: 30px 0; }
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
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .expiry {
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

            <p>
                Vous avez été invité(e) à rejoindre <strong>{{ config('app.name') }}</strong>.
                Cliquez sur le bouton ci-dessous pour activer votre compte et choisir votre mot de passe.
            </p>

            <div class="info-box">
                <strong>Email :</strong> {{ $user->email }}
            </div>

            <div style="text-align: center;">
                <a href="{{ $invitationUrl }}" class="button">
                    Activer mon compte
                </a>
            </div>

            <div class="expiry">
                Ce lien expire dans 72 heures.
                Après expiration, contactez l'administrateur pour recevoir une nouvelle invitation.
            </div>

            <p class="url-fallback">
                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                {{ $invitationUrl }}
            </p>

            <p>Si vous n'attendiez pas cette invitation, ignorez cet email.</p>

            <p>Cordialement,<br><strong>L'équipe NovaTech</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} NovaTech. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
