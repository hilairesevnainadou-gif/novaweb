{{-- resources/views/admin/billing/show-invoice.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Facture ' . $invoice->invoice_number . ' - NovaTech Admin')
@section('page-title', 'Facture ' . $invoice->invoice_number)

@push('styles')
<style>
    .invoice-header {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .invoice-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-group h4 {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .info-group p {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .badge-draft { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-sent { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-paid { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-partial { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-overdue { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all var(--transition-fast);
    }

    .btn-primary { background: var(--brand-primary); color: white; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }
    .btn-secondary { background: var(--bg-tertiary); color: var(--text-primary); border: 1px solid var(--border-light); }
    .btn-secondary:hover { background: var(--bg-hover); }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .btn-warning { background: #f59e0b; color: white; }
    .btn-warning:hover { background: #d97706; }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }

    .invoice-table th {
        text-align: left;
        padding: 0.75rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
    }

    .invoice-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-light);
    }

    .totals {
        width: 300px;
        margin-left: auto;
    }

    .totals tr td {
        padding: 0.5rem 0;
    }

    .payment-form {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .form-group label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        display: block;
        margin-bottom: 0.25rem;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .payments-table th,
    .payments-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    .payments-table th {
        background: var(--bg-tertiary);
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--brand-primary);
        display: inline-block;
    }

    .amount-warning {
        font-size: 0.7rem;
        color: #f59e0b;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .invoice-info, .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .totals {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="invoice-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div>
            <h2 style="margin: 0 0 0.25rem 0;">Facture {{ $invoice->invoice_number }}</h2>
            <p style="color: var(--text-secondary);">Date d'émission: {{ $invoice->issue_date->format('d/m/Y') }}</p>
        </div>
        <div>
            @if($invoice->status == 'draft')
                <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Brouillon</span>
            @elseif($invoice->status == 'sent')
                <span class="badge badge-sent"><i class="fas fa-paper-plane"></i> Envoyée</span>
            @elseif($invoice->status == 'partially_paid')
                <span class="badge badge-partial"><i class="fas fa-clock"></i> Partiellement payée</span>
            @elseif($invoice->status == 'paid')
                <span class="badge badge-paid"><i class="fas fa-check-circle"></i> Payée</span>
            @elseif($invoice->status == 'overdue')
                <span class="badge badge-overdue"><i class="fas fa-exclamation-triangle"></i> En retard</span>
            @endif
        </div>
    </div>
</div>

<div class="action-buttons">
    @if($invoice->status != 'paid')
        @can('billing.invoices.send')
        <form action="{{ route('admin.billing.invoices.send', $invoice) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Envoyer par email
            </button>
        </form>
        @endcan
    @endif
    <a href="{{ route('admin.billing.invoices.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- Informations -->
<div class="invoice-info">
    <div class="info-group">
        <h4>Émetteur</h4>
        <p><strong>{{ $company->name ?? config('app.name') }}</strong></p>
        <p>{{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
        <p>{{ $company->email ?? 'contact@novatech.bj' }}</p>
        <p>{{ $company->phone ?? '+229 66 18 55 95' }}</p>
        @if($company->tax_number ?? false)
        <p>IFU: {{ $company->tax_number }}</p>
        @endif
    </div>
    <div class="info-group">
        <h4>Client</h4>
        <p><strong>{{ $invoice->client->name }}</strong></p>
        <p>{{ $invoice->client->address ?? '' }}</p>
        <p>{{ $invoice->client->email }}</p>
        <p>{{ $invoice->client->phone ?? '' }}</p>
        @if($invoice->client->tax_number)
        <p>IFU: {{ $invoice->client->tax_number }}</p>
        @endif
    </div>
    <div class="info-group">
        <h4>Détails</h4>
        <p>N° Facture: {{ $invoice->invoice_number }}</p>
        <p>Date d'émission: {{ $invoice->issue_date->format('d/m/Y') }}</p>
        <p>Date d'échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
        @if($invoice->due_date < now() && $invoice->status != 'paid')
        <p style="color: #ef4444;"> Facture en retard</p>
        @endif
    </div>
</div>

<!-- Lignes -->
<table class="invoice-table">
    <thead>
        <tr>
            <th>Description</th>
            <th>Montant HT</th>
            <th>TVA</th>
            <th>Montant TTC</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $invoice->description }}</td>
            <td>{{ number_format($invoice->subtotal, 2, ',', ' ') }} FCFA</td>
            <td>{{ $invoice->tax_rate }}% ({{ number_format($invoice->tax_amount, 2, ',', ' ') }} FCFA)</td>
            <td><strong>{{ number_format($invoice->total, 2, ',', ' ') }} FCFA</strong></td>
        </tr>
    </tbody>
</table>

<!-- Totaux -->
<table class="totals">
    <tr><td>Sous-total HT :</td><td style="text-align: right;">{{ number_format($invoice->subtotal, 2, ',', ' ') }} FCFA</td></tr>
    <tr><td>TVA ({{ $invoice->tax_rate }}%) :</td><td style="text-align: right;">{{ number_format($invoice->tax_amount, 2, ',', ' ') }} FCFA</td></tr>
    <tr style="border-top: 1px solid var(--border-light);"><td><strong>Total TTC :</strong></td><td style="text-align: right;"><strong>{{ number_format($invoice->total, 2, ',', ' ') }} FCFA</strong></td></tr>
    @if($invoice->paid_amount > 0)
    <tr><td>Montant déjà payé :</td><td style="text-align: right; color: #10b981;">{{ number_format($invoice->paid_amount, 2, ',', ' ') }} FCFA</td></tr>
    @endif
    @if($invoice->remaining_amount > 0)
    <tr style="border-top: 2px solid var(--border-light);"><td><strong>Reste à payer :</strong></td><td style="text-align: right;"><strong style="color: #f59e0b;">{{ number_format($invoice->remaining_amount, 2, ',', ' ') }} FCFA</strong></td></tr>
    @endif
</table>

<!-- Formulaire paiement -->
@if($invoice->status != 'paid' && $invoice->remaining_amount > 0)
@can('billing.payments.create')
<div class="payment-form">
    <h3 class="section-title">Enregistrer un paiement</h3>
    <form action="{{ route('admin.billing.invoices.payment', $invoice) }}" method="POST" id="paymentForm">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Montant (max: {{ number_format($invoice->remaining_amount, 2, ',', ' ') }} FCFA)</label>
                <input type="number" name="amount" id="amountInput" class="form-control" step="0.01" min="0.01" max="{{ $invoice->remaining_amount }}" required>
                <div class="amount-warning" id="amountWarning"></div>
            </div>
            <div class="form-group">
                <label>Type de paiement</label>
                <select name="payment_type" class="form-control" required>
                    <option value="deposit">Acompte</option>
                    <option value="partial">Paiement partiel</option>
                    <option value="full">Paiement complet</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mode de paiement</label>
                <select name="payment_method" class="form-control" required>
                    <option value="cash">Espèces</option>
                    <option value="bank_transfer">Virement bancaire</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="card">Carte bancaire</option>
                    <option value="check">Chèque</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date du paiement</label>
                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Référence (optionnel)</label>
                <input type="text" name="reference" class="form-control" placeholder="N° transaction, chèque...">
            </div>
            <div class="form-group">
                <label>Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="Notes...">
            </div>
        </div>
        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-success" id="submitBtn">
                <i class="fas fa-save"></i> Enregistrer le paiement
            </button>
        </div>
    </form>
</div>
@endcan
@endif

<!-- Historique paiements -->
@if($invoice->payments->count() > 0)
<div style="margin-top: 1.5rem;">
    <h3 class="section-title">Historique des paiements</h3>
    <table class="payments-table">
        <thead>
            <tr>
                <th>N° Paiement</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Type</th>
                <th>Mode</th>
                <th>Référence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->payments as $payment)
            <tr>
                <td>{{ $payment->payment_number }}</td>
                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td>{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
                <td>
                    @if($payment->payment_type == 'deposit') Acompte
                    @elseif($payment->payment_type == 'partial') Partiel
                    @else Complet
                    @endif
                </td>
                <td>
                    @if($payment->payment_method == 'cash') Espèces
                    @elseif($payment->payment_method == 'bank_transfer') Virement
                    @elseif($payment->payment_method == 'mobile_money') Mobile Money
                    @else {{ $payment->payment_method }}
                    @endif
                </td>
                <td>{{ $payment->reference ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@push('scripts')
<script>
    const amountInput = document.getElementById('amountInput');
    const amountWarning = document.getElementById('amountWarning');
    const submitBtn = document.getElementById('submitBtn');
    const remainingAmount = {{ $invoice->remaining_amount }};
    const form = document.getElementById('paymentForm');

    if (amountInput) {
        amountInput.addEventListener('input', function() {
            let value = parseFloat(this.value);

            if (isNaN(value)) {
                amountWarning.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }

            if (value > remainingAmount) {
                amountWarning.innerHTML = ' Le montant ne peut pas dépasser ' + remainingAmount.toFixed(2) + ' FCFA';
                amountWarning.style.color = '#ef4444';
                submitBtn.disabled = true;
            } else if (value <= 0) {
                amountWarning.innerHTML = ' Le montant doit être supérieur à 0';
                amountWarning.style.color = '#ef4444';
                submitBtn.disabled = true;
            } else {
                amountWarning.innerHTML = '';
                submitBtn.disabled = false;
            }
        });

        form.addEventListener('submit', function(e) {
            let value = parseFloat(amountInput.value);

            if (isNaN(value) || value <= 0) {
                e.preventDefault();
                amountWarning.innerHTML = ' Veuillez saisir un montant valide';
                amountWarning.style.color = '#ef4444';
                return false;
            }

            if (value > remainingAmount) {
                e.preventDefault();
                amountWarning.innerHTML = ' Le montant ne peut pas dépasser ' + remainingAmount.toFixed(2) + ' FCFA';
                amountWarning.style.color = '#ef4444';
                return false;
            }

            // Arrondir à 2 décimales avant l'envoi
            amountInput.value = value.toFixed(2);
        });
    }
</script>
@endpush
