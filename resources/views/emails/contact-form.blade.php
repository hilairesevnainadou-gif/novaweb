<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isCopy ? 'Copie de votre message' : 'Nouveau message de contact' }}</title>
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
        .greeting strong {
            color: #1e293b;
        }
        .highlight {
            color: #2563eb;
            font-weight: 600;
        }
        .info-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #475569;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            color: #1e293b;
        }
        .message-content {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-top: 20px;
        }
        .tech-info {
            margin-top: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
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
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .info-item {
                margin-bottom: 12px;
                padding-bottom: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                @if($isCopy)
                    <h1>Copie de votre message</h1>
                    <p>Nova Tech Benin - Solutions Informatiques & Web</p>
                @else
                    <h1>Nouveau message de contact</h1>
                    <p>Formulaire de contact - Nova Tech Benin</p>
                @endif
            </div>

            <div class="content">
                @if($isCopy)
                    <div class="greeting">
                        Bonjour <span class="highlight">{{ $data['name'] }}</span>,
                    </div>
                    <p>Nous accusons reception de votre message. Voici un recapitulatif :</p>
                @else
                    <div class="greeting">
                        Vous avez recu un nouveau message de contact :
                    </div>
                @endif

                <div class="info-section">
                    <div class="info-item">
                        <span class="label">Nom complet :</span>
                        <span class="value">{{ $data['name'] }}</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Email :</span>
                        <span class="value">{{ $data['email'] }}</span>
                    </div>

                    @if($data['phone'])
                    <div class="info-item">
                        <span class="label">Telephone :</span>
                        <span class="value">{{ $data['phone'] }}</span>
                    </div>
                    @endif

                    <div class="info-item">
                        <span class="label">Service souhaite :</span>
                        <span class="value">{{ $data['service_label'] }}</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Recu le :</span>
                        <span class="value">{{ $data['received_at'] }}</span>
                    </div>
                </div>

                <div class="message-content">
                    <span class="label">Message :</span>
                    <p class="value">{{ $data['message'] }}</p>
                </div>

                @if(!$isCopy)
                    <div class="tech-info">
                        <p style="margin: 0; color: #475569;">
                            <strong>Informations techniques :</strong><br>
                            IP : {{ $data['ip_address'] }}<br>
                            Navigateur : {{ Str::limit($data['user_agent'], 100) }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="footer">
                @if($isCopy)
                    <p>Nous traiterons votre demande dans les plus brefs delais.</p>
                    <p>Vous recevrez une reponse dans les 24h ouvertes.</p>
                @endif
                <p style="margin-top: 15px;">
                    <strong>Nova Tech Benin</strong><br>
                    {{ config('app.company_address', 'Abomey-Calavi, Benin') }}<br>
                    {{ config('app.company_phone', '+229 66185595') }}<br>
                    {{ config('app.url', 'https://novatech.bj') }}
                </p>
                <p>&copy; {{ date('Y') }} Nova Tech Benin. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
