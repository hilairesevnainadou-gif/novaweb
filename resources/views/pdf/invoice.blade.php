{{-- resources/views/pdf/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        /* ════════════════════════════════════
           RESET & BASE  –  A4 : 210 × 297 mm
           DomPDF 96 dpi → 794 × 1123 px
        ════════════════════════════════════ */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
            width: 794px;
        }

        .page { width: 794px; min-height: 1123px; background: #ffffff; }

        /* ════ HEADER ════ */
        .header-band {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            width: 100%;
        }
        .header-inner {
            display: table;
            width: 100%;
            padding: 22px 32px;
        }

        /* Logo */
        .header-logo-cell {
            display: table-cell;
            width: 130px;
            vertical-align: middle;
        }
        .logo-box {
            width: 110px; height: 70px;
            background: #ffffff;
            border-radius: 8px;
            display: table;
            text-align: center;
            overflow: hidden;
        }
        .logo-box-inner {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .logo-box img {
            max-width: 100px; max-height: 60px;
            display: block; margin: auto;
            object-fit: contain;
        }
        .logo-text-fallback {
            font-size: 13px; font-weight: bold; color: #4f46e5;
        }

        /* Titre */
        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .header-title-cell h1 {
            font-size: 26px; font-weight: bold;
            color: #fff; letter-spacing: 4px;
            margin-bottom: 3px;
        }
        .header-title-cell .invoice-num {
            font-size: 12px;
            color: rgba(255,255,255,.85);
            letter-spacing: 1px;
        }

        /* Statut */
        .header-status-cell {
            display: table-cell;
            width: 130px;
            vertical-align: middle;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-paid    { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-partial { background: #dbeafe; color: #1e40af; }

        /* ════ BODY ════ */
        .body { padding: 24px 32px; }

        /* ── Parties ── */
        .parties-row { display: table; width: 100%; margin-bottom: 20px; }
        .party-cell  { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
        .party-cell:last-child { padding-right: 0; padding-left: 16px; }

        .party-label {
            font-size: 9px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 1px;
            color: #94a3b8; margin-bottom: 6px;
        }
        .party-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 14px;
        }
        .party-name {
            font-size: 13px; font-weight: bold;
            color: #1e293b; margin-bottom: 6px;
        }

        /* Info table */
        .info-tbl { width: 100%; border-collapse: collapse; }
        .info-tbl td { padding: 2px 0; vertical-align: top; font-size: 10px; }
        .info-tbl .lbl { color: #64748b; width: 80px; font-weight: 600; white-space: nowrap; }
        .info-tbl .val { color: #334155; }

        /* ── Meta strip ── */
        .meta-strip {
            display: table; width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e0e7ff;
            border-radius: 6px;
            background: #eef2ff;
            overflow: hidden;
        }
        .meta-cell {
            display: table-cell; width: 25%;
            padding: 10px 14px; vertical-align: top;
            border-right: 1px solid #c7d2fe;
        }
        .meta-cell:last-child { border-right: none; }
        .meta-lbl {
            font-size: 8px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .8px;
            color: #6366f1; margin-bottom: 3px;
        }
        .meta-val { font-size: 11px; font-weight: bold; color: #1e293b; }

        /* ── Section title ── */
        .section-title {
            font-size: 10px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .8px;
            color: #4f46e5;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 4px; margin-bottom: 10px;
        }

        /* ── Items table ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .items-table thead tr { background: #4f46e5; }
        .items-table thead th {
            padding: 9px 12px;
            font-size: 9px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .5px;
            color: #fff; text-align: left;
        }
        .items-table thead th.right  { text-align: right; }
        .items-table thead th.center { text-align: center; }
        .items-table tbody tr { background: #fff; }
        .items-table tbody tr:nth-child(even) { background: #f8fafc; }
        .items-table tbody td {
            padding: 10px 12px;
            font-size: 10px; color: #334155;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .items-table tbody td.right  { text-align: right; }
        .items-table tbody td.center { text-align: center; }
        .item-desc { font-weight: 600; color: #1e293b; }
        .item-sub  { font-size: 9px; color: #64748b; margin-top: 2px; }

        /* ── Totals ── */
        .totals-wrap  { display: table; width: 100%; }
        .totals-left  { display: table-cell; vertical-align: top; width: 55%; padding-right: 20px; }
        .totals-right { display: table-cell; vertical-align: top; width: 45%; }

        .totals-table { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; overflow: hidden; }
        .totals-table td { padding: 8px 12px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        .totals-table tr:last-child td { border-bottom: none; }
        .totals-table .lbl { color: #475569; }
        .totals-table .amt { text-align: right; color: #1e293b; font-weight: 600; }
        .total-final td {
            padding: 10px 12px;
            font-size: 12px; font-weight: bold;
            color: #fff; background: #4f46e5;
            border-bottom: none;
        }
        .total-final .lbl { color: #c7d2fe; font-size: 10px; }
        .total-final .amt { text-align: right; font-size: 13px; }
        .remaining-row td { color: #d97706 !important; }

        .notes-box {
            background: #eff6ff;
            border-left: 3px solid #4f46e5;
            border-radius: 0 6px 6px 0;
            padding: 10px 14px;
            font-size: 10px; color: #334155;
        }

        /* ── Payment section ── */
        .payment-cols { display: table; width: 100%; }
        .payment-col  { display: table-cell; vertical-align: top; width: 50%; padding-right: 12px; }
        .payment-col:last-child { padding-right: 0; padding-left: 12px; }

        .payment-box { border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .payment-box-header {
            background: #f1f5f9; padding: 7px 12px;
            font-size: 9px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .6px;
            color: #475569; border-bottom: 1px solid #e2e8f0;
        }
        .payment-box-body { padding: 12px 14px; background: #ffffff; }

        /* Pay table – bank info */
        .pay-tbl { width: 100%; border-collapse: collapse; }
        .pay-tbl tr + tr td { border-top: 1px solid #f1f5f9; }
        .pay-tbl td { padding: 5px 6px; font-size: 10px; vertical-align: middle; }
        .pay-tbl .plbl {
            color: #64748b; font-weight: 700;
            width: 100px; white-space: nowrap;
            background: #f8fafc; border-radius: 3px; padding-left: 8px;
        }
        .pay-tbl .pval { color: #1e293b; font-weight: 600; }
        .pay-tbl .pval-number {
            color: #4f46e5; font-weight: 800;
            font-size: 11px; letter-spacing: 1.5px;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        /* Mobile Money */
        .mobile-hero { text-align: center; padding: 8px 0 6px; }
        .operator-badge {
            display: inline-block;
            background: #fef3c7; color: #92400e;
            padding: 3px 12px; border-radius: 12px;
            font-size: 9px; font-weight: bold;
        }
        .mobile-number-big {
            font-size: 20px; font-weight: bold; color: #d97706;
            letter-spacing: 3px; margin: 6px 0 4px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .mobile-hint { font-size: 9px; color: #94a3b8; margin-top: 4px; }

        /* Reference note */
        .ref-note {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 10px; color: #991b1b;
            text-align: center; margin-top: 14px;
        }

        /* ════ FOOTER ════ */
        .footer-band {
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
            padding: 16px 32px;
            display: table; width: 100%;
        }
        .footer-left {
            display: table-cell;
            vertical-align: middle;
            font-size: 9px; color: #94a3b8;
            width: 58%;
        }
        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 42%;
        }

        /* ── QR Code ── */
        .qr-block { display: inline-block; text-align: center; }
        .qr-frame {
            display: inline-block;
            background: #ffffff;
            border: 2px solid #e0e7ff;
            border-radius: 10px;
            padding: 6px;
        }
        .qr-frame svg { display: block; width: 80px; height: 80px; }
        .qr-frame img { display: block; width: 80px; height: 80px; }
        .qr-caption {
            font-size: 8px; color: #6366f1;
            font-weight: bold; margin-top: 5px;
            letter-spacing: .4px;
        }
        .qr-num {
            font-size: 7px; color: #94a3b8; margin-top: 2px;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 16px 0; }
        .text-green { color: #059669; }
        .fw-bold    { font-weight: bold; }
    </style>
</head>
<body>
<div class="page">

    {{-- ══════════════════════════════════════
         HEADER : Logo | Titre | Statut
    ══════════════════════════════════════ --}}
    <div class="header-band">
        <div class="header-inner">

            {{-- Logo --}}
            <div class="header-logo-cell">
                <div class="logo-box">
                    <div class="logo-box-inner">
                        @if($company && $company->logo)
                            <img src="{{ public_path('storage/' . $company->logo) }}" alt="{{ $company->name }}">
                        @else
                            <span class="logo-text-fallback">
                                {{ strtoupper(substr($company->name ?? config('app.name'), 0, 3)) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Titre --}}
            <div class="header-title-cell">
                <h1>FACTURE</h1>
                <div class="invoice-num">N° {{ $invoice->invoice_number }}</div>
            </div>

            {{-- Statut --}}
            <div class="header-status-cell">
                @if($invoice->status == 'paid')
                    <span class="status-badge status-paid">PAYÉE</span>
                @elseif(isset($invoice->due_date) && $invoice->due_date < now())
                    <span class="status-badge status-overdue">EN RETARD</span>
                @elseif($invoice->status == 'partially_paid')
                    <span class="status-badge status-partial">◑ PARTIELLE</span>
                @else
                    <span class="status-badge status-pending">● EN ATTENTE</span>
                @endif
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════
         BODY
    ══════════════════════════════════════ --}}
    <div class="body">

        {{-- ── Émetteur / Client ── --}}
        <div class="parties-row">

            <div class="party-cell">
                <div class="party-label">Émetteur</div>
                <div class="party-box">
                    <div class="party-name">{{ $company->name ?? config('app.name') }}</div>
                    <table class="info-tbl">
                        <tr>
                            <td class="lbl">Adresse</td>
                            <td class="val">{{ $company->address ?? 'Abomey-Calavi, Bénin' }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Email</td>
                            <td class="val">{{ $company->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Téléphone</td>
                            <td class="val">{{ $company->phone ?? '—' }}</td>
                        </tr>
                        @if($company->ifu)
                        <tr>
                            <td class="lbl">IFU</td>
                            <td class="val">{{ $company->ifu }}</td>
                        </tr>
                        @endif
                        @if($company->rccm)
                        <tr>
                            <td class="lbl">RCCM</td>
                            <td class="val">{{ $company->rccm }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="party-cell">
                <div class="party-label">Facturé à</div>
                <div class="party-box">
                    <div class="party-name">{{ $invoice->client->name }}</div>
                    <table class="info-tbl">
                        @if($invoice->client->address)
                        <tr>
                            <td class="lbl">Adresse</td>
                            <td class="val">{{ $invoice->client->address }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="lbl">Email</td>
                            <td class="val">{{ $invoice->client->email ?? '—' }}</td>
                        </tr>
                        @if($invoice->client->phone)
                        <tr>
                            <td class="lbl">Téléphone</td>
                            <td class="val">{{ $invoice->client->phone }}</td>
                        </tr>
                        @endif
                        @if($invoice->client->tax_number)
                        <tr>
                            <td class="lbl">IFU</td>
                            <td class="val">{{ $invoice->client->tax_number }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>

        {{-- ── Meta strip ── --}}
        <div class="meta-strip">
            <div class="meta-cell">
                <div class="meta-lbl">N° Facture</div>
                <div class="meta-val">{{ $invoice->invoice_number }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-lbl">Date d'émission</div>
                <div class="meta-val">{{ $invoice->issue_date->format('d/m/Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-lbl">Date d'échéance</div>
                <div class="meta-val">{{ $invoice->due_date->format('d/m/Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-lbl">Devise</div>
                <div class="meta-val">FCFA (XOF)</div>
            </div>
        </div>

        {{-- ── Lignes de prestation ── --}}
        <div class="section-title">Détail des prestations</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:50%;">Description</th>
                    <th class="right"  style="width:18%;">Montant HT</th>
                    <th class="center" style="width:14%;">TVA</th>
                    <th class="right"  style="width:18%;">Montant TTC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-desc">
                            {{ $invoice->description ?? ($invoice->service->name ?? 'Prestation de services') }}
                        </div>
                        @if($invoice->notes ?? false)
                            <div class="item-sub">{{ $invoice->notes }}</div>
                        @endif
                    </td>
                    <td class="right">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                    <td class="center">{{ $invoice->tax_rate }}%</td>
                    <td class="right fw-bold">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>

        {{-- ── Totaux ── --}}
        <div class="totals-wrap">

            <div class="totals-left">
                @if($invoice->payment_terms ?? false)
                    <div class="party-label" style="margin-bottom:4px;">Conditions de paiement</div>
                    <div class="notes-box">{{ $invoice->payment_terms }}</div>
                @endif
            </div>

            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td class="lbl">Sous-total HT</td>
                        <td class="amt">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="lbl">TVA ({{ $invoice->tax_rate }}%)</td>
                        <td class="amt">{{ number_format($invoice->tax_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="total-final">
                        <td class="lbl">Total TTC</td>
                        <td class="amt">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @if(($invoice->paid_amount ?? 0) > 0)
                    <tr>
                        <td class="lbl text-green">Déjà payé</td>
                        <td class="amt text-green">− {{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="remaining-row">
                        <td class="lbl"><strong>Reste à payer</strong></td>
                        <td class="amt"><strong>{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                    @endif
                </table>
            </div>

        </div>

        {{-- ── Instructions de paiement ── --}}
        @if($company->bank_name || $company->bank_account_number || $company->mobile_money_number)
        <hr class="divider">
        <div class="section-title" style="margin-top:4px;">Instructions de paiement</div>

        <div class="payment-cols">

            @if($company->bank_name || $company->bank_account_number)
            <div class="payment-col">
                <div class="payment-box">
                    <div class="payment-box-header"> Virement bancaire</div>
                    <div class="payment-box-body">
                        <table class="pay-tbl">
                            @if($company->bank_name)
                            <tr>
                                <td class="plbl">Banque</td>
                                <td class="pval">{{ $company->bank_name }}</td>
                            </tr>
                            @endif
                            @if($company->bank_account_name)
                            <tr>
                                <td class="plbl">Titulaire</td>
                                <td class="pval">{{ $company->bank_account_name }}</td>
                            </tr>
                            @endif
                            @if($company->bank_account_number)
                            <tr>
                                <td class="plbl">N° Compte</td>
                                <td class="pval pval-number">{{ $company->bank_account_number }}</td>
                            </tr>
                            @endif
                            @if($company->bank_iban)
                            <tr>
                                <td class="plbl">IBAN</td>
                                <td class="pval pval-number">{{ $company->bank_iban }}</td>
                            </tr>
                            @endif
                            @if($company->bank_swift)
                            <tr>
                                <td class="plbl">SWIFT / BIC</td>
                                <td class="pval pval-number">{{ $company->bank_swift }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($company->mobile_money_number)
            <div class="payment-col">
                <div class="payment-box">
                    <div class="payment-box-header"> Mobile Money</div>
                    <div class="payment-box-body">
                        <div class="mobile-hero">
                            <div style="margin-bottom:6px;">
                                <span class="operator-badge">
                                    @if($company->mobile_money_operator == 'mtn') MTN Mobile Money
                                    @elseif($company->mobile_money_operator == 'moov') MOOV Money
                                    @elseif($company->mobile_money_operator == 'celcom') Celcom
                                    @else {{ $company->mobile_money_operator }}
                                    @endif
                                </span>
                            </div>
                            <div class="mobile-number-big">{{ $company->mobile_money_number }}</div>
                            <div class="mobile-hint">Composez ce numéro pour effectuer votre paiement</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        @if($company->payment_instructions)
            <div class="notes-box" style="margin-top:8px;">{{ $company->payment_instructions }}</div>
        @endif
        @endif

        {{-- ── Référence obligatoire ── --}}
        <div class="ref-note">
            <strong>Important :</strong> Veuillez mentionner
            <strong>{{ $invoice->invoice_number }}</strong> comme référence de paiement.
        </div>

    </div>{{-- /body --}}

    {{-- ══════════════════════════════════════
         FOOTER : Infos légales | QR Code
    ══════════════════════════════════════ --}}
    <div class="footer-band">

        <div class="footer-left">
            <p>Merci de votre confiance. Contact : {{ $company->email ?? 'contact@example.bj' }}</p>
            <p style="margin-top:3px;">
                {{ $company->name ?? config('app.name') }} –
                {{ $company->address ?? 'Abomey-Calavi, Bénin' }}
                @if($company->ifu) | IFU : {{ $company->ifu }}@endif
            </p>
            <p style="margin-top:3px;">
                © {{ date('Y') }} {{ $company->name ?? config('app.name') }}. Tous droits réservés.
            </p>
            <p style="margin-top:5px; font-size:8px; color:#cbd5e1;">
                Généré le {{ now()->format('d/m/Y à H:i') }} · Document officiel
            </p>
        </div>

        {{-- QR Code (généré par BillingController::buildInvoicePdf) --}}
        {{-- QR Code --}}
<div class="footer-right">
    <div class="qr-block">
        <div class="qr-frame">
            @if(isset($qrSvg) && $qrSvg)
                {!! $qrSvg !!}
            @else
                <div style="width:80px;height:80px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-size:10px;">
                    QR Code
                </div>
            @endif
        </div>
        <div class="qr-caption">Vérifier {{ $invoice->invoice_number ?? $payment->payment_number ?? '' }}</div>
        <div class="qr-num">{{ $invoice->invoice_number ?? $payment->payment_number ?? '' }}</div>
    </div>
</div>

    </div>

</div>
</body>
</html>
