{{-- resources/views/admin/newsletter/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Newsletter - NovaTech Admin')
@section('page-title', 'Gestion de la newsletter')

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
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); }
    .stat-icon.blue i { color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.green i { color: #10b981; }
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }
    .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); }
    .stat-icon.purple i { color: #8b5cf6; }
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
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
        z-index: 9999;
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
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.3s ease;
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
        font-size: 1.25rem;
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
        border: none;
    }
    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
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
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    .btn-warning:hover {
        background: #d97706;
    }
    .btn-success {
        background: #10b981;
        color: white;
    }
    .btn-success:hover {
        background: #059669;
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
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }
    .newsletter-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    .newsletter-table thead {
        background: var(--bg-tertiary);
    }
    .newsletter-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .newsletter-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .newsletter-table tbody tr {
        transition: background 0.2s;
    }
    .newsletter-table tbody tr:hover {
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
    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-inactive {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
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
        transition: all 0.2s ease;
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
        color: #ef4444;
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
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .table-row {
        animation: fadeInUp 0.3s ease forwards;
    }
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }
    .toast-notification.show {
        transform: translateX(0);
    }
    .toast-notification.success {
        border-left: 4px solid #10b981;
    }
    .toast-notification.error {
        border-left: 4px solid #ef4444;
    }
    .toast-notification i {
        font-size: 1.25rem;
    }
    .toast-notification.success i {
        color: #10b981;
    }
    .toast-notification.error i {
        color: #ef4444;
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title-section">
        <h1>Newsletter</h1>
        <p>Gérez les abonnés à la newsletter</p>
    </div>
    <div>
        <a href="{{ route('admin.newsletter.export') }}" class="btn-primary">
            <i class="fas fa-download"></i> Exporter (CSV)
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><i class="fas fa-users"></i></div></div>
        <div class="stat-value" id="statTotal">{{ $totalCount }}</div>
        <div class="stat-label">Total abonnés</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value" id="statActive">{{ $activeCount }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon yellow"><i class="fas fa-ban"></i></div></div>
        <div class="stat-value" id="statInactive">{{ $inactiveCount }}</div>
        <div class="stat-label">Désabonnés</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div></div>
        <div class="stat-value" id="statThisMonth">
            @php
                $newCount = 0;
                foreach($subscribers as $s) {
                    if($s->created_at->month == now()->month) $newCount++;
                }
                echo $newCount;
            @endphp
        </div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div><input type="text" id="search" placeholder="Rechercher un email..." class="filter-input" autocomplete="off"></div>
        <div>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Désabonné</option>
            </select>
        </div>
        <div></div>
        <div><button onclick="resetFilters()" class="btn-reset"><i class="fas fa-undo-alt"></i> Réinitialiser</button></div>
    </div>
</div>

<div class="table-container">
    <table class="newsletter-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>IP</th>
                <th>Date d'abonnement</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="newsletter-list">
            @forelse($subscribers as $index => $subscriber)
            <tr class="table-row"
                data-id="{{ $subscriber->id }}"
                data-email="{{ strtolower($subscriber->email) }}"
                data-status="{{ $subscriber->is_active ? 'active' : 'inactive' }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="user-avatar" style="width: 2rem; height: 2rem; border-radius: 9999px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem;">
                            {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name" style="font-weight: 500;">{{ $subscriber->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size: 0.75rem; color: var(--text-tertiary);">
                        {{ $subscriber->ip_address ?? '-' }}
                    </div>
                </td>
                <td>
                    <div style="font-size: 0.875rem;">{{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y') : $subscriber->created_at->format('d/m/Y') }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ $subscriber->created_at->format('H:i') }}</div>
                </td>
                <td>
                    @if($subscriber->is_active)
                        <span class="badge badge-active">
                            <i class="fas fa-check-circle"></i> Actif
                        </span>
                    @else
                        <span class="badge badge-inactive">
                            <i class="fas fa-ban"></i> Désabonné
                        </span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        @if($subscriber->is_active)
                            <button type="button" class="action-btn unsubscribe-btn"
                                    data-id="{{ $subscriber->id }}"
                                    data-email="{{ $subscriber->email }}">
                                <i class="fas fa-ban"></i>
                            </button>
                        @else
                            <button type="button" class="action-btn resubscribe-btn"
                                    data-id="{{ $subscriber->id }}"
                                    data-email="{{ $subscriber->email }}">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                        @endif
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $subscriber->id }}"
                                data-email="{{ $subscriber->email }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="5" class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <p>Aucun abonné à la newsletter</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($subscribers->hasPages())
<div class="pagination-wrapper">{{ $subscribers->links() }}</div>
@endif

<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Confirmation</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p id="modalWarning" class="warning-text"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn">Confirmer</button>
        </div>
    </div>
</div>

<div id="toast" class="toast-notification">
    <i id="toastIcon" class="fas"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');

    let currentAction = null;
    const csrfToken = '{{ csrf_token() }}';

    function showToast(message, type) {
        type = type || 'success';
        toastIcon.className = 'fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle');
        toastMessage.textContent = message;
        toast.className = 'toast-notification ' + type + ' show';
        setTimeout(function() { toast.classList.remove('show'); }, 3000);
    }

    function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;
        modalConfirmBtn.className = 'btn ' + confirmClass;
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
        if (currentAction) currentAction();
        closeModal();
    }

    modal.onclick = function(e) {
        if (e.target === modal) closeModal();
    };
    modalConfirmBtn.onclick = confirmAction;
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
    });

    // Désabonner
    function initUnsubscribeButtons() {
        var buttons = document.querySelectorAll('.unsubscribe-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleUnsubscribeClick);
            buttons[i].addEventListener('click', handleUnsubscribeClick);
        }
    }

    function handleUnsubscribeClick() {
        var id = this.dataset.id;
        var email = this.dataset.email;
        openModal(
            'Désabonnement',
            'Êtes-vous sûr de vouloir désabonner "' + email + '" ?',
            'L\'utilisateur ne recevra plus la newsletter.',
            'Désabonner',
            'btn-warning',
            function() { unsubscribe(id); }
        );
    }

    function unsubscribe(id) {
        fetch('/admin/newsletter/' + id + '/unsubscribe', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            showToast('Abonné désabonné avec succès', 'success');
            setTimeout(function() { location.reload(); }, 1000);
        })
        .catch(function(error) {
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Réabonner
    function initResubscribeButtons() {
        var buttons = document.querySelectorAll('.resubscribe-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleResubscribeClick);
            buttons[i].addEventListener('click', handleResubscribeClick);
        }
    }

    function handleResubscribeClick() {
        var id = this.dataset.id;
        var email = this.dataset.email;
        openModal(
            'Réabonnement',
            'Êtes-vous sûr de vouloir réabonner "' + email + '" ?',
            'L\'utilisateur recevra à nouveau la newsletter.',
            'Réabonner',
            'btn-success',
            function() { resubscribe(id); }
        );
    }

    function resubscribe(id) {
        fetch('/admin/newsletter/' + id + '/resubscribe', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            showToast('Abonné réactivé avec succès', 'success');
            setTimeout(function() { location.reload(); }, 1000);
        })
        .catch(function(error) {
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Supprimer
    function initDeleteButtons() {
        var buttons = document.querySelectorAll('.delete-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleDeleteClick);
            buttons[i].addEventListener('click', handleDeleteClick);
        }
    }

    function handleDeleteClick() {
        var id = this.dataset.id;
        var email = this.dataset.email;
        openModal(
            'Suppression',
            'Êtes-vous sûr de vouloir supprimer "' + email + '" ?',
            'Action irréversible.',
            'Supprimer',
            'btn-danger',
            function() { deleteSubscriber(id); }
        );
    }

    function deleteSubscriber(id) {
        fetch('/admin/newsletter/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            showToast('Abonné supprimé avec succès', 'success');
            setTimeout(function() { location.reload(); }, 1000);
        })
        .catch(function(error) {
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Filtres
    function filterTable() {
        var searchTerm = (searchInput ? searchInput.value.toLowerCase() : '');
        var statusValue = (statusFilter ? statusFilter.value : '');

        var visibleCount = 0;
        var activeCount = 0;
        var inactiveCount = 0;

        var rows = document.querySelectorAll('#newsletter-list tr:not(#emptyStateRow)');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var show = true;
            var email = row.dataset.email || '';
            var status = row.dataset.status || '';

            if (searchTerm && email.indexOf(searchTerm) === -1) {
                show = false;
            }

            if (show && statusValue && status !== statusValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
                if (status === 'active') activeCount++;
                else inactiveCount++;
            }
        }

        document.getElementById('statTotal').textContent = visibleCount;
        document.getElementById('statActive').textContent = activeCount;
        document.getElementById('statInactive').textContent = inactiveCount;
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        filterTable();
    }

    var timer;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (statusFilter) statusFilter.addEventListener('change', filterTable);

    document.addEventListener('DOMContentLoaded', function() {
        initUnsubscribeButtons();
        initResubscribeButtons();
        initDeleteButtons();
    });

    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
