{{-- resources/views/emails/payment_receipt.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recu de paiement - {{ $payment->payment_number }}</title>
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
            margin: 0 auto;
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
        .header-logo {
            max-width: 100px;
            max-height: 80px;
            margin-bottom: 16px;
            display: inline-block;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header .company-name {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 8px;
        }
        .header .payment-number {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 8px;
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
        .check-icon {
            text-align: center;
            font-size: 56px;
            color: #16a34a;
            margin: 10px 0;
        }
        .payment-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin: 24px 0;
            border: 1px solid #e2e8f0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #475569;
            width: 40%;
        }
        .detail-value {
            color: #1e293b;
            width: 60%;
            text-align: right;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #16a34a;
            text-align: center;
            margin: 20px 0;
        }
        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-deposit {
            background: #fef3c7;
            color: #d97706;
        }
        .badge-full {
            background: #d1fae5;
            color: #059669;
        }
        .badge-partial {
            background: #e0f2fe;
            color: #0284c7;
        }
        .remaining-box {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 8px;
            border: 1px solid #fde68a;
        }
        .remaining-box p {
            margin: 0;
            color: #d97706;
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
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 4px;
            }
            .detail-value {
                width: 100%;
                text-align: left;
            }
            .amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                @if(isset($company) && $company->logo_url)
                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="header-logo">
                @endif
                <h1>RECU DE PAIEMENT</h1>
                <div class="company-name">{{ $company->name ?? config('app.name') }}</div>
                <div class="payment-number">N° {{ $payment->payment_number }}</div>
            </div>

            <div class="content">
                <div class="check-icon">
                    ✓
                </div>

                <div class="greeting">
                    Bonjour <strong>{{ $payment->client->name }}</strong>,
                </div>

                <p>Nous vous confirmons la reception de votre paiement.</p>

                <div class="payment-details">
                    <div class="detail-row">
                        <span class="detail-label">Numero de paiement</span>
                        <span class="detail-value">{{ $payment->payment_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date du paiement</span>
                        <span class="detail-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type de paiement</span>
                        <span class="detail-value">
                            @if($payment->payment_type == 'deposit') Acompte
                            @elseif($payment->payment_type == 'partial') Paiement partiel
                            @else Paiement complet
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mode de paiement</span>
                        <span class="detail-value">
                            @if($payment->payment_method == 'cash') Especes
                            @elseif($payment->payment_method == 'bank_transfer') Virement bancaire
                            @elseif($payment->payment_method == 'mobile_money') Mobile Money
                            @elseif($payment->payment_method == 'card') Carte bancaire
                            @else {{ $payment->payment_method }}
                            @endif
                        </span>
                    </div>
                    @if($payment->reference)
                    <div class="detail-row">
                        <span class="detail-label">Reference</span>
                        <span class="detail-value">{{ $payment->reference }}</span>
                    </div>
                    @endif
                    @if($payment->invoice)
                    <div class="detail-row">
                        <span class="detail-label">Facture associee</span>
                        <span class="detail-value">{{ $payment->invoice->invoice_number }}</span>
                    </div>
                    @endif
                </div>

                <div class="amount">
                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                </div>

                <div class="text-center">
                    @if($payment->payment_type == 'deposit')
                        <span class="badge badge-deposit">Acompte enregistre</span>
                    @elseif($payment->payment_type == 'partial')
                        <span class="badge badge-partial">Paiement partiel</span>
                    @else
                        <span class="badge badge-full">Paiement valide</span>
                    @endif
                </div>

                @if(isset($payment->invoice) && $payment->invoice && property_exists($payment->invoice, 'remaining_amount') && $payment->invoice->remaining_amount > 0)
                <div class="remaining-box">
                    <p><strong>Solde restant : {{ number_format($payment->invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong></p>
                </div>
                @endif

                <hr>

                <p style="font-size: 14px; color: #64748b; margin-top: 20px; text-align: center;">
                    Merci de votre confiance.
                </p>
            </div>

            <div class="footer">
                <p><strong>{{ $company->name ?? config('app.name') }}</strong></p>
                <p>{{ $company->address ?? 'Abomey-Calavi, Benin' }}</p>
                <p>Email : {{ $company->email ?? 'contact@exemple.bj' }} | Tel : {{ $company->phone ?? '+229 XX XX XX XX' }}</p>
                <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
