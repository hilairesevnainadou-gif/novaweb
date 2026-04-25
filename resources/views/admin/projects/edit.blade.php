{{-- resources/views/admin/projects/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier — ' . $project->name . ' · NovaTech Admin')
@section('page-title', 'Modifier le projet')

@push('styles')
<style>
    /* ── Layout ── */
    .edit-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Page header ── */
    .edit-page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.4rem;
    }

    .edit-page-header .back-btn {
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
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }

    .edit-page-header .back-btn:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: rgba(59,130,246,.3);
    }

    .edit-page-header .header-meta {
        flex: 1;
        min-width: 0;
    }

    .edit-page-header h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    .edit-page-header .header-sub {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.2rem;
        font-size: 0.72rem;
        color: var(--text-tertiary);
    }

    .header-status-pill {
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

    .status-planning  { background: rgba(100,116,139,.18); color: #94a3b8; border: 1px solid rgba(100,116,139,.3); }
    .status-in_progress { background: rgba(59,130,246,.15); color: #60a5fa; border: 1px solid rgba(59,130,246,.3); }
    .status-review    { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
    .status-completed { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
    .status-cancelled { background: rgba(239,68,68,.15);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }

    /* ── Section cards ── */
    .form-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: visible;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
    }

    .form-card:last-child { margin-bottom: 0; }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,.02);
        border-radius: 14px 14px 0 0;
    }

    .form-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        flex-shrink: 0;
    }

    .icon-blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .icon-purple { background: rgba(139,92,246,.15);  color: #a78bfa; }
    .icon-green  { background: rgba(16,185,129,.15);  color: #34d399; }
    .icon-amber  { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .icon-cyan   { background: rgba(6,182,212,.15);   color: #22d3ee; }

    .form-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: 0.01em;
    }

    .form-card-desc {
        font-size: 0.67rem;
        color: var(--text-tertiary);
        margin-left: auto;
    }

    .form-card-body {
        padding: 1.1rem;
    }

    /* ── Grid ── */
    .fg-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.8rem; }
    .fg-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.8rem; }
    .fg-full { grid-column: 1 / -1; }

    /* ── Field ── */
    .field {
        display: flex;
        flex-direction: column;
        gap: 0.32rem;
    }

    .field label {
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
    }

    .field label .req {
        color: #f87171;
        margin-left: 0.2rem;
    }

    .field-hint {
        font-size: 0.63rem;
        color: var(--text-disabled);
        line-height: 1.3;
    }

    /* ── Input base ── */
    .fi {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-height: 38px;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast), background var(--transition-fast);
        outline: none;
        width: 100%;
    }

    .fi:hover {
        border-color: var(--border-heavy);
        background: var(--bg-elevated);
    }

    .fi:focus {
        border-color: var(--brand-primary);
        background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    .fi::placeholder { color: var(--text-disabled); font-size: 0.77rem; }

    textarea.fi {
        resize: vertical;
        min-height: 80px;
        max-height: 140px;
        line-height: 1.5;
    }

    select.fi { cursor: pointer; }

    .fi.is-invalid {
        border-color: #f87171;
        background: rgba(239,68,68,.05);
    }

    .fi.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

    .invalid-msg {
        font-size: 0.65rem;
        color: #f87171;
        margin-top: 0.1rem;
    }

    /* ── Input with prefix icon ── */
    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .fi { padding-left: 2.2rem; }

    .input-icon-wrap .fi-icon {
        position: absolute;
        left: 0.7rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-disabled);
        font-size: 0.78rem;
        pointer-events: none;
    }

    /* ── Progress slider ── */
    .progress-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .progress-track {
        position: relative;
        height: 6px;
        border-radius: 999px;
        background: var(--bg-elevated);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent));
        transition: width 0.3s ease;
    }

    input[type="range"].range-fi {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 999px;
        background: transparent;
        outline: none;
        cursor: pointer;
    }

    input[type="range"].range-fi::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--brand-primary);
        border: 2px solid var(--bg-primary);
        box-shadow: 0 0 0 2px var(--brand-primary);
        cursor: pointer;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .progress-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--brand-primary);
        line-height: 1;
    }

    .progress-pct { font-size: 0.65rem; color: var(--text-tertiary); }

    /* ── Technology tags ── */
    .tags-input-wrap {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem;
        min-height: 38px;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: text;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }

    .tags-input-wrap:focus-within {
        border-color: var(--brand-primary);
        background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.18rem 0.5rem;
        border-radius: 6px;
        background: rgba(59,130,246,.15);
        border: 1px solid rgba(59,130,246,.3);
        color: #60a5fa;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: default;
        white-space: nowrap;
    }

    .tag-chip .remove-tag {
        cursor: pointer;
        opacity: 0.7;
        font-size: 0.6rem;
        transition: opacity .15s;
        background: none;
        border: none;
        color: inherit;
        padding: 0;
        line-height: 1;
    }

    .tag-chip .remove-tag:hover { opacity: 1; }

    #tag-input-field {
        border: none;
        outline: none;
        background: transparent;
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-width: 100px;
        flex: 1;
        padding: 0.1rem 0;
    }

    #tag-input-field::placeholder { color: var(--text-disabled); font-size: 0.77rem; }

    /* ── Alert ── */
    .alert-errors {
        display: flex;
        gap: 0.75rem;
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.25);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.1rem;
    }

    .alert-errors .alert-icon {
        font-size: 1rem;
        color: #f87171;
        margin-top: 0.05rem;
        flex-shrink: 0;
    }

    .alert-errors ul {
        margin: 0;
        padding-left: 1.1rem;
        font-size: 0.78rem;
        color: #fca5a5;
        line-height: 1.6;
    }

    .alert-errors .alert-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #f87171;
        margin-bottom: 0.3rem;
    }

    /* ── Action bar ── */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: rgba(255,255,255,.015);
        border-radius: 0 0 14px 14px;
    }

    .action-bar .left { display: flex; align-items: center; gap: 0.5rem; }
    .action-bar .right { display: flex; align-items: center; gap: 0.5rem; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.52rem 1rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }

    .btn-ghost:hover {
        background: var(--bg-tertiary);
        color: var(--text-primary);
    }

    .btn-save {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }

    .btn-save:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(59,130,246,.4);
    }

    .btn-danger-ghost {
        background: transparent;
        color: #f87171;
        border: 1px solid rgba(239,68,68,.25);
    }

    .btn-danger-ghost:hover {
        background: rgba(239,68,68,.08);
        border-color: rgba(239,68,68,.45);
    }

    /* ── Sidebar ── */
    .sidebar-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
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

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row:first-child { padding-top: 0; }

    .info-row-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: var(--text-tertiary);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .info-row-content { flex: 1; min-width: 0; }

    .info-row-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        letter-spacing: 0.06em;
        font-weight: 700;
    }

    .info-row-value {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-top: 0.08rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .info-row-value.muted { color: var(--text-tertiary); font-weight: 400; }

    /* Progress mini in sidebar */
    .mini-progress {
        margin-top: 0.5rem;
    }

    .mini-progress-bar {
        height: 5px;
        border-radius: 999px;
        background: var(--bg-elevated);
        overflow: hidden;
        margin-top: 0.35rem;
    }

    .mini-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent));
    }

    .mini-progress-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.68rem;
    }

    .mini-progress-row .label { color: var(--text-tertiary); }
    .mini-progress-row .val   { font-weight: 700; color: var(--brand-primary); }

    /* Quick link */
    .quick-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.75rem;
        border-radius: 8px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.76rem;
        font-weight: 600;
        transition: all var(--transition-fast);
        margin-top: 0.5rem;
    }

    .quick-link:first-child { margin-top: 0; }

    .quick-link:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: rgba(59,130,246,.3);
    }

    .quick-link i { font-size: 0.78rem; width: 14px; text-align: center; }

    /* ── Priority badge ── */
    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.18rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
    }

    .priority-low      { background: rgba(16,185,129,.12); color: #34d399; }
    .priority-medium   { background: rgba(59,130,246,.12); color: #60a5fa; }
    .priority-high     { background: rgba(245,158,11,.12); color: #fbbf24; }
    .priority-critical { background: rgba(239,68,68,.12);  color: #f87171; }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .edit-layout { grid-template-columns: 1fr; }
        .sidebar-card { position: static; }
        .fg-3 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 680px) {
        .fg-2, .fg-3 { grid-template-columns: 1fr; }
    }

    /* ── Searchable Select ── */
    .searchable-select { position: relative; }

    .ss-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: 0.8rem;
        min-height: 38px;
        cursor: pointer;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast), background var(--transition-fast);
        user-select: none;
    }

    .ss-trigger:hover { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .ss-trigger.open  { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }

    .ss-display {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-disabled);
        font-size: 0.77rem;
    }

    .ss-display.has-value { color: var(--text-primary); font-size: 0.8rem; }

    .ss-arrow {
        font-size: 0.65rem;
        color: var(--text-tertiary);
        transition: transform var(--transition-fast);
        flex-shrink: 0;
        margin-left: 0.5rem;
    }

    .ss-trigger.open .ss-arrow { transform: rotate(180deg); }

    .ss-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0; right: 0;
        z-index: 200;
        background: var(--bg-primary);
        border: 1px solid var(--border-medium);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,.18);
        overflow: hidden;
    }

    .ss-dropdown.open { display: block; animation: ssIn 0.15s ease; }

    @keyframes ssIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ss-search-wrap {
        position: relative;
        padding: 0.45rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .ss-search-icon {
        position: absolute;
        left: 0.95rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-tertiary);
        font-size: 0.7rem;
        pointer-events: none;
    }

    .ss-search {
        width: 100%;
        padding: 0.4rem 0.7rem 0.4rem 1.9rem;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.78rem;
        outline: none;
        transition: border-color var(--transition-fast);
        font-family: inherit;
    }

    .ss-search:focus { border-color: var(--brand-primary); }

    .ss-options { max-height: 190px; overflow-y: auto; padding: 0.2rem 0; }

    .ss-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.8rem;
        font-size: 0.8rem;
        color: var(--text-primary);
        cursor: pointer;
        transition: background var(--transition-fast);
    }

    .ss-option:hover   { background: var(--bg-hover); }
    .ss-option.selected { background: rgba(59,130,246,.1); color: var(--brand-primary); font-weight: 600; }
    .ss-option.hidden   { display: none; }

    .ss-option-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: var(--brand-primary);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .ss-no-results {
        padding: 0.65rem 0.8rem;
        font-size: 0.78rem;
        color: var(--text-tertiary);
        text-align: center;
    }

    .ss-trigger.is-invalid { border-color: #f87171; background: rgba(239,68,68,.05); }
    .ss-trigger.is-invalid.open { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
</style>
@endpush

@section('content')

@php
    $statusLabels = [
        'planning'    => 'Planification',
        'in_progress' => 'En cours',
        'review'      => 'En revue',
        'completed'   => 'Terminé',
        'cancelled'   => 'Annulé',
    ];
    $priorityIcons = [
        'low'      => 'fa-arrow-down',
        'medium'   => 'fa-equals',
        'high'     => 'fa-arrow-up',
        'critical' => 'fa-fire',
    ];
    $currentStatus   = $project->status ?? 'planning';
    $currentPriority = $project->priority ?? 'medium';
@endphp

{{-- Page Header --}}
<div class="edit-page-header">
    <a href="{{ route('admin.projects.show', $project) }}" class="back-btn" title="Retour au projet">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="header-meta">
        <h1>{{ $project->name }}</h1>
        <div class="header-sub">
            <span><i class="fas fa-hashtag" style="font-size:.6rem;margin-right:2px"></i>{{ $project->project_number }}</span>
            <span style="color:var(--border-heavy)">·</span>
            <span class="header-status-pill status-{{ $currentStatus }}">
                <i class="fas fa-circle" style="font-size:.4rem"></i>
                {{ $statusLabels[$currentStatus] ?? $currentStatus }}
            </span>
            <span style="color:var(--border-heavy)">·</span>
            <span class="priority-badge priority-{{ $currentPriority }}">
                <i class="fas {{ $priorityIcons[$currentPriority] ?? 'fa-equals' }}" style="font-size:.55rem"></i>
                {{ $priorities[$currentPriority] ?? $currentPriority }}
            </span>
        </div>
    </div>
</div>

{{-- Validation Errors --}}
@if($errors->any())
<div class="alert-errors">
    <div class="alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
    <div>
        <div class="alert-title">{{ $errors->count() }} erreur(s) à corriger</div>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('admin.projects.update', $project) }}" method="POST" id="edit-project-form">
    @csrf
    @method('PUT')

    {{-- Technologies hidden inputs (populated by JS before submit) --}}
    <div id="technologies-hidden-container">
        @php
            $existingTechs = old('technologies', isset($project) && $project->technologies
                ? (is_array($project->technologies) ? $project->technologies : [$project->technologies])
                : []);
        @endphp
        @foreach($existingTechs as $t)
            @if(trim($t))
                <input type="hidden" name="technologies[]" value="{{ trim($t) }}">
            @endif
        @endforeach
    </div>

    <div class="edit-layout">

        {{-- ══════════════ MAIN FORM ══════════════ --}}
        <div>

            {{-- Section 1: Informations générales --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-blue"><i class="fas fa-folder-open"></i></div>
                    <span class="form-card-title">Informations générales</span>
                    <span class="form-card-desc">Identité du projet</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Nom du projet <span class="req">*</span></label>
                            <input type="text" name="name" class="fi {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $project->name) }}"
                                   placeholder="Ex : Refonte site vitrine ACME" required autofocus>
                            @error('name')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Type de projet <span class="req">*</span></label>
                                <select name="type" class="fi {{ $errors->has('type') ? 'is-invalid' : '' }}" required>
                                    <option value="">— Choisir un type —</option>
                                    @foreach($types as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('type', $project->type) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                @error('type')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Priorité <span class="req">*</span></label>
                                <select name="priority" class="fi {{ $errors->has('priority') ? 'is-invalid' : '' }}" required>
                                    <option value="">— Choisir —</option>
                                    @foreach($priorities as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('priority', $project->priority) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                @error('priority')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" class="fi {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Décrivez les objectifs, le périmètre et les livrables attendus…">{{ old('description', $project->description) }}</textarea>
                            @error('description')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 2: Équipe & Client --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-purple"><i class="fas fa-users"></i></div>
                    <span class="form-card-title">Équipe & Client</span>
                    <span class="form-card-desc">Responsabilités</span>
                </div>
                <div class="form-card-body">
                    @php
                        $pmId     = old('project_manager_id', $project->project_manager_id);
                        $clientId = old('client_id', $project->client_id);
                        $selPm     = ($projectManagers ?? collect())->firstWhere('id', $pmId);
                        $selClient = ($clients ?? collect())->firstWhere('id', $clientId);
                    @endphp
                    <div class="fg-2">

                        {{-- Chef de projet --}}
                        <div class="field">
                            <label>Chef de projet <span class="req">*</span></label>
                            <div class="searchable-select" id="pm-select-wrapper">
                                <input type="hidden" name="project_manager_id" id="pm_id_input" value="{{ $pmId }}" required>
                                <div class="ss-trigger {{ $errors->has('project_manager_id') ? 'is-invalid' : '' }}" id="pm-trigger">
                                    <span class="ss-display {{ $selPm ? 'has-value' : '' }}">
                                        {{ $selPm ? $selPm->name : '— Sélectionner —' }}
                                    </span>
                                    <i class="fas fa-chevron-down ss-arrow"></i>
                                </div>
                                <div class="ss-dropdown" id="pm-dropdown">
                                    <div class="ss-search-wrap">
                                        <i class="fas fa-search ss-search-icon"></i>
                                        <input type="text" class="ss-search" placeholder="Rechercher un chef de projet…" autocomplete="off">
                                    </div>
                                    <div class="ss-options">
                                        <div class="ss-option {{ !$pmId ? 'selected' : '' }}" data-value="" data-label="— Sélectionner —">— Sélectionner —</div>
                                        @foreach($projectManagers ?? [] as $pm)
                                            <div class="ss-option {{ $pmId == $pm->id ? 'selected' : '' }}" data-value="{{ $pm->id }}" data-label="{{ $pm->name }}">
                                                <span class="ss-option-avatar">{{ strtoupper(substr($pm->name, 0, 1)) }}</span>
                                                {{ $pm->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if(($projectManagers ?? collect())->isEmpty())
                                <div class="field-hint" style="color:#f87171"><i class="fas fa-exclamation-triangle"></i> Aucun chef de projet disponible</div>
                            @else
                                <div class="field-hint">{{ ($projectManagers ?? collect())->count() }} chef(s) disponible(s)</div>
                            @endif
                            @error('project_manager_id')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        {{-- Client --}}
                        <div class="field">
                            <label>Client</label>
                            <div class="searchable-select" id="client-select-wrapper">
                                <input type="hidden" name="client_id" id="client_id_input" value="{{ $clientId }}">
                                <div class="ss-trigger {{ $errors->has('client_id') ? 'is-invalid' : '' }}" id="client-trigger">
                                    <span class="ss-display {{ $selClient ? 'has-value' : '' }}">
                                        @if($selClient)
                                            {{ $selClient->name }}{{ $selClient->company_name ? ' · '.$selClient->company_name : '' }}
                                        @else
                                            — Aucun client —
                                        @endif
                                    </span>
                                    <i class="fas fa-chevron-down ss-arrow"></i>
                                </div>
                                <div class="ss-dropdown" id="client-dropdown">
                                    <div class="ss-search-wrap">
                                        <i class="fas fa-search ss-search-icon"></i>
                                        <input type="text" class="ss-search" placeholder="Rechercher un client…" autocomplete="off">
                                    </div>
                                    <div class="ss-options">
                                        <div class="ss-option {{ !$clientId ? 'selected' : '' }}" data-value="" data-label="— Aucun client —">— Aucun client —</div>
                                        @foreach($clients ?? [] as $client)
                                            @php $clientLabel = $client->name.($client->company_name ? ' · '.$client->company_name : ''); @endphp
                                            <div class="ss-option {{ $clientId == $client->id ? 'selected' : '' }}" data-value="{{ $client->id }}" data-label="{{ $clientLabel }}">
                                                {{ $clientLabel }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('client_id')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 3: Planning & Statut --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-green"><i class="fas fa-calendar-days"></i></div>
                    <span class="form-card-title">Planning & Statut</span>
                    <span class="form-card-desc">Calendrier et avancement</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="fg-3">
                            <div class="field">
                                <label>Statut <span class="req">*</span></label>
                                <select name="status" class="fi {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                                    @foreach($statuses as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('status', $project->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Date de début</label>
                                <input type="date" name="start_date" class="fi {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                @error('start_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Date de fin estimée</label>
                                <input type="date" name="end_date" class="fi {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Progress --}}
                        <div class="field">
                            <label>Progression du projet</label>
                            <div class="progress-wrap">
                                <div class="progress-label">
                                    <div>
                                        <span class="progress-value" id="progress-display">{{ old('progress_percentage', $project->progress_percentage ?? 0) }}</span>
                                        <span class="progress-pct">%</span>
                                    </div>
                                    <span class="progress-pct" id="progress-state-label">
                                        @php
                                            $pct = old('progress_percentage', $project->progress_percentage ?? 0);
                                        @endphp
                                        @if($pct == 0) Pas démarré
                                        @elseif($pct < 25) Démarrage
                                        @elseif($pct < 50) En cours
                                        @elseif($pct < 75) Avancé
                                        @elseif($pct < 100) Quasi terminé
                                        @else Terminé
                                        @endif
                                    </span>
                                </div>
                                <div style="position:relative">
                                    <div class="progress-track" id="progress-track">
                                        <div class="progress-fill" id="progress-fill"
                                             style="width:{{ old('progress_percentage', $project->progress_percentage ?? 0) }}%"></div>
                                    </div>
                                    <input type="range" name="progress_percentage" id="progress-range"
                                           class="range-fi" min="0" max="100" step="1"
                                           value="{{ old('progress_percentage', $project->progress_percentage ?? 0) }}"
                                           style="position:absolute;top:0;left:0;right:0;bottom:0;opacity:0;width:100%;height:6px">
                                </div>
                            </div>
                            <div class="field-hint">Glissez pour mettre à jour la progression</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 4: Budget & Ressources --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-amber"><i class="fas fa-coins"></i></div>
                    <span class="form-card-title">Budget & Ressources</span>
                    <span class="form-card-desc">Coûts et charge de travail</span>
                </div>
                <div class="form-card-body">
                    <div class="fg-2">

                        <div class="field">
                            <label>Budget estimé (FCFA)</label>
                            <div class="input-icon-wrap">
                                <span class="fi-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" step="1000" name="budget" class="fi {{ $errors->has('budget') ? 'is-invalid' : '' }}"
                                       value="{{ old('budget', $project->budget) }}" placeholder="0" min="0">
                            </div>
                            @error('budget')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Heures estimées</label>
                            <div class="input-icon-wrap">
                                <span class="fi-icon"><i class="fas fa-clock"></i></span>
                                <input type="number" step="0.5" name="estimated_hours" class="fi {{ $errors->has('estimated_hours') ? 'is-invalid' : '' }}"
                                       value="{{ old('estimated_hours', $project->estimated_hours) }}" placeholder="0" min="0">
                            </div>
                            @error('estimated_hours')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 5: Liens & Technologies --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-cyan"><i class="fas fa-code-branch"></i></div>
                    <span class="form-card-title">Liens & Technologies</span>
                    <span class="form-card-desc">Ressources techniques</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="fg-2">
                            <div class="field">
                                <label>URL du Repository</label>
                                <div class="input-icon-wrap">
                                    <span class="fi-icon"><i class="fab fa-github"></i></span>
                                    <input type="url" name="repository_url" class="fi {{ $errors->has('repository_url') ? 'is-invalid' : '' }}"
                                           value="{{ old('repository_url', $project->repository_url) }}"
                                           placeholder="https://github.com/org/repo">
                                </div>
                                @error('repository_url')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>URL de production</label>
                                <div class="input-icon-wrap">
                                    <span class="fi-icon"><i class="fas fa-globe"></i></span>
                                    <input type="url" name="production_url" class="fi {{ $errors->has('production_url') ? 'is-invalid' : '' }}"
                                           value="{{ old('production_url', $project->production_url) }}"
                                           placeholder="https://monsite.com">
                                </div>
                                @error('production_url')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label>Technologies utilisées</label>
                            <div class="tags-input-wrap" id="tags-container" onclick="document.getElementById('tag-input-field').focus()">
                                @php
                                    $techs = old('technologies_arr', isset($project) && $project->technologies
                                        ? (is_array($project->technologies) ? $project->technologies : explode(',', $project->technologies))
                                        : []);
                                    $techs = array_filter(array_map('trim', $techs));
                                @endphp
                                @foreach($techs as $tech)
                                    @if($tech)
                                    <span class="tag-chip" data-tag="{{ $tech }}">
                                        {{ $tech }}
                                        <button type="button" class="remove-tag" onclick="removeTag('{{ addslashes($tech) }}')">&times;</button>
                                    </span>
                                    @endif
                                @endforeach
                                <input type="text" id="tag-input-field"
                                       placeholder="{{ count($techs) ? 'Ajouter…' : 'PHP, Laravel, Vue.js, MySQL…' }}">
                            </div>
                            <div class="field-hint">Tapez un nom et appuyez sur <kbd style="font-size:.6rem;padding:.1rem .3rem;border-radius:4px;background:var(--bg-elevated);border:1px solid var(--border-medium)">Entrée</kbd> ou virgule pour ajouter</div>
                        </div>

                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="action-bar">
                    <div class="left">
                        <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-ghost">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                    </div>
                    <div class="right">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-floppy-disk"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end main --}}

        {{-- ══════════════ SIDEBAR ══════════════ --}}
        <div>

            {{-- Project Summary --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Résumé du projet</div>
                <div class="sidebar-body">

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-hashtag"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Numéro</div>
                            <div class="info-row-value">{{ $project->project_number }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Créé le</div>
                            <div class="info-row-value">{{ $project->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    @if($project->projectManager)
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Chef de projet</div>
                            <div class="info-row-value">{{ $project->projectManager->name }}</div>
                        </div>
                    </div>
                    @endif

                    @if($project->client)
                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-building"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Client</div>
                            <div class="info-row-value">{{ $project->client->name }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-list-check"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Tâches</div>
                            <div class="info-row-value">{{ $project->tasks_count ?? $project->tasks()->count() }} tâche(s)</div>
                        </div>
                    </div>

                    <div class="mini-progress">
                        <div class="mini-progress-row">
                            <span class="label">Progression actuelle</span>
                            <span class="val" id="sidebar-progress-val">{{ $project->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="mini-progress-bar">
                            <div class="mini-progress-fill" id="sidebar-progress-fill"
                                 style="width:{{ $project->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-bolt" style="margin-right:.35rem"></i>Actions rapides</div>
                <div class="sidebar-body">
                    <a href="{{ route('admin.projects.show', $project) }}" class="quick-link">
                        <i class="fas fa-eye"></i> Voir le projet
                    </a>
                    <a href="{{ route('admin.tasks.global-index', ['project_id' => $project->id]) }}" class="quick-link">
                        <i class="fas fa-list-check"></i> Gérer les tâches
                    </a>
                    @if($project->repository_url)
                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener" class="quick-link">
                        <i class="fab fa-github"></i> Ouvrir le repository
                    </a>
                    @endif
                    @if($project->production_url)
                    <a href="{{ $project->production_url }}" target="_blank" rel="noopener" class="quick-link">
                        <i class="fas fa-arrow-up-right-from-square"></i> Voir en production
                    </a>
                    @endif
                </div>
            </div>

        </div>{{-- end sidebar --}}

    </div>{{-- end edit-layout --}}
</form>

@endsection

@push('scripts')
<script>
/* ── Searchable Select ── */
(function () {
    function initSS(wrapperId, inputId) {
        const wrapper = document.getElementById(wrapperId);
        if (!wrapper) return;

        const hidden  = document.getElementById(inputId);
        const trigger = wrapper.querySelector('.ss-trigger');
        const display = wrapper.querySelector('.ss-display');
        const dropdown= wrapper.querySelector('.ss-dropdown');
        const search  = wrapper.querySelector('.ss-search');
        const options = wrapper.querySelectorAll('.ss-option');

        function open()  { trigger.classList.add('open'); dropdown.classList.add('open'); search?.focus(); }
        function close() {
            trigger.classList.remove('open'); dropdown.classList.remove('open');
            if (search) { search.value = ''; filter(''); }
        }

        function filter(q) {
            const lq = q.toLowerCase().trim();
            let vis = 0;
            options.forEach(o => {
                const show = !lq || o.textContent.toLowerCase().includes(lq);
                o.classList.toggle('hidden', !show);
                if (show) vis++;
            });
            let nr = wrapper.querySelector('.ss-no-results');
            if (vis === 0) {
                if (!nr) { nr = document.createElement('div'); nr.className = 'ss-no-results'; nr.textContent = 'Aucun résultat'; wrapper.querySelector('.ss-options').appendChild(nr); }
                nr.style.display = 'block';
            } else if (nr) { nr.style.display = 'none'; }
        }

        trigger.addEventListener('click', () => dropdown.classList.contains('open') ? close() : open());
        search?.addEventListener('input', e => filter(e.target.value));

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                hidden.value = opt.dataset.value;
                display.textContent = opt.dataset.label ?? opt.textContent.trim();
                display.classList.toggle('has-value', !!opt.dataset.value);
                close();
            });
        });

        document.addEventListener('click', e => { if (!wrapper.contains(e.target)) close(); });
    }

    initSS('pm-select-wrapper',     'pm_id_input');
    initSS('client-select-wrapper', 'client_id_input');
})();

(function () {
    /* ── Progress range ── */
    const rangeInput    = document.getElementById('progress-range');
    const progressFill  = document.getElementById('progress-fill');
    const progressDisp  = document.getElementById('progress-display');
    const sidebarVal    = document.getElementById('sidebar-progress-val');
    const sidebarFill   = document.getElementById('sidebar-progress-fill');
    const stateLabel    = document.getElementById('progress-state-label');

    const stateLabels = [
        [0,   'Pas démarré'],
        [1,   'Démarrage'],
        [25,  'En cours'],
        [50,  'Avancé'],
        [75,  'Quasi terminé'],
        [100, 'Terminé'],
    ];

    function getStateLabel(v) {
        if (v === 0)   return 'Pas démarré';
        if (v < 25)    return 'Démarrage';
        if (v < 50)    return 'En cours';
        if (v < 75)    return 'Avancé';
        if (v < 100)   return 'Quasi terminé';
        return 'Terminé';
    }

    rangeInput.addEventListener('input', function () {
        const v = this.value;
        progressFill.style.width  = v + '%';
        progressDisp.textContent  = v;
        sidebarVal.textContent    = v + '%';
        sidebarFill.style.width   = v + '%';
        stateLabel.textContent    = getStateLabel(parseInt(v));
    });

    /* ── Technology tags ── */
    const tagInput        = document.getElementById('tag-input-field');
    const tagsContainer   = document.getElementById('tags-container');
    const hiddenContainer = document.getElementById('technologies-hidden-container');

    function getTags() {
        return Array.from(tagsContainer.querySelectorAll('.tag-chip'))
                    .map(el => el.dataset.tag);
    }

    function syncHidden() {
        hiddenContainer.innerHTML = '';
        getTags().forEach(t => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'technologies[]';
            inp.value = t;
            hiddenContainer.appendChild(inp);
        });
        tagInput.placeholder = getTags().length ? 'Ajouter…' : 'PHP, Laravel, Vue.js, MySQL…';
    }

    function addTag(raw) {
        const name = raw.trim().replace(/,+$/, '').trim();
        if (!name) return;
        if (getTags().map(t => t.toLowerCase()).includes(name.toLowerCase())) return;

        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.dataset.tag = name;
        chip.innerHTML = `${name} <button type="button" class="remove-tag" onclick="removeTag('${name.replace(/'/g,"\\'")}')">×</button>`;
        tagsContainer.insertBefore(chip, tagInput);
        syncHidden();
    }

    window.removeTag = function (name) {
        const chip = tagsContainer.querySelector(`.tag-chip[data-tag="${CSS.escape(name)}"]`);
        if (chip) { chip.remove(); syncHidden(); }
    };

    tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addTag(this.value); this.value = ''; }
        if (e.key === 'Backspace' && !this.value) {
            const chips = tagsContainer.querySelectorAll('.tag-chip');
            if (chips.length) chips[chips.length - 1].remove();
            syncHidden();
        }
    });

    tagInput.addEventListener('input', function () {
        if (this.value.endsWith(',')) { addTag(this.value); this.value = ''; }
    });

    tagInput.addEventListener('blur', function () {
        if (this.value) { addTag(this.value); this.value = ''; }
    });
})();
</script>
@endpush
