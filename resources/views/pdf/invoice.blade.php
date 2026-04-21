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
            width: 120px;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
        }
        .invoice-details {
            margin: 30px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .invoice-table th {
            background: #f8fafc;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .invoice-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 6px 0;
        }
        .totals .total {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
            margin-top: 10px;
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
        .bank-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mb-2 {
            margin-bottom: 10px;
        }
        .mt-3 {
            margin-top: 15px;
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
            <!-- Émetteur -->
            <div class="company-info">
                <h3>{{ $company->name ?? config('app.name') }}</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Adresse</div>
                        <div class="info-value">{{ $company->address ?? 'Abomey-Calavi, Bénin' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $company->email ?? 'contact@novatech.bj' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">{{ $company->phone ?? '+229 66 18 55 95' }}</div>
                    </div>
                    @if($company->tax_number ?? false)
                    <div class="info-row">
                        <div class="info-label">IFU</div>
                        <div class="info-value">{{ $company->tax_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client -->
            <div class="client-info">
                <h3>Facturé à</h3>
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
                    @if($invoice->client->tax_number)
                    <div class="info-row">
                        <div class="info-label">IFU</div>
                        <div class="info-value">{{ $invoice->client->tax_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Détails facture -->
            <div class="invoice-details">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Date d'émission</div>
                        <div class="info-value">{{ $invoice->issue_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date d'échéance</div>
                        <div class="info-value">{{ $invoice->due_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            @if($invoice->status == 'paid')
                                <span class="status-badge status-paid">Payée</span>
                            @elseif($invoice->due_date < now())
                                <span class="status-badge status-overdue">En retard</span>
                            @else
                                <span class="status-badge status-pending">En attente</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lignes -->
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
                        <td>{{ $invoice->description }}</td>
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
                    <tr class="total">
                        <td><strong>TOTAL TTC</strong></td>
                        <td class="text-right"><strong>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                    @if($invoice->paid_amount > 0)
                    <tr>
                        <td>Montant déjà payé</td>
                        <td class="text-right" style="color: #10b981;">{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td><strong>Reste à payer</strong></td>
                        <td class="text-right"><strong style="color: #f59e0b;">{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Informations bancaires -->
            <div class="bank-info">
                <strong>Informations de paiement</strong><br>
                Banque : {{ $company->bank_name ?? 'Banque Atlantique Bénin' }}<br>
                Compte : {{ $company->bank_account ?? 'CI 123 456 789' }}<br>
                IBAN : {{ $company->bank_iban ?? 'BJ23 1234 5678 9012 3456 7890 12' }}
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Merci de votre confiance. En cas de question, contactez-nous à {{ $company->email ?? 'contact@novatech.bj' }}</p>
                <p>{{ $company->name ?? config('app.name') }} - {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
