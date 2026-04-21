{{-- resources/views/admin/clients/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Clients - NovaTech Admin')
@section('page-title', 'Gestion des Clients')

@push('styles')
<style>
    /* ========== STATS GRID ========== */
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

    /* ========== FILTERS CONTAINER ========== */
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
        transition: all 0.2s;
        outline: none;
    }

    .filter-input:focus, .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-input::placeholder {
        color: var(--text-tertiary);
    }

    /* Grid system */
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

    /* ========== BUTTONS ========== */
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
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* ========== TABLE ========== */
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .clients-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .clients-table thead {
        background: var(--bg-tertiary);
    }

    .clients-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .clients-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .clients-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Client logo */
    .client-logo {
        width: 45px;
        height: 45px;
        border-radius: 0.5rem;
        object-fit: cover;
        background: var(--bg-tertiary);
    }

    .client-logo-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, var(--brand-primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .client-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .client-email {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    /* Badges */
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

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-services {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    /* Actions */
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
        transition: all 0.2s;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .action-btn.delete:hover {
        color: var(--brand-error);
    }

    /* Empty state */
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

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    /* Page header */
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

    /* Animations */
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

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    /* ========== MODAL STYLES ========== */
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
        color: #f59e0b;
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
        transition: all 0.2s;
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

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }
</style>
@endpush

@section('content')
@can('clients.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>Clients</h1>
        <p>Gérez votre portefeuille clients</p>
    </div>
    @can('clients.create')
    <div>
        <a href="{{ route('admin.clients.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouveau client
        </a>
    </div>
    @endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $clients->total() }}</div>
        <div class="stat-label">Total clients</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $activeCount }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalInvoiced ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Chiffre d'affaires</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $outstandingCount ?? 0 }}</div>
        <div class="stat-label">Factures impayées</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher un client..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
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
    <table class="clients-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Contact</th>
                <th>Services</th>
                <th>Total facturé</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="clientsTableBody">
            @forelse($clients as $index => $client)
            <tr class="table-row" data-id="{{ $client->id }}" data-name="{{ strtolower($client->name) }}" data-email="{{ strtolower($client->email) }}" data-status="{{ $client->is_active ? '1' : '0' }}" style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($client->logo)
                            <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="client-logo">
                        @else
                            <div class="client-logo-placeholder">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                        <div>
                            <div class="client-name">{{ $client->name }}</div>
                            <div class="client-email">{{ $client->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size: 0.875rem;">
                        <div><i class="fas fa-user"></i> {{ $client->contact_name ?? '-' }}</div>
                        <div><i class="fas fa-phone"></i> {{ $client->phone ?? '-' }}</div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-services">
                        <i class="fas fa-cogs"></i> {{ $client->services->count() }} service(s)
                    </span>
                </td>
                <td>
                    <div>
                        <strong>{{ number_format($client->total_invoiced, 0, ',', ' ') }} FCFA</strong>
                        @if($client->balance > 0)
                            <div style="font-size: 0.7rem; color: #f59e0b;">
                                Solde: {{ number_format($client->balance, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($client->is_active)
                        <span class="badge badge-active">
                            <i class="fas fa-check-circle"></i> Actif
                        </span>
                    @else
                        <span class="badge badge-inactive">
                            <i class="fas fa-ban"></i> Inactif
                        </span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.clients.show', $client) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('clients.edit')
                        <a href="{{ route('admin.clients.edit', $client) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        <a href="{{ route('admin.billing.invoices.create', ['client_id' => $client->id]) }}" class="action-btn" title="Créer une facture">
                            <i class="fas fa-file-invoice"></i>
                        </a>
                        @can('clients.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $client->id }}"
                                data-name="{{ $client->name }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="6" class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Aucun client trouvé</p>
                    <p style="font-size: 0.875rem;">Commencez par créer votre premier client</p>
                    @can('clients.create')
                    <a href="{{ route('admin.clients.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Créer un client
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($clients->hasPages())
<div class="pagination-wrapper">
    {{ $clients->links() }}
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
            <button id="modalConfirmBtn" class="btn btn-danger">Supprimer</button>
        </div>
    </div>
</div>
@endcan

@cannot('clients.view')
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
    const statusSelect = document.getElementById('status');

    // Éléments du modal
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    let currentAction = null;

    // Fonctions du modal
    function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;

        // Réinitialiser les classes du bouton
        modalConfirmBtn.className = 'btn';
        modalConfirmBtn.classList.add(confirmClass);

        currentAction = onConfirm;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentAction = null;
    }

    function confirmAction() {
        if (currentAction && typeof currentAction === 'function') {
            currentAction();
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

    // Supprimer client
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            openModal(
                'Supprimer le client',
                `Êtes-vous sûr de vouloir supprimer le client "${name}" ?`,
                'Attention : Toutes les factures et paiements associés seront également supprimés.',
                'Supprimer',
                'btn-danger',
                () => {
                    // Afficher un indicateur de chargement
                    modalConfirmBtn.disabled = true;
                    modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

                    fetch(`{{ url('admin/clients') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Client supprimé avec succès', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Une erreur est survenue', 'error');
                            modalConfirmBtn.disabled = false;
                            modalConfirmBtn.innerHTML = 'Supprimer';
                            modalConfirmBtn.classList.add('btn-danger');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Une erreur technique est survenue', 'error');
                        modalConfirmBtn.disabled = false;
                        modalConfirmBtn.innerHTML = 'Supprimer';
                        modalConfirmBtn.classList.add('btn-danger');
                    });
                }
            );
        });
    });

    // Fonction de notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            font-size: 14px;
            z-index: 10001;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Ajout des styles pour l'animation
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);

    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';

        const rows = document.querySelectorAll('.clients-table tbody tr:not(#emptyStateRow)');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let show = true;
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const status = row.dataset.status || '';

            if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) {
                show = false;
            }
            if (show && statusValue && status !== statusValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
            if (show) hasVisibleRows = true;
        });

        // Afficher un message si aucun résultat
        const noResultsRow = document.getElementById('noResultsRow');
        if (!hasVisibleRows && rows.length > 0) {
            if (!noResultsRow) {
                const tbody = document.getElementById('clientsTableBody');
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="6" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                    <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-undo-alt"></i> Réinitialiser les filtres
                    </button>
                </td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        filterTable();
    }

    let timer;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (statusSelect) {
        statusSelect.addEventListener('change', filterTable);
    }

    // Exposer les fonctions globalement
    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
