{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Notifications - NovaTech Admin')
@section('page-title', 'Notifications')

@push('styles')
<style>
    /* ============================================
       NOTIFICATIONS PAGE STYLES
    ============================================ */

    .notifications-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Header Actions */
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .notifications-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 500;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .stat-badge.unread {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .btn-mark-all {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-mark-all:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: var(--brand-primary);
    }

    /* Filters */
    .filters-bar {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 0.75rem 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.75rem;
        outline: none;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--brand-primary);
    }

    /* Notifications List */
    .notifications-list {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: var(--bg-hover);
    }

    .notification-item.unread {
        background: var(--bg-selected);
    }

    .notification-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notification-icon.intervention { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .notification-icon.contact { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .notification-icon.expense { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .notification-icon.rating { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .notification-icon.system { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .notification-message {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        font-size: 0.65rem;
        color: var(--text-tertiary);
    }

    .notification-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.125rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .notification-badge.unread {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .notification-badge.read {
        background: var(--bg-tertiary);
        color: var(--text-tertiary);
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .mark-read-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }

    .mark-read-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    /* Empty State */
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

    .empty-state p {
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .notifications-header {
            flex-direction: column;
            align-items: stretch;
        }

        .notification-item {
            flex-direction: column;
        }

        .notification-icon {
            width: 2rem;
            height: 2rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')

@php
    $unreadCount = $notifications->where('is_read', false)->count();
    $totalCount = $notifications->total();
@endphp

<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <div class="notifications-stats">
            <span class="stat-badge">Total: {{ $totalCount }}</span>
            @if($unreadCount > 0)
            <span class="stat-badge unread">Non lues: {{ $unreadCount }}</span>
            @endif
        </div>
        @if($unreadCount > 0)
        <button onclick="markAllAsRead()" class="btn-mark-all">
            <i class="fas fa-check-double"></i>
            Tout marquer comme lu
        </button>
        @endif
    </div>

    <!-- Filters -->
    <div class="filters-bar">
        <select id="typeFilter" class="filter-select" onchange="filterNotifications()">
            <option value="">Tous les types</option>
            <option value="intervention">Interventions</option>
            <option value="contact">Messages</option>
            <option value="expense">Dépenses</option>
            <option value="rating">Évaluations</option>
            <option value="system">Système</option>
        </select>

        <select id="statusFilter" class="filter-select" onchange="filterNotifications()">
            <option value="">Tous les statuts</option>
            <option value="unread">Non lues</option>
            <option value="read">Lues</option>
        </select>

        <div style="flex: 1;"></div>

        <span class="stat-badge" style="cursor: pointer;" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Rafraîchir
        </span>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list" id="notificationsList">
        @forelse($notifications as $notification)
        <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}"
             data-id="{{ $notification->id }}"
             data-type="{{ $notification->type }}"
             data-read="{{ $notification->is_read ? 'read' : 'unread' }}">

            <div class="notification-icon {{ $notification->type }}">
                @switch($notification->type)
                    @case('intervention')
                        <i class="fas fa-tools"></i>
                        @break
                    @case('contact')
                        <i class="fas fa-envelope"></i>
                        @break
                    @case('expense')
                        <i class="fas fa-receipt"></i>
                        @break
                    @case('rating')
                        <i class="fas fa-star"></i>
                        @break
                    @default
                        <i class="fas fa-bell"></i>
                @endswitch
            </div>

            <div class="notification-content">
                <div class="notification-title">{{ $notification->title }}</div>
                <div class="notification-message">{{ $notification->message }}</div>
                <div class="notification-meta">
                    <span class="notification-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $notification->created_at->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm') }}
                    </span>
                    <span class="notification-date">
                        <i class="fas fa-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                    <span class="notification-badge {{ !$notification->is_read ? 'unread' : 'read' }}">
                        <i class="fas {{ !$notification->is_read ? 'fa-circle' : 'fa-check-circle' }}"></i>
                        {{ !$notification->is_read ? 'Non lue' : 'Lue' }}
                    </span>
                </div>
            </div>

            <div class="notification-actions">
                @if(!$notification->is_read)
                <button onclick="markAsRead({{ $notification->id }}, event)" class="mark-read-btn" title="Marquer comme lu">
                    <i class="fas fa-check"></i>
                </button>
                @endif
                @if($notification->url)
                <a href="{{ $notification->url }}" class="mark-read-btn" title="Voir le détail">
                    <i class="fas fa-arrow-right"></i>
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>Aucune notification pour le moment</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="pagination-wrapper">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Marquer une notification comme lue
    function markAsRead(notificationId, event) {
        if (event) event.stopPropagation();

        fetch('{{ url("admin/notifications") }}/' + notificationId + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'apparence de la notification
                const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                    notificationItem.setAttribute('data-read', 'read');

                    // Mettre à jour le badge
                    const badge = notificationItem.querySelector('.notification-badge');
                    if (badge) {
                        badge.classList.remove('unread');
                        badge.classList.add('read');
                        badge.innerHTML = '<i class="fas fa-check-circle"></i> Lue';
                    }

                    // Cacher le bouton "marquer comme lu"
                    const markBtn = notificationItem.querySelector('.mark-read-btn:first-child');
                    if (markBtn && markBtn.innerHTML.includes('fa-check')) {
                        markBtn.style.display = 'none';
                    }
                }

                // Mettre à jour le compteur
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Marquer toutes les notifications comme lues
    function markAllAsRead() {
        fetch('{{ route("admin.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Mettre à jour le compteur de notifications non lues
    function updateUnreadCount() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const headerStats = document.querySelector('.notifications-stats');
        const markAllBtn = document.querySelector('.btn-mark-all');

        if (headerStats) {
            const totalSpan = headerStats.querySelector('.stat-badge:first-child');
            const unreadSpan = headerStats.querySelector('.stat-badge.unread');

            if (unreadCount === 0) {
                if (unreadSpan) unreadSpan.remove();
                if (markAllBtn) markAllBtn.style.display = 'none';
            } else {
                if (unreadSpan) {
                    unreadSpan.innerHTML = `Non lues: ${unreadCount}`;
                } else {
                    const newUnreadSpan = document.createElement('span');
                    newUnreadSpan.className = 'stat-badge unread';
                    newUnreadSpan.innerHTML = `Non lues: ${unreadCount}`;
                    headerStats.appendChild(newUnreadSpan);
                }
                if (markAllBtn) markAllBtn.style.display = 'inline-flex';
            }
        }

        // Mettre à jour le badge dans le header principal
        const headerNotificationDot = document.querySelector('.notification-dot');
        if (headerNotificationDot) {
            if (unreadCount > 0) {
                headerNotificationDot.textContent = unreadCount > 9 ? '9+' : unreadCount;
                headerNotificationDot.style.display = 'flex';
            } else {
                headerNotificationDot.style.display = 'none';
            }
        }
    }

    // Filtrer les notifications
    function filterNotifications() {
        const typeFilter = document.getElementById('typeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        const notifications = document.querySelectorAll('.notification-item');
        let visibleCount = 0;

        notifications.forEach(notification => {
            let show = true;

            // Filtre par type
            if (typeFilter && notification.getAttribute('data-type') !== typeFilter) {
                show = false;
            }

            // Filtre par statut
            if (statusFilter && notification.getAttribute('data-read') !== statusFilter) {
                show = false;
            }

            notification.style.display = show ? 'flex' : 'none';
            if (show) visibleCount++;
        });

        // Afficher un message si aucun résultat
        const noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && notifications.length > 0) {
            if (!noResultsMsg) {
                const container = document.getElementById('notificationsList');
                const msg = document.createElement('div');
                msg.id = 'noResultsMsg';
                msg.className = 'empty-state';
                msg.innerHTML = `
                    <i class="fas fa-filter"></i>
                    <p>Aucune notification ne correspond à vos critères</p>
                    <button onclick="resetFilters()" class="btn-mark-all" style="margin-top: 0.5rem;">
                        <i class="fas fa-undo-alt"></i> Réinitialiser les filtres
                    </button>
                `;
                container.appendChild(msg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // Réinitialiser les filtres
    function resetFilters() {
        document.getElementById('typeFilter').value = '';
        document.getElementById('statusFilter').value = '';
        filterNotifications();
    }

    // Rendre le filtre accessible sur window
    window.filterNotifications = filterNotifications;
    window.resetFilters = resetFilters;
    window.markAsRead = markAsRead;
    window.markAllAsRead = markAllAsRead;
</script>
@endpush
