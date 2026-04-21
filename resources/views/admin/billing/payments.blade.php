{{-- resources/views/admin/billing/payments.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Paiements - NovaTech Admin')
@section('page-title', 'Gestion des paiements')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.indigo { background: rgba(59, 130, 246, 0.1); }
    .stat-icon.indigo i { color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.green i { color: #10b981; }
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }
    .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.cyan { background: rgba(6, 182, 212, 0.1); }
    .stat-icon.cyan i { color: #06b6d4; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        letter-spacing: 0.5px;
    }

    .filters-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        outline: none;
    }

    .filter-input:focus, .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-reset {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
    }

    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .payments-table thead {
        background: var(--bg-tertiary);
    }

    .payments-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .payments-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .payments-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
        white-space: nowrap;
    }

    .badge-deposit { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-partial { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-full { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .actions-cell {
        text-align: right;
        white-space: nowrap;
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.375rem;
        transition: all var(--transition-fast);
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-tertiary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .page-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 640px) {
        .page-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .page-title-section h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .page-title-section p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .gap-3 {
        gap: 0.75rem;
    }

    @media (min-width: 640px) {
        .sm\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    .table-row {
        animation: fadeInUp 0.3s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal styles */
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
        max-width: 450px;
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

    .modal-body p {
        margin: 0 0 0.5rem 0;
        line-height: 1.6;
        color: var(--text-secondary);
    }

    .modal-body .warning-text {
        color: var(--brand-warning);
        font-size: 0.875rem;
        margin-top: 0.75rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: none;
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-primary {
        background: var(--brand-primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Toast notification */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        z-index: 10001;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toast-success {
        background: #10b981;
        color: white;
    }

    .toast-error {
        background: #ef4444;
        color: white;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
@can('billing.payments.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>Paiements</h1>
        <p>Gérez tous les paiements enregistrés</p>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
        <div class="stat-value">{{ $payments->total() }}</div>
        <div class="stat-label">Total paiements</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-money-bill"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalAmount ?? $payments->sum('amount'), 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Montant total</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-percent"></i>
            </div>
        </div>
        <div class="stat-value">{{ $depositCount ?? $payments->where('payment_type', 'deposit')->count() }}</div>
        <div class="stat-label">Acomptes</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
        <div class="stat-value">{{ $thisMonthCount ?? $payments->where('payment_date', '>=', now()->startOfMonth())->count() }}</div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="N° paiement, client..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="payment_type" class="filter-select">
                <option value="">Tous types</option>
                <option value="deposit">Acompte</option>
                <option value="partial">Paiement partiel</option>
                <option value="full">Paiement complet</option>
            </select>
        </div>
        <div>
            <select id="client_id" class="filter-select">
                <option value="">Tous clients</option>
                @foreach($clients ?? [] as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="btn-reset">
                <i class="fas fa-undo-alt"></i>
                Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Tableau -->
<div class="table-container">
    <table class="payments-table">
        <thead>
            <tr>
                <th>N° Paiement</th>
                <th>Client</th>
                <th>N° Facture</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Type</th>
                <th>Mode</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="paymentsTableBody">
            @forelse($payments as $index => $payment)
            <tr class="table-row" data-id="{{ $payment->id }}" data-number="{{ strtolower($payment->payment_number) }}" data-client="{{ strtolower($payment->client->name ?? '') }}" data-type="{{ $payment->payment_type }}" data-client-id="{{ $payment->client_id }}" style="animation-delay: {{ $index * 0.03 }}s;">
                <td><strong>{{ $payment->payment_number ?? '#' . $payment->id }}</strong></td>
                <td>{{ $payment->client->name ?? 'Client supprimé' }}</td>
                <td>{{ $payment->invoice->invoice_number ?? '-' }}</td>
                <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Non définie' }}</td>
                <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                <td>
                    @if($payment->payment_type == 'deposit')
                        <span class="badge badge-deposit"><i class="fas fa-percent"></i> Acompte</span>
                    @elseif($payment->payment_type == 'partial')
                        <span class="badge badge-partial"><i class="fas fa-clock"></i> Partiel</span>
                    @else
                        <span class="badge badge-full"><i class="fas fa-check-circle"></i> Complet</span>
                    @endif
                </td>
                <td>
                    @if($payment->payment_method == 'cash') <i class="fas fa-money-bill-wave"></i> Espèces
                    @elseif($payment->payment_method == 'bank_transfer') <i class="fas fa-university"></i> Virement
                    @elseif($payment->payment_method == 'mobile_money') <i class="fas fa-mobile-alt"></i> Mobile Money
                    @elseif($payment->payment_method == 'card') <i class="fas fa-credit-card"></i> Carte
                    @elseif($payment->payment_method == 'check') <i class="fas fa-money-check"></i> Chèque
                    @else <i class="fas fa-check"></i> {{ $payment->payment_method ?? 'Non spécifié' }}
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.billing.payments.show', $payment) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('billing.payments.resend')
                        <button type="button" class="action-btn resend-btn"
                                data-id="{{ $payment->id }}"
                                data-number="{{ $payment->payment_number ?? '#' . $payment->id }}"
                                title="Renvoyer le reçu">
                            <i class="fas fa-envelope"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="8" class="empty-state">
                    <i class="fas fa-credit-card"></i>
                    <p>Aucun paiement trouvé</p>
                    <p style="font-size: 0.875rem;">Les paiements apparaîtront ici une fois enregistrés</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($payments->hasPages())
<div class="pagination-wrapper">
    {{ $payments->links() }}
</div>
@endif

<!-- Modal de confirmation -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Confirmation</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p id="modalWarning" class="warning-text"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-primary">Confirmer</button>
        </div>
    </div>
</div>

<!-- Formulaire caché pour l'envoi -->
<form id="resendForm" method="POST" style="display: none;">
    @csrf
</form>
@endcan

@cannot('billing.payments.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
    const typeSelect = document.getElementById('payment_type');
    const clientSelect = document.getElementById('client_id');
    const resendForm = document.getElementById('resendForm');

    // Éléments du modal
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    let currentAction = null;
    let isLoading = false;

    // Fonction pour afficher une notification
    function showNotification(message, type = 'success') {
        // Supprimer les notifications existantes
        const existingNotifications = document.querySelectorAll('.toast-notification');
        existingNotifications.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `toast-notification toast-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Fonctions du modal
    function openModal(title, message, warning, confirmText, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;
        modalConfirmBtn.disabled = false;
        modalConfirmBtn.innerHTML = confirmText;

        currentAction = onConfirm;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentAction = null;
        isLoading = false;
    }

    async function confirmAction() {
        if (currentAction && typeof currentAction === 'function' && !isLoading) {
            isLoading = true;
            modalConfirmBtn.disabled = true;
            modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';

            try {
                await currentAction();
            } catch (error) {
                console.error('Erreur lors de l\'action:', error);
                showNotification('Une erreur est survenue', 'error');
            } finally {
                isLoading = false;
                modalConfirmBtn.disabled = false;
                modalConfirmBtn.innerHTML = 'Confirmer';
            }
        }
        closeModal();
    }

    // Écouteurs du modal
    modalConfirmBtn.onclick = confirmAction;

    // Fermer en cliquant sur l'overlay
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeModal();
        }
    };

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Renvoyer reçu - Version avec formulaire standard (plus fiable)
    document.querySelectorAll('.resend-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const number = this.dataset.number;

            openModal(
                'Renvoyer le reçu',
                `Êtes-vous sûr de vouloir renvoyer le reçu "${number}" par email ?`,
                'Le client recevra le reçu par email avec le PDF en pièce jointe.',
                'Renvoyer',
                () => {
                    // Soumettre le formulaire directement
                    resendForm.action = `{{ url('admin/billing/payments') }}/${id}/resend`;
                    resendForm.submit();
                }
            );
        });
    });

    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const typeValue = typeSelect?.value || '';
        const clientId = clientSelect?.value || '';

        let visibleCount = 0;
        document.querySelectorAll('.payments-table tbody tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const number = row.dataset.number || '';
            const client = row.dataset.client || '';
            const type = row.dataset.type || '';
            const rowClientId = row.dataset.clientId || '';

            if (searchTerm && !number.includes(searchTerm) && !client.includes(searchTerm)) show = false;
            if (show && typeValue && type !== typeValue) show = false;
            if (show && clientId && rowClientId !== clientId) show = false;

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Gérer l'affichage du message "aucun résultat"
        const noResultsRow = document.getElementById('noResultsRow');
        const allRows = document.querySelectorAll('.payments-table tbody tr:not(#emptyStateRow):not(#noResultsRow)');

        if (allRows.length > 0 && visibleCount === 0) {
            if (!noResultsRow) {
                const tbody = document.getElementById('paymentsTableBody');
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="8" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                   </td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (typeSelect) typeSelect.value = '';
        if (clientSelect) clientSelect.value = '';
        filterTable();
    }

    let timer;
    if (searchInput) searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(filterTable, 300);
    });
    if (typeSelect) typeSelect.addEventListener('change', filterTable);
    if (clientSelect) clientSelect.addEventListener('change', filterTable);

    // Exposer les fonctions globales
    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
    window.showNotification = showNotification;
</script>
@endpush
