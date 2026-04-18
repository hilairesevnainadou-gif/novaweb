{{-- resources/views/admin/contacts/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Messages de contact - NovaTech Admin')
@section('page-title', 'Gestion des messages')

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
    .contacts-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .contacts-table thead {
        background: var(--bg-tertiary);
    }
    .contacts-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .contacts-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .contacts-table tbody tr {
        transition: background 0.2s;
    }
    .contacts-table tbody tr:hover {
        background: var(--bg-hover);
    }
    .contacts-table tbody tr.unread {
        background: rgba(59, 130, 246, 0.05);
    }
    .contact-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .contact-info {
        flex: 1;
        min-width: 0;
    }
    .contact-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    .contact-email {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    .message-subject {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    .message-preview {
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
    .badge-read {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-unread {
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
    .action-btn.read:hover {
        color: #10b981;
    }
    .action-btn.unread:hover {
        color: #f59e0b;
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
        .message-preview {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title-section">
        <h1>Messages de contact</h1>
        <p>Gérez les messages envoyés via le formulaire de contact</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><i class="fas fa-envelope"></i></div></div>
        <div class="stat-value" id="statTotal">{{ $contacts->total() }}</div>
        <div class="stat-label">Total messages</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value" id="statRead">
            @php
                $readCount = 0;
                foreach($contacts as $c) {
                    if($c->is_read) $readCount++;
                }
                echo $readCount;
            @endphp
        </div>
        <div class="stat-label">Lus</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon yellow"><i class="fas fa-circle"></i></div></div>
        <div class="stat-value" id="statUnread">{{ $unreadCount }}</div>
        <div class="stat-label">Non lus</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div></div>
        <div class="stat-value" id="statThisMonth">
            @php
                $thisMonthCount = 0;
                foreach($contacts as $c) {
                    if($c->created_at->month == now()->month) $thisMonthCount++;
                }
                echo $thisMonthCount;
            @endphp
        </div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div><input type="text" id="search" placeholder="Rechercher un message..." class="filter-input" autocomplete="off"></div>
        <div>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="read">Lu</option>
                <option value="unread">Non lu</option>
            </select>
        </div>
        <div></div>
        <div><button onclick="resetFilters()" class="btn-reset"><i class="fas fa-undo-alt"></i> Réinitialiser</button></div>
    </div>
</div>

<div class="table-container">
    <table class="contacts-table">
        <thead>
            <tr>
                <th>Expéditeur</th>
                <th>Message</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="contacts-list">
            @forelse($contacts as $index => $contact)
            <tr class="table-row {{ !$contact->is_read ? 'unread' : '' }}"
                data-id="{{ $contact->id }}"
                data-name="{{ strtolower($contact->name) }}"
                data-email="{{ strtolower($contact->email) }}"
                data-subject="{{ strtolower($contact->subject) }}"
                data-message="{{ strtolower($contact->message) }}"
                data-status="{{ $contact->is_read ? 'read' : 'unread' }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="contact-avatar" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;">
                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                        </div>
                        <div class="contact-info">
                            <div class="contact-name">{{ $contact->name }}</div>
                            <div class="contact-email">{{ $contact->email }}</div>
                            @if($contact->phone)
                                <div style="font-size: 0.7rem; color: var(--text-tertiary); margin-top: 0.25rem;">
                                    <i class="fas fa-phone"></i> {{ $contact->phone }}
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="message-subject">{{ $contact->subject }}</div>
                    <div class="message-preview">{{ Str::limit($contact->message, 80) }}</div>
                </td>
                <td>
                    @if($contact->is_read)
                        <span class="badge badge-read">
                            <i class="fas fa-check-circle"></i> Lu
                        </span>
                    @else
                        <span class="badge badge-unread">
                            <i class="fas fa-circle"></i> Non lu
                        </span>
                    @endif
                </td>
                <td>
                    <div style="font-size: 0.875rem;">{{ $contact->created_at->format('d/m/Y') }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ $contact->created_at->format('H:i') }}</div>
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!$contact->is_read)
                            <button type="button" class="action-btn mark-read-btn"
                                    data-id="{{ $contact->id }}">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        @else
                            <button type="button" class="action-btn mark-unread-btn"
                                    data-id="{{ $contact->id }}">
                                <i class="fas fa-circle"></i>
                            </button>
                        @endif
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $contact->id }}"
                                data-name="{{ $contact->name }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="5" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun message trouvé</p>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($contacts->hasPages())
<div class="pagination-wrapper">{{ $contacts->links() }}</div>
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

    // Marquer comme lu
    function initMarkReadButtons() {
        var buttons = document.querySelectorAll('.mark-read-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleMarkReadClick);
            buttons[i].addEventListener('click', handleMarkReadClick);
        }
    }

    function handleMarkReadClick() {
        var id = this.dataset.id;
        markAsRead(id);
    }

    function markAsRead(id) {
        fetch('/admin/contacts/' + id + '/read', {
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
            showToast('Message marqué comme lu', 'success');
            setTimeout(function() { location.reload(); }, 500);
        })
        .catch(function(error) {
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Marquer comme non lu
    function initMarkUnreadButtons() {
        var buttons = document.querySelectorAll('.mark-unread-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleMarkUnreadClick);
            buttons[i].addEventListener('click', handleMarkUnreadClick);
        }
    }

    function handleMarkUnreadClick() {
        var id = this.dataset.id;
        markAsUnread(id);
    }

    function markAsUnread(id) {
        fetch('/admin/contacts/' + id + '/unread', {
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
            showToast('Message marqué comme non lu', 'success');
            setTimeout(function() { location.reload(); }, 500);
        })
        .catch(function(error) {
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Suppression
    function initDeleteButtons() {
        var buttons = document.querySelectorAll('.delete-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].removeEventListener('click', handleDeleteClick);
            buttons[i].addEventListener('click', handleDeleteClick);
        }
    }

    function handleDeleteClick() {
        var id = this.dataset.id;
        var name = this.dataset.name;
        openModal(
            'Supprimer le message',
            'Êtes-vous sûr de vouloir supprimer le message de "' + name + '" ?',
            'Action irréversible. Le message sera définitivement supprimé.',
            'Supprimer',
            'btn-danger',
            function() { deleteContact(id); }
        );
    }

    function deleteContact(id) {
        fetch('/admin/contacts/' + id, {
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
            showToast('Message supprimé avec succès', 'success');
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
        var readCount = 0;
        var unreadCount = 0;

        var rows = document.querySelectorAll('#contacts-list tr:not(#emptyStateRow)');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var show = true;
            var name = row.dataset.name || '';
            var email = row.dataset.email || '';
            var subject = row.dataset.subject || '';
            var message = row.dataset.message || '';
            var status = row.dataset.status || '';

            if (searchTerm && name.indexOf(searchTerm) === -1 && email.indexOf(searchTerm) === -1 &&
                subject.indexOf(searchTerm) === -1 && message.indexOf(searchTerm) === -1) {
                show = false;
            }

            if (show && statusValue && status !== statusValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
                if (status === 'read') readCount++;
                else unreadCount++;
            }
        }

        document.getElementById('statTotal').textContent = visibleCount;
        document.getElementById('statRead').textContent = readCount;
        document.getElementById('statUnread').textContent = unreadCount;
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
        initMarkReadButtons();
        initMarkUnreadButtons();
        initDeleteButtons();
    });

    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
