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
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #10b981;
        }
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            color: #10b981;
            margin-bottom: 15px;
        }
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            text-align: left;
        }
        .info-row {
            padding: 5px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $company_name ?? config('app.name') }}</h1>
            <p>Test de configuration des sauvegardes</p>
        </div>

        <div class="content">
            <div class="success-icon">✅</div>
            <h2>Test réussi !</h2>
            <p>Votre configuration des sauvegardes fonctionne correctement.</p>

            <div class="info-box">
                <div class="info-row"><strong>Date du test :</strong> {{ $test_date ?? date('d/m/Y H:i:s') }}</div>
                <div class="info-row"><strong>Message :</strong> {{ $email_message ?? 'Email de test automatique' }}</div>
            </div>

            <p>Ceci est un email de test automatique. Aucune action n'est requise.</p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement par {{ $company_name ?? config('app.name') }}.</p>
            <p>&copy; {{ date('Y') }} {{ $company_name ?? config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
