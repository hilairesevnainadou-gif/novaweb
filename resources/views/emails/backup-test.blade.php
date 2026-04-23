{{-- resources/views/emails/backup-test.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de configuration - {{ $company_name ?? config('app.name') }}</title>
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
            margin: 20px auto;
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
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.8;
            font-size: 14px;
        }
        .content {
            padding: 32px 24px;
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            color: #10b981;
            margin-bottom: 15px;
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
            text-align: left;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
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
        h2 {
            color: #1e293b;
            margin: 0 0 16px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <h1>{{ $company_name ?? config('app.name') }}</h1>
                <p>Test de configuration des sauvegardes</p>
            </div>

            <div class="content">
                <div class="success-icon">✓</div>
                <h2>Test reussi !</h2>
                <p>Votre configuration des sauvegardes fonctionne correctement.</p>

                <div class="info-box">
                    <div class="info-row"><strong>Date du test :</strong> {{ $test_date ?? date('d/m/Y H:i:s') }}</div>
                    <div class="info-row"><strong>Message :</strong> {{ $email_message ?? 'Email de test automatique' }}</div>
                </div>

                <p>Ceci est un email de test automatique. Aucune action n'est requise.</p>
            </div>

            <div class="footer">
                <p>Cet email a ete envoye automatiquement par {{ $company_name ?? config('app.name') }}.</p>
                <p>&copy; {{ date('Y') }} {{ $company_name ?? config('app.name') }}. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
