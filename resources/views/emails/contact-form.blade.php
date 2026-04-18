<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isCopy ? 'Copie de votre message' : 'Nouveau message de contact' }}</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            color: #555;
        }
        .message-content {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-top: 20px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .highlight {
            color: #667eea;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
            .email-header {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            @if($isCopy)
                <h1>📋 Copie de votre message</h1>
                <p>Nova Tech Bénin - Solutions Informatiques & Web</p>
            @else
                <h1>📨 Nouveau message de contact</h1>
                <p>Formulaire de contact - Nova Tech Bénin</p>
            @endif
        </div>

        <div class="email-body">
            @if($isCopy)
                <p>Bonjour <span class="highlight">{{ $data['name'] }}</span>,</p>
                <p>Nous accusons réception de votre message. Voici un récapitulatif :</p>
            @else
                <p>Vous avez reçu un nouveau message de contact :</p>
            @endif

            <div class="info-section">
                <div class="info-item">
                    <span class="label">👤 Nom complet :</span>
                    <span class="value">{{ $data['name'] }}</span>
                </div>

                <div class="info-item">
                    <span class="label">📧 Email :</span>
                    <span class="value">{{ $data['email'] }}</span>
                </div>

                @if($data['phone'])
                <div class="info-item">
                    <span class="label">📞 Téléphone :</span>
                    <span class="value">{{ $data['phone'] }}</span>
                </div>
                @endif

                <div class="info-item">
                    <span class="label">🛠️ Service souhaité :</span>
                    <span class="value">{{ $data['service_label'] }}</span>
                </div>

                <div class="info-item">
                    <span class="label">📅 Reçu le :</span>
                    <span class="value">{{ $data['received_at'] }}</span>
                </div>
            </div>

            <div class="message-content">
                <span class="label">💬 Message :</span>
                <p class="value">{{ $data['message'] }}</p>
            </div>

            @if(!$isCopy)
                <div style="margin-top: 25px; padding: 15px; background: #f0f7ff; border-radius: 8px;">
                    <p style="margin: 0; color: #555;">
                        <strong>Informations techniques :</strong><br>
                        📱 IP : {{ $data['ip_address'] }}<br>
                        🌐 Navigateur : {{ Str::limit($data['user_agent'], 100) }}
                    </p>
                </div>
            @endif
        </div>

        <div class="email-footer">
            @if($isCopy)
                <p>Nous traiterons votre demande dans les plus brefs délais.</p>
                <p>✅ Vous recevrez une réponse dans les 24h ouvrées.</p>
            @endif
            <p style="margin-top: 15px;">
                <strong>Nova Tech Bénin</strong><br>
                📍 {{ config('app.company_address', 'Abomey-Calavi, Bénin') }}<br>
                📞 {{ config('app.company_phone', '+229 66185595') }}<br>
                🌐 {{ config('app.url', 'https://novatech.bj') }}
            </p>
        </div>
    </div>
</body>
</html>
