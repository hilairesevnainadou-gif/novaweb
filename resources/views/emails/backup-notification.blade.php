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
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #475569;
        }
        .info-value {
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
            margin: 16px 0;
        }
        .button:hover {
            background: #1d4ed8;
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
                <h1>{{ $company_name }}</h1>
                <p>Sauvegarde {{ $backup_type }}</p>
            </div>

            <div class="content">
                <div class="greeting">
                    Rapport de sauvegarde
                </div>

                <p>Une sauvegarde {{ strtolower($backup_type) }} a ete effectuee avec succes.</p>

                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Date :</span>
                        <span class="info-value">{{ $backup_date }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type :</span>
                        <span class="info-value">{{ $backup_type }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fichier :</span>
                        <span class="info-value">{{ $backup_filename }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Taille :</span>
                        <span class="info-value">{{ $backup_size }}</span>
                    </div>
                </div>

                <p>Le fichier de sauvegarde est joint a cet email.</p>

                <div class="text-center">
                    <a href="{{ $backup_path }}" class="button">Telecharger la sauvegarde</a>
                </div>
            </div>

            <div class="footer">
                <p>Cet email a ete envoye automatiquement par {{ $company_name }}.</p>
                <p>&copy; {{ date('Y') }} {{ $company_name }}. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
