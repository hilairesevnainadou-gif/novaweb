{{-- resources/views/admin/projects/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $project->name . ' - NovaTech Admin')
@section('page-title', $project->name)

@push('styles')
<style>
    .project-header {
        position: relative;
        background: linear-gradient(160deg, var(--bg-secondary) 0%, rgba(59, 130, 246, 0.04) 100%);
        border-radius: 1rem;
        border: 1px solid var(--border-light);
        padding: 1.4rem;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .project-header::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -40px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.15), transparent 65%);
        pointer-events: none;
    }

    .project-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 0.9rem;
        margin-top: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.7rem;
        border: 1px solid var(--border-light);
        border-radius: 0.75rem;
        background: rgba(148, 163, 184, 0.04);
    }

    .meta-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--brand-primary);
        flex-shrink: 0;
    }

    .meta-label {
        font-size: 0.66rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        letter-spacing: 0.06em;
        font-weight: 700;
    }

    .meta-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.84rem;
    }

    .tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        background: var(--bg-secondary);
        border-radius: 0.9rem;
        padding: 0.45rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-light);
    }

    .tab-btn {
        flex: 1;
        min-width: 130px;
        padding: 0.68rem 0.9rem;
        background: transparent;
        border: none;
        border-radius: 0.6rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .tab-btn.active {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.28);
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .stats-grid-small {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .stat-card-small {
        background: var(--bg-secondary);
        border-radius: 0.85rem;
        padding: 0.95rem;
        text-align: center;
        border: 1px solid var(--border-light);
    }

    .stat-value-small {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
    }

    .stat-label-small {
        font-size: 0.66rem;
        color: var(--text-tertiary);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    .task-list {
        background: var(--bg-secondary);
        border-radius: 0.9rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .task-table {
        width: 100%;
        border-collapse: collapse;
    }

    .task-table th {
        padding: 0.82rem 0.9rem;
        text-align: left;
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-tertiary);
        letter-spacing: 0.05em;
    }

    .task-table td {
        padding: 0.82rem 0.9rem;
        border-bottom: 1px dashed var(--border-light);
        color: var(--text-primary);
        font-size: 0.84rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.23rem 0.65rem;
        font-size: 0.66rem;
        font-weight: 700;
        border-radius: 9999px;
    }

    .btn-sm {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.38rem 0.74rem;
        font-size: 0.74rem;
        border-radius: 0.42rem;
        text-decoration: none;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        transition: all 0.2s;
    }

    .btn-sm:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-primary-sm {
        background: var(--brand-primary);
        color: #fff;
        border-color: transparent;
    }

    .btn-primary-sm:hover {
        background: var(--brand-primary-hover);
        color: #fff;
    }

    .meeting-item {
        background: var(--bg-secondary);
        border-radius: 0.85rem;
        padding: 0.9rem;
        margin-bottom: 0.65rem;
        border: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.65rem;
    }

    /* ── Status badges ── */
    .badge-planning    { background: rgba(100,116,139,.18); color: #94a3b8;  border: 1px solid rgba(100,116,139,.3); }
    .badge-inprogress  { background: rgba(59,130,246,.15);  color: #60a5fa;  border: 1px solid rgba(59,130,246,.3); }
    .badge-review      { background: rgba(245,158,11,.15);  color: #fbbf24;  border: 1px solid rgba(245,158,11,.3); }
    .badge-completed   { background: rgba(16,185,129,.15);  color: #34d399;  border: 1px solid rgba(16,185,129,.3); }
    .badge-cancelled   { background: rgba(239,68,68,.15);   color: #f87171;  border: 1px solid rgba(239,68,68,.3); }

    /* ── Priority badges ── */
    .badge-low      { background: rgba(16,185,129,.12);  color: #34d399;  border: 1px solid rgba(16,185,129,.25); }
    .badge-medium   { background: rgba(59,130,246,.12);  color: #60a5fa;  border: 1px solid rgba(59,130,246,.25); }
    .badge-high     { background: rgba(245,158,11,.12);  color: #fbbf24;  border: 1px solid rgba(245,158,11,.25); }
    .badge-critical { background: rgba(239,68,68,.12);   color: #f87171;  border: 1px solid rgba(239,68,68,.25); }

    /* ── Action button (icon) ── */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.75rem;
        transition: all 0.18s;
        margin-left: 0.25rem;
    }

    .action-btn:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: rgba(59,130,246,.35);
    }

    /* ── btn-primary ── */
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
        transition: background 0.18s;
    }

    .btn-primary:hover { background: var(--brand-primary-hover); color: #fff; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--text-tertiary);
    }

    .empty-state i { font-size: 2rem; margin-bottom: 0.65rem; display: block; }
    .empty-state p  { margin: 0 0 0.5rem; font-size: 0.85rem; }

    /* ── Activity feed ── */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px dashed var(--border-light);
    }

    .activity-item:last-child { border-bottom: none; }

    .activity-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--brand-primary);
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .activity-content { flex: 1; min-width: 0; }

    .activity-description {
        font-size: 0.82rem;
        color: var(--text-primary);
        line-height: 1.45;
    }

    .activity-time {
        font-size: 0.68rem;
        color: var(--text-tertiary);
        margin-top: 0.2rem;
    }

    /* ── Table last row / header corners ── */
    .task-table tbody tr:last-child td { border-bottom: none; }

    @media (max-width: 980px) {
        .stats-grid-small { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 768px) {
        .meeting-item { flex-direction: column; align-items: flex-start; }
        .project-meta { grid-template-columns: 1fr; }
    }

    /* ── Delete modal ── */
    .del-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0; visibility: hidden;
        transition: opacity .22s, visibility .22s;
        padding: 1rem;
    }
    .del-modal-overlay.active { opacity: 1; visibility: visible; }
    .del-modal-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 16px;
        width: 100%; max-width: 420px;
        transform: translateY(10px) scale(.97);
        transition: transform .24s cubic-bezier(.22,1,.36,1);
        box-shadow: 0 24px 60px rgba(0,0,0,.45);
        overflow: hidden;
    }
    .del-modal-overlay.active .del-modal-box { transform: translateY(0) scale(1); }
    .del-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: .95rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .del-modal-header h3 {
        font-size: .9rem; font-weight: 700; margin: 0;
        color: var(--text-primary);
        display: flex; align-items: center; gap: .5rem;
    }
    .del-modal-close {
        width: 28px; height: 28px; border-radius: 7px;
        background: var(--bg-tertiary); border: 1px solid var(--border-light);
        color: var(--text-tertiary); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; transition: all .15s;
    }
    .del-modal-close:hover { background: var(--bg-hover); color: var(--text-primary); }
    .del-modal-body { padding: 1.1rem 1.25rem; }
    .del-modal-body p { font-size: .83rem; color: var(--text-secondary); line-height: 1.6; margin: 0 0 .75rem; }
    .del-modal-body p:last-of-type { margin-bottom: 0; }
    .del-modal-subject { font-weight: 700; color: var(--text-primary); }
    .del-modal-warn {
        display: flex; align-items: center; gap: .5rem;
        padding: .6rem .85rem;
        background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px; font-size: .75rem; color: #f87171; margin-top: .85rem;
    }
    .del-modal-footer {
        display: flex; justify-content: flex-end; gap: .6rem;
        padding: .85rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .btn-del-cancel {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .46rem .9rem; border-radius: 8px;
        font-size: .78rem; font-weight: 700; cursor: pointer;
        background: var(--bg-tertiary); color: var(--text-secondary);
        border: 1px solid var(--border-medium); transition: all .18s; font-family: inherit;
    }
    .btn-del-cancel:hover { background: var(--bg-hover); color: var(--text-primary); }
    .btn-del-confirm {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .46rem .9rem; border-radius: 8px;
        font-size: .78rem; font-weight: 700; cursor: pointer;
        background: #ef4444; color: #fff; border: none;
        box-shadow: 0 2px 8px rgba(239,68,68,.3);
        transition: all .18s; font-family: inherit;
    }
    .btn-del-confirm:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-del-confirm:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* ── Activities pagination ── */
    .act-pagination { margin-top: 1rem; display: flex; justify-content: flex-end; }

    /* ── Form modals (add member / add document) ── */
    .fm-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0; visibility: hidden;
        transition: opacity .22s, visibility .22s;
        padding: 1rem;
    }
    .fm-overlay.active { opacity: 1; visibility: visible; }
    .fm-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 16px;
        width: 100%; max-width: 480px;
        transform: translateY(10px) scale(.97);
        transition: transform .24s cubic-bezier(.22,1,.36,1);
        box-shadow: 0 24px 60px rgba(0,0,0,.45);
        overflow: hidden;
    }
    .fm-overlay.active .fm-box { transform: translateY(0) scale(1); }
    .fm-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: .95rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .fm-header h3 {
        font-size: .9rem; font-weight: 700; margin: 0;
        color: var(--text-primary);
        display: flex; align-items: center; gap: .5rem;
    }
    .fm-header h3 .fm-icon {
        width: 28px; height: 28px; border-radius: 8px;
        background: rgba(99,102,241,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem; color: #818cf8; flex-shrink: 0;
    }
    .fm-close {
        width: 28px; height: 28px; border-radius: 7px;
        background: var(--bg-tertiary); border: 1px solid var(--border-light);
        color: var(--text-tertiary); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; transition: all .15s;
    }
    .fm-close:hover { background: var(--bg-hover); color: var(--text-primary); }
    .fm-body { padding: 1.1rem 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
    .fm-field { display: flex; flex-direction: column; gap: .28rem; }
    .fm-label {
        font-size: .62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-tertiary);
    }
    .fm-label span { color: #f87171; margin-left: .15rem; }
    .fm-input {
        padding: .48rem .75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: .82rem; font-family: inherit;
        outline: none; width: 100%; box-sizing: border-box;
    }
    .fm-input:focus { border-color: var(--border-medium); outline: none; }
    .fm-input::placeholder { color: var(--text-disabled); font-size: .78rem; }
    select.fm-input { cursor: pointer; }
    input[type="file"].fm-input { cursor: pointer; padding: .38rem .75rem; }
    .fm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

    /* ── Member picker ── */
    .fm-user-search {
        display: flex; align-items: center; gap: .5rem;
        padding: .45rem .65rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-medium);
        border-radius: 8px;
        margin-bottom: .6rem;
    }
    .fm-user-search i { font-size: .72rem; color: var(--text-tertiary); flex-shrink: 0; }
    .fm-user-search input {
        flex: 1; background: transparent; border: none; outline: none;
        color: var(--text-primary); font-size: .8rem; font-family: inherit; min-width: 0;
    }
    .fm-user-search input::placeholder { color: var(--text-disabled); }
    .fm-user-list {
        max-height: 220px; overflow-y: auto; overscroll-behavior: contain;
        border: 1px solid var(--border-light);
        border-radius: 9px;
        background: var(--bg-tertiary);
    }
    .fm-user-item {
        display: flex; align-items: center; gap: .7rem;
        padding: .6rem .85rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border-light);
        transition: background .12s;
        position: relative;
    }
    .fm-user-item:last-child { border-bottom: none; }
    .fm-user-item:hover { background: var(--bg-hover); }
    .fm-user-item.selected {
        background: rgba(99,102,241,.08);
        border-left: 3px solid var(--brand-primary);
        padding-left: calc(.85rem - 3px);
    }
    .fm-user-item.selected:hover { background: rgba(99,102,241,.12); }
    .fm-uav {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700;
        background: rgba(99,102,241,.15); color: #818cf8;
    }
    .fm-user-item.selected .fm-uav { background: rgba(99,102,241,.25); color: #a5b4fc; }
    .fm-uname { font-size: .82rem; font-weight: 600; color: var(--text-primary); }
    .fm-uemail { font-size: .67rem; color: var(--text-tertiary); margin-top: .05rem; }
    .fm-ucheck {
        margin-left: auto; font-size: .72rem; color: var(--brand-primary);
        display: none; flex-shrink: 0;
    }
    .fm-user-item.selected .fm-ucheck { display: block; }
    .fm-no-users {
        padding: 1.5rem; text-align: center;
        font-size: .78rem; color: var(--text-tertiary);
    }
    .fm-selected-info {
        display: none; align-items: center; gap: .55rem;
        padding: .5rem .75rem;
        background: rgba(99,102,241,.07);
        border: 1px solid rgba(99,102,241,.18);
        border-radius: 8px; margin-top: .5rem;
        font-size: .78rem; color: var(--text-secondary);
    }
    .fm-selected-info.visible { display: flex; }
    .fm-selected-info strong { color: var(--text-primary); }
    .fm-footer {
        display: flex; justify-content: flex-end; gap: .6rem;
        padding: .85rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }
    .fm-btn-cancel {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .46rem .9rem; border-radius: 8px;
        font-size: .78rem; font-weight: 700; cursor: pointer;
        background: var(--bg-tertiary); color: var(--text-secondary);
        border: 1px solid var(--border-medium); transition: all .18s; font-family: inherit;
    }
    .fm-btn-cancel:hover { background: var(--bg-hover); color: var(--text-primary); }
    .fm-btn-submit {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .46rem 1rem; border-radius: 8px;
        font-size: .78rem; font-weight: 700; cursor: pointer;
        background: var(--brand-primary); color: #fff; border: none;
        box-shadow: 0 2px 8px rgba(99,102,241,.3);
        transition: all .18s; font-family: inherit;
    }
    .fm-btn-submit:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }
    .fm-btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* ── File drop zone ── */
    .fm-file-zone {
        border: 2px dashed var(--border-medium);
        border-radius: 10px;
        padding: 1.25rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .18s, background .18s;
        position: relative;
        background: var(--bg-tertiary);
    }
    .fm-file-zone:hover, .fm-file-zone.drag-over {
        border-color: var(--brand-primary);
        background: rgba(99,102,241,.05);
    }
    .fm-file-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .fm-file-zone i { font-size: 1.4rem; color: var(--text-tertiary); display: block; margin-bottom: .4rem; }
    .fm-file-zone .fz-label { font-size: .78rem; color: var(--text-secondary); font-weight: 600; }
    .fm-file-zone .fz-hint  { font-size: .68rem; color: var(--text-disabled); margin-top: .2rem; }
    .fm-file-name { font-size: .75rem; color: var(--brand-primary); font-weight: 600; margin-top: .35rem; display: none; }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $canEditProjects = $user->can('projects.edit');
    $canViewTasks    = $user->can('projects.view');
    $canCreateTasks  = $user->can('tasks.create');
    $isProjectManager = $project->project_manager_id === $user->id;
    $canManageMembers = $canEditProjects || $isProjectManager;
    $canManageDocs    = $canEditProjects || $project->isMember($user->id);
@endphp

<div class="project-header">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0;">{{ $project->name }}</h1>
                <span class="badge badge-{{ str_replace('_', '', $project->status) }}">
                    {{ $project->status_label }}
                </span>
            </div>
            <p style="color: var(--text-secondary); margin: 0;">{{ $project->project_number }}</p>
        </div>
        @if($canEditProjects)
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
        @endif
    </div>

    <div class="project-meta">
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-user-tie"></i></div>
            <div>
                <div class="meta-label">Chef de projet</div>
                <div class="meta-value">{{ $project->projectManager->name ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-building"></i></div>
            <div>
                <div class="meta-label">Client</div>
                <div class="meta-value">{{ $project->client->name ?? 'Non assigné' }}</div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="meta-label">Dates</div>
                <div class="meta-value">
                    {{ $project->start_date?->format('d/m/Y') ?? 'N/A' }} →
                    {{ $project->end_date?->format('d/m/Y') ?? 'N/A' }}
                </div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <div class="meta-label">Progression</div>
                <div class="meta-value">{{ $project->progress_percentage }}%</div>
            </div>
        </div>
    </div>

    @if($project->description)
    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
        <div class="meta-label" style="margin-bottom: 0.5rem;">Description</div>
        <p style="color: var(--text-primary); margin: 0;">{{ $project->description }}</p>
    </div>
    @endif
</div>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" data-tab="overview">Vue d'ensemble</button>
    <button class="tab-btn" data-tab="tasks">Tâches</button>
    <button class="tab-btn" data-tab="meetings">Réunions</button>
    <button class="tab-btn" data-tab="documents">
        Documents
        @if($project->documents->count())
            <span style="background:rgba(59,130,246,.2);color:#60a5fa;font-size:.6rem;padding:.1rem .4rem;border-radius:9999px;margin-left:.3rem">{{ $project->documents->count() }}</span>
        @endif
    </button>
    <button class="tab-btn" data-tab="team">
        Équipe
        <span style="background:rgba(59,130,246,.2);color:#60a5fa;font-size:.6rem;padding:.1rem .4rem;border-radius:9999px;margin-left:.3rem">{{ $project->members->count() + 1 }}</span>
    </button>
    <button class="tab-btn" data-tab="activities">Activités</button>
</div>

<!-- Tab Overview -->
<div class="tab-content active" id="tab-overview">
    <div class="stats-grid-small">
        <div class="stat-card-small">
            <div class="stat-value-small">{{ $stats['total_tasks'] }}</div>
            <div class="stat-label-small">Tâches totales</div>
        </div>
        <div class="stat-card-small">
            <div class="stat-value-small">{{ $stats['completed_tasks'] }}</div>
            <div class="stat-label-small">Terminées</div>
        </div>
        <div class="stat-card-small">
            <div class="stat-value-small">{{ $stats['in_progress_tasks'] }}</div>
            <div class="stat-label-small">En cours</div>
        </div>
        <div class="stat-card-small">
            <div class="stat-value-small">{{ $stats['pending_review_tasks'] }}</div>
            <div class="stat-label-small">En revue</div>
        </div>
    </div>

    @if($project->technologies)
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-top: 1rem;">
        <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem;">
            <i class="fas fa-code"></i> Technologies utilisées
        </h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @foreach($project->technologies as $tech)
                <span style="background: var(--bg-tertiary); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem;">
                    {{ $tech }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    @if($project->repository_url || $project->production_url)
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-top: 1rem;">
        <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem;">
            <i class="fas fa-link"></i> Liens utiles
        </h3>
        <div style="display: flex; gap: 1rem;">
            @if($project->repository_url)
                <a href="{{ $project->repository_url }}" target="_blank" class="btn-sm">
                    <i class="fab fa-github"></i> Repository
                </a>
            @endif
            @if($project->production_url)
                <a href="{{ $project->production_url }}" target="_blank" class="btn-sm">
                    <i class="fas fa-globe"></i> Site en production
                </a>
            @endif
            @if($project->staging_url)
                <a href="{{ $project->staging_url }}" target="_blank" class="btn-sm">
                    <i class="fas fa-flask"></i> Staging
                </a>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Tab Tasks -->
<div class="tab-content" id="tab-tasks">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1rem; font-weight: 600; margin: 0;">
            <i class="fas fa-tasks"></i> Liste des tâches
        </h3>
        @if($canCreateTasks)
        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary-sm btn-sm">
            <i class="fas fa-plus"></i> Nouvelle tâche
        </a>
        @endif
    </div>

    <div class="task-list">
        <table class="task-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Tâche</th>
                    <th>Assignée à</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Date échéance</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->tasks as $task)
                <tr>
                    <td><strong>{{ $task->task_number }}</strong></td>
                    <td>
                        <div>{{ $task->title }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ Str::limit($task->description, 50) }}</div>
                    </td>
                    <td>{{ $task->assignee->name ?? 'Non assigné' }}</td>
                    <td>
                        <span class="badge badge-{{ $task->priority }}">
                            {{ $task->priority_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '', $task->status) }}">
                            {{ $task->status_label }}
                        </span>
                    </td>
                    <td>
                        @if($task->due_date)
                            @if($task->is_overdue)
                                <span style="color: #ef4444;">{{ $task->due_date->format('d/m/Y') }}</span>
                            @else
                                {{ $task->due_date->format('d/m/Y') }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="action-btn">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <p>Aucune tâche pour ce projet</p>
                        @if($canCreateTasks)
                        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Créer une tâche
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tab Meetings -->
<div class="tab-content" id="tab-meetings">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1rem; font-weight: 600; margin: 0;">
            <i class="fas fa-calendar-alt"></i> Réunions
        </h3>
        @if($user->can('meetings.create'))
        <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-primary-sm btn-sm">
            <i class="fas fa-plus"></i> Planifier une réunion
        </a>
        @endif
    </div>

    @forelse($project->meetings as $meeting)
    <div class="meeting-item">
        <div>
            <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $meeting->title }}</div>
            <div style="font-size: 0.75rem; color: var(--text-tertiary);">
                <i class="far fa-calendar"></i> {{ $meeting->meeting_date->format('d/m/Y à H:i') }}
                • <i class="far fa-clock"></i> {{ $meeting->formatted_duration }}
            </div>
            @if($meeting->location)
            <div style="font-size: 0.75rem; color: var(--text-tertiary); margin-top: 0.25rem;">
                <i class="fas fa-map-marker-alt"></i> {{ $meeting->location }}
            </div>
            @endif
        </div>
        <div>
            <span class="badge badge-{{ $meeting->status === 'scheduled' ? 'review' : ($meeting->status === 'completed' ? 'completed' : 'cancelled') }}">
                {{ $meeting->status_label }}
            </span>
            <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state" style="padding: 2rem;">
        <i class="fas fa-calendar-alt"></i>
        <p>Aucune réunion planifiée</p>
    </div>
    @endforelse
</div>

<!-- Tab Documents -->
<div class="tab-content" id="tab-documents">

    @if(session('success'))
    <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#34d399;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="font-size:1rem;font-weight:600;margin:0;">
            <i class="fas fa-file-alt"></i> Documents
        </h3>
        @if($canManageDocs)
        <button type="button" class="btn-primary-sm btn-sm" onclick="openDocModal()">
            <i class="fas fa-plus"></i> Ajouter un document
        </button>
        @endif
    </div>

    {{-- Document list --}}
    <div style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:0.9rem;overflow:hidden;">
        @forelse($project->documents as $doc)
        <div style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1rem;border-bottom:1px dashed var(--border-light);">
            <div style="width:38px;height:38px;border-radius:9px;background:var(--bg-tertiary);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.05rem;color:{{ $doc->file_icon_color }};">
                <i class="{{ $doc->file_icon }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.85rem;color:var(--text-primary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $doc->title }}</div>
                <div style="font-size:.7rem;color:var(--text-tertiary);margin-top:.1rem;">
                    {{ $doc->original_name }} · {{ $doc->size_formatted }}
                    @if($doc->description)
                        · {{ $doc->description }}
                    @endif
                </div>
                <div style="font-size:.68rem;color:var(--text-disabled);margin-top:.1rem;">
                    Ajouté par {{ $doc->uploader->name }} · {{ $doc->created_at->diffForHumans() }}
                </div>
            </div>
            <div style="display:flex;gap:.35rem;flex-shrink:0;">
                <a href="{{ route('admin.projects.documents.download', [$project, $doc]) }}"
                   class="action-btn" title="Télécharger">
                    <i class="fas fa-download"></i>
                </a>
                @if($canEditProjects || $doc->user_id === $user->id || $isProjectManager)
                <form id="del-doc-{{ $doc->id }}" action="{{ route('admin.projects.documents.destroy', [$project, $doc]) }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>
                <button type="button" class="action-btn"
                        style="color:#f87171;border-color:rgba(239,68,68,.2);"
                        title="Supprimer"
                        onclick="openDelModal('del-doc-{{ $doc->id }}', '{{ addslashes($doc->title) }}', 'document')">
                    <i class="fas fa-trash"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:2.5rem 1rem;">
            <i class="fas fa-folder-open"></i>
            <p>Aucun document pour ce projet</p>
            @if($canManageDocs)
            <button type="button" class="btn-primary" style="margin-top:.75rem;" onclick="openDocModal()">
                <i class="fas fa-plus"></i> Ajouter le premier document
            </button>
            @endif
        </div>
        @endforelse
    </div>
</div>

<!-- Tab Équipe -->
<div class="tab-content" id="tab-team">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="font-size:1rem;font-weight:600;margin:0;">
            <i class="fas fa-users"></i> Membres de l'équipe
        </h3>
        @if($canManageMembers && $nonMembers->count())
        <button type="button" class="btn-primary-sm btn-sm" onclick="openMemberModal()">
            <i class="fas fa-user-plus"></i> Ajouter un membre
        </button>
        @endif
    </div>

    {{-- Liste des membres --}}
    <div style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:0.9rem;overflow:hidden;">

        {{-- Chef de projet (toujours en premier) --}}
        <div style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1rem;border-bottom:1px dashed var(--border-light);">
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(59,130,246,.15);display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#60a5fa;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($project->projectManager->name ?? 'N', 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.85rem;color:var(--text-primary);">{{ $project->projectManager->name ?? 'N/A' }}</div>
                <div style="font-size:.7rem;color:var(--text-tertiary);">{{ $project->projectManager->email ?? '' }}</div>
            </div>
            <span style="background:rgba(59,130,246,.15);color:#60a5fa;border:1px solid rgba(59,130,246,.3);font-size:.65rem;font-weight:700;padding:.2rem .6rem;border-radius:9999px;">Chef de projet</span>
        </div>

        {{-- Membres assignés --}}
        @forelse($project->members as $member)
        <div style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1rem;border-bottom:1px dashed var(--border-light);">
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(139,92,246,.15);display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#a78bfa;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.85rem;color:var(--text-primary);">{{ $member->name }}</div>
                <div style="font-size:.7rem;color:var(--text-tertiary);">{{ $member->email }}</div>
            </div>
            @if($member->pivot->role)
            <span style="background:var(--bg-tertiary);color:var(--text-secondary);border:1px solid var(--border-light);font-size:.65rem;font-weight:700;padding:.2rem .6rem;border-radius:9999px;">
                {{ $member->pivot->role }}
            </span>
            @endif
            @if($canManageMembers)
            <form id="del-member-{{ $member->id }}" action="{{ route('admin.projects.members.destroy', [$project, $member]) }}" method="POST" style="display:none;">
                @csrf @method('DELETE')
            </form>
            <button type="button" class="action-btn"
                    style="color:#f87171;border-color:rgba(239,68,68,.2);"
                    title="Retirer"
                    onclick="openDelModal('del-member-{{ $member->id }}', '{{ addslashes($member->name) }}', 'membre')">
                <i class="fas fa-user-minus"></i>
            </button>
            @endif
        </div>
        @empty
        @if(!$project->projectManager)
        <div class="empty-state" style="padding:2rem;">
            <i class="fas fa-users"></i>
            <p>Aucun membre assigné</p>
        </div>
        @endif
        @endforelse

        @if($project->members->isEmpty() && $project->projectManager)
        <div style="padding:.85rem 1rem;font-size:.78rem;color:var(--text-tertiary);text-align:center;">
            Seul le chef de projet est actuellement assigné.
            @if($canManageMembers && $nonMembers->count())
                Utilisez le formulaire ci-dessus pour ajouter des membres.
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Tab Activities -->
<div class="tab-content" id="tab-activities">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;">
        <h3 style="font-size:1rem;font-weight:600;margin:0;">
            <i class="fas fa-history"></i> Historique des activités
        </h3>
        <span style="font-size:.75rem;color:var(--text-tertiary);">
            {{ $activities->total() }} entrée(s) · page {{ $activities->currentPage() }}/{{ $activities->lastPage() }}
        </span>
    </div>

    <div style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:.9rem;padding:.5rem 1rem;">
        @forelse($activities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                <i class="{{ $activity->activity_icon }}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">{{ $activity->description }}</div>
                <div class="activity-time">
                    <i class="far fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                    · par {{ $activity->user_name }}
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:2rem;">
            <i class="fas fa-history"></i>
            <p>Aucune activité enregistrée</p>
        </div>
        @endforelse
    </div>

    @if($activities->hasPages())
    <div class="act-pagination">{{ $activities->links() }}</div>
    @endif
</div>

{{-- ── Modal : Ajouter un membre ── --}}
@if($canManageMembers && $nonMembers->count())
<div id="modal-add-member" class="fm-overlay" role="dialog" aria-modal="true" aria-labelledby="fm-member-title">
    <div class="fm-box" style="max-width:460px;">
        <div class="fm-header">
            <h3 id="fm-member-title">
                <span class="fm-icon"><i class="fas fa-user-plus"></i></span>
                Ajouter un membre
            </h3>
            <button type="button" class="fm-close" onclick="closeMemberModal()" aria-label="Fermer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.projects.members.store', $project) }}" method="POST" id="form-add-member">
            @csrf
            <input type="hidden" name="user_id" id="fm-user-id-input">
            <div class="fm-body">

                {{-- Recherche + liste --}}
                <div class="fm-field">
                    <label class="fm-label">Utilisateur <span>*</span></label>

                    <div class="fm-user-search">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" id="fm-user-search" placeholder="Rechercher par nom ou email…" autocomplete="off">
                    </div>

                    <div id="fm-user-list" class="fm-user-list">
                        @foreach($nonMembers as $u)
                        <div class="fm-user-item" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}">
                            <div class="fm-uav">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div class="fm-uname">{{ $u->name }}</div>
                                <div class="fm-uemail">{{ $u->email }}</div>
                            </div>
                            <i class="fas fa-check fm-ucheck"></i>
                        </div>
                        @endforeach
                    </div>

                    <div id="fm-no-users" class="fm-no-users" style="display:none;">
                        <i class="fas fa-magnifying-glass" style="display:block;margin-bottom:.4rem;opacity:.3;font-size:1.2rem;"></i>
                        Aucun utilisateur trouvé
                    </div>

                    <div id="fm-selected-info" class="fm-selected-info">
                        <i class="fas fa-circle-check" style="color:var(--brand-primary);font-size:.85rem;flex-shrink:0;"></i>
                        <span>Sélectionné : <strong id="fm-selected-name"></strong></span>
                    </div>
                </div>

                {{-- Rôle --}}
                <div class="fm-field">
                    <label class="fm-label">Rôle dans le projet</label>
                    <input type="text" name="role" class="fm-input"
                           placeholder="Ex : Développeur Front-End, Designer UI…">
                </div>

            </div>
            <div class="fm-footer">
                <button type="button" class="fm-btn-cancel" onclick="closeMemberModal()">
                    <i class="fas fa-xmark"></i> Annuler
                </button>
                <button type="submit" class="fm-btn-submit" id="fm-member-submit" disabled>
                    <i class="fas fa-user-plus"></i> Ajouter au projet
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── Modal : Ajouter un document ── --}}
@if($canManageDocs)
<div id="modal-add-doc" class="fm-overlay" role="dialog" aria-modal="true" aria-labelledby="fm-doc-title">
    <div class="fm-box">
        <div class="fm-header">
            <h3 id="fm-doc-title">
                <span class="fm-icon"><i class="fas fa-cloud-arrow-up"></i></span>
                Ajouter un document
            </h3>
            <button type="button" class="fm-close" onclick="closeDocModal()" aria-label="Fermer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.projects.documents.store', $project) }}" method="POST"
              enctype="multipart/form-data" id="form-add-doc">
            @csrf
            <div class="fm-body">
                <div class="fm-field">
                    <label class="fm-label">Titre <span>*</span></label>
                    <input type="text" name="title" required class="fm-input"
                           placeholder="Ex : Cahier des charges v2">
                </div>
                <div class="fm-field">
                    <label class="fm-label">Fichier <span>*</span></label>
                    <div class="fm-file-zone" id="file-zone">
                        <input type="file" name="file" required id="fm-file-input"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.zip,.rar,.txt,.csv">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <div class="fz-label">Cliquez ou déposez un fichier ici</div>
                        <div class="fz-hint">PDF, Word, Excel, Image, ZIP — max 10 Mo</div>
                        <div class="fm-file-name" id="fm-file-name"></div>
                    </div>
                </div>
                <div class="fm-field">
                    <label class="fm-label">Description</label>
                    <input type="text" name="description" class="fm-input"
                           placeholder="Brève description du document…">
                </div>
            </div>
            <div class="fm-footer">
                <button type="button" class="fm-btn-cancel" onclick="closeDocModal()">
                    <i class="fas fa-xmark"></i> Annuler
                </button>
                <button type="submit" class="fm-btn-submit" id="fm-doc-submit">
                    <i class="fas fa-upload"></i> Uploader
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── Modal suppression générique ── --}}
<div id="del-modal" class="del-modal-overlay" role="dialog" aria-modal="true">
    <div class="del-modal-box">
        <div class="del-modal-header">
            <h3>
                <i class="fas fa-trash" style="color:#f87171;font-size:.85rem"></i>
                Confirmer la suppression
            </h3>
            <button type="button" class="del-modal-close" onclick="closeDelModal()" aria-label="Fermer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="del-modal-body">
            <p>Vous êtes sur le point de supprimer le <span id="del-modal-type"></span> <span id="del-modal-subject" class="del-modal-subject"></span>.</p>
            <div class="del-modal-warn">
                <i class="fas fa-triangle-exclamation"></i>
                Cette action est irréversible.
            </div>
        </div>
        <div class="del-modal-footer">
            <button type="button" class="btn-del-cancel" onclick="closeDelModal()">
                <i class="fas fa-xmark"></i> Annuler
            </button>
            <button type="button" class="btn-del-confirm" id="del-modal-confirm">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Tabs ── */
    const defaultTab = '{{ request()->has("act_page") ? "activities" : request("tab", "overview") }}';

    function activateTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === tabId));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.toggle('active', c.id === 'tab-' + tabId));
    }

    activateTab(defaultTab);

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            activateTab(this.dataset.tab);
        });
    });

    /* ── Delete modal ── */
    const modal     = document.getElementById('del-modal');
    const typeEl    = document.getElementById('del-modal-type');
    const subjectEl = document.getElementById('del-modal-subject');
    const confirmBtn = document.getElementById('del-modal-confirm');
    let pendingForm  = null;

    window.openDelModal = function (formId, name, type) {
        pendingForm = document.getElementById(formId);
        if (!pendingForm) return;
        typeEl.textContent    = type;
        subjectEl.textContent = '« ' + name + ' »';
        confirmBtn.disabled   = false;
        confirmBtn.innerHTML  = '<i class="fas fa-trash"></i> Supprimer';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeDelModal = function () {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        pendingForm = null;
    };

    confirmBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression…';
        pendingForm.submit();
    });

    modal.addEventListener('click', e => { if (e.target === modal) closeDelModal(); });

    /* ── Modal Ajouter Membre ── */
    const memberModal   = document.getElementById('modal-add-member');
    const memberSubmit  = document.getElementById('fm-member-submit');
    const userIdInput   = document.getElementById('fm-user-id-input');
    const userSearchIn  = document.getElementById('fm-user-search');
    const userListEl    = document.getElementById('fm-user-list');
    const noUsersEl     = document.getElementById('fm-no-users');
    const selectedInfo  = document.getElementById('fm-selected-info');
    const selectedName  = document.getElementById('fm-selected-name');
    const userItems     = Array.from(document.querySelectorAll('.fm-user-item'));

    function resetMemberModal() {
        if (userIdInput)  userIdInput.value = '';
        if (userSearchIn) userSearchIn.value = '';
        if (memberSubmit) memberSubmit.disabled = true;
        if (selectedInfo) selectedInfo.classList.remove('visible');
        userItems.forEach(i => i.classList.remove('selected'));
        filterUsers('');
    }

    function filterUsers(q) {
        const lq = q.toLowerCase().trim();
        let visible = 0;
        userItems.forEach(item => {
            const match = !lq
                || item.dataset.name.toLowerCase().includes(lq)
                || item.dataset.email.toLowerCase().includes(lq);
            item.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (noUsersEl)  noUsersEl.style.display  = (visible === 0) ? '' : 'none';
        if (userListEl) userListEl.style.display  = (visible > 0)  ? '' : 'none';
    }

    userItems.forEach(item => {
        item.addEventListener('click', () => {
            userItems.forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            if (userIdInput)   userIdInput.value   = item.dataset.id;
            if (memberSubmit)  memberSubmit.disabled = false;
            if (selectedName)  selectedName.textContent = item.dataset.name;
            if (selectedInfo)  selectedInfo.classList.add('visible');
        });
    });

    userSearchIn?.addEventListener('input', () => filterUsers(userSearchIn.value));

    window.openMemberModal = function () {
        if (!memberModal) return;
        resetMemberModal();
        memberModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => userSearchIn?.focus(), 80);
    };
    window.closeMemberModal = function () {
        if (!memberModal) return;
        memberModal.classList.remove('active');
        document.body.style.overflow = '';
    };
    memberModal?.addEventListener('click', e => { if (e.target === memberModal) closeMemberModal(); });
    document.getElementById('form-add-member')?.addEventListener('submit', function () {
        if (!userIdInput?.value) { return false; }
        if (memberSubmit) {
            memberSubmit.disabled = true;
            memberSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout…';
        }
    });

    /* ── Modal Ajouter Document ── */
    const docModal   = document.getElementById('modal-add-doc');
    const docSubmit  = document.getElementById('fm-doc-submit');
    const fileInput  = document.getElementById('fm-file-input');
    const fileZone   = document.getElementById('file-zone');
    const fileNameEl = document.getElementById('fm-file-name');

    window.openDocModal = function () {
        if (!docModal) return;
        docModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => docModal.querySelector('input[name="title"]')?.focus(), 80);
    };
    window.closeDocModal = function () {
        if (!docModal) return;
        docModal.classList.remove('active');
        document.body.style.overflow = '';
    };
    docModal?.addEventListener('click', e => { if (e.target === docModal) closeDocModal(); });

    fileInput?.addEventListener('change', function () {
        if (this.files.length && fileNameEl) {
            fileNameEl.textContent = this.files[0].name;
            fileNameEl.style.display = 'block';
        }
    });

    fileZone?.addEventListener('dragover', e => { e.preventDefault(); fileZone.classList.add('drag-over'); });
    fileZone?.addEventListener('dragleave', () => fileZone.classList.remove('drag-over'));
    fileZone?.addEventListener('drop', e => {
        e.preventDefault();
        fileZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length && fileInput) {
            fileInput.files = e.dataTransfer.files;
            if (fileNameEl) { fileNameEl.textContent = e.dataTransfer.files[0].name; fileNameEl.style.display = 'block'; }
        }
    });

    docModal?.querySelector('form')?.addEventListener('submit', function () {
        if (docSubmit) {
            docSubmit.disabled = true;
            docSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Upload…';
        }
    });

    /* ── Fermeture globale Échap ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeDelModal(); closeMemberModal(); closeDocModal(); }
    });
})();
</script>
@endpush
