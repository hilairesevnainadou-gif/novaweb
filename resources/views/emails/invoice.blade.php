{{-- resources/views/emails/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }}</title>
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
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
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
        .header .invoice-number {
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
        .amount-box {
            text-align: center;
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            margin: 24px 0;
        }
        .amount-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: 700;
            color: #3b82f6;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }
        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }
        .status-paid {
            background: #d1fae5;
            color: #059669;
        }
        .status-overdue {
            background: #fee2e2;
            color: #dc2626;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
        }
        .button:hover {
            background: #2563eb;
        }
        .bank-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin: 24px 0;
            font-size: 13px;
        }
        .bank-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #1e293b;
        }
        .bank-info p {
            margin: 6px 0;
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
        .mt-4 {
            margin-top: 24px;
        }
        .mb-4 {
            margin-bottom: 24px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                padding: 12px 0;
            }
            .info-value {
                margin-top: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <h1>FACTURE</h1>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            </div>

            <div class="content">
                <div class="greeting">
                    Bonjour <strong>{{ $invoice->client->name }}</strong>,
                </div>

                <p>Veuillez trouver ci-joint votre facture <strong>{{ $invoice->invoice_number }}</strong>.</p>

                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Date d'émission</span>
                        <span class="info-value">{{ $invoice->issue_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date d'échéance</span>
                        <span class="info-value">{{ $invoice->due_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ $invoice->description }}</span>
                    </div>
                </div>

                <div class="amount-box">
                    <div class="amount-label">Montant total TTC</div>
                    <div class="amount-value">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</div>
                </div>

                <div class="text-center mb-4">
                    @if($invoice->status == 'paid')
                        <span class="status-badge status-paid">Payée</span>
                    @elseif($invoice->due_date < now())
                        <span class="status-badge status-overdue">En retard</span>
                    @else
                        <span class="status-badge status-pending">En attente de paiement</span>
                    @endif
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="button">
                        Voir la facture en ligne
                    </a>
                </div>

                <div class="bank-info">
                    <h4>Informations de paiement</h4>
                    <p>Banque : {{ $company->bank_name ?? 'Banque Atlantique Benin' }}</p>
                    <p>Compte : {{ $company->bank_account ?? 'CI 123 456 789' }}</p>
                    <p>IBAN : {{ $company->bank_iban ?? 'BJ23 1234 5678 9012 3456 7890 12' }}</p>
                </div>

                <p style="font-size: 13px; color: #64748b; margin-top: 24px;">
                    Merci de votre confiance.<br>
                    L'equipe {{ $company->name ?? config('app.name') }}
                </p>
            </div>

            <div class="footer">
                <p>{{ $company->name ?? config('app.name') }}</p>
                <p>{{ $company->address ?? 'Abomey-Calavi, Benin' }}</p>
                <p>Email: {{ $company->email ?? 'contact@novatech.bj' }} | Tel: {{ $company->phone ?? '+229 66 18 55 95' }}</p>
                <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
