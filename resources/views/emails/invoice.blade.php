{{-- resources/views/emails/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }} - {{ $company->name ?? config('app.name') }}</title>
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
        .header-accent { display: inline-block; width: 40px; height: 3px; background: #2563eb; border-radius: 2px; margin: 12px auto 0; }

        /* Body */
        .body    { padding: 32px 28px; }
        .body p  { margin: 0 0 16px; font-size: 14px; color: #334155; }
        .body h2 { margin: 0 0 20px; font-size: 18px; color: #0f172a; }

        /* Info box */
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 4px 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #64748b; }
        .info-value { color: #0f172a; }

        /* Amount */
        .amount-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin: 20px 0; text-align: center; }
        .amount-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 6px; }
        .amount-value { font-size: 34px; font-weight: 800; color: #2563eb; }

        /* Status badge */
        .badge-wrap { text-align: center; margin: 12px 0; }
        .badge { display: inline-block; padding: 5px 16px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #fffbeb; color: #d97706; border: 1px solid #fcd34d; }
        .badge-paid    { background: #f0fdf4; color: #059669; border: 1px solid #6ee7b7; }
        .badge-overdue { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }

        /* Payment section */
        .pay-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 20px; margin-bottom: 16px; }
        .pay-box.momo { background: #fffbeb; border-color: #fcd34d; }
        .pay-box-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .pay-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; font-size: 12px; }
        .pay-row:last-child { border-bottom: none; }
        .pay-label { font-weight: 600; color: #64748b; }
        .pay-value { color: #0f172a; }
        .pay-value.mono { font-family: monospace; font-size: 13px; }

        /* Alerts */
        .alert { border-radius: 8px; padding: 12px 16px; margin: 16px 0; font-size: 13px; }
        .alert-info  { background: #eff6ff; border-left: 4px solid #2563eb; color: #1e40af; }
        .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; text-align: center; }
        .alert strong { display: block; margin-bottom: 3px; }

        hr { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center; font-size: 12px; color: #94a3b8; }
        .footer p { margin: 3px 0; }
        .footer strong { color: #64748b; }

        @media (max-width: 600px) {
            .body { padding: 24px 18px; }
            .info-row, .pay-row { flex-direction: column; gap: 3px; }
            .amount-value { font-size: 26px; }
        }
    </style>
</head>
<body>
<div class="wrap">
<div class="card">

    <div class="header">
        <div class="brand">{{ $company->name ?? config('app.name') }}</div>
        <div class="doc-type">Facture</div>
        <div class="doc-num">{{ $invoice->invoice_number }}</div>
        <div class="header-accent"></div>
    </div>

    <div class="body">
        <p>Bonjour <strong>{{ $invoice->client->name }}</strong>,</p>
        <p>Veuillez trouver ci-dessous votre facture <strong>{{ $invoice->invoice_number }}</strong>.</p>

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
            <div class="amount-label">Montant total à payer</div>
            <div class="amount-value">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</div>
        </div>

        <div class="badge-wrap">
            @if($invoice->status == 'paid')
                <span class="badge badge-paid">Payée</span>
            @elseif($invoice->due_date < now())
                <span class="badge badge-overdue">En retard</span>
            @else
                <span class="badge badge-pending">En attente de paiement</span>
            @endif
        </div>

        {{-- ── Section paiement ── --}}
        <hr>
        <p style="font-size:13px; font-weight:700; color:#0f172a; margin:0 0 16px;">Instructions de paiement</p>

        @if($company->bank_name || $company->bank_account_number)
        <div class="pay-box">
            <div class="pay-box-title">Virement bancaire</div>
            @if($company->bank_name)
            <div class="pay-row"><span class="pay-label">Banque</span><span class="pay-value">{{ $company->bank_name }}</span></div>
            @endif
            @if($company->bank_account_name)
            <div class="pay-row"><span class="pay-label">Titulaire</span><span class="pay-value">{{ $company->bank_account_name }}</span></div>
            @endif
            @if($company->bank_account_number)
            <div class="pay-row"><span class="pay-label">N° de compte</span><span class="pay-value mono">{{ $company->bank_account_number }}</span></div>
            @endif
            @if($company->bank_iban)
            <div class="pay-row"><span class="pay-label">IBAN</span><span class="pay-value mono">{{ $company->bank_iban }}</span></div>
            @endif
            @if($company->bank_swift)
            <div class="pay-row"><span class="pay-label">SWIFT / BIC</span><span class="pay-value mono">{{ $company->bank_swift }}</span></div>
            @endif
        </div>
        @endif

        @if($company->mobile_money_number)
        <div class="pay-box momo">
            <div class="pay-box-title" style="color:#92400e;">Mobile Money</div>
            <div style="text-align:center; font-size:26px; font-weight:800; font-family:monospace; letter-spacing:3px; color:#92400e; margin:10px 0;">
                {{ $company->mobile_money_number }}
            </div>
            <div style="text-align:center; font-size:12px; color:#b45309;">
                Opérateur :
                @if($company->mobile_money_operator == 'mtn') MTN
                @elseif($company->mobile_money_operator == 'moov') MOOV
                @elseif($company->mobile_money_operator == 'celcom') Celcom
                @else {{ $company->mobile_money_operator }}
                @endif
            </div>
            <div style="text-align:center; font-size:12px; color:#92400e; margin-top:8px;">Utilisez le numéro ci-dessus pour effectuer votre paiement.</div>
        </div>
        @endif

        @if($company->payment_instructions)
        <div class="alert alert-info">
            <strong>Instructions supplémentaires</strong>
            {{ $company->payment_instructions }}
        </div>
        @endif

        @if(!$company->bank_name && !$company->bank_account_number && !$company->mobile_money_number)
        <div class="pay-box" style="text-align:center; color:#64748b; font-size:13px;">
            Veuillez nous contacter pour obtenir les informations de paiement.
        </div>
        @endif

        <div class="alert alert-error">
            Référence obligatoire : mentionnez le numéro <strong>{{ $invoice->invoice_number }}</strong> lors de votre paiement.
        </div>

        <hr>

        <p style="text-align:center; font-size:13px; color:#64748b;">
            Merci de votre confiance.<br>
            <strong>{{ $company->name ?? config('app.name') }}</strong>
        </p>
    </div>

    <div class="footer">
        <p><strong>{{ $company->name ?? config('app.name') }}</strong> — {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
        <p>{{ $company->email ?? 'contact@novatech.bj' }} | {{ $company->phone ?? '+229 XX XX XX XX' }}</p>
        <p>&copy; {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits réservés.</p>
    </div>

</div>
</div>
</body>
</html>
