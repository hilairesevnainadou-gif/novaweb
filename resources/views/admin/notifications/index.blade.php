@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@push('styles')
<style>
    /* ── Wrapper ── */
    .notif-wrap {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ── Stats bar ── */
    .notif-stats {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .notif-stat {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 160px;
    }

    .notif-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .notif-stat-icon.total   { background: rgba(59,130,246,0.12); color: var(--brand-primary); }
    .notif-stat-icon.unread  { background: rgba(239,68,68,0.12);  color: var(--brand-error); }
    .notif-stat-icon.read    { background: rgba(16,185,129,0.12); color: var(--brand-success); }

    .notif-stat-val {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .notif-stat-lbl {
        font-size: 11px;
        color: var(--text-tertiary);
        margin-top: 3px;
    }

    /* ── Card ── */
    .notif-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    /* ── Toolbar ── */
    .notif-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        gap: 12px;
        flex-wrap: wrap;
        background: var(--bg-tertiary);
    }

    .notif-toolbar-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notif-count-badge {
        font-size: 10px;
        font-weight: 700;
        background: var(--brand-error);
        color: #fff;
        border-radius: 999px;
        padding: 2px 8px;
    }

    .notif-toolbar-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: var(--radius-md);
        border: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }

    .btn-ghost:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: var(--brand-primary);
    }

    .btn-danger-ghost {
        background: transparent;
        color: var(--brand-error);
        border: 1px solid rgba(239,68,68,0.3);
    }

    .btn-danger-ghost:hover {
        background: rgba(239,68,68,0.08);
    }

    /* ── Filter tabs ── */
    .notif-filters {
        display: flex;
        gap: 4px;
        padding: 10px 20px;
        border-bottom: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .filter-tab {
        padding: 5px 14px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: transparent;
        color: var(--text-tertiary);
        transition: all var(--transition-fast);
        font-family: inherit;
        white-space: nowrap;
    }

    .filter-tab.active,
    .filter-tab:hover {
        background: var(--bg-selected);
        color: var(--brand-primary);
    }

    /* ── Notification item ── */
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        transition: background var(--transition-fast), opacity 0.25s ease;
        position: relative;
    }

    .notif-item:last-child { border-bottom: none; }

    .notif-item.unread {
        background: var(--bg-selected);
        border-left: 3px solid var(--brand-primary);
    }

    .notif-item:hover { background: var(--bg-hover); }

    .notif-item.removing {
        opacity: 0;
        pointer-events: none;
    }

    .notif-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .notif-icon.type-contact      { background: rgba(59,130,246,0.12); color: var(--brand-primary); }
    .notif-icon.type-intervention { background: rgba(245,158,11,0.12); color: var(--brand-warning); }
    .notif-icon.type-default      { background: rgba(139,92,246,0.12); color: var(--brand-secondary); }

    .notif-body { flex: 1; min-width: 0; }

    .notif-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 3px;
    }

    .notif-message {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 5px;
        line-height: 1.55;
    }

    .notif-meta {
        font-size: 11px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .notif-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--brand-primary);
        flex-shrink: 0;
        margin-top: 6px;
    }

    .notif-actions {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex-shrink: 0;
        opacity: 0;
        transition: opacity var(--transition-fast);
    }

    .notif-item:hover .notif-actions { opacity: 1; }

    .notif-action-btn {
        width: 28px;
        height: 28px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        border: none;
        background: var(--bg-tertiary);
        color: var(--text-tertiary);
        transition: all var(--transition-fast);
    }

    .notif-action-btn.read-btn:hover   { background: rgba(16,185,129,0.12); color: var(--brand-success); }
    .notif-action-btn.delete-btn:hover { background: rgba(239,68,68,0.12);  color: var(--brand-error); }

    /* ── Empty state ── */
    .notif-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 56px 24px;
        gap: 12px;
        color: var(--text-tertiary);
    }

    .notif-empty i { font-size: 38px; opacity: 0.35; }
    .notif-empty p { font-size: 14px; }

    /* ── Pagination ── */
    .notif-pagination {
        padding: 14px 20px;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        background: var(--bg-tertiary);
    }

    .notif-pagination-info {
        font-size: 12px;
        color: var(--text-tertiary);
    }

    /* Restyle des liens de pagination Laravel */
    .notif-pagination nav { display: inline-flex; }

    .notif-pagination .pagination {
        display: flex;
        gap: 3px;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    .notif-pagination .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        padding: 0 8px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--border-medium);
        background: var(--bg-secondary);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all var(--transition-fast);
        cursor: pointer;
    }

    .notif-pagination .page-item.active .page-link {
        background: var(--brand-primary);
        border-color: var(--brand-primary);
        color: #fff;
    }

    .notif-pagination .page-item .page-link:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .notif-pagination .page-item.disabled .page-link {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    @media (max-width: 640px) {
        .notif-stat { min-width: 120px; }
        .notif-item { padding: 12px 14px; gap: 10px; }
    }
</style>
@endpush

@section('content')

@php
    $totalCount  = $notifications->total();
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
    $readCount   = $totalCount - $unreadCount;
@endphp

<div class="notif-wrap">

    {{-- ── Stats ── --}}
    <div class="notif-stats">
        <div class="notif-stat">
            <div class="notif-stat-icon total"><i class="fas fa-bell"></i></div>
            <div>
                <div class="notif-stat-val">{{ $totalCount }}</div>
                <div class="notif-stat-lbl">Total</div>
            </div>
        </div>
        <div class="notif-stat">
            <div class="notif-stat-icon unread"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="notif-stat-val">{{ $unreadCount }}</div>
                <div class="notif-stat-lbl">Non lues</div>
            </div>
        </div>
        <div class="notif-stat">
            <div class="notif-stat-icon read"><i class="fas fa-check-double"></i></div>
            <div>
                <div class="notif-stat-val">{{ $readCount }}</div>
                <div class="notif-stat-lbl">Lues</div>
            </div>
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="notif-card">

        {{-- Toolbar --}}
        <div class="notif-toolbar">
            <span class="notif-toolbar-title">
                Toutes les notifications
                @if($unreadCount > 0)
                    <span class="notif-count-badge" id="unreadBadge">
                        {{ $unreadCount }} non lue{{ $unreadCount > 1 ? 's' : '' }}
                    </span>
                @endif
            </span>
            <div class="notif-toolbar-actions">
                @if($unreadCount > 0)
                <button class="btn-sm btn-ghost" id="markAllReadBtn">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
                @endif
                @if($readCount > 0)
                <button class="btn-sm btn-danger-ghost" id="deleteAllReadBtn">
                    <i class="fas fa-trash"></i> Supprimer les lues
                </button>
                @endif
            </div>
        </div>

        {{-- Filtres --}}
        <div class="notif-filters">
            <button class="filter-tab active" data-filter="all">Toutes</button>
            <button class="filter-tab" data-filter="unread">Non lues</button>
            <button class="filter-tab" data-filter="read">Lues</button>
            <button class="filter-tab" data-filter="contact_message">
                <i class="fas fa-envelope" style="font-size:11px;"></i> Messages
            </button>
            <button class="filter-tab" data-filter="intervention">
                <i class="fas fa-tools" style="font-size:11px;"></i> Interventions
            </button>
        </div>

        {{-- Liste --}}
        <div id="notifList">
            @forelse($notifications as $notification)
            <div class="notif-item {{ !$notification->is_read ? 'unread' : '' }}"
                 id="notif-{{ $notification->id }}"
                 data-id="{{ $notification->id }}"
                 data-type="{{ $notification->type }}"
                 data-read="{{ $notification->is_read ? '1' : '0' }}">

                <div class="notif-icon type-{{ $notification->type === 'contact_message' ? 'contact' : ($notification->type === 'intervention' ? 'intervention' : 'default') }}">
                    <i class="fas {{ $notification->type === 'contact_message' ? 'fa-envelope' : ($notification->type === 'intervention' ? 'fa-tools' : 'fa-bell') }}"></i>
                </div>

                <div class="notif-body">
                    <div class="notif-title">{{ $notification->title }}</div>
                    <div class="notif-message">{{ $notification->message }}</div>
                    <div class="notif-meta">
                        <i class="fas fa-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                        @if($notification->url)
                            &bull;
                            <a href="{{ $notification->url }}" style="color:var(--brand-primary); font-weight:600;">
                                Voir le détail <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
                            </a>
                        @endif
                        @if($notification->is_read && $notification->read_at)
                            &bull; Lu {{ $notification->read_at->diffForHumans() }}
                        @endif
                    </div>
                </div>

                @if(!$notification->is_read)
                <div class="notif-dot" id="dot-{{ $notification->id }}"></div>
                @endif

                <div class="notif-actions">
                    @if(!$notification->is_read)
                    <button class="notif-action-btn read-btn"
                            title="Marquer comme lu"
                            onclick="markRead({{ $notification->id }})">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                    <button class="notif-action-btn delete-btn"
                            title="Supprimer"
                            onclick="deleteNotif({{ $notification->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="notif-empty" id="emptyState">
                <i class="fas fa-bell-slash"></i>
                <p>Aucune notification pour le moment</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="notif-pagination">
            <span class="notif-pagination-info">
                Affichage de <strong>{{ $notifications->firstItem() }}</strong> à <strong>{{ $notifications->lastItem() }}</strong>
                sur <strong>{{ $notifications->total() }}</strong> notification{{ $notifications->total() > 1 ? 's' : '' }}
            </span>
            {{ $notifications->onEachSide(1)->links() }}
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ─── Retirer un item avec animation ─── */
function removeItem(id) {
    const el = document.getElementById('notif-' + id);
    if (!el) return;
    el.classList.add('removing');
    setTimeout(() => { el.remove(); checkEmpty(); }, 260);
}

/* ─── Vérifier si la liste est vide ─── */
function checkEmpty() {
    const list    = document.getElementById('notifList');
    const visible = [...list.querySelectorAll('.notif-item')].filter(el => el.style.display !== 'none');
    document.getElementById('emptyStateFilter')?.remove();
    if (visible.length === 0 && !document.getElementById('emptyState')) {
        list.innerHTML = `
            <div class="notif-empty" id="emptyState">
                <i class="fas fa-bell-slash"></i>
                <p>Aucune notification pour le moment</p>
            </div>`;
    }
    // Mettre à jour le badge non-lues
    const dots = list.querySelectorAll('.notif-dot');
    if (dots.length === 0) {
        document.getElementById('unreadBadge')?.remove();
    }
}

/* ─── Marquer comme lu ─── */
function markRead(id) {
    fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        const el = document.getElementById('notif-' + id);
        if (!el) return;
        el.classList.remove('unread');
        el.style.borderLeft = '';
        el.dataset.read = '1';
        document.getElementById('dot-' + id)?.remove();
        el.querySelector('.read-btn')?.remove();
        checkEmpty();
    })
    .catch(console.error);
}

