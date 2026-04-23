<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur {{ config('app.name') }}</title>
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
        .credentials-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .credential-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .credential-item:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-weight: 600;
            color: #475569;
        }
        .credential-value {
            color: #1e293b;
            font-family: monospace;
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
        .features-list {
            margin: 20px 0;
            padding-left: 20px;
        }
        .features-list li {
            margin: 8px 0;
            color: #475569;
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

                <p>Bienvenue sur {{ config('app.name') }} ! Votre compte a ete cree avec succes par l'equipe d'administration.</p>

                <div class="credentials-box">
                    <div class="credential-item">
                        <span class="credential-label">Email :</span>
                        <span class="credential-value">{{ $user->email }}</span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Mot de passe temporaire :</span>
                        <span class="credential-value"><strong>{{ $password }}</strong></span>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ $loginUrl }}" class="button">Se connecter a mon compte</a>
                </div>

                <div class="warning-box">
                    <strong>Important :</strong> Ce mot de passe est temporaire. Nous vous recommandons de le modifier lors de votre premiere connexion.
                </div>

                <p>Une fois connecte, vous pourrez :</p>
                <ul class="features-list">
                    <li>Modifier votre mot de passe</li>
                    <li>Mettre a jour votre profil</li>
                    <li>Acceder a toutes les fonctionnalites selon vos permissions</li>
                </ul>

                <p>Si vous n'etes pas a l'origine de cette creation, veuillez ignorer cet email ou contacter l'administrateur.</p>

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
