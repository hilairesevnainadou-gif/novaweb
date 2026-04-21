{{-- resources/views/pdf/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
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
        }
        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }
        .invoice-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .invoice-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .invoice-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .invoice-body {
            padding: 30px;
        }
        .company-info, .client-info {
            margin-bottom: 30px;
        }
        .company-info h3, .client-info h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #4f46e5;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
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
        .invoice-details {
            margin: 30px 0;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .invoice-table th {
            background: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals {
            width: 100%;
            max-width: 350px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 8px 0;
        }
        .totals .total-row {
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-paid { background: #d1fae5; color: #059669; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-overdue { background: #fee2e2; color: #dc2626; }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        .payment-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .bank-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .bank-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        .bank-info strong {
            color: #475569;
            display: inline-block;
            width: 120px;
        }
        .mobile-info {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .mobile-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        .mobile-number {
            font-size: 18px;
            font-weight: bold;
            color: #92400e;
            text-align: center;
            margin: 10px 0;
            letter-spacing: 1px;
        }
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #4f46e5;
            padding: 12px;
            border-radius: 8px;
            font-size: 10px;
            margin-top: 15px;
        }
        .warning-note {
            background: #fef2f2;
            padding: 10px;
            border-radius: 8px;
            font-size: 10px;
            text-align: center;
            margin-top: 20px;
            color: #991b1b;
        }
        .text-right {
            text-align: right;
        }
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
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>FACTURE</h1>
            <p>N° {{ $invoice->invoice_number }}</p>
        </div>

        <div class="invoice-body">
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
                    @if($company->rccm)
                    <div class="info-row">
                        <div class="info-label">RCCM</div>
                        <div class="info-value">{{ $company->rccm }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client -->
            <div class="client-info">
                <h3>Facture a</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nom</div>
                        <div class="info-value">{{ $invoice->client->name }}</div>
                    </div>
                    @if($invoice->client->address)
                    <div class="info-row">
                        <div class="info-label">Adresse</div>
                        <div class="info-value">{{ $invoice->client->address }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $invoice->client->email }}</div>
                    </div>
                    @if($invoice->client->phone)
                    <div class="info-row">
                        <div class="info-label">Telephone</div>
                        <div class="info-value">{{ $invoice->client->phone }}</div>
                    </div>
                    @endif
                    @if($invoice->client->tax_number)
                    <div class="info-row">
                        <div class="info-label">IFU</div>
                        <div class="info-value">{{ $invoice->client->tax_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Details facture -->
            <div class="invoice-details">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Date d'emission</div>
                        <div class="info-value">{{ $invoice->issue_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date d'echéance</div>
                        <div class="info-value">{{ $invoice->due_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            @if($invoice->status == 'paid')
                                <span class="status-badge status-paid">Payee</span>
                            @elseif($invoice->due_date < now())
                                <span class="status-badge status-overdue">En retard</span>
                            @else
                                <span class="status-badge status-pending">En attente de paiement</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lignes de facture -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Montant HT</th>
                        <th class="text-right">TVA</th>
                        <th class="text-right">Montant TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->description ?? ($invoice->service->name ?? 'Prestation de services') }}</td>
                        <td class="text-right">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right">{{ $invoice->tax_rate }}%</td>
                        <td class="text-right">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totaux -->
            <div class="totals">
                <table>
                    <tr>
                        <td>Sous-total HT</td>
                        <td class="text-right">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td>TVA ({{ $invoice->tax_rate }}%)</td>
                        <td class="text-right">{{ number_format($invoice->tax_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>TOTAL TTC</strong></td>
                        <td class="text-right"><strong>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                    @if($invoice->paid_amount > 0)
                    <tr>
                        <td>Montant deja paye</td>
                        <td class="text-right" style="color: #10b981;">- {{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td><strong>Reste a payer</strong></td>
                        <td class="text-right"><strong style="color: #f59e0b;">{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Section Paiement -->
            <div class="payment-section">
                <div class="payment-title">Instructions de paiement</div>

                <!-- Virement bancaire -->
                @if($company->bank_name || $company->bank_account_number)
                <div class="bank-info">
                    <p><strong>Virement bancaire</strong></p>
                    @if($company->bank_name)
                    <p><strong>Banque :</strong> {{ $company->bank_name }}</p>
                    @endif
                    @if($company->bank_account_name)
                    <p><strong>Titulaire :</strong> {{ $company->bank_account_name }}</p>
                    @endif
                    @if($company->bank_account_number)
                    <p><strong>Compte :</strong> {{ $company->bank_account_number }}</p>
                    @endif
                    @if($company->bank_iban)
                    <p><strong>IBAN :</strong> {{ $company->bank_iban }}</p>
                    @endif
                    @if($company->bank_swift)
                    <p><strong>SWIFT/BIC :</strong> {{ $company->bank_swift }}</p>
                    @endif
                </div>
                @endif

                <!-- Mobile Money -->
                @if($company->mobile_money_number)
                <div class="mobile-info">
                    <p class="text-center"><strong>Paiement Mobile Money</strong></p>
                    <div class="mobile-number">{{ $company->mobile_money_number }}</div>
                    <p class="text-center">
                        @if($company->mobile_money_operator == 'mtn')
                            Operateur: MTN
                        @elseif($company->mobile_money_operator == 'moov')
                            Operateur: MOOV
                        @elseif($company->mobile_money_operator == 'celcom')
                            Operateur: Celcom
                        @else
                            Operateur: {{ $company->mobile_money_operator }}
                        @endif
                    </p>
                    <p class="text-center" style="font-size: 10px; margin-top: 8px;">
                        Utilisez le numero ci-dessus pour effectuer votre paiement
                    </p>
                </div>
                @endif

                <!-- Instructions supplementaires -->
                @if($company->payment_instructions)
                <div class="instructions">
                    <p>{{ $company->payment_instructions }}</p>
                </div>
                @endif

                <!-- Message par defaut -->
                @if(!$company->bank_name && !$company->bank_account_number && !$company->mobile_money_number)
                <div class="bank-info">
                    <p class="text-center">Veuillez nous contacter pour obtenir les informations de paiement.</p>
                </div>
                @endif
            </div>

            <!-- Information importante -->
            <div class="warning-note">
                <strong>Important :</strong> Veuillez utiliser le numero de facture <strong>{{ $invoice->invoice_number }}</strong> comme reference de votre paiement.
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Merci de votre confiance. En cas de question, contactez-nous a {{ $company->email ?? 'contact@example.bj' }}</p>
                <p>{{ $company->name ?? config('app.name') }} - {{ $company->address ?? 'Abomey-Calavi, Benin' }}</p>
                <p>© {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits reserves.</p>
            </div>
        </div>
    </div>
</body>
</html>
