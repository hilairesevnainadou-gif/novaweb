{{-- resources/views/pdf/payment_receipt.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recu de paiement - {{ $payment->payment_number ?? '#' . $payment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
            background: #f8fafc;
        }
        .receipt-box {
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .receipt-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .receipt-header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .receipt-body {
            padding: 25px;
        }
        .check-icon {
            text-align: center;
            font-size: 48px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .check-icon span {
            display: inline-block;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            background: #d1fae5;
        }
        .company-info, .client-info {
            margin-bottom: 20px;
        }
        .company-info h3, .client-info h3 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #059669;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 4px 0;
            font-weight: 600;
            color: #475569;
            width: 130px;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
        }
        .amount {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .amount-number {
            font-size: 32px;
            font-weight: bold;
            color: #10b981;
        }
        .amount-label {
            font-size: 11px;
            color: #64748b;
            margin-top: 8px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-deposit { background: #fef3c7; color: #d97706; }
        .badge-partial { background: #dbeafe; color: #2563eb; }
        .badge-full { background: #d1fae5; color: #059669; }
        .badge-cash { background: #e0e7ff; color: #4338ca; }
        .badge-transfer { background: #f3e8ff; color: #9333ea; }
        .badge-mobile { background: #fef3c7; color: #d97706; }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .mb-2 {
            margin-bottom: 10px;
        }
        .mt-3 {
            margin-top: 15px;
        }
        .remaining-balance {
            margin-top: 15px;
            padding: 12px;
            background: #fef3c7;
            border-radius: 8px;
            text-align: center;
        }
        .remaining-balance span {
            color: #d97706;
            font-weight: bold;
        }
        .payment-details {
            margin: 15px 0;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #e2e8f0;
        }
        .signature {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="receipt-header">
            <h1>RECU DE PAIEMENT</h1>
            <p>N° {{ $payment->payment_number ?? '#' . $payment->id }}</p>
        </div>

        <div class="receipt-body">
            <div class="check-icon">
                <span>✓</span>
            </div>

            <!-- Emetteur -->
            <div class="company-info">
                <h3>{{ $company->name ?? config('app.name') }}</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Adresse</div>
                        <div class="info-value">{{ $company->address ?? 'Abomey-Calavi, Benin' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $company->email ?? 'contact@example.bj' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telephone</div>
                        <div class="info-value">{{ $company->phone ?? '+229 XX XX XX XX' }}</div>
                    </div>
                    @if($company->ifu)
                    <div class="info-row">
                        <div class="info-label">IFU</div>
                        <div class="info-value">{{ $company->ifu }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client -->
            <div class="client-info">
                <h3>Paye par</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nom</div>
                        <div class="info-value">{{ $payment->client->name ?? 'Client' }}</div>
                    </div>
                    @if($payment->client->email ?? false)
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $payment->client->email }}</div>
                    </div>
                    @endif
                    @if($payment->client->phone ?? false)
                    <div class="info-row">
                        <div class="info-label">Telephone</div>
                        <div class="info-value">{{ $payment->client->phone }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Details paiement -->
            <div class="payment-details">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Date du paiement</div>
                        <div class="info-value">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Non definie' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Type de paiement</div>
                        <div class="info-value">
                            @if($payment->payment_type == 'deposit')
                                <span class="badge badge-deposit">Acompte</span>
                            @elseif($payment->payment_type == 'partial')
                                <span class="badge badge-partial">Paiement partiel</span>
                            @else
                                <span class="badge badge-full">Paiement complet</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Mode de paiement</div>
                        <div class="info-value">
                            @if($payment->payment_method == 'cash')
                                <span class="badge badge-cash">Especes</span>
                            @elseif($payment->payment_method == 'bank_transfer')
                                <span class="badge badge-transfer">Virement bancaire</span>
                            @elseif($payment->payment_method == 'mobile_money')
                                <span class="badge badge-mobile">Mobile Money</span>
                            @elseif($payment->payment_method == 'card')
                                Carte bancaire
                            @elseif($payment->payment_method == 'check')
                                Cheque
                            @else
                                {{ $payment->payment_method ?? 'Non specifie' }}
                            @endif
                        </div>
                    </div>
                    @if($payment->reference)
                    <div class="info-row">
                        <div class="info-label">Reference</div>
                        <div class="info-value">{{ $payment->reference }}</div>
                    </div>
                    @endif
                    @if($payment->invoice)
                    <div class="info-row">
                        <div class="info-label">Facture associee</div>
                        <div class="info-value">
                            <strong>{{ $payment->invoice->invoice_number ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    @endif
                    @if($payment->notes)
                    <div class="info-row">
                        <div class="info-label">Notes</div>
                        <div class="info-value">{{ $payment->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Montant -->
            <div class="amount">
                <div class="amount-number">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                <div class="amount-label">Montant paye</div>
            </div>

            <!-- Solde restant -->
            @if($payment->invoice && $payment->invoice->remaining_amount > 0)
            <div class="remaining-balance">
                <span>Solde restant : {{ number_format($payment->invoice->remaining_amount, 0, ',', ' ') }} FCFA</span>
            </div>
            @elseif($payment->invoice && $payment->invoice->remaining_amount == 0)
            <div class="remaining-balance" style="background: #d1fae5;">
                <span style="color: #059669;">Facture completement payee</span>
            </div>
            @endif

            <!-- Informations supplementaires -->
            @if($payment->payment_method == 'mobile_money' && $company->mobile_money_number)
            <hr>
            <div style="font-size: 10px; color: #64748b; text-align: center;">
                <p>Paiement effectue via Mobile Money</p>
                <p>Numero: {{ $company->mobile_money_number }}</p>
                @if($company->mobile_money_operator)
                <p>Operateur:
                    @if($company->mobile_money_operator == 'mtn') MTN
                    @elseif($company->mobile_money_operator == 'moov') MOOV
                    @elseif($company->mobile_money_operator == 'celcom') Celcom
                    @else {{ $company->mobile_money_operator }}
                    @endif
                </p>
                @endif
            </div>
            @endif

            @if($payment->payment_method == 'bank_transfer' && ($company->bank_name || $company->bank_account_number))
            <hr>
            <div style="font-size: 10px; color: #64748b; text-align: center;">
                <p>Virement bancaire recu sur le compte :</p>
                @if($company->bank_name)<p>Banque: {{ $company->bank_name }}</p>@endif
                @if($company->bank_account_number)<p>Compte: {{ $company->bank_account_number }}</p>@endif
            </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p>Ce document fait office de recu officiel.</p>
                <p>Merci de votre confiance.</p>
                <p>{{ $company->name ?? config('app.name') }} - {{ $company->address ?? 'Abomey-Calavi, Benin' }}</p>
                <p>© {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits reserves.</p>
            </div>

            <!-- Signature -->
            <div class="signature">
                <div>Fait a {{ $company->city ?? 'Cotonou' }}, le {{ now()->format('d/m/Y') }}</div>
                <div>Cachet et signature</div>
            </div>
        </div>
    </div>
</body>
</html>