/* ─── Supprimer ─── */
function deleteNotif(id) {
    fetch(`/admin/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { if (data.success) removeItem(id); })
    .catch(console.error);
}

/* ─── Tout marquer comme lu ─── */
document.getElementById('markAllReadBtn')?.addEventListener('click', function () {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        document.querySelectorAll('.notif-item.unread').forEach(el => {
            el.classList.remove('unread');
            el.style.borderLeft = '';
            el.dataset.read = '1';
            el.querySelector('.notif-dot')?.remove();
            el.querySelector('.read-btn')?.remove();
        });
        this.remove();
        checkEmpty();
    })
    .catch(console.error);
});

/* ─── Supprimer toutes les lues ─── */
document.getElementById('deleteAllReadBtn')?.addEventListener('click', function () {
    const readItems = [...document.querySelectorAll('.notif-item[data-read="1"]')];
    if (readItems.length === 0) return;
    const ids = readItems.map(el => el.dataset.id);
    Promise.all(ids.map(id =>
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).then(r => r.json())
    )).then(results => {
        results.forEach((data, i) => { if (data.success) removeItem(ids[i]); });
    }).catch(console.error);
});

/* ─── Filtres client-side ─── */
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        let anyVisible = false;

        document.querySelectorAll('.notif-item').forEach(item => {
            let show = true;
            if      (filter === 'unread') show = item.dataset.read === '0';
            else if (filter === 'read')   show = item.dataset.read === '1';
            else if (filter !== 'all')    show = item.dataset.type === filter;
            item.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });

        document.getElementById('emptyStateFilter')?.remove();
        if (!anyVisible && !document.getElementById('emptyState')) {
            document.getElementById('notifList').insertAdjacentHTML('beforeend', `
                <div class="notif-empty" id="emptyStateFilter">
                    <i class="fas fa-filter"></i>
                    <p>Aucune notification dans cette catégorie</p>
                </div>`);
        }
    });
});
</script>
@endpush
