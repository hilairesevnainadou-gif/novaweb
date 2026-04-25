{{-- resources/views/admin/meetings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Réunions · NovaTech Admin')
@section('page-title', 'Réunions')

@push('styles')
<style>
    /* ── Page header ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .page-header h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.15rem;
    }

    .page-header p {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin: 0;
    }

    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
        margin-bottom: 1.1rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 0.95rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.12);
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .icon-blue   { background: rgba(59,130,246,.12);  color: #60a5fa; }
    .icon-green  { background: rgba(16,185,129,.12);  color: #34d399; }
    .icon-amber  { background: rgba(245,158,11,.12);  color: #fbbf24; }
    .icon-purple { background: rgba(139,92,246,.12);  color: #a78bfa; }

    .stat-info {}
    .stat-value { font-size: 1.35rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .stat-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-tertiary); margin-top: .2rem; }

    /* ── Filters ── */
    .filters-bar {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        align-items: center;
    }

    .fi {
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: 0.78rem;
        font-family: inherit;
        min-height: 34px;
        transition: border-color .18s, box-shadow .18s;
        outline: none;
        flex: 1;
        min-width: 150px;
    }
    .fi:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi::placeholder { color: var(--text-disabled); font-size: 0.75rem; }
    select.fi { cursor: pointer; }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }
    .btn-reset:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-heavy); }

    /* ── Table card ── */
    .table-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,.02);
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .table-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .table-card-count {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    /* ── Table ── */
    .meetings-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .meetings-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .meetings-table td {
        padding: 0.82rem 1rem;
        border-bottom: 1px dashed var(--border-light);
        color: var(--text-primary);
        font-size: 0.82rem;
        vertical-align: middle;
    }

    .meetings-table tbody tr:last-child td { border-bottom: none; }
    .meetings-table tbody tr:hover td { background: var(--bg-hover); }

    .meeting-title { font-weight: 600; color: var(--text-primary); margin-bottom: .15rem; }
    .meeting-sub   { font-size: 0.68rem; color: var(--text-tertiary); line-height: 1.3; }

    /* ── Badges ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-scheduled  { background: rgba(16,185,129,.15);  color: #34d399;  border: 1px solid rgba(16,185,129,.3); }
    .badge-in_progress{ background: rgba(59,130,246,.15);  color: #60a5fa;  border: 1px solid rgba(59,130,246,.3); }
    .badge-completed  { background: rgba(100,116,139,.18); color: #94a3b8;  border: 1px solid rgba(100,116,139,.3); }
    .badge-cancelled  { background: rgba(239,68,68,.15);   color: #f87171;  border: 1px solid rgba(239,68,68,.3); }

    /* ── Participant avatars ── */
    .avatars {
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .avatar-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(59,130,246,.2);
        border: 2px solid var(--bg-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.62rem;
        font-weight: 700;
        color: #60a5fa;
        margin-left: -4px;
    }

    .avatars .avatar-circle:first-child { margin-left: 0; }

    .avatar-more {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.45rem;
        height: 22px;
        border-radius: 999px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-medium);
        font-size: 0.62rem;
        font-weight: 600;
        color: var(--text-tertiary);
        margin-left: 4px;
    }

    /* ── Action buttons ── */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.72rem;
        cursor: pointer;
        transition: all .18s;
    }

    .action-btn:hover           { background: var(--bg-hover); color: var(--brand-primary); border-color: rgba(59,130,246,.35); }
    .action-btn.action-delete:hover { background: rgba(239,68,68,.08); color: #f87171; border-color: rgba(239,68,68,.35); }

    /* ── New meeting button ── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.52rem 1rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        background: var(--brand-primary);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: all .18s;
        box-shadow: 0 2px 8px rgba(59,130,246,.3);
    }

    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); color: #fff; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-tertiary);
    }
    .empty-state i { font-size: 2.2rem; display: block; margin-bottom: 0.75rem; opacity: .5; }
    .empty-state p { margin: 0 0 0.25rem; font-size: 0.85rem; }

    /* ── Overflow for horizontal scroll ── */
    .table-overflow { overflow-x: auto; }

    /* ── Pagination ── */
    .pagination-wrapper {
        padding: 0.85rem 1rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
    }

    /* ── Modal ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all .25s;
    }

    .modal-overlay.active { opacity: 1; visibility: visible; }

    .modal-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 14px;
        width: 90%;
        max-width: 420px;
        transform: scale(.96);
        transition: transform .25s;
        box-shadow: 0 24px 50px rgba(0,0,0,.3);
    }

    .modal-overlay.active .modal-box { transform: scale(1); }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
    }

    .modal-header h3 { font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--text-primary); }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        font-size: 1rem;
        padding: 0.25rem;
        transition: color .15s;
    }

    .modal-close:hover { color: var(--text-primary); }

    .modal-body {
        padding: 1.25rem;
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .modal-warning {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.75rem;
        color: #fbbf24;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
        padding: 0.9rem 1.25rem;
        border-top: 1px solid var(--border-light);
    }

    .btn { display: inline-flex; align-items: center; gap: .35rem; padding: .46rem .9rem; border-radius: 8px; font-size: .78rem; font-weight: 700; cursor: pointer; border: none; transition: all .18s; }
    .btn-ghost-modal { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-medium); }
    .btn-ghost-modal:hover { background: var(--bg-hover); color: var(--text-primary); }
    .btn-danger { background: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 540px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: .6rem; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

@can('meetings.view')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1><i class="fas fa-calendar-alt" style="color:var(--brand-primary);margin-right:.4rem;font-size:.95rem"></i>Réunions
            @if(isset($project))
                <span style="font-weight:400;color:var(--text-tertiary);font-size:.85rem">— {{ $project->name }}</span>
            @endif
        </h1>
        <p>{{ $meetings->total() }} réunion(s) au total</p>
    </div>
    @can('meetings.create')
    <a href="{{ isset($project) ? route('admin.projects.meetings.create', $project) : '#' }}" class="btn-primary">
        <i class="fas fa-plus"></i> Planifier une réunion
    </a>
    @endcan
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $meetings->total() }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['upcoming'] ?? 0 }}</div>
            <div class="stat-label">À venir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-amber"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['today'] ?? 0 }}</div>
            <div class="stat-label">Aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
            <div class="stat-label">Terminées</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="filters-bar">
    <input type="text" id="f-search" class="fi" placeholder="&#xf002;  Rechercher une réunion…" autocomplete="off">
    <select id="f-status" class="fi" style="max-width:170px">
        <option value="">Tous statuts</option>
        <option value="scheduled">Planifiée</option>
        <option value="in_progress">En cours</option>
        <option value="completed">Terminée</option>
        <option value="cancelled">Annulée</option>
    </select>
    <select id="f-period" class="fi" style="max-width:160px">
        <option value="">Toutes périodes</option>
        <option value="today">Aujourd'hui</option>
        <option value="upcoming">À venir</option>
        <option value="past">Passées</option>
    </select>
    <button onclick="resetFilters()" class="btn-reset">
        <i class="fas fa-rotate-left"></i> Réinitialiser
    </button>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="fas fa-list" style="margin-right:.35rem;opacity:.6"></i>Liste des réunions</span>
        <span class="table-card-count" id="visible-count">{{ $meetings->count() }} affichée(s)</span>
    </div>

    <div class="table-overflow">
        <table class="meetings-table">
            <thead>
                <tr>
                    <th>Réunion</th>
                    <th>Organisateur</th>
                    <th>Participants</th>
                    <th>Date &amp; Heure</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="meetings-tbody">
                @forelse($meetings as $meeting)
                <tr data-title="{{ strtolower($meeting->title) }}"
                    data-status="{{ $meeting->status }}"
                    data-date="{{ $meeting->meeting_date?->format('Y-m-d') ?? '' }}">

                    <td>
                        <div class="meeting-title">{{ $meeting->title }}</div>
                        @if($meeting->description)
                            <div class="meeting-sub">{{ Str::limit($meeting->description, 55) }}</div>
                        @endif
                        @if($meeting->location)
                            <div class="meeting-sub"><i class="fas fa-map-marker-alt" style="font-size:.6rem;margin-right:2px"></i>{{ $meeting->location }}</div>
                        @endif
                    </td>

                    <td>
                        <div style="display:flex;align-items:center;gap:.45rem">
                            <span style="width:26px;height:26px;border-radius:50%;background:rgba(59,130,246,.15);display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#60a5fa;flex-shrink:0">
                                {{ strtoupper(substr($meeting->organizer->name ?? '?', 0, 1)) }}
                            </span>
                            <span style="font-size:.8rem">{{ $meeting->organizer->name ?? '—' }}</span>
                        </div>
                    </td>

                    <td>
                        @php
                            $attendees   = $meeting->attendees_list ?? collect();
                            $shown       = $attendees->take(3);
                            $remaining   = $attendees->count() - $shown->count();
                        @endphp
                        @if($attendees->count())
                            <div class="avatars">
                                @foreach($shown as $att)
                                    <span class="avatar-circle" title="{{ $att->name }}">{{ strtoupper(substr($att->name, 0, 1)) }}</span>
                                @endforeach
                                @if($remaining > 0)
                                    <span class="avatar-more">+{{ $remaining }}</span>
                                @endif
                            </div>
                        @else
                            <span style="font-size:.74rem;color:var(--text-disabled)">Aucun</span>
                        @endif
                    </td>

                    <td>
                        @if($meeting->meeting_date)
                            <div style="font-weight:600">{{ $meeting->meeting_date->format('d/m/Y') }}</div>
                            <div class="meeting-sub">{{ $meeting->meeting_date->format('H:i') }}</div>
                        @else
                            <span style="color:var(--text-disabled)">—</span>
                        @endif
                    </td>

                    <td>
                        <span style="font-size:.78rem;color:var(--text-secondary)">
                            {{ $meeting->formatted_duration ?? ($meeting->duration_minutes . ' min') }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $meeting->status }}">
                            <i class="fas
                                @switch($meeting->status)
                                    @case('scheduled')   fa-calendar-check @break
                                    @case('in_progress') fa-circle-dot     @break
                                    @case('completed')   fa-check-circle   @break
                                    @case('cancelled')   fa-ban            @break
                                    @default             fa-circle
                                @endswitch
                            " style="font-size:.55rem"></i>
                            {{ $meeting->status_label }}
                        </span>
                    </td>

                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:.35rem">
                            <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('meetings.edit')
                            <a href="{{ route('admin.meetings.edit', $meeting) }}" class="action-btn" title="Modifier">
                                <i class="fas fa-pencil"></i>
                            </a>
                            @endcan
                            @can('meetings.delete')
                            <button type="button" class="action-btn action-delete"
                                    data-id="{{ $meeting->id }}"
                                    data-title="{{ $meeting->title }}"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="row-empty">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p style="font-weight:600">Aucune réunion planifiée</p>
                            <p style="font-size:.78rem">Commencez par planifier votre première réunion</p>
                            @can('meetings.create')
                            @if(isset($project))
                            <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-primary" style="margin-top:.85rem">
                                <i class="fas fa-plus"></i> Planifier une réunion
                            </a>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse

                <tr id="row-no-results" style="display:none">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-magnifying-glass"></i>
                            <p style="font-weight:600">Aucun résultat</p>
                            <p style="font-size:.78rem">Aucune réunion ne correspond à vos critères</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($meetings->hasPages())
    <div class="pagination-wrapper">
        {{ $meetings->links() }}
    </div>
    @endif
</div>

{{-- Delete modal --}}
<div id="delete-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-trash" style="color:#f87171;margin-right:.4rem"></i>Supprimer la réunion</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <span id="modal-msg"></span>
            <div class="modal-warning"><i class="fas fa-triangle-exclamation"></i> Cette action est irréversible.</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost-modal" onclick="closeModal()">Annuler</button>
            <button id="modal-confirm" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

@endcan

@cannot('meetings.view')
<div class="empty-state" style="padding:3rem">
    <i class="fas fa-lock"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot

@endsection

@push('scripts')
<script>
(function () {
    /* ── Filters ── */
    const fSearch = document.getElementById('f-search');
    const fStatus = document.getElementById('f-status');
    const fPeriod = document.getElementById('f-period');
    const rows    = Array.from(document.querySelectorAll('#meetings-tbody tr[data-status]'));
    const rowNone = document.getElementById('row-no-results');
    const countEl = document.getElementById('visible-count');
    const today   = new Date().toISOString().slice(0, 10);

    function filter() {
        const q  = fSearch?.value.toLowerCase().trim() ?? '';
        const st = fStatus?.value ?? '';
        const pd = fPeriod?.value ?? '';
        let visible = 0;

        rows.forEach(row => {
            const title  = row.dataset.title  ?? '';
            const status = row.dataset.status ?? '';
            const date   = row.dataset.date   ?? '';

            let show = true;
            if (q  && !title.includes(q))     show = false;
            if (st && status !== st)           show = false;
            if (pd === 'today'    && date !== today)  show = false;
            if (pd === 'upcoming' && date <= today)   show = false;
            if (pd === 'past'     && date >= today)   show = false;

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (rowNone) rowNone.style.display = (rows.length > 0 && visible === 0) ? '' : 'none';
        if (countEl) countEl.textContent = visible + ' affichée(s)';
    }

    window.resetFilters = function () {
        if (fSearch) fSearch.value = '';
        if (fStatus) fStatus.value = '';
        if (fPeriod) fPeriod.value = '';
        filter();
    };

    let timer;
    fSearch?.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(filter, 250); });
    fStatus?.addEventListener('change', filter);
    fPeriod?.addEventListener('change', filter);

    /* ── Delete modal ── */
    const modal      = document.getElementById('delete-modal');
    const modalMsg   = document.getElementById('modal-msg');
    const modalBtn   = document.getElementById('modal-confirm');
    let deleteId     = null;

    function openModal(id, title) {
        deleteId = id;
        modalMsg.textContent = `Voulez-vous vraiment supprimer la réunion « ${title} » ?`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    window.closeModal = function () {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        deleteId = null;
    };

    modal.addEventListener('click', e => { if (e.target === modal) window.closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') window.closeModal(); });

    document.querySelectorAll('.action-delete').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn.dataset.id, btn.dataset.title));
    });

    modalBtn?.addEventListener('click', function () {
        if (!deleteId) return;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression…';

        fetch(`{{ url('admin/meetings') }}/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(() => location.reload())
        .catch(() => {
            alert('Une erreur est survenue.');
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash"></i> Supprimer';
            window.closeModal();
        });
    });
})();
</script>
@endpush
