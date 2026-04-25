{{-- resources/views/admin/meetings/global-index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Réunions · NovaTech Admin')
@section('page-title', 'Réunions')

@push('styles')
<style>
    /* ─── Page header ─── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .page-header h1 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 .2rem;
    }
    .page-header p { font-size: .75rem; color: var(--text-tertiary); margin: 0; }

    /* ─── Stats row ─── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: .85rem;
        margin-bottom: 1.25rem;
    }
    .stat-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: .9rem 1rem;
        display: flex;
        align-items: center;
        gap: .8rem;
        box-shadow: var(--shadow-sm);
    }
    .stat-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
    }
    .si-indigo  { background: rgba(99,102,241,.12); color: #818cf8; }
    .si-green   { background: rgba(34,197,94,.12);  color: #4ade80; }
    .si-amber   { background: rgba(245,158,11,.12); color: #fbbf24; }
    .si-purple  { background: rgba(139,92,246,.12); color: #a78bfa; }
    .si-red     { background: rgba(244,63,94,.12);  color: #fb7185; }
    .stat-val   { font-size: 1.35rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .stat-lbl   { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-tertiary); margin-top: .2rem; }

    /* ─── Filter bar ─── */
    .filters-bar {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
    }
    .filters-grid {
        display: grid;
        grid-template-columns: minmax(200px, 2fr) repeat(auto-fill, minmax(148px, 1fr));
        gap: .65rem;
        margin-bottom: .75rem;
    }
    .filter-group { display: flex; flex-direction: column; gap: .28rem; }
    .filter-label {
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-tertiary);
        padding-left: .15rem;
    }
    .fi {
        width: 100%;
        padding: .45rem .75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: .78rem;
        font-family: inherit;
        min-height: 34px;
        transition: border-color .18s, box-shadow .18s;
        outline: none;
        box-sizing: border-box;
    }
    .fi:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .fi::placeholder { color: var(--text-disabled); font-size: .75rem; }
    select.fi { cursor: pointer; }

    .filters-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .65rem;
        padding-top: .75rem;
        border-top: 1px dashed var(--border-light);
        flex-wrap: wrap;
    }
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        align-items: center;
        min-height: 24px;
    }
    .filter-tag {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .18rem .6rem;
        border-radius: 999px;
        background: rgba(99,102,241,.1);
        color: #818cf8;
        font-size: .68rem; font-weight: 600;
        border: 1px solid rgba(99,102,241,.2);
    }
    .filters-btns { display: flex; gap: .5rem; flex-shrink: 0; }

    .btn-filter {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .44rem 1rem;
        border-radius: 8px;
        background: var(--brand-primary);
        color: #fff;
        font-size: .75rem; font-weight: 700;
        border: none; cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
        font-family: inherit;
    }
    .btn-filter:hover { background: var(--brand-primary-hover); }

    .btn-reset {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .44rem .85rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-size: .75rem; font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }
    .btn-reset:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-heavy); }

    @media (max-width: 768px) {
        .filters-grid { grid-template-columns: 1fr 1fr; }
        .filters-footer { flex-direction: column; align-items: flex-start; }
        .filters-btns { width: 100%; justify-content: flex-end; }
    }
    @media (max-width: 480px) {
        .filters-grid { grid-template-columns: 1fr; }
    }

    /* ─── Toolbar ─── */
    .list-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .85rem;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .list-count { font-size: .78rem; color: var(--text-tertiary); }
    .list-count strong { color: var(--text-primary); }

    .view-toggle { display: flex; gap: .35rem; }
    .view-btn {
        display: flex; align-items: center; justify-content: center;
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all .18s;
        font-size: .82rem;
    }
    .view-btn:hover { color: var(--brand-primary); border-color: rgba(99,102,241,.35); }
    .view-btn.active { background: var(--brand-primary); color: #fff; border-color: var(--brand-primary); }

    /* ─── Badges statut ─── */
    .badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .2rem .6rem;
        border-radius: 999px;
        font-size: .63rem; font-weight: 700;
        white-space: nowrap;
    }
    .badge-scheduled   { background: rgba(34,197,94,.14);   color: #4ade80;  border: 1px solid rgba(34,197,94,.28); }
    .badge-in_progress { background: rgba(99,102,241,.14);  color: #818cf8;  border: 1px solid rgba(99,102,241,.28); }
    .badge-completed   { background: rgba(100,116,139,.18); color: #94a3b8;  border: 1px solid rgba(100,116,139,.28); }
    .badge-cancelled   { background: rgba(244,63,94,.14);   color: #fb7185;  border: 1px solid rgba(244,63,94,.28); }

    /* ─── Badge rôle personnel ─── */
    .role-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .62rem; font-weight: 700;
        border-radius: 999px;
        padding: .18rem .55rem;
        white-space: nowrap;
    }
    .role-organizer { background: rgba(249,115,22,.12); color: #f97316; }
    .role-manager   { background: rgba(34,197,94,.12);  color: #22c55e; }
    .role-attendee  { background: rgba(99,102,241,.12); color: #818cf8; }

    /* ─── Table (vue liste) ─── */
    .table-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .table-overflow { overflow-x: auto; }
    .meetings-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    .meetings-table th {
        padding: .7rem 1rem;
        text-align: left;
        font-size: .62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-tertiary);
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }
    .meetings-table td {
        padding: .8rem 1rem;
        border-bottom: 1px dashed var(--border-light);
        font-size: .82rem;
        vertical-align: middle;
        color: var(--text-primary);
    }
    .meetings-table tbody tr:last-child td { border-bottom: none; }
    .meetings-table tbody tr:hover td { background: var(--bg-hover); }
    .t-title  { font-weight: 600; color: var(--text-primary); margin-bottom: .12rem; }
    .t-sub    { font-size: .68rem; color: var(--text-tertiary); }
    .t-today  { background: rgba(245,158,11,.06); }
    .t-today td { border-bottom-color: rgba(245,158,11,.12) !important; }

    /* Participant avatars */
    .avatars { display: inline-flex; align-items: center; }
    .av {
        width: 24px; height: 24px;
        border-radius: 50%;
        background: rgba(99,102,241,.2);
        border: 2px solid var(--bg-secondary);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .6rem; font-weight: 700; color: #818cf8;
        margin-left: -5px;
        flex-shrink: 0;
    }
    .avatars .av:first-child { margin-left: 0; }
    .av-more {
        display: inline-flex; align-items: center; justify-content: center;
        height: 20px; padding: 0 .4rem;
        border-radius: 999px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-medium);
        font-size: .6rem; font-weight: 600; color: var(--text-tertiary);
        margin-left: 4px;
    }

    /* ─── Action buttons ─── */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px;
        border-radius: 7px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: .72rem;
        cursor: pointer;
        transition: all .18s;
    }
    .action-btn:hover       { background: var(--bg-hover); color: var(--brand-primary); border-color: rgba(99,102,241,.35); }
    .action-btn.ab-del:hover { background: rgba(244,63,94,.08); color: #fb7185; border-color: rgba(244,63,94,.35); }

    /* ─── Vue cartes ─── */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .meeting-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        display: flex; flex-direction: column;
        transition: transform .2s, box-shadow .2s;
        box-shadow: var(--shadow-sm);
        position: relative;
    }
    .meeting-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    /* Barre colorée gauche selon statut */
    .meeting-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        border-radius: 14px 0 0 14px;
    }
    .meeting-card.s-scheduled::before   { background: #4ade80; }
    .meeting-card.s-in_progress::before { background: #818cf8; }
    .meeting-card.s-completed::before   { background: #94a3b8; }
    .meeting-card.s-cancelled::before   { background: #fb7185; }

    .card-top {
        padding: 1rem 1rem .6rem 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .6rem;
    }
    .card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); margin-bottom: .25rem; line-height: 1.3; }
    .card-project { font-size: .7rem; color: var(--brand-primary); display: flex; align-items: center; gap: .3rem; }
    .card-roles { padding: 0 1rem .6rem 1.1rem; display: flex; gap: .35rem; flex-wrap: wrap; }
    .card-meta {
        padding: .6rem 1rem;
        border-top: 1px dashed var(--border-light);
        display: flex; flex-direction: column; gap: .4rem;
        flex: 1;
    }
    .card-meta-row { display: flex; align-items: center; gap: .45rem; font-size: .73rem; color: var(--text-secondary); }
    .card-meta-row i { width: 14px; text-align: center; color: var(--text-tertiary); font-size: .7rem; flex-shrink: 0; }
    .card-meta-row a { color: var(--brand-secondary); text-decoration: none; }
    .card-meta-row a:hover { text-decoration: underline; }
    .card-footer {
        padding: .65rem 1rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,.015);
    }

    /* ─── Bouton primaire ─── */
    .btn-primary {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .52rem 1rem;
        border-radius: 8px;
        font-size: .78rem; font-weight: 700;
        text-decoration: none;
        background: var(--brand-primary);
        color: #fff;
        border: none; cursor: pointer;
        transition: all .18s;
        box-shadow: 0 2px 8px rgba(99,102,241,.3);
    }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); color: #fff; }

    /* ─── Empty state ─── */
    .empty-state {
        text-align: center; padding: 3.5rem 1.5rem;
        color: var(--text-tertiary);
    }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: .85rem; opacity: .4; }
    .empty-state p { margin: 0 0 .3rem; font-size: .85rem; }

    /* ─── Pagination ─── */
    .pagination-wrapper {
        padding: .85rem 1rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
    }
    .cards-pagination { margin-top: 1rem; display: flex; justify-content: flex-end; }

    /* ─── Modal (base) ─── */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0; visibility: hidden;
        transition: opacity .22s, visibility .22s;
        padding: 1rem;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 16px;
        width: 100%; max-width: 420px;
        transform: translateY(12px) scale(.97);
        transition: transform .25s cubic-bezier(.22,1,.36,1);
        box-shadow: 0 24px 64px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.05);
        overflow: hidden;
    }
    .modal-overlay.active .modal-box { transform: translateY(0) scale(1); }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.1rem .9rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .modal-header h3 {
        font-size: .9rem; font-weight: 700; margin: 0;
        color: var(--text-primary);
        display: flex; align-items: center; gap: .5rem;
    }
    .modal-header h3 .mh-icon {
        width: 28px; height: 28px; border-radius: 8px;
        background: rgba(99,102,241,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; color: #818cf8;
    }
    .modal-close {
        width: 28px; height: 28px; border-radius: 7px;
        background: var(--bg-tertiary); border: 1px solid var(--border-light);
        color: var(--text-tertiary); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; transition: all .15s;
        flex-shrink: 0;
    }
    .modal-close:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-medium); }

    .modal-body { font-size: .82rem; color: var(--text-secondary); line-height: 1.6; }
    .modal-warn { display: flex; align-items: center; gap: .5rem; margin-top: .75rem; font-size: .75rem; color: #fbbf24; }
    .modal-footer {
        display: flex; justify-content: flex-end; gap: .6rem;
        padding: .85rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .btn { display: inline-flex; align-items: center; gap: .35rem; padding: .46rem .9rem; border-radius: 8px; font-size: .78rem; font-weight: 700; cursor: pointer; border: none; transition: all .18s; font-family: inherit; }
    .btn-ghost  { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-medium); }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }
    .btn-danger { background: #f43f5e; color: #fff; }
    .btn-danger:hover { background: #e11d48; }

    /* ─── Modal projet : recherche ─── */
    .mp-search-wrap {
        padding: .85rem 1rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .mp-search-field {
        display: flex; align-items: center; gap: .5rem;
        padding: .5rem .75rem;
        background: var(--bg-tertiary);
        border: 1.5px solid var(--border-medium);
        border-radius: 9px;
        transition: border-color .18s, box-shadow .18s;
    }
    .mp-search-field:focus-within {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,.14);
        background: var(--bg-elevated);
    }
    .mp-search-icon {
        font-size: .72rem; color: var(--text-tertiary); flex-shrink: 0;
        transition: color .18s;
    }
    .mp-search-field:focus-within .mp-search-icon { color: var(--brand-primary); }
    .mp-search-input {
        flex: 1; background: transparent; border: none; outline: none;
        color: var(--text-primary); font-size: .82rem; font-family: inherit;
        min-width: 0; line-height: 1.4;
    }
    .mp-search-input::placeholder { color: var(--text-disabled); font-size: .78rem; }
    .mp-search-clear {
        width: 18px; height: 18px; border-radius: 50%;
        background: var(--border-medium); border: none;
        color: var(--text-tertiary); cursor: pointer;
        display: none; align-items: center; justify-content: center;
        font-size: .58rem; flex-shrink: 0;
        transition: all .15s;
    }
    .mp-search-clear.visible { display: flex; }
    .mp-search-clear:hover { background: rgba(244,63,94,.18); color: #fb7185; }

    /* ─── Modal projet : liste ─── */
    .mp-list { max-height: 300px; overflow-y: auto; overscroll-behavior: contain; }
    .mp-item {
        display: flex; align-items: center; gap: .75rem;
        padding: .72rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border-light);
        transition: background .12s, padding-left .15s;
        position: relative;
    }
    .mp-item:last-child { border-bottom: none; }
    .mp-item:hover, .mp-item.focused { background: var(--bg-hover); padding-left: 1.2rem; }
    .mp-item.focused { outline: none; }
    .mp-dot {
        width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
        box-shadow: 0 0 0 2px rgba(255,255,255,.08);
    }
    .mp-item-body { flex: 1; min-width: 0; }
    .mp-item-name {
        font-size: .82rem; font-weight: 600; color: var(--text-primary);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        line-height: 1.3;
    }
    .mp-item-sub {
        font-size: .67rem; color: var(--text-tertiary); margin-top: .08rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .mp-status-pill {
        padding: .16rem .5rem; border-radius: 6px;
        font-size: .6rem; font-weight: 700; flex-shrink: 0;
        white-space: nowrap;
    }
    .mp-chevron { font-size: .62rem; color: var(--text-disabled); flex-shrink: 0; transition: color .12s, transform .12s; }
    .mp-item:hover .mp-chevron, .mp-item.focused .mp-chevron { color: var(--brand-primary); transform: translateX(2px); }

    .mp-empty {
        padding: 2.5rem 1.5rem; text-align: center; color: var(--text-tertiary);
    }
    .mp-empty i { font-size: 1.8rem; display: block; margin-bottom: .65rem; opacity: .3; }
    .mp-empty p { margin: 0; font-size: .78rem; }

    /* ─── Modal projet : footer ─── */
    .mp-footer {
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
        padding: .7rem 1rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .mp-footer-hint {
        font-size: .67rem; color: var(--text-tertiary);
        display: flex; align-items: center; gap: .3rem;
    }
    .mp-kbd {
        display: inline-flex; align-items: center; justify-content: center;
        padding: .05rem .35rem; border-radius: 4px;
        background: var(--bg-tertiary); border: 1px solid var(--border-medium);
        font-size: .58rem; font-weight: 700; color: var(--text-secondary);
        font-family: monospace;
    }

    /* ─── Responsive général ─── */
    @media (max-width: 1100px) { .stats-row { grid-template-columns: repeat(3, 1fr); } .cards-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 540px)  { .stats-row { grid-template-columns: 1fr 1fr; } .cards-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
@php
    $userId = auth()->id();

    $roleLabel = function ($meeting) use ($userId): ?array {
        if ($meeting->organizer_id == $userId) {
            return ['key' => 'organizer', 'label' => 'Organisateur', 'icon' => 'fa-star', 'class' => 'role-organizer'];
        }
        if ($meeting->project?->project_manager_id == $userId) {
            return ['key' => 'manager',   'label' => 'Chef de projet', 'icon' => 'fa-crown',   'class' => 'role-manager'];
        }
        if (in_array($userId, $meeting->attendees ?? [])) {
            return ['key' => 'attendee',  'label' => 'Participant',    'icon' => 'fa-user',    'class' => 'role-attendee'];
        }
        return null;
    };
@endphp

@can('meetings.view')

{{-- ─── Page header ─── --}}
<div class="page-header">
    <div>
        <h1>
            <i class="fas fa-calendar-alt" style="color:var(--brand-primary);margin-right:.4rem;font-size:.95rem"></i>
            {{ $canViewAll ? 'Toutes les réunions' : 'Mes réunions' }}
        </h1>
        <p>
            @if($canViewAll)
                Vue d'ensemble de toutes les réunions de l'entreprise
            @else
                Les réunions que vous organisez ou auxquelles vous participez
            @endif
        </p>
    </div>
    @can('meetings.create')
    <button id="btn-new-meeting" class="btn-primary" type="button">
        <i class="fas fa-plus"></i> Planifier une réunion
    </button>
    @endcan
</div>

@can('meetings.create')
{{-- ─── Modal : choisir un projet ─── --}}
<div id="modal-project" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="mp-title">
    <div class="modal-box" style="max-width:500px;">

        {{-- En-tête --}}
        <div class="modal-header">
            <h3 id="mp-title">
                <span class="mh-icon"><i class="fas fa-folder-open"></i></span>
                Choisir un projet
            </h3>
            <button type="button" class="modal-close" id="modal-close-btn" aria-label="Fermer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        {{-- Barre de recherche --}}
        <div class="mp-search-wrap">
            <div class="mp-search-field">
                <i class="fas fa-magnifying-glass mp-search-icon"></i>
                <input type="text" id="modal-search" class="mp-search-input"
                       placeholder="Rechercher un projet…" autocomplete="off" role="searchbox">
                <button type="button" id="mp-search-clear" class="mp-search-clear" aria-label="Effacer">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Liste des projets --}}
        <div class="modal-body" style="padding:0;">
            <div id="modal-project-list" class="mp-list">
                @forelse($projects as $proj)
                @php
                    $dotColor = match($proj->status) {
                        'planning'    => '#94a3b8',
                        'in_progress' => '#60a5fa',
                        'review'      => '#fbbf24',
                        'completed'   => '#34d399',
                        default       => '#fb7185'
                    };
                    $pillBg = match($proj->status) {
                        'planning'    => 'rgba(148,163,184,.15)',
                        'in_progress' => 'rgba(96,165,250,.15)',
                        'review'      => 'rgba(251,191,36,.15)',
                        'completed'   => 'rgba(52,211,153,.15)',
                        default       => 'rgba(251,113,133,.15)'
                    };
                @endphp
                <div class="mp-item" tabindex="0" role="option"
                     data-name="{{ $proj->name }}"
                     data-url="{{ route('admin.projects.meetings.create', $proj) }}">
                    <span class="mp-dot" style="background:{{ $dotColor }};"></span>
                    <div class="mp-item-body">
                        <div class="mp-item-name">{{ $proj->name }}</div>
                        <div class="mp-item-sub">
                            {{ $proj->project_number }}
                            @if($proj->client)
                                &nbsp;·&nbsp;{{ $proj->client->company_name ?? $proj->client->name }}
                            @endif
                        </div>
                    </div>
                    <span class="mp-status-pill" style="background:{{ $pillBg }};color:{{ $dotColor }};">
                        {{ $proj->status_label }}
                    </span>
                    <i class="fas fa-chevron-right mp-chevron"></i>
                </div>
                @empty
                <div class="mp-empty">
                    <i class="fas fa-folder-open"></i>
                    <p style="font-weight:600;margin-bottom:.25rem">Aucun projet disponible</p>
                    <p>Vous n'êtes membre d'aucun projet actif.</p>
                </div>
                @endforelse
            </div>

            <div id="modal-no-result" class="mp-empty" style="display:none;">
                <i class="fas fa-magnifying-glass"></i>
                <p style="font-weight:600;margin-bottom:.25rem">Aucun résultat</p>
                <p>Aucun projet ne correspond à votre recherche.</p>
            </div>
        </div>

        {{-- Pied --}}
        <div class="mp-footer">
            <div class="mp-footer-hint">
                <span class="mp-kbd">↑↓</span> naviguer
                &nbsp;·&nbsp;
                <span class="mp-kbd">↵</span> ouvrir
                &nbsp;·&nbsp;
                <span class="mp-kbd">Esc</span> fermer
            </div>
            <button type="button" class="btn btn-ghost" id="modal-cancel-btn">Annuler</button>
        </div>

    </div>
</div>
@endcan

{{-- ─── Stats ─── --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon si-indigo"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ $stats['upcoming'] }}</div>
            <div class="stat-lbl">À venir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-amber"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-val">{{ $stats['today'] }}</div>
            <div class="stat-lbl">Aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-val">{{ $stats['completed'] }}</div>
            <div class="stat-lbl">Terminées</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-ban"></i></div>
        <div>
            <div class="stat-val">{{ $stats['cancelled'] }}</div>
            <div class="stat-lbl">Annulées</div>
        </div>
    </div>
</div>

{{-- ─── Filtres ─── --}}
@php
    $activeFilters = array_filter([
        'search'     => request('search'),
        'status'     => request('status'),
        'period'     => request('period'),
        'project_id' => request('project_id'),
        'my_role'    => request('my_role'),
    ]);
    $filterLabels = [
        'search'     => 'Recherche : "' . request('search') . '"',
        'status'     => match(request('status')) {
            'scheduled'   => 'Statut : Planifiée',
            'in_progress' => 'Statut : En cours',
            'completed'   => 'Statut : Terminée',
            'cancelled'   => 'Statut : Annulée',
            default       => ''
        },
        'period'     => match(request('period')) {
            'today'    => 'Période : Aujourd\'hui',
            'week'     => 'Période : Cette semaine',
            'upcoming' => 'Période : À venir',
            'past'     => 'Période : Passées',
            default    => ''
        },
        'project_id' => 'Projet : ' . ($projects->find(request('project_id'))?->name ?? ''),
        'my_role'    => match(request('my_role')) {
            'organizer' => 'Rôle : Organisateur',
            'attendee'  => 'Rôle : Participant',
            default     => ''
        },
    ];
@endphp

<form method="GET" action="{{ route('admin.meetings.global-index') }}" class="filters-bar" id="filterForm">

    {{-- Ligne d'inputs ─── --}}
    <div class="filters-grid">

        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-magnifying-glass" style="margin-right:.2rem"></i>Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="fi" placeholder="Titre, lieu…" autocomplete="off">
        </div>

        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-circle-dot" style="margin-right:.2rem"></i>Statut</label>
            <select name="status" class="fi">
                <option value="">Tous</option>
                <option value="scheduled"   {{ request('status') === 'scheduled'   ? 'selected' : '' }}>Planifiée</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Terminée</option>
                <option value="cancelled"   {{ request('status') === 'cancelled'   ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label"><i class="far fa-calendar" style="margin-right:.2rem"></i>Période</label>
            <select name="period" class="fi">
                <option value="">Toutes</option>
                <option value="today"    {{ request('period') === 'today'    ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week"     {{ request('period') === 'week'     ? 'selected' : '' }}>Cette semaine</option>
                <option value="upcoming" {{ request('period') === 'upcoming' ? 'selected' : '' }}>À venir</option>
                <option value="past"     {{ request('period') === 'past'     ? 'selected' : '' }}>Passées</option>
            </select>
        </div>

        @if($projects->count())
        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-folder" style="margin-right:.2rem"></i>Projet</label>
            <select name="project_id" class="fi">
                <option value="">Tous les projets</option>
                @foreach($projects as $proj)
                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                    {{ $proj->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif

        @if($canViewAll)
        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-user-tag" style="margin-right:.2rem"></i>Mon implication</label>
            <select name="my_role" class="fi">
                <option value="">Tous</option>
                <option value="organizer" {{ request('my_role') === 'organizer' ? 'selected' : '' }}>Organisateur</option>
                <option value="attendee"  {{ request('my_role') === 'attendee'  ? 'selected' : '' }}>Participant</option>
            </select>
        </div>
        @endif

    </div>

    {{-- Pied : tags actifs + boutons ─── --}}
    <div class="filters-footer">
        <div class="filter-tags">
            @if(count($activeFilters))
                <span style="font-size:.62rem;color:var(--text-tertiary);margin-right:.2rem">Filtres actifs :</span>
                @foreach($activeFilters as $key => $val)
                    @if(!empty($filterLabels[$key]))
                    @php
                        $removeParams = $activeFilters;
                        unset($removeParams[$key]);
                        $removeUrl = route('admin.meetings.global-index') . ($removeParams ? ('?' . http_build_query($removeParams)) : '');
                    @endphp
                    <span class="filter-tag">
                        {{ $filterLabels[$key] }}
                        <a href="{{ $removeUrl }}" title="Retirer" style="color:inherit;opacity:.6;text-decoration:none;line-height:1;">
                            <i class="fas fa-xmark" style="font-size:.55rem"></i>
                        </a>
                    </span>
                    @endif
                @endforeach
            @else
                <span style="font-size:.7rem;color:var(--text-disabled)">Aucun filtre actif</span>
            @endif
        </div>
        <div class="filters-btns">
            @if(count($activeFilters))
            <a href="{{ route('admin.meetings.global-index') }}" class="btn-reset">
                <i class="fas fa-rotate-left"></i> Effacer
            </a>
            @endif
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Appliquer
            </button>
        </div>
    </div>

</form>

{{-- ─── Toolbar : compteur + toggle vue ─── --}}
<div class="list-toolbar">
    <span class="list-count">
        <strong>{{ $meetings->total() }}</strong>
        réunion(s){{ !$canViewAll ? ' vous concernant' : '' }}
        @if(request()->hasAny(['search','status','period','project_id','my_role']))
            <span style="color:var(--brand-warning);font-size:.7rem;margin-left:.4rem">
                <i class="fas fa-filter" style="font-size:.6rem"></i> filtres actifs
            </span>
        @endif
    </span>
    <div class="view-toggle">
        <button id="btn-list" class="view-btn" title="Vue liste"><i class="fas fa-list"></i></button>
        <button id="btn-card" class="view-btn" title="Vue cartes"><i class="fas fa-table-cells-large"></i></button>
    </div>
</div>

{{-- ════════════════════════════════════════
     VUE LISTE
════════════════════════════════════════ --}}
<div id="view-list">
    <div class="table-card">
        <div class="table-overflow">
            <table class="meetings-table">
                <thead>
                    <tr>
                        <th>Réunion</th>
                        @if($canViewAll)<th>Projet</th>@endif
                        <th>Date &amp; Heure</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>{{ $canViewAll ? 'Organisateur' : 'Mon rôle' }}</th>
                        <th>Participants</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                    @php
                        $role    = $roleLabel($meeting);
                        $isToday = $meeting->meeting_date?->isToday();
                        $attendees = $meeting->attendees ?? [];
                        $attendeesList = $meeting->attendees_list;
                        $shown   = $attendeesList->take(3);
                        $rest    = $attendeesList->count() - $shown->count();
                    @endphp
                    <tr class="{{ $isToday ? 't-today' : '' }}">

                        <td>
                            <div class="t-title">
                                @if($isToday)
                                    <span style="color:var(--brand-warning);font-size:.6rem;margin-right:.3rem"><i class="fas fa-circle"></i></span>
                                @endif
                                {{ $meeting->title }}
                            </div>
                            @if(! $canViewAll && $meeting->project)
                                <div class="t-sub"><i class="fas fa-folder" style="font-size:.6rem;margin-right:2px"></i>{{ $meeting->project->name }}</div>
                            @endif
                            @if($meeting->location)
                                <div class="t-sub"><i class="fas fa-map-marker-alt" style="font-size:.6rem;margin-right:2px"></i>{{ $meeting->location }}</div>
                            @endif
                        </td>

                        @if($canViewAll)
                        <td>
                            <span style="font-size:.78rem;color:var(--text-secondary)">{{ $meeting->project?->name ?? '—' }}</span>
                        </td>
                        @endif

                        <td>
                            @if($meeting->meeting_date)
                                <div style="font-weight:600">{{ $meeting->meeting_date->format('d/m/Y') }}</div>
                                <div class="t-sub">{{ $meeting->meeting_date->format('H:i') }}</div>
                            @else
                                <span style="color:var(--text-disabled)">—</span>
                            @endif
                        </td>

                        <td>
                            <span style="font-size:.78rem;color:var(--text-secondary)">{{ $meeting->formatted_duration }}</span>
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
                            @if($canViewAll)
                                <div style="display:flex;align-items:center;gap:.4rem">
                                    <span style="width:24px;height:24px;border-radius:50%;background:rgba(99,102,241,.15);display:inline-flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:700;color:#818cf8;flex-shrink:0">
                                        {{ strtoupper(substr($meeting->organizer?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span style="font-size:.78rem">{{ $meeting->organizer?->name ?? '—' }}</span>
                                </div>
                            @else
                                @if($role)
                                    <span class="role-badge {{ $role['class'] }}">
                                        <i class="fas {{ $role['icon'] }}" style="font-size:.58rem"></i>
                                        {{ $role['label'] }}
                                    </span>
                                @else
                                    <span style="color:var(--text-disabled);font-size:.75rem">—</span>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($attendeesList->count())
                                <div class="avatars">
                                    @foreach($shown as $att)
                                        <span class="av" title="{{ $att->name }}">{{ strtoupper(substr($att->name, 0, 1)) }}</span>
                                    @endforeach
                                    @if($rest > 0)
                                        <span class="av-more">+{{ $rest }}</span>
                                    @endif
                                </div>
                            @else
                                <span style="font-size:.72rem;color:var(--text-disabled)">Aucun</span>
                            @endif
                        </td>

                        <td>
                            <div style="display:flex;justify-content:flex-end;gap:.35rem">
                                <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('meetings.edit')
                                @if($canViewAll || $meeting->organizer_id == $userId)
                                <a href="{{ route('admin.meetings.edit', $meeting) }}" class="action-btn" title="Modifier">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                @endif
                                @endcan
                                @can('meetings.delete')
                                @if($canViewAll || $meeting->organizer_id == $userId)
                                <button type="button" class="action-btn ab-del"
                                        data-id="{{ $meeting->id }}"
                                        data-title="{{ $meeting->title }}"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $canViewAll ? 8 : 7 }}">
                            <div class="empty-state">
                                <i class="fas fa-calendar-alt"></i>
                                <p style="font-weight:600">Aucune réunion trouvée</p>
                                <p style="font-size:.78rem">
                                    @if(request()->hasAny(['search','status','period','project_id','my_role']))
                                        Aucune réunion ne correspond à vos critères de recherche.
                                    @elseif($canViewAll)
                                        Aucune réunion n'a encore été planifiée.
                                    @else
                                        Vous n'avez aucune réunion pour le moment.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($meetings->hasPages())
        <div class="pagination-wrapper">{{ $meetings->links() }}</div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════
     VUE CARTES
════════════════════════════════════════ --}}
<div id="view-card" style="display:none">
    @if($meetings->count())
    <div class="cards-grid">
        @foreach($meetings as $meeting)
        @php
            $role      = $roleLabel($meeting);
            $isToday   = $meeting->meeting_date?->isToday();
            $attendees = $meeting->attendees ?? [];
        @endphp
        <div class="meeting-card s-{{ $meeting->status }}">

            {{-- Entête carte --}}
            <div class="card-top">
                <div style="flex:1;min-width:0">
                    <div class="card-title">
                        @if($isToday)
                            <span style="color:var(--brand-warning);font-size:.58rem;margin-right:.3rem"><i class="fas fa-circle"></i></span>
                        @endif
                        {{ $meeting->title }}
                    </div>
                    @if($meeting->project)
                    <div class="card-project">
                        <i class="fas fa-folder" style="font-size:.6rem"></i>
                        {{ $meeting->project->name }}
                    </div>
                    @endif
                </div>
                <span class="badge badge-{{ $meeting->status }}" style="flex-shrink:0">
                    {{ $meeting->status_label }}
                </span>
            </div>

            {{-- Rôles --}}
            @if($role || ($canViewAll && $meeting->organizer_id == $userId))
            <div class="card-roles">
                @if($role)
                    <span class="role-badge {{ $role['class'] }}">
                        <i class="fas {{ $role['icon'] }}" style="font-size:.58rem"></i>
                        {{ $role['label'] }}
                    </span>
                @endif
            </div>
            @endif

            {{-- Méta --}}
            <div class="card-meta">
                <div class="card-meta-row">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $meeting->meeting_date?->format('d/m/Y à H:i') ?? '—' }}</span>
                </div>
                <div class="card-meta-row">
                    <i class="far fa-clock"></i>
                    <span>{{ $meeting->formatted_duration }}</span>
                </div>
                @if($canViewAll)
                <div class="card-meta-row">
                    <i class="fas fa-user"></i>
                    <span>{{ $meeting->organizer?->name ?? '—' }}</span>
                </div>
                @endif
                @if($meeting->location)
                <div class="card-meta-row">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $meeting->location }}</span>
                </div>
                @endif
                @if($meeting->meeting_link)
                <div class="card-meta-row">
                    <i class="fas fa-video"></i>
                    <a href="{{ $meeting->meeting_link }}" target="_blank">Rejoindre la réunion</a>
                </div>
                @endif
                @if(count($attendees))
                <div class="card-meta-row">
                    <i class="fas fa-users"></i>
                    <span>{{ count($attendees) }} participant(s)</span>
                </div>
                @endif
            </div>

            {{-- Footer actions --}}
            <div class="card-footer">
                <span style="font-size:.68rem;color:var(--text-tertiary)">
                    @if($meeting->meeting_date?->isFuture() && $meeting->status === 'scheduled')
                        <i class="fas fa-hourglass-half" style="font-size:.6rem;margin-right:2px;color:var(--brand-warning)"></i>
                        Dans {{ $meeting->meeting_date->diffForHumans() }}
                    @elseif($isToday)
                        <i class="fas fa-circle" style="font-size:.55rem;margin-right:2px;color:var(--brand-warning)"></i>
                        Aujourd'hui
                    @else
                        {{ $meeting->meeting_date?->diffForHumans() ?? '' }}
                    @endif
                </span>
                <div style="display:flex;gap:.35rem">
                    <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>
                    @can('meetings.edit')
                    @if($canViewAll || $meeting->organizer_id == $userId)
                    <a href="{{ route('admin.meetings.edit', $meeting) }}" class="action-btn" title="Modifier">
                        <i class="fas fa-pencil"></i>
                    </a>
                    @endif
                    @endcan
                    @can('meetings.delete')
                    @if($canViewAll || $meeting->organizer_id == $userId)
                    <button type="button" class="action-btn ab-del"
                            data-id="{{ $meeting->id }}"
                            data-title="{{ $meeting->title }}"
                            title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($meetings->hasPages())
    <div class="cards-pagination">{{ $meetings->links() }}</div>
    @endif

    @else
    <div class="empty-state" style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:14px">
        <i class="fas fa-calendar-alt"></i>
        <p style="font-weight:600">Aucune réunion trouvée</p>
        <p style="font-size:.78rem">
            @if(request()->hasAny(['search','status','period','project_id','my_role']))
                Aucune réunion ne correspond à vos critères.
            @else
                Aucune réunion pour le moment.
            @endif
        </p>
    </div>
    @endif
</div>

{{-- ─── Modal suppression ─── --}}
<div id="del-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-trash" style="color:#fb7185;margin-right:.4rem"></i>Supprimer la réunion</h3>
            <button class="modal-close" onclick="closeDelModal()"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <span id="del-msg"></span>
            <div class="modal-warn"><i class="fas fa-triangle-exclamation"></i> Cette action est irréversible.</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeDelModal()">Annuler</button>
            <button id="del-confirm" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

@endcan

@cannot('meetings.view')
<div class="empty-state" style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:14px">
    <i class="fas fa-lock"></i>
    <p style="font-weight:600">Accès refusé</p>
    <p style="font-size:.78rem">Vous n'avez pas la permission de voir les réunions.</p>
</div>
@endcannot

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Toggle vue liste / cartes ── */
    const viewList = document.getElementById('view-list');
    const viewCard = document.getElementById('view-card');
    const btnList  = document.getElementById('btn-list');
    const btnCard  = document.getElementById('btn-card');

    function setView(mode) {
        const isList = mode === 'list';
        if (viewList) viewList.style.display = isList ? '' : 'none';
        if (viewCard) viewCard.style.display = isList ? 'none' : '';
        btnList?.classList.toggle('active', isList);
        btnCard?.classList.toggle('active', !isList);
        try { localStorage.setItem('meetings_view', mode); } catch(e) {}
    }

    const savedView = (() => { try { return localStorage.getItem('meetings_view') || 'list'; } catch(e) { return 'list'; } })();
    setView(savedView);

    btnList?.addEventListener('click', () => setView('list'));
    btnCard?.addEventListener('click', () => setView('card'));

    /* ── Modal suppression ── */
    const modal     = document.getElementById('del-modal');
    const delMsg    = document.getElementById('del-msg');
    const delBtn    = document.getElementById('del-confirm');
    let   deleteId  = null;

    function openDelModal(id, title) {
        deleteId = id;
        if (delMsg) delMsg.textContent = `Voulez-vous vraiment supprimer la réunion « ${title} » ?`;
        modal?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    window.closeDelModal = function () {
        modal?.classList.remove('active');
        document.body.style.overflow = '';
        deleteId = null;
    };

    modal?.addEventListener('click', e => { if (e.target === modal) window.closeDelModal(); });

    document.querySelectorAll('.ab-del').forEach(btn => {
        btn.addEventListener('click', () => openDelModal(btn.dataset.id, btn.dataset.title));
    });

    delBtn?.addEventListener('click', function () {
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
            window.closeDelModal();
        });
    });

    /* ── Modal sélection projet ── */
    const projectModal  = document.getElementById('modal-project');
    const btnNewMeeting = document.getElementById('btn-new-meeting');
    const modalSearch   = document.getElementById('modal-search');
    const searchClear   = document.getElementById('mp-search-clear');
    const projItems     = Array.from(document.querySelectorAll('.mp-item'));
    const noResult      = document.getElementById('modal-no-result');
    let focusedIdx      = -1;

    function openProjectModal() {
        if (!projectModal) return;
        projectModal.classList.add('active');
        projectModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        focusedIdx = -1;
        if (modalSearch) {
            modalSearch.value = '';
            filterProjects('');
            if (searchClear) searchClear.classList.remove('visible');
            setTimeout(() => modalSearch.focus(), 80);
        }
    }

    function closeProjectModal() {
        if (!projectModal) return;
        projectModal.classList.remove('active');
        projectModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        focusedIdx = -1;
        projItems.forEach(i => i.classList.remove('focused'));
    }

    function getVisibleItems() {
        return projItems.filter(i => i.style.display !== 'none');
    }

    function setFocus(idx) {
        const visible = getVisibleItems();
        projItems.forEach(i => i.classList.remove('focused'));
        if (idx < 0 || idx >= visible.length) { focusedIdx = -1; return; }
        focusedIdx = idx;
        visible[idx].classList.add('focused');
        visible[idx].scrollIntoView({ block: 'nearest' });
    }

    function filterProjects(q) {
        const lq = q.toLowerCase().trim();
        let visible = 0;
        projItems.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const matches = !lq || name.includes(lq);
            item.style.display = matches ? '' : 'none';
            item.classList.remove('focused');
            if (matches) visible++;
        });
        focusedIdx = -1;
        if (noResult) noResult.style.display = (visible === 0 && projItems.length > 0) ? '' : 'none';
    }

    modalSearch?.addEventListener('input', () => {
        const val = modalSearch.value;
        filterProjects(val);
        if (searchClear) searchClear.classList.toggle('visible', val.length > 0);
    });

    searchClear?.addEventListener('click', () => {
        modalSearch.value = '';
        filterProjects('');
        searchClear.classList.remove('visible');
        modalSearch.focus();
    });

    modalSearch?.addEventListener('keydown', e => {
        const visible = getVisibleItems();
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setFocus(Math.min(focusedIdx + 1, visible.length - 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setFocus(Math.max(focusedIdx - 1, 0));
        } else if (e.key === 'Enter' && focusedIdx >= 0) {
            e.preventDefault();
            window.location.href = visible[focusedIdx].dataset.url;
        }
    });

    projItems.forEach((item, i) => {
        item.addEventListener('click', () => { window.location.href = item.dataset.url; });
        item.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); window.location.href = item.dataset.url; }
        });
    });

    btnNewMeeting?.addEventListener('click', openProjectModal);
    document.getElementById('modal-close-btn')?.addEventListener('click', closeProjectModal);
    document.getElementById('modal-cancel-btn')?.addEventListener('click', closeProjectModal);
    projectModal?.addEventListener('click', e => { if (e.target === projectModal) closeProjectModal(); });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeProjectModal(); window.closeDelModal(); }
    });

})();
</script>
@endpush
