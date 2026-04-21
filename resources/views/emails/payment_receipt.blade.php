{{-- resources/views/emails/payment_receipt.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement - {{ $payment->payment_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .payment-details {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .payment-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-details td {
            padding: 8px 0;
        }
        .payment-details td:first-child {
            font-weight: 600;
            color: #475569;
        }
        .payment-details td:last-child {
            text-align: right;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #10b981;
            text-align: center;
            margin: 20px 0;
        }
        .check-icon {
            text-align: center;
            font-size: 60px;
            color: #10b981;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-deposit { background: #fef3c7; color: #d97706; }
        .badge-full { background: #d1fae5; color: #059669; }
        @media (max-width: 600px) {
            .container { padding: 10px; }
            .content { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reçu de paiement</h1>
            <p>{{ $payment->payment_number }}</p>
        </div>
        <div class="content">
            <div class="check-icon">
                ✓
            </div>

            <p>Bonjour <strong>{{ $payment->client->name }}</strong>,</p>

            <p>Nous vous confirmons la réception de votre paiement.</p>

            <div class="payment-details">
                <table>
                    <tr><td>N° Paiement</td><td>{{ $payment->payment_number }}</td></tr>
                    <tr><td>Date du paiement</td><td>{{ $payment->payment_date->format('d/m/Y') }}</td></tr>
                    <tr><td>Type de paiement</td><td>
                        @if($payment->payment_type == 'deposit') Acompte
                        @elseif($payment->payment_type == 'partial') Paiement partiel
                        @else Paiement complet
                        @endif
                    </td></tr>
                    <tr><td>Mode de paiement</td><td>
                        @if($payment->payment_method == 'cash') Espèces
                        @elseif($payment->payment_method == 'bank_transfer') Virement bancaire
                        @elseif($payment->payment_method == 'mobile_money') Mobile Money
                        @elseif($payment->payment_method == 'card') Carte bancaire
                        @else {{ $payment->payment_method }}
                        @endif
                    </td></tr>
                    @if($payment->reference)
                    <tr><td>Référence</td><td>{{ $payment->reference }}</td></tr>
                    @endif
                    @if($payment->invoice)
                    <tr><td>Facture associée</td><td>{{ $payment->invoice->invoice_number }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="amount">
                {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
            </div>

            <div style="text-align: center;">
                @if($payment->payment_type == 'deposit')
                    <span class="badge badge-deposit">Acompte enregistré</span>
                @else
                    <span class="badge badge-full">Paiement validé</span>
                @endif
            </div>

            @if($payment->invoice && $payment->invoice->remaining_amount > 0)
            <div style="text-align: center; margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px;">
                <p style="margin: 0; color: #d97706;">
                    <strong>Solde restant : {{ number_format($payment->invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong>
                </p>
            </div>
            @endif

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('admin.billing.payments.show', $payment) }}" class="button">Voir le reçu en ligne</a>
            </div>

            <p style="font-size: 14px; color: #64748b; margin-top: 20px;">
                Merci de votre confiance.<br>
                L'équipe {{ $company->name ?? config('app.name') }}
            </p>
        </div>
        <div class="footer">
            <p>{{ $company->name ?? config('app.name') }} - {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
            <p>Email: {{ $company->email ?? 'contact@novatech.bj' }} | Tél: {{ $company->phone ?? '+229 66 18 55 95' }}</p>
            <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
