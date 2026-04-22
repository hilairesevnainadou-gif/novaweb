{{-- resources/views/emails/backup-notification.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sauvegarde - {{ $company_name }}</title>
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
            border-bottom: 2px solid #3b82f6;
        }
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
        }
        .content {
            padding: 20px 0;
        }
        .info-box {
            background-color: #f0f4f8;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .success {
            color: #10b981;
        }
        .warning {
            color: #f59e0b;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
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
            <h1>{{ $company_name }}</h1>
            <p>Sauvegarde {{ $backup_type }}</p>
        </div>

        <div class="content">
            <h2> Rapport de sauvegarde</h2>
            <p>Une sauvegarde {{ strtolower($backup_type) }} a été effectuée avec succès.</p>

            <div class="info-box">
                <div class="info-row">
                    <div class="info-label">Date :</div>
                    <div class="info-value">{{ $backup_date }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Type :</div>
                    <div class="info-value">{{ $backup_type }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fichier :</div>
                    <div class="info-value">{{ $backup_filename }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Taille :</div>
                    <div class="info-value">{{ $backup_size }}</div>
                </div>
            </div>

            <p>Le fichier de sauvegarde est joint à cet email.</p>

            <a href="{{ $backup_path }}" class="button">📥 Télécharger la sauvegarde</a>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement par {{ $company_name }}.</p>
            <p>&copy; {{ date('Y') }} {{ $company_name }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
