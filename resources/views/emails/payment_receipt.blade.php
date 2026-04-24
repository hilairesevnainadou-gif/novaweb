{{-- resources/views/emails/payment_receipt.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement - {{ $payment->payment_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1e293b; background: #f1f5f9; margin: 0; padding: 0; }
        .wrap    { max-width: 600px; margin: 32px auto; padding: 0 16px; }
        .card    { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }

        /* Header */
        .header  { background: #0f172a; padding: 32px 24px; text-align: center; }
        .header .brand { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }
        .header .doc-type { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.45); margin-top: 6px; }
        .header .doc-num  { font-size: 15px; font-weight: 600; color: rgba(255,255,255,0.8); margin-top: 4px; }
        .header-accent { display: inline-block; width: 40px; height: 3px; background: #10b981; border-radius: 2px; margin: 12px auto 0; }

        /* Body */
        .body    { padding: 32px 28px; }
        .body p  { margin: 0 0 16px; font-size: 14px; color: #334155; }

        /* Icon */
        .success-icon { text-align: center; font-size: 52px; margin: 0 0 16px; }

        /* Info box */
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 4px 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #64748b; }
        .info-value { color: #0f172a; }

        /* Amount */
        .amount-box { background: #f0fdf4; border: 1px solid #6ee7b7; border-radius: 10px; padding: 24px; margin: 20px 0; text-align: center; }
        .amount-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #059669; margin-bottom: 6px; }
        .amount-value { font-size: 34px; font-weight: 800; color: #059669; }

        /* Status badge */
        .badge-wrap { text-align: center; margin: 12px 0; }
        .badge { display: inline-block; padding: 5px 16px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-deposit { background: #fffbeb; color: #d97706; border: 1px solid #fcd34d; }
        .badge-full    { background: #f0fdf4; color: #059669; border: 1px solid #6ee7b7; }

        /* Remaining balance */
        .remaining-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 14px 16px; text-align: center; font-size: 13px; color: #92400e; margin: 16px 0; }

        /* Alerts */
        .alert { border-radius: 8px; padding: 14px 16px; margin: 16px 0; font-size: 13px; }
        .alert-success { background: #f0fdf4; border-left: 4px solid #10b981; color: #065f46; }
        .alert strong  { display: block; margin-bottom: 4px; }

        hr { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center; font-size: 12px; color: #94a3b8; }
        .footer p { margin: 3px 0; }
        .footer strong { color: #64748b; }

        @media (max-width: 600px) {
            .body { padding: 24px 18px; }
            .info-row { flex-direction: column; gap: 3px; }
            .amount-value { font-size: 26px; }
        }
    </style>
</head>
<body>
<div class="wrap">
<div class="card">

    <div class="header">
        <div class="brand">{{ $company->name ?? config('app.name') }}</div>
        <div class="doc-type">Reçu de paiement</div>
        <div class="doc-num">{{ $payment->payment_number }}</div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        <div class="success-icon">✓</div>

        <p>Bonjour <strong>{{ $payment->client->name }}</strong>,</p>
        <p>Nous vous confirmons la bonne réception de votre paiement.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">N° de paiement</span>
                <span class="info-value">{{ $payment->payment_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date du paiement</span>
                <span class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Type de paiement</span>
                <span class="info-value">
                    @if($payment->payment_type == 'deposit') Acompte
                    @elseif($payment->payment_type == 'partial') Paiement partiel
                    @else Paiement complet
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Mode de paiement</span>
                <span class="info-value">
                    @if($payment->payment_method == 'cash') Espèces
                    @elseif($payment->payment_method == 'bank_transfer') Virement bancaire
                    @elseif($payment->payment_method == 'mobile_money') Mobile Money
                    @elseif($payment->payment_method == 'card') Carte bancaire
                    @else {{ $payment->payment_method }}
                    @endif
                </span>
            </div>
            @if($payment->reference)
            <div class="info-row">
                <span class="info-label">Référence</span>
                <span class="info-value">{{ $payment->reference }}</span>
            </div>
            @endif
            @if($payment->invoice)
            <div class="info-row">
                <span class="info-label">Facture associée</span>
                <span class="info-value">{{ $payment->invoice->invoice_number }}</span>
            </div>
            @endif
        </div>

        <div class="amount-box">
            <div class="amount-label">Montant reçu</div>
            <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
        </div>

        <div class="badge-wrap">
            @if($payment->payment_type == 'deposit')
                <span class="badge badge-deposit">Acompte enregistré</span>
            @else
                <span class="badge badge-full">Paiement validé</span>
            @endif
        </div>

        @if($payment->invoice && $payment->invoice->remaining_amount > 0)
        <div class="remaining-box">
            <strong>Solde restant :</strong> {{ number_format($payment->invoice->remaining_amount, 0, ',', ' ') }} FCFA
        </div>
        @endif

        <hr>

        <p style="text-align:center; font-size:13px; color:#64748b;">
            Merci de votre confiance.<br>
            <strong>{{ $company->name ?? config('app.name') }}</strong>
        </p>
    </div>

    <div class="footer">
        <p><strong>{{ $company->name ?? config('app.name') }}</strong> — {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
        <p>{{ $company->email ?? 'contact@novatech.bj' }} | {{ $company->phone ?? '+229 66 18 55 95' }}</p>
        <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits réservés.</p>
    </div>

</div>
</div>
</body>
</html>
