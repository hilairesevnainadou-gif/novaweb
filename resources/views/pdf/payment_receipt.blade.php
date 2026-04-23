{{-- resources/views/pdf/payment_receipt.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement – {{ $payment->payment_number ?? '#' . $payment->id }}</title>
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
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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
            background: #ffffff; border-radius: 8px;
            display: table; text-align: center; overflow: hidden;
        }
        .logo-box-inner {
            display: table-cell;
            vertical-align: middle; text-align: center;
        }
        .logo-box img {
            max-width: 100px; max-height: 60px;
            display: block; margin: auto; object-fit: contain;
        }
        .logo-text-fallback {
            font-size: 13px; font-weight: bold; color: #059669;
        }

        /* Titre */
        .header-title-cell {
            display: table-cell;
            vertical-align: middle; text-align: center;
        }
        .header-title-cell h1 {
            font-size: 24px; font-weight: bold;
            color: #fff; letter-spacing: 3px; margin-bottom: 3px;
        }
        .header-title-cell .receipt-num {
            font-size: 12px; color: rgba(255,255,255,.85); letter-spacing: 1px;
        }

        /* Badge montant */
        .header-amount-cell {
            display: table-cell;
            width: 140px; vertical-align: middle; text-align: right;
        }
        .amount-badge {
            background: rgba(255,255,255,.18);
            border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 8px; padding: 8px 12px; text-align: center;
        }
        .amount-badge-val { font-size: 15px; font-weight: bold; color: #fff; }
        .amount-badge-lbl {
            font-size: 8px; color: rgba(255,255,255,.75);
            letter-spacing: .5px; text-transform: uppercase; margin-top: 2px;
        }

        /* Bande de confirmation */
        .check-strip {
            background: #ecfdf5; border-bottom: 1px solid #a7f3d0;
            padding: 10px 32px; text-align: center;
            font-size: 11px; font-weight: bold; color: #065f46;
        }

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
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 12px 14px;
        }
        .party-name { font-size: 13px; font-weight: bold; color: #1e293b; margin-bottom: 6px; }

        .info-tbl { width: 100%; border-collapse: collapse; }
        .info-tbl td { padding: 2px 0; vertical-align: top; font-size: 10px; }
        .info-tbl .lbl { color: #64748b; width: 80px; font-weight: 600; white-space: nowrap; }
        .info-tbl .val { color: #334155; }

        /* ── Detail strip ── */
        .detail-strip {
            display: table; width: 100%;
            border: 1px solid #e2e8f0; border-radius: 6px;
            overflow: hidden; margin-bottom: 20px;
        }
        .detail-cell {
            display: table-cell; width: 25%;
            padding: 10px 14px; vertical-align: top;
            border-right: 1px solid #e2e8f0; background: #f8fafc;
        }
        .detail-cell:last-child { border-right: none; }
        .detail-lbl {
            font-size: 8px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .8px;
            color: #059669; margin-bottom: 4px;
        }
        .detail-val { font-size: 11px; font-weight: bold; color: #1e293b; }

        /* ── Badges ── */
        .badge { display: inline-block; padding: 3px 9px; border-radius: 20px; font-size: 9px; font-weight: bold; }
        .badge-deposit  { background: #fef3c7; color: #92400e; }
        .badge-partial  { background: #dbeafe; color: #1e40af; }
        .badge-full     { background: #d1fae5; color: #065f46; }
        .badge-cash     { background: #e0e7ff; color: #3730a3; }
        .badge-transfer { background: #f3e8ff; color: #7e22ce; }
        .badge-mobile   { background: #fef3c7; color: #92400e; }
        .badge-card     { background: #f0fdf4; color: #166534; }
        .badge-check    { background: #f1f5f9; color: #475569; }

        /* ── Amount hero ── */
        .amount-hero {
            background: #ecfdf5; border: 1px solid #a7f3d0;
            border-radius: 8px; text-align: center;
            padding: 20px 16px; margin-bottom: 20px;
        }
        .amount-hero-val { font-size: 30px; font-weight: bold; color: #059669; letter-spacing: 1px; }
        .amount-hero-lbl {
            font-size: 10px; color: #64748b; margin-top: 4px;
            text-transform: uppercase; letter-spacing: .8px;
        }

        /* ── Balance ── */
        .balance-box {
            border-radius: 6px; padding: 10px 16px;
            text-align: center; margin-bottom: 16px;
            font-size: 11px; font-weight: bold;
        }
        .balance-remaining { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .balance-settled   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }

        /* ── Invoice ref ── */
        .inv-ref-box {
            display: table; width: 100%;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 10px 14px; margin-bottom: 16px;
        }
        .inv-ref-lbl {
            display: table-cell; font-size: 10px;
            color: #64748b; font-weight: 600;
            vertical-align: middle; width: 180px;
        }
        .inv-ref-val {
            display: table-cell; font-size: 11px;
            font-weight: bold; color: #1e293b; vertical-align: middle;
        }

        /* ── Section title ── */
        .section-title {
            font-size: 10px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .8px;
            color: #059669; border-bottom: 2px solid #059669;
            padding-bottom: 4px; margin-bottom: 10px;
        }

        /* ── Method boxes ── */
        .method-box { border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; margin-bottom: 12px; }
        .method-box-header {
            background: #f1f5f9; padding: 7px 12px;
            font-size: 9px; font-weight: bold;
            text-transform: uppercase; letter-spacing: .6px;
            color: #475569; border-bottom: 1px solid #e2e8f0;
        }
        .method-box-body { padding: 12px 14px; background: #ffffff; }

        /* Pay table */
        .pay-tbl { width: 100%; border-collapse: collapse; }
        .pay-tbl tr + tr td { border-top: 1px solid #f1f5f9; }
        .pay-tbl td { padding: 5px 6px; font-size: 10px; vertical-align: middle; }
        .pay-tbl .plbl {
            color: #64748b; font-weight: 700;
            width: 110px; white-space: nowrap;
            background: #f8fafc; border-radius: 3px; padding-left: 8px;
        }
        .pay-tbl .pval { color: #1e293b; font-weight: 600; }
        .pay-tbl .pval-number {
            color: #059669; font-weight: 800;
            font-size: 11px; letter-spacing: 1.5px;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        /* Mobile Money */
        .mobile-hero { text-align: center; padding: 6px 0; }
        .operator-badge {
            display: inline-block; background: #fef3c7; color: #92400e;
            padding: 3px 12px; border-radius: 12px;
            font-size: 9px; font-weight: bold; margin-bottom: 6px;
        }
        .mobile-number-big {
            font-size: 22px; font-weight: bold; color: #d97706;
            letter-spacing: 3px; margin: 6px 0 4px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .mobile-hint { font-size: 9px; color: #94a3b8; margin-top: 4px; }

        .notes-box {
            background: #eff6ff;
            border-left: 3px solid #059669;
            border-radius: 0 6px 6px 0;
            padding: 10px 14px;
            font-size: 10px; color: #334155; margin-top: 12px;
        }

        /* ════ FOOTER ════ */
        .footer-band {
            background: #f8fafc; border-top: 2px solid #e2e8f0;
            padding: 16px 32px; display: table; width: 100%;
        }
        .footer-left {
            display: table-cell; vertical-align: middle;
            font-size: 9px; color: #94a3b8; width: 58%;
        }
        .footer-right {
            display: table-cell; vertical-align: middle;
            text-align: right; width: 42%;
        }

        /* ── QR Code ── */
        .qr-block { display: inline-block; text-align: center; }
        .qr-frame {
            display: inline-block; background: #ffffff;
            border: 2px solid #a7f3d0; border-radius: 10px; padding: 6px;
        }
        .qr-frame svg { display: block; width: 80px; height: 80px; }
        .qr-frame img { display: block; width: 80px; height: 80px; }
        .qr-caption { font-size: 8px; color: #059669; font-weight: bold; margin-top: 5px; }
        .qr-num { font-size: 7px; color: #94a3b8; margin-top: 2px; font-family: 'DejaVu Sans Mono', monospace; }

        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 16px 0; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
<div class="page">

    {{-- ══════════════════════════════════════
         HEADER : Logo | Titre | Montant
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
                <h1>REÇU DE PAIEMENT</h1>
                <div class="receipt-num">N° {{ $payment->payment_number ?? '#' . $payment->id }}</div>
            </div>

            {{-- Montant --}}
            <div class="header-amount-cell">
                <div class="amount-badge">
                    <div class="amount-badge-val">{{ number_format($payment->amount, 0, ',', ' ') }}</div>
                    <div class="amount-badge-lbl">FCFA perçus</div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bande confirmation --}}
    <div class="check-strip">✓ &nbsp; Paiement enregistré avec succès</div>

    {{-- ══════════════════════════════════════
         BODY
    ══════════════════════════════════════ --}}
    <div class="body">

        {{-- ── Émetteur / Payeur ── --}}
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
                    </table>
                </div>
            </div>

            <div class="party-cell">
                <div class="party-label">Payé par</div>
                <div class="party-box">
                    <div class="party-name">{{ $payment->client->name ?? 'Client' }}</div>
                    <table class="info-tbl">
                        @if($payment->client->email ?? false)
                        <tr>
                            <td class="lbl">Email</td>
                            <td class="val">{{ $payment->client->email }}</td>
                        </tr>
                        @endif
                        @if($payment->client->phone ?? false)
                        <tr>
                            <td class="lbl">Téléphone</td>
                            <td class="val">{{ $payment->client->phone }}</td>
                        </tr>
                        @endif
                        @if($payment->client->address ?? false)
                        <tr>
                            <td class="lbl">Adresse</td>
                            <td class="val">{{ $payment->client->address }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>

        {{-- ── Detail strip ── --}}
        <div class="detail-strip">
            <div class="detail-cell">
                <div class="detail-lbl">Date de paiement</div>
                <div class="detail-val">
                    {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : now()->format('d/m/Y') }}
                </div>
            </div>
            <div class="detail-cell">
                <div class="detail-lbl">Type</div>
                <div class="detail-val">
                    @if($payment->payment_type == 'deposit')
                        <span class="badge badge-deposit">Acompte</span>
                    @elseif($payment->payment_type == 'partial')
                        <span class="badge badge-partial">Partiel</span>
                    @else
                        <span class="badge badge-full">Complet</span>
                    @endif
                </div>
            </div>
            <div class="detail-cell">
                <div class="detail-lbl">Mode</div>
                <div class="detail-val">
                    @if($payment->payment_method == 'cash')
                        <span class="badge badge-cash">Espèces</span>
                    @elseif($payment->payment_method == 'bank_transfer')
                        <span class="badge badge-transfer">Virement</span>
                    @elseif($payment->payment_method == 'mobile_money')
                        <span class="badge badge-mobile">Mobile Money</span>
                    @elseif($payment->payment_method == 'card')
                        <span class="badge badge-card">Carte</span>
                    @elseif($payment->payment_method == 'check')
                        <span class="badge badge-check">Chèque</span>
                    @else
                        {{ $payment->payment_method ?? '—' }}
                    @endif
                </div>
            </div>
            <div class="detail-cell">
                <div class="detail-lbl">Référence</div>
                <div class="detail-val">{{ $payment->reference ?? '—' }}</div>
            </div>
        </div>

        {{-- ── Montant hero ── --}}
        <div class="amount-hero">
            <div class="amount-hero-val">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
            <div class="amount-hero-lbl">Montant encaissé</div>
        </div>

        {{-- ── Facture associée ── --}}
        @if($payment->invoice)
        <div class="inv-ref-box">
            <div class="inv-ref-lbl">Facture associée :</div>
            <div class="inv-ref-val">{{ $payment->invoice->invoice_number ?? 'N/A' }}</div>
        </div>
        @endif

        {{-- ── Solde restant ── --}}
        @if($payment->invoice)
            @if($payment->invoice->remaining_amount > 0)
            <div class="balance-box balance-remaining">
                Solde restant sur la facture :
                <strong>{{ number_format($payment->invoice->remaining_amount, 0, ',', ' ') }} FCFA</strong>
            </div>
            @else
            <div class="balance-box balance-settled">
                ✓ Facture entièrement réglée
            </div>
            @endif
        @endif

        {{-- ── Notes ── --}}
        @if($payment->notes)
        <div class="notes-box">
            <strong>Notes :</strong> {{ $payment->notes }}
        </div>
        @endif

        {{-- ── Détails Mobile Money ── --}}
        @if($payment->payment_method == 'mobile_money' && $company->mobile_money_number)
        <hr class="divider">
        <div class="section-title">Détails Mobile Money</div>
        <div class="method-box">
            <div class="method-box-header">Paiement Mobile Money</div>
            <div class="method-box-body">
                <div class="mobile-hero">
                    <div>
                        <span class="operator-badge">
                            @if($company->mobile_money_operator == 'mtn') MTN Mobile Money
                            @elseif($company->mobile_money_operator == 'moov') MOOV Money
                            @elseif($company->mobile_money_operator == 'celcom') Celcom
                            @else {{ $company->mobile_money_operator }}
                            @endif
                        </span>
                    </div>
                    <div class="mobile-number-big">{{ $company->mobile_money_number }}</div>
                    <div class="mobile-hint">Numéro sur lequel le paiement a été effectué</div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Détails Virement bancaire ── --}}
        @if($payment->payment_method == 'bank_transfer' && ($company->bank_name || $company->bank_account_number))
        <hr class="divider">
        <div class="section-title">Détails virement bancaire</div>
        <div class="method-box">
            <div class="method-box-header"> Virement reçu sur le compte</div>
            <div class="method-box-body">
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
                </table>
            </div>
        </div>
        @endif

        {{-- ── Mention officielle ── --}}
        <div style="margin-top:16px; background:#f0fdf4; border:1px solid #86efac; border-radius:6px; padding:8px 14px; font-size:10px; color:#166534; text-align:center;">
            Ce document tient lieu de reçu officiel de paiement.
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
