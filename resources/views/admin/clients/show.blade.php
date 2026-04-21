{{-- resources/views/admin/clients/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Client - ' . $client->name . ' - NovaTech Admin')
@section('page-title', 'Détail du client')

@push('styles')
<style>
    /* ============================================
       CLIENTS SHOW — aligned with design system
    ============================================ */

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
    }
    .breadcrumb a { color: var(--text-tertiary); transition: color var(--transition-fast); }
    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.6rem; }

    /* ── Client Header ── */
    .client-header {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        box-shadow: var(--shadow-xs);
    }

    .client-logo-large {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
    }

    .client-logo-placeholder {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--brand-primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .client-info {
        flex: 1;
    }

    .client-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: var(--text-primary);
    }

    .client-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .client-meta i {
        width: 1rem;
        margin-right: 0.375rem;
        color: var(--text-tertiary);
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    /* ── Stats Grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        padding: 1rem 1.25rem;
        border: 1px solid var(--border-light);
        transition: all var(--transition-fast);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
    }

    /* ── Section Cards ── */
    .section-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .section-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--brand-primary);
        font-size: 1rem;
    }

    .section-body {
        padding: 1.5rem;
    }

    /* ── Info Grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .info-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .info-label i {
        width: 0.875rem;
        text-align: center;
    }

    .info-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        word-break: break-word;
    }

    .info-value.empty {
        color: var(--text-tertiary);
        font-style: italic;
    }

    /* ── Services List ── */
    .services-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .service-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(59, 130, 246, 0.1);
        color: var(--brand-primary);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .service-tag i {
        font-size: 0.75rem;
    }

    /* ── Tables ── */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        background: var(--bg-tertiary);
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .data-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* ── Badges ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: var(--radius-full);
        white-space: nowrap;
    }

    .badge-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .badge-overdue {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* ── Buttons ── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--brand-primary);
        color: white;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-fast);
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-fast);
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast);
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    /* ── Payment Modal ── */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-elevated);
        border-radius: 0.75rem;
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 500px;
        transform: scale(0.95);
        transition: transform 0.3s ease;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-overlay.active .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1.125rem;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: var(--text-primary);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 0.375rem;
        color: var(--text-secondary);
    }

    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-tertiary);
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    /* ── Responsive ── */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .client-header {
            flex-direction: column;
            text-align: center;
        }

        .header-actions {
            width: 100%;
            justify-content: center;
        }

        .client-meta {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.clients.index') }}">Clients</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $client->name }}</span>
</nav>

{{-- En-tête client --}}
<div class="client-header">
    @if($client->logo)
        <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="client-logo-large">
    @else
        <div class="client-logo-placeholder">
            <i class="fas {{ $client->client_type == 'company' ? 'fa-building' : 'fa-user' }}"></i>
        </div>
    @endif

    <div class="client-info">
        <h2>
            {{ $client->name }}
            @if($client->client_type == 'company' && $client->company_name && $client->company_name != $client->name)
                <span style="font-size: 0.875rem; color: var(--text-tertiary); font-weight: normal;">
                    ({{ $client->company_name }})
                </span>
            @endif
        </h2>
        <div class="client-meta">
            <span><i class="fas fa-envelope"></i> {{ $client->email }}</span>
            @if($client->phone)
                <span><i class="fas fa-phone"></i> {{ $client->phone }}</span>
            @endif
            @if($client->city)
                <span><i class="fas fa-map-marker-alt"></i> {{ $client->city }}{{ $client->country ? ', ' . $client->country : '' }}</span>
            @endif
            <span>
                <i class="fas fa-circle" style="font-size: 0.5rem; color: {{ $client->is_active ? '#10b981' : '#ef4444' }}"></i>
                {{ $client->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>
    </div>

    <div class="header-actions">
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.billing.invoices.create', ['client_id' => $client->id]) }}" class="btn-secondary">
            <i class="fas fa-file-invoice"></i> Facturer
        </a>
    </div>
</div>

{{-- Statistiques --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalInvoiced ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Total facturé</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalPaid ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Total payé</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: {{ ($balance ?? 0) > 0 ? 'var(--brand-warning)' : '#10b981' }};">
            {{ number_format($balance ?? 0, 0, ',', ' ') }} FCFA
        </div>
        <div class="stat-label">Solde restant</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $client->services->count() }}</div>
        <div class="stat-label">Services souscrits</div>
    </div>
</div>

{{-- Services --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-cogs"></i> Services souscrits
        </div>
        @can('services.assign')
        <a href="{{ route('admin.clients.edit', $client) }}#services" class="btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
            <i class="fas fa-plus"></i> Gérer les services
        </a>
        @endcan
    </div>
    <div class="section-body">
        <div class="services-list">
            @forelse($client->services as $service)
                <div class="service-tag">
                    <i class="fas fa-check-circle"></i>
                    {{ $service->name ?? $service->title }}
                    @if($service->pivot && $service->pivot->price)
                        <span style="font-size: 0.6875rem; opacity: 0.8;">({{ number_format($service->pivot->price, 0, ',', ' ') }} FCFA)</span>
                    @endif
                </div>
            @empty
                <span class="info-value empty">
                    <i class="fas fa-info-circle"></i> Aucun service souscrit
                </span>
            @endforelse
        </div>
    </div>
</div>

{{-- Factures --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-file-invoice"></i> Factures
        </div>
        <a href="{{ route('admin.billing.invoices.create', ['client_id' => $client->id]) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
            <i class="fas fa-plus"></i> Nouvelle facture
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Date</th>
                    <th>Échéance</th>
                    <th>Montant TTC</th>
                    <th>Payé</th>
                    <th>Reste</th>
                    <th>Statut</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->invoices()->orderBy('issue_date', 'desc')->get() as $invoice)
                <tr>
                    <td>
                        <strong>{{ $invoice->invoice_number }}</strong>
                    </td>
                    <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                    <td>
                        {{ $invoice->due_date->format('d/m/Y') }}
                        @if($invoice->is_overdue)
                            <span style="color: #ef4444; font-size: 0.625rem;">
                                <i class="fas fa-exclamation-circle"></i> En retard
                            </span>
                        @endif
                    </td>
                    <td>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @php
                            $remaining = $invoice->total - $invoice->paid_amount;
                        @endphp
                        @if($remaining > 0)
                            <span style="color: #f59e0b;">{{ number_format($remaining, 0, ',', ' ') }} FCFA</span>
                        @else
                            <span style="color: #10b981;">0 FCFA</span>
                        @endif
                    </td>
                    <td>
                        @if($invoice->status == 'paid')
                            <span class="badge badge-paid"><i class="fas fa-check-circle"></i> Payée</span>
                        @elseif($invoice->status == 'partially_paid')
                            <span class="badge badge-pending"><i class="fas fa-chart-simple"></i> Partiel</span>
                        @elseif($invoice->is_overdue)
                            <span class="badge badge-overdue"><i class="fas fa-exclamation-triangle"></i> En retard</span>
                        @else
                            <span class="badge badge-pending"><i class="fas fa-clock"></i> En attente</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                            <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="action-btn" title="Voir la facture">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($invoice->status != 'paid')
                            <button type="button" class="action-btn" title="Enregistrer un paiement" onclick="openPaymentModal('{{ $invoice->id }}', '{{ $invoice->invoice_number }}', {{ $remaining }})">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            @endif
                            <a href="{{ route('admin.billing.invoices.send', $invoice) }}" class="action-btn" title="Envoyer par email" onclick="return confirm('Envoyer cette facture par email ?')">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <p>Aucune facture</p>
                        <a href="{{ route('admin.billing.invoices.create', ['client_id' => $client->id]) }}" class="btn-primary" style="margin-top: 0.5rem;">
                            Créer la première facture
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Paiements --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-credit-card"></i> Historique des paiements
        </div>
        <a href="{{ route('admin.billing.payments.index') }}" class="btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
            <i class="fas fa-list"></i> Voir tous les paiements
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Paiement</th>
                    <th>Date</th>
                    <th>Facture</th>
                    <th>Montant</th>
                    <th>Type</th>
                    <th>Mode</th>
                    <th>Référence</th>
                    <th>Statut</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->payments()->orderBy('payment_date', 'desc')->get() as $payment)
                <tr>
                    <td>{{ $payment->payment_number ?? '#' . $payment->id }}</td>
                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td>
                        @if($payment->invoice)
                            <a href="{{ route('admin.billing.invoices.show', $payment->invoice) }}" style="color: var(--brand-primary);">
                                {{ $payment->invoice->invoice_number }}
                            </a>
                        @else
                            <span class="empty">-</span>
                        @endif
                    </td>
                    <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                    <td>
                        @if($payment->payment_type == 'deposit')
                            <span class="badge badge-pending">Acompte</span>
                        @elseif($payment->payment_type == 'partial')
                            <span class="badge badge-pending">Partiel</span>
                        @else
                            <span class="badge badge-paid">Complet</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->payment_method == 'cash')
                            <i class="fas fa-money-bill-wave"></i> Espèces
                        @elseif($payment->payment_method == 'bank_transfer')
                            <i class="fas fa-university"></i> Virement
                        @elseif($payment->payment_method == 'mobile_money')
                            <i class="fas fa-mobile-alt"></i> Mobile Money
                        @elseif($payment->payment_method == 'card')
                            <i class="fas fa-credit-card"></i> Carte bancaire
                        @elseif($payment->payment_method == 'check')
                            <i class="fas fa-money-check"></i> Chèque
                        @else
                            {{ $payment->payment_method }}
                        @endif
                    </td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td>
                        @if($payment->status == 'completed')
                            <span class="badge badge-paid"><i class="fas fa-check"></i> Validé</span>
                        @elseif($payment->status == 'pending')
                            <span class="badge badge-pending"><i class="fas fa-clock"></i> En attente</span>
                        @elseif($payment->status == 'failed')
                            <span class="badge badge-overdue"><i class="fas fa-times"></i> Échoué</span>
                        @else
                            <span class="badge badge-pending">{{ $payment->status }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.billing.payments.show', $payment) }}" class="action-btn" title="Voir le détail">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('billing.payments.resend')
                        <form action="{{ route('admin.billing.payments.resend', $payment) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn" title="Renvoyer le reçu" onclick="return confirm('Renvoyer le reçu par email ?')">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="empty-state">
                        <i class="fas fa-credit-card"></i>
                        <p>Aucun paiement enregistré</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Informations complémentaires --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-info-circle"></i> Informations complémentaires
        </div>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><i class="fas fa-id-card"></i> Type de client</span>
                <span class="info-value">
                    @if($client->client_type == 'company')
                        <i class="fas fa-building"></i> Entreprise
                    @else
                        <i class="fas fa-user"></i> Particulier
                    @endif
                </span>
            </div>

            @if($client->client_type == 'company')
            <div class="info-item">
                <span class="info-label"><i class="fas fa-building"></i> Raison sociale</span>
                <span class="info-value">{{ $client->company_name ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-file-invoice"></i> IFU / N° fiscal</span>
                <span class="info-value">{{ $client->tax_number ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-globe"></i> Site web</span>
                <span class="info-value">
                    @if($client->website)
                        <a href="{{ $client->website }}" target="_blank" rel="noopener noreferrer" style="color: var(--brand-primary);">
                            {{ $client->website }}
                        </a>
                    @else
                        <span class="empty">Non renseigné</span>
                    @endif
                </span>
            </div>
            @endif

            <div class="info-item">
                <span class="info-label"><i class="fas fa-venus-mars"></i> Genre</span>
                <span class="info-value">
                    @if($client->gender == 'M') Homme
                    @elseif($client->gender == 'F') Femme
                    @else Non spécifié
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-road"></i> Adresse</span>
                <span class="info-value">{{ $client->address ?? 'Non renseignée' }}</span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-city"></i> Ville / Pays</span>
                <span class="info-value">
                    {{ $client->city ?? 'Non renseignée' }}
                    @if($client->country)
                        ({{ $client->country }})
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-user-tie"></i> Personne de contact</span>
                <span class="info-value">
                    @if($client->contact_name)
                        <strong>{{ $client->contact_name }}</strong>
                        @if($client->contact_position)
                            <span style="color: var(--text-tertiary);"> - {{ $client->contact_position }}</span>
                        @endif
                    @else
                        <span class="empty">Non renseigné</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-chart-line"></i> Cycle de facturation</span>
                <span class="info-value">
                    @if($client->billing_cycle == 'monthly')
                        <i class="fas fa-calendar-alt"></i> Mensuel
                    @elseif($client->billing_cycle == 'quarterly')
                        <i class="fas fa-calendar-week"></i> Trimestriel
                    @elseif($client->billing_cycle == 'yearly')
                        <i class="fas fa-calendar-year"></i> Annuel
                    @else
                        <i class="fas fa-bolt"></i> Paiement unique
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-envelope"></i> Factures par email</span>
                <span class="info-value">
                    @if($client->invoice_by_email)
                        <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Activé</span>
                    @else
                        <span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Désactivé</span>
                    @endif
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-calendar-plus"></i> Date d'inscription</span>
                <span class="info-value">{{ $client->created_at->format('d/m/Y à H:i') }}</span>
            </div>

            <div class="info-item">
                <span class="info-label"><i class="fas fa-history"></i> Dernière modification</span>
                <span class="info-value">{{ $client->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Modal d'enregistrement de paiement --}}
<div id="paymentModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-credit-card"></i> Enregistrer un paiement</h3>
            <button class="modal-close" onclick="closePaymentModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="paymentForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Facture</label>
                    <input type="text" id="invoiceInfo" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant restant</label>
                    <input type="text" id="remainingAmount" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant à payer <span class="required">*</span></label>
                    <input type="number" id="paymentAmount" name="amount" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mode de paiement <span class="required">*</span></label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Sélectionner</option>
                        <option value="cash">Espèces</option>
                        <option value="bank_transfer">Virement bancaire</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Carte bancaire</option>
                        <option value="check">Chèque</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de paiement</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Référence (optionnel)</label>
                    <input type="text" name="reference" class="form-control" placeholder="N° de transaction, chèque, etc.">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Commentaires..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closePaymentModal()">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer le paiement</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Modal de paiement
    const paymentModal = document.getElementById('paymentModal');
    const paymentForm = document.getElementById('paymentForm');
    const invoiceInfo = document.getElementById('invoiceInfo');
    const remainingAmount = document.getElementById('remainingAmount');
    const paymentAmount = document.getElementById('paymentAmount');

    function openPaymentModal(invoiceId, invoiceNumber, remaining) {
        // Construction correcte de l'URL avec Laravel route()
        const url = "{{ route('admin.billing.invoices.payment', ':invoice') }}".replace(':invoice', invoiceId);

        // Mettre à jour le formulaire
        paymentForm.action = url;
        invoiceInfo.value = invoiceNumber;
        remainingAmount.value = new Intl.NumberFormat('fr-FR').format(remaining) + ' FCFA';
        paymentAmount.max = remaining;
        paymentAmount.value = remaining;

        // Afficher le modal
        paymentModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        paymentModal.classList.remove('active');
        document.body.style.overflow = '';
        paymentForm.reset();
    }

    // Fermer en cliquant sur l'overlay
    paymentModal.onclick = function(e) {
        if (e.target === paymentModal) {
            closePaymentModal();
        }
    };

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && paymentModal.classList.contains('active')) {
            closePaymentModal();
        }
    });

    // Validation du montant
    paymentAmount.addEventListener('change', function() {
        const max = parseFloat(this.max);
        const value = parseFloat(this.value);
        if (value > max) {
            alert('Le montant ne peut pas dépasser le solde restant (' + new Intl.NumberFormat('fr-FR').format(max) + ' FCFA)');
            this.value = max;
        }
        if (value < 0) {
            this.value = 0;
        }
    });
</script>
@endpush
