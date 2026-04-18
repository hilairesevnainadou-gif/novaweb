{{-- resources/views/admin/tickets/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tickets support - NovaTech Admin')
@section('page-title', 'Gestion des tickets')

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
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }
    .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.green i { color: #10b981; }
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
    .tickets-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .tickets-table thead {
        background: var(--bg-tertiary);
    }
    .tickets-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .tickets-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .tickets-table tbody tr {
        transition: background 0.2s;
    }
    .tickets-table tbody tr:hover {
        background: var(--bg-hover);
    }
    .ticket-id {
        font-weight: 600;
        color: var(--brand-primary);
        font-family: monospace;
    }
    .ticket-subject {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    .ticket-preview {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
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
    .badge-status-open {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    .badge-status-in_progress {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .badge-status-closed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-priority-low {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-priority-medium {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    .badge-priority-high {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .badge-priority-urgent {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        .ticket-preview {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title-section">
        <h1>Tickets support</h1>
        <p>Gérez les tickets de support client</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><i class="fas fa-ticket-alt"></i></div></div>
        <div class="stat-value" id="statTotal">{{ $tickets->total() }}</div>
        <div class="stat-label">Total tickets</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><i class="fas fa-inbox"></i></div></div>
        <div class="stat-value" id="statOpen">{{ $openTickets }}</div>
        <div class="stat-label">Ouverts</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon yellow"><i class="fas fa-spinner"></i></div></div>
        <div class="stat-value" id="statInProgress">{{ $inProgressTickets }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value" id="statClosed">{{ $closedTickets }}</div>
        <div class="stat-label">Fermés</div>
    </div>
</div>

<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div><input type="text" id="search" placeholder="Rechercher un ticket..." class="filter-input" autocomplete="off"></div>
        <div>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="open">Ouvert</option>
                <option value="in_progress">En cours</option>
                <option value="closed">Fermé</option>
            </select>
        </div>
        <div>
            <select id="priorityFilter" class="filter-select">
                <option value="">Toutes priorités</option>
                <option value="low">Basse</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
                <option value="urgent">Urgente</option>
            </select>
        </div>
        <div><button onclick="resetFilters()" class="btn-reset"><i class="fas fa-undo-alt"></i> Réinitialiser</button></div>
    </div>
</div>

<div class="table-container">
    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Client</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="tickets-list">
            @forelse($tickets as $index => $ticket)
            <tr class="table-row"
                data-id="{{ $ticket->id }}"
                data-subject="{{ strtolower($ticket->subject) }}"
                data-name="{{ strtolower($ticket->user->name ?? '') }}"
                data-email="{{ strtolower($ticket->user->email ?? '') }}"
                data-status="{{ $ticket->status }}"
                data-priority="{{ $ticket->priority }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td class="ticket-id">#{{ $ticket->id }}</td>
                <td>
                    <div class="ticket-subject">{{ $ticket->subject }}</div>
                    <div class="ticket-preview">{{ Str::limit($ticket->description ?? $ticket->messages->first()->message ?? '', 60) }}</div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="user-avatar" style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name" style="font-weight: 600;">{{ $ticket->user->name ?? 'Utilisateur' }}</div>
                            <div class="user-email" style="font-size: 0.75rem; color: var(--text-secondary);">{{ $ticket->user->email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @php
                        $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'];
                        $priorityIcons = ['low' => 'arrow-down', 'medium' => 'minus', 'high' => 'arrow-up', 'urgent' => 'exclamation-triangle'];
                    @endphp
                    <span class="badge badge-priority-{{ $ticket->priority }}">
                        <i class="fas fa-{{ $priorityIcons[$ticket->priority] ?? 'flag' }}"></i>
                        {{ $priorityLabels[$ticket->priority] ?? ucfirst($ticket->priority) }}
                    </span>
                </td>
                <td>
                    @php
                        $statusLabels = ['open' => 'Ouvert', 'in_progress' => 'En cours', 'closed' => 'Fermé'];
                        $statusIcons = ['open' => 'inbox', 'in_progress' => 'spinner', 'closed' => 'check-circle'];
                    @endphp
                    <span class="badge badge-status-{{ $ticket->status }}">
                        <i class="fas fa-{{ $statusIcons[$ticket->status] ?? 'circle' }}"></i>
                        {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                    </span>
                </td>
                <td>
                    <div style="font-size: 0.875rem;">{{ $ticket->created_at->format('d/m/Y') }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ $ticket->created_at->format('H:i') }}</div>
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($ticket->status !== 'closed')
                            <button type="button" class="action-btn close-ticket-btn"
                                    data-id="{{ $ticket->id }}"
                                    data-subject="{{ $ticket->subject }}">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        @endif
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $ticket->id }}"
                                data-subject="{{ $ticket->subject }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="7" class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <p>Aucun ticket trouvé</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($tickets->hasPages())
<div class="pagination-wrapper">{{ $tickets->links() }}</div>
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
    const priorityFilter = document.getElementById('priorityFilter');
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

    // Fermer un ticket
    function initCloseTicketButtons() {
        var buttons = document.querySelectorAll('.close-ticket-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleCloseClick);
            buttons[i].addEventListener('click', handleCloseClick);
        }
    }

    function handleCloseClick() {
        var id = this.dataset.id;
        var subject = this.dataset.subject;
        openModal(
            'Fermer le ticket',
            'Êtes-vous sûr de vouloir fermer le ticket #' + id + ' - "' + subject + '" ?',
            'Le ticket sera marqué comme fermé.',
            'Fermer',
            'btn-warning',
            function() { closeTicket(id); }
        );
    }

    function closeTicket(id) {
        fetch('/admin/tickets/' + id + '/close', {
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
            if (data.success !== undefined) {
                showToast('Ticket fermé avec succès', 'success');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showToast('Une erreur est survenue', 'error');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Supprimer un ticket
    function initDeleteButtons() {
        var buttons = document.querySelectorAll('.delete-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleDeleteClick);
            buttons[i].addEventListener('click', handleDeleteClick);
        }
    }

    function handleDeleteClick() {
        var id = this.dataset.id;
        var subject = this.dataset.subject;
        openModal(
            'Supprimer le ticket',
            'Êtes-vous sûr de vouloir supprimer le ticket #' + id + ' - "' + subject + '" ?',
            'Action irréversible.',
            'Supprimer',
            'btn-danger',
            function() { deleteTicket(id); }
        );
    }

    function deleteTicket(id) {
        fetch('/admin/tickets/' + id, {
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
            if (data.success !== undefined) {
                showToast('Ticket supprimé avec succès', 'success');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showToast('Une erreur est survenue', 'error');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Filtres
    function filterTable() {
        var searchTerm = (searchInput ? searchInput.value.toLowerCase() : '');
        var statusValue = (statusFilter ? statusFilter.value : '');
        var priorityValue = (priorityFilter ? priorityFilter.value : '');

        var visibleCount = 0;
        var openCount = 0;
        var inProgressCount = 0;
        var closedCount = 0;

        var rows = document.querySelectorAll('#tickets-list tr:not(#emptyStateRow)');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var show = true;
            var subject = row.dataset.subject || '';
            var name = row.dataset.name || '';
            var email = row.dataset.email || '';
            var status = row.dataset.status || '';
            var priority = row.dataset.priority || '';

            if (searchTerm && subject.indexOf(searchTerm) === -1 && name.indexOf(searchTerm) === -1 && email.indexOf(searchTerm) === -1) {
                show = false;
            }

            if (show && statusValue && status !== statusValue) {
                show = false;
            }

            if (show && priorityValue && priority !== priorityValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
                if (status === 'open') openCount++;
                else if (status === 'in_progress') inProgressCount++;
                else if (status === 'closed') closedCount++;
            }
        }

        document.getElementById('statTotal').textContent = visibleCount;
        document.getElementById('statOpen').textContent = openCount;
        document.getElementById('statInProgress').textContent = inProgressCount;
        document.getElementById('statClosed').textContent = closedCount;
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (priorityFilter) priorityFilter.value = '';
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
    if (priorityFilter) priorityFilter.addEventListener('change', filterTable);

    document.addEventListener('DOMContentLoaded', function() {
        initCloseTicketButtons();
        initDeleteButtons();
    });

    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
