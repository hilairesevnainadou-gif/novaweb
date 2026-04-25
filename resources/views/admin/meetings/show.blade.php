{{-- resources/views/admin/meetings/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $meeting->title . ' · NovaTech Admin')
@section('page-title', $meeting->title)

@push('styles')
<style>
    /* ── Layout ── */
    .show-layout {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Page header ── */
    .show-page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.4rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-medium);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all .18s;
        flex-shrink: 0;
    }

    .back-btn:hover { background: var(--bg-hover); color: var(--brand-primary); border-color: rgba(59,130,246,.3); }

    .header-meta { flex: 1; min-width: 0; }

    .header-meta h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
        margin: 0;
    }

    .header-sub {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.2rem;
        font-size: 0.72rem;
        color: var(--text-tertiary);
        flex-wrap: wrap;
    }

    /* ── Status badges ── */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.18rem 0.55rem;
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .status-scheduled  { background: rgba(16,185,129,.15);  color: #34d399;  border: 1px solid rgba(16,185,129,.3); }
    .status-in_progress{ background: rgba(59,130,246,.15);  color: #60a5fa;  border: 1px solid rgba(59,130,246,.3); }
    .status-completed  { background: rgba(100,116,139,.18); color: #94a3b8;  border: 1px solid rgba(100,116,139,.3); }
    .status-cancelled  { background: rgba(239,68,68,.15);   color: #f87171;  border: 1px solid rgba(239,68,68,.3); }

    /* ── Cards ── */
    .info-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .info-card:last-child { margin-bottom: 0; }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,.02);
    }

    .card-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .icon-blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .icon-purple { background: rgba(139,92,246,.15);  color: #a78bfa; }
    .icon-green  { background: rgba(16,185,129,.15);  color: #34d399; }
    .icon-amber  { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .icon-red    { background: rgba(239,68,68,.15);   color: #f87171; }
    .icon-cyan   { background: rgba(6,182,212,.15);   color: #22d3ee; }

    .card-title { font-size: 0.8rem; font-weight: 700; color: var(--text-primary); }
    .card-desc  { font-size: 0.67rem; color: var(--text-tertiary); margin-left: auto; }

    .info-card-body { padding: 1.1rem; }

    /* ── Info rows ── */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }

    .detail-item-full { grid-column: 1 / -1; }

    .detail-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        color: var(--text-tertiary);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .detail-content { flex: 1; min-width: 0; }

    .detail-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
    }

    .detail-value {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-top: 0.1rem;
        line-height: 1.4;
    }

    .detail-value a {
        color: var(--brand-primary);
        text-decoration: none;
        word-break: break-all;
    }

    .detail-value a:hover { text-decoration: underline; }

    /* ── Participants ── */
    .participants-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .participant-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.3rem 0.65rem;
        border-radius: 8px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        font-size: 0.75rem;
        color: var(--text-primary);
    }

    .participant-avatar {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(59,130,246,.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 700;
        color: #60a5fa;
        flex-shrink: 0;
    }

    /* ── Notes / compte-rendu ── */
    .prose-block {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.7;
        white-space: pre-line;
    }

    .section-label {
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
    }

    .divider {
        border: none;
        border-top: 1px solid var(--border-light);
        margin: 1rem 0;
    }

    .action-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .action-items-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        font-size: 0.82rem;
        color: var(--text-secondary);
    }

    .action-items-list li::before {
        content: '';
        display: inline-flex;
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1.5px solid rgba(59,130,246,.4);
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ── Empty compte-rendu ── */
    .empty-minutes {
        text-align: center;
        padding: 1.75rem 1rem;
        color: var(--text-tertiary);
    }

    .empty-minutes i { font-size: 2rem; display: block; margin-bottom: 0.65rem; opacity: .4; }
    .empty-minutes p { margin: 0 0 0.25rem; font-size: 0.82rem; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.48rem 0.95rem;
        border-radius: 8px;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }

    .btn-primary { background: var(--brand-primary); color: #fff; box-shadow: 0 2px 8px rgba(59,130,246,.3); }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); color: #fff; }

    .btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-medium); }
    .btn-ghost:hover { background: var(--bg-tertiary); color: var(--text-primary); }

    .btn-danger-ghost { background: transparent; color: #f87171; border: 1px solid rgba(239,68,68,.25); }
    .btn-danger-ghost:hover { background: rgba(239,68,68,.08); border-color: rgba(239,68,68,.45); }

    /* ── Sidebar ── */
    .sidebar-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
        position: sticky;
        top: 80px;
    }

    .sidebar-card:last-child { margin-bottom: 0; }

    .sidebar-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-tertiary);
        background: rgba(255,255,255,.02);
    }

    .sidebar-body { padding: 0.85rem 1rem; }

    .sb-row {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-light);
    }
    .sb-row:first-child { padding-top: 0; }
    .sb-row:last-child  { border-bottom: none; padding-bottom: 0; }

    .sb-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: var(--text-tertiary);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .sb-label { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-tertiary); }
    .sb-value { font-size: 0.76rem; font-weight: 600; color: var(--text-primary); margin-top: .06rem; }
    .sb-value.muted { font-weight: 400; color: var(--text-tertiary); }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.52rem 0.75rem;
        border-radius: 8px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all .18s;
        margin-top: 0.5rem;
    }

    .quick-link:first-child { margin-top: 0; }
    .quick-link:hover { background: var(--bg-hover); color: var(--brand-primary); border-color: rgba(59,130,246,.3); }
    .quick-link i { font-size: 0.75rem; width: 14px; text-align: center; }

    /* ── Countdown banner (upcoming meetings) ── */
    .countdown-banner {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: rgba(59,130,246,.08);
        border: 1px solid rgba(59,130,246,.2);
        margin-bottom: 1rem;
        font-size: 0.8rem;
        color: #60a5fa;
    }

    .countdown-banner i { font-size: 1rem; flex-shrink: 0; }

    /* ── Delete modal ── */
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
        transition: opacity .25s, visibility .25s;
    }

    .modal-overlay.active { opacity: 1; visibility: visible; }

    .modal-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 14px;
        width: 90%;
        max-width: 420px;
        transform: scale(.95);
        transition: transform .25s;
        box-shadow: 0 24px 50px rgba(0,0,0,.35);
    }

    .modal-overlay.active .modal-box { transform: scale(1); }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
    }

    .modal-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        font-size: 1rem;
        padding: .25rem;
        border-radius: 6px;
        transition: all .15s;
        line-height: 1;
    }

    .modal-close:hover { background: var(--bg-hover); color: var(--text-primary); }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-body p {
        font-size: .82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin: 0 0 .75rem;
    }

    .modal-body p:last-child { margin-bottom: 0; }

    .modal-meeting-name {
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-warning {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem .85rem;
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px;
        font-size: .75rem;
        color: #f87171;
        margin-top: .75rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: .6rem;
        padding: .9rem 1.25rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-modal-cancel {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }

    .btn-modal-cancel:hover { background: var(--bg-hover); color: var(--text-primary); }

    .btn-modal-delete {
        background: #ef4444;
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(239,68,68,.3);
    }

    .btn-modal-delete:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-modal-delete:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .show-layout { grid-template-columns: 1fr; }
        .sidebar-card { position: static; }
    }

    @media (max-width: 640px) {
        .detail-grid { grid-template-columns: 1fr; }
        .detail-item-full { grid-column: 1; }
    }
</style>
@endpush

@section('content')

@php
    $user        = auth()->user();
    $canEdit     = $user->can('meetings.edit') && in_array($meeting->status, ['scheduled', 'in_progress']);
    $canDelete   = $user->can('meetings.delete');
    $statusIcons = [
        'scheduled'   => 'fa-calendar-check',
        'in_progress' => 'fa-circle-dot',
        'completed'   => 'fa-check-circle',
        'cancelled'   => 'fa-ban',
    ];
    $isUpcoming  = $meeting->status === 'scheduled' && $meeting->meeting_date > now();
    $canViewProject = $user->can('projects.view');
    $backUrl     = $canViewProject
        ? route('admin.projects.meetings.index', $meeting->project)
        : route('admin.meetings.global-index');
@endphp

{{-- Page Header --}}
<div class="show-page-header">
    <a href="{{ $backUrl }}" class="back-btn" title="Retour">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="header-meta">
        <h1>{{ $meeting->title }}</h1>
        <div class="header-sub">
            <span><i class="fas fa-folder-open" style="font-size:.6rem;margin-right:2px"></i>{{ $meeting->project->name }}</span>
            <span style="color:var(--border-heavy)">·</span>
            <span class="status-pill status-{{ $meeting->status }}">
                <i class="fas {{ $statusIcons[$meeting->status] ?? 'fa-circle' }}" style="font-size:.4rem"></i>
                {{ $meeting->status_label }}
            </span>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0">
        @if($canEdit)
        <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-primary">
            <i class="fas fa-pencil"></i> Modifier
        </a>
        @endif
        @if($canDelete)
        <form method="POST" action="{{ route('admin.meetings.destroy', $meeting) }}" id="delete-form">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-danger-ghost" onclick="confirmDelete()">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        @endif
    </div>
</div>

{{-- Upcoming banner --}}
@if($isUpcoming)
<div class="countdown-banner">
    <i class="fas fa-bell"></i>
    <span>
        Cette réunion est prévue le <strong>{{ $meeting->meeting_date->format('d/m/Y à H:i') }}</strong>
        — dans <strong>{{ $meeting->meeting_date->diffForHumans() }}</strong>
    </span>
</div>
@endif

<div class="show-layout">

    {{-- ══════ MAIN ══════ --}}
    <div>

        {{-- Section 1 : Informations générales --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="card-icon icon-blue"><i class="fas fa-calendar-alt"></i></div>
                <span class="card-title">Informations générales</span>
                <span class="card-desc">Détails de la réunion</span>
            </div>
            <div class="info-card-body">
                <div class="detail-grid">

                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">{{ $meeting->meeting_date->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-clock"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Heure</div>
                            <div class="detail-value">{{ $meeting->meeting_date->format('H:i') }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Durée</div>
                            <div class="detail-value">{{ $meeting->formatted_duration }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Organisé par</div>
                            <div class="detail-value">{{ $meeting->organizer->name ?? '—' }}</div>
                        </div>
                    </div>

                    @if($meeting->location)
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Lieu</div>
                            <div class="detail-value">{{ $meeting->location }}</div>
                        </div>
                    </div>
                    @endif

                    @if($meeting->meeting_link)
                    <div class="detail-item {{ $meeting->location ? '' : '' }}">
                        <div class="detail-icon"><i class="fas fa-video"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Lien de réunion</div>
                            <div class="detail-value">
                                <a href="{{ $meeting->meeting_link }}" target="_blank" rel="noopener">
                                    <i class="fas fa-arrow-up-right-from-square" style="font-size:.65rem;margin-right:3px"></i>
                                    Rejoindre la réunion
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($meeting->description)
                    <div class="detail-item detail-item-full">
                        <div class="detail-icon"><i class="fas fa-align-left"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Description / Ordre du jour</div>
                            <div class="detail-value" style="font-weight:400;color:var(--text-secondary);white-space:pre-line;line-height:1.6">{{ $meeting->description }}</div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Section 2 : Participants --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="card-icon icon-purple"><i class="fas fa-users"></i></div>
                <span class="card-title">Participants</span>
                @php $attendees = $meeting->attendees_list ?? collect(); @endphp
                <span class="card-desc">{{ $attendees->count() }} personne(s)</span>
            </div>
            <div class="info-card-body">
                @if($attendees->count())
                    <div class="participants-grid">
                        @foreach($attendees as $att)
                        <div class="participant-chip">
                            <span class="participant-avatar">{{ strtoupper(substr($att->name, 0, 1)) }}</span>
                            <span>{{ $att->name }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size:.82rem;color:var(--text-tertiary);margin:0">Aucun participant enregistré pour cette réunion.</p>
                @endif
            </div>
        </div>

        {{-- Section 3 : Compte-rendu --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="card-icon icon-green"><i class="fas fa-file-lines"></i></div>
                <span class="card-title">Compte-rendu</span>
                <span class="card-desc">Notes &amp; décisions</span>
            </div>

            @if($meeting->minutes || $meeting->decisions || ($meeting->action_items && count($meeting->action_items)))
            <div class="info-card-body">

                @if($meeting->minutes)
                <div>
                    <div class="section-label">Notes de réunion</div>
                    <div class="prose-block">{{ $meeting->minutes }}</div>
                </div>
                @endif

                @if($meeting->decisions)
                    @if($meeting->minutes)<hr class="divider">@endif
                    <div>
                        <div class="section-label">Décisions prises</div>
                        <div class="prose-block">{{ $meeting->decisions }}</div>
                    </div>
                @endif

                @if($meeting->action_items && count($meeting->action_items))
                    <hr class="divider">
                    <div>
                        <div class="section-label">Actions à mener</div>
                        <ul class="action-items-list" style="margin-top:.5rem">
                            @foreach($meeting->action_items as $action)
                                <li>{{ $action }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
            @elseif($meeting->status === 'completed')
            <div class="info-card-body">
                <div class="empty-minutes">
                    <i class="fas fa-file-circle-question"></i>
                    <p style="font-weight:600">Aucun compte-rendu</p>
                    <p style="font-size:.76rem">La réunion est terminée mais aucun compte-rendu n'a été ajouté.</p>
                    @if($user->can('meetings.edit'))
                    <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-primary" style="margin-top:.85rem">
                        <i class="fas fa-plus"></i> Ajouter un compte-rendu
                    </a>
                    @endif
                </div>
            </div>
            @else
            <div class="info-card-body">
                <div class="empty-minutes">
                    <i class="fas fa-file-alt"></i>
                    <p style="font-weight:600">Compte-rendu non disponible</p>
                    <p style="font-size:.76rem">Le compte-rendu sera ajouté après la tenue de la réunion.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Section 4 : Notes --}}
        @if($meeting->notes)
        <div class="info-card">
            <div class="info-card-header">
                <div class="card-icon icon-amber"><i class="fas fa-sticky-note"></i></div>
                <span class="card-title">Notes supplémentaires</span>
            </div>
            <div class="info-card-body">
                <div class="prose-block">{{ $meeting->notes }}</div>
            </div>
        </div>
        @endif

    </div>{{-- end main --}}

    {{-- ══════ SIDEBAR ══════ --}}
    <div>

        {{-- Résumé --}}
        <div class="sidebar-card">
            <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Résumé</div>
            <div class="sidebar-body">

                <div class="sb-row">
                    <div class="sb-icon"><i class="fas fa-folder-open"></i></div>
                    <div>
                        <div class="sb-label">Projet</div>
                        <div class="sb-value">{{ $meeting->project->name }}</div>
                    </div>
                </div>

                <div class="sb-row">
                    <div class="sb-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="sb-label">Date</div>
                        <div class="sb-value">{{ $meeting->meeting_date->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>

                <div class="sb-row">
                    <div class="sb-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div>
                        <div class="sb-label">Durée</div>
                        <div class="sb-value">{{ $meeting->formatted_duration }}</div>
                    </div>
                </div>

                <div class="sb-row">
                    <div class="sb-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="sb-label">Participants</div>
                        <div class="sb-value">{{ $attendees->count() }} personne(s)</div>
                    </div>
                </div>

                <div class="sb-row">
                    <div class="sb-icon"><i class="fas fa-calendar-plus"></i></div>
                    <div>
                        <div class="sb-label">Créée le</div>
                        <div class="sb-value muted">{{ $meeting->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="sidebar-card">
            <div class="sidebar-header"><i class="fas fa-bolt" style="margin-right:.35rem"></i>Actions rapides</div>
            <div class="sidebar-body">

                @if($canViewProject)
                <a href="{{ route('admin.projects.show', $meeting->project) }}" class="quick-link">
                    <i class="fas fa-folder-open"></i> Voir le projet
                </a>
                @endif

                <a href="{{ $backUrl }}" class="quick-link">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $canViewProject ? 'Réunions du projet' : 'Liste des réunions' }}
                </a>

                @if($canEdit)
                <a href="{{ route('admin.meetings.edit', $meeting) }}" class="quick-link">
                    <i class="fas fa-pencil"></i> Modifier la réunion
                </a>
                @endif

                @if($meeting->meeting_link)
                <a href="{{ $meeting->meeting_link }}" target="_blank" rel="noopener" class="quick-link">
                    <i class="fas fa-video"></i> Rejoindre la réunion
                </a>
                @endif

                @if($canDelete)
                <button type="button" onclick="confirmDelete()"
                        class="quick-link" style="width:100%;text-align:left;cursor:pointer;background:transparent;border:1px solid rgba(239,68,68,.25);color:#f87171;">
                    <i class="fas fa-trash"></i> Supprimer la réunion
                </button>
                @endif

            </div>
        </div>

    </div>{{-- end sidebar --}}

</div>{{-- end layout --}}

{{-- Delete modal (always in DOM, triggered by button) --}}
<div id="delete-modal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3>
                <i class="fas fa-trash" style="color:#f87171"></i>
                Supprimer la réunion
            </h3>
            <button class="modal-close" onclick="closeDeleteModal()" aria-label="Fermer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Vous êtes sur le point de supprimer la réunion <span class="modal-meeting-name">« {{ $meeting->title }} »</span>.</p>
            <p>Cette opération supprimera définitivement la réunion et toutes ses données associées.</p>
            <div class="modal-warning">
                <i class="fas fa-triangle-exclamation"></i>
                Cette action est irréversible.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-modal-cancel" onclick="closeDeleteModal()">
                <i class="fas fa-xmark"></i> Annuler
            </button>
            <button type="button" class="btn btn-modal-delete" id="confirm-delete-btn" onclick="submitDelete()">
                <i class="fas fa-trash"></i> Supprimer définitivement
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete() {
    var modal = document.getElementById('delete-modal');
    if (!modal) return;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    var modal = document.getElementById('delete-modal');
    if (!modal) return;
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function submitDelete() {
    var btn = document.getElementById('confirm-delete-btn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression…';
    }
    var form = document.getElementById('delete-form');
    if (form) form.submit();
}

document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('delete-modal');
    if (!modal) return;

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeDeleteModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeDeleteModal();
        }
    });
});
</script>
@endpush
