{{-- resources/views/emails/invoice-reminder.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de paiement - Facture {{ $invoice->invoice_number }}</title>
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
        .reminder-badge {
            display: inline-block;
            background: #f59e0b;
            color: #fff;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
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
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .alert-box.overdue {
            background: #fee2e2;
            border-left-color: #dc2626;
        }
        .alert-box.overdue .alert-title {
            color: #dc2626;
        }
        .alert-title {
            font-weight: 700;
            margin-bottom: 8px;
            color: #d97706;
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
            background: #f8fafc;
            border-radius: 12px;
            margin: 24px 0;
            border: 1px solid #e2e8f0;
        }
        .amount-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: 700;
            color: #2563eb;
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
        .status-overdue {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Section paiement */
        .payment-section {
            margin: 24px 0;
        }
        .payment-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
        }

        /* Carte bancaire */
        .bank-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .bank-card h4 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #1e293b;
        }
        .bank-detail {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 13px;
        }
        .bank-detail:last-child {
            border-bottom: none;
        }
        .bank-label {
            font-weight: 600;
            color: #475569;
        }
        .bank-value {
            color: #1e293b;
            font-family: monospace;
            font-size: 14px;
        }

        /* Mobile Money */
        .mobile-card {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .mobile-card h4 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #92400e;
        }
        .mobile-number {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #92400e;
            font-family: monospace;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        .mobile-operator {
            text-align: center;
            font-size: 12px;
            color: #b45309;
        }

        /* Bouton d'action */
        .action-button {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin: 16px 0;
            text-align: center;
        }
        .action-button:hover {
            background: #1d4ed8;
        }

        /* Instructions */
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            font-size: 13px;
            color: #1e40af;
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
        .warning-note {
            background: #fef2f2;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 12px;
            color: #991b1b;
            text-align: center;
            border: 1px solid #fecaca;
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
            .info-row {
                flex-direction: column;
                padding: 12px 0;
            }
            .info-value {
                margin-top: 4px;
            }
            .bank-detail {
                flex-direction: column;
                gap: 4px;
            }
            .mobile-number {
                font-size: 18px;
            }
            .amount-value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <h1>RAPPEL DE PAIEMENT</h1>
                <div class="invoice-number">Facture {{ $invoice->invoice_number }}</div>
                @if($type === 'overdue')
                    <div class="reminder-badge">⚠️ PAYEMENT EN RETARD</div>
                @elseif($type === 'upcoming')
                    <div class="reminder-badge">⏰ ÉCHÉANCE PROCHAINE</div>
                @else
                    <div class="reminder-badge">📌 RAPPEL</div>
                @endif
            </div>

            <div class="content">
                <div class="greeting">
                    Bonjour <strong>{{ $invoice->client->name }}</strong>,
                </div>

                @if($type === 'overdue')
                <div class="alert-box overdue">
                    <div class="alert-title">⚠️ Votre facture est en retard de paiement</div>
                    <p>Nous constatons que votre facture <strong>{{ $invoice->invoice_number }}</strong> n'a pas encore été réglée à la date d'échéance du <strong>{{ $invoice->due_date->format('d/m/Y') }}</strong>.</p>
                </div>
                @elseif($type === 'upcoming')
                <div class="alert-box">
                    <div class="alert-title">⏰ Votre facture arrive à échéance</div>
                    <p>Nous vous rappelons que votre facture <strong>{{ $invoice->invoice_number }}</strong> arrivera à échéance le <strong>{{ $invoice->due_date->format('d/m/Y') }}</strong>.</p>
                </div>
                @else
                <div class="alert-box">
                    <div class="alert-title">📌 Rappel de paiement</div>
                    <p>Nous vous rappelons que votre facture <strong>{{ $invoice->invoice_number }}</strong> est en attente de règlement.</p>
                </div>
                @endif

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
                        <span class="info-value">{{ $invoice->description ?? ($invoice->service->name ?? 'Prestation de services') }}</span>
                    </div>
                </div>

                <div class="amount-box">
                    <div class="amount-label">Montant restant à payer</div>
                    <div class="amount-value">{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</div>
                </div>

                <div class="text-center mb-4">
                    @if($invoice->due_date < now())
                        <span class="status-badge status-overdue">En retard</span>
                    @else
                        <span class="status-badge status-pending">En attente de paiement</span>
                    @endif
                </div>

                <!-- Bouton pour voir la facture -->
                <div class="text-center">
                    <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="action-button">
                        📄 Voir ma facture
                    </a>
                </div>

                <hr>

                <!-- SECTION PAIEMENT -->
                <div class="payment-section">
                    <div class="payment-title">Instructions de paiement</div>

                    <!-- Paiement par virement bancaire -->
                    @if($company->bank_name || $company->bank_account_number)
                    <div class="bank-card">
                        <h4>Virement bancaire</h4>
                        @if($company->bank_name)
                        <div class="bank-detail">
                            <span class="bank-label">Banque :</span>
                            <span class="bank-value">{{ $company->bank_name }}</span>
                        </div>
                        @endif
                        @if($company->bank_account_name)
                        <div class="bank-detail">
                            <span class="bank-label">Titulaire du compte :</span>
                            <span class="bank-value">{{ $company->bank_account_name }}</span>
                        </div>
                        @endif
                        @if($company->bank_account_number)
                        <div class="bank-detail">
                            <span class="bank-label">Numéro de compte :</span>
                            <span class="bank-value">{{ $company->bank_account_number }}</span>
                        </div>
                        @endif
                        @if($company->bank_iban)
                        <div class="bank-detail">
                            <span class="bank-label">IBAN :</span>
                            <span class="bank-value">{{ $company->bank_iban }}</span>
                        </div>
                        @endif
                        @if($company->bank_swift)
                        <div class="bank-detail">
                            <span class="bank-label">SWIFT / BIC :</span>
                            <span class="bank-value">{{ $company->bank_swift }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Paiement par Mobile Money -->
                    @if($company->mobile_money_number)
                    <div class="mobile-card">
                        <h4>Paiement Mobile Money</h4>
                        <div class="mobile-number">
                            {{ $company->mobile_money_number }}
                        </div>
                        <div class="mobile-operator">
                            @if($company->mobile_money_operator == 'mtn')
                                Opérateur: MTN
                            @elseif($company->mobile_money_operator == 'moov')
                                Opérateur: MOOV
                            @elseif($company->mobile_money_operator == 'celcom')
                                Opérateur: Celcom
                            @else
                                Opérateur: {{ $company->mobile_money_operator }}
                            @endif
                        </div>
                        <div style="text-align: center; margin-top: 12px; font-size: 12px; color: #92400e;">
                            Utilisez le numéro ci-dessus pour effectuer votre paiement
                        </div>
                    </div>
                    @endif

                    <!-- Instructions supplémentaires -->
                    @if($company->payment_instructions)
                    <div class="instructions">
                        <p><strong>Instructions :</strong></p>
                        <p>{{ $company->payment_instructions }}</p>
                    </div>
                    @endif

                    <!-- Message par défaut si aucune info bancaire -->
                    @if(!$company->bank_name && !$company->bank_account_number && !$company->mobile_money_number)
                    <div class="bank-card">
                        <p style="text-align: center; color: #64748b;">
                            Veuillez nous contacter pour obtenir les informations de paiement.
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Information importante -->
                <div class="warning-note">
                    <strong>Important :</strong> Veuillez utiliser le numéro de facture <strong>{{ $invoice->invoice_number }}</strong> comme référence de votre paiement.
                </div>

                <hr>

                <p style="font-size: 13px; color: #64748b; margin-top: 24px; text-align: center;">
                    En cas de difficulté, n'hésitez pas à nous contacter.<br>
                    <strong>{{ $company->name ?? config('app.name') }}</strong>
                </p>
            </div>

            <div class="footer">
                <p><strong>{{ $company->name ?? config('app.name') }}</strong></p>
                <p>{{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
                <p>Email : {{ $company->email ?? 'contact@exemple.bj' }} | Tél : {{ $company->phone ?? '+229 XX XX XX XX' }}</p>
                <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
