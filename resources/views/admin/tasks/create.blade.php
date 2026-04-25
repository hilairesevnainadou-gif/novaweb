{{-- resources/views/admin/tasks/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle tâche · NovaTech Admin')
@section('page-title', 'Nouvelle tâche')

@push('styles')
<style>
    /* ── Layout ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 288px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Page header ── */
    .page-header {
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
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }

    .back-btn:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: rgba(59,130,246,.3);
    }

    .page-header h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.25;
    }

    .page-header .header-sub {
        font-size: 0.72rem;
        color: var(--text-tertiary);
        margin-top: 0.18rem;
    }

    /* ── Section cards ── */
    .form-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
    }

    .form-card:last-child { margin-bottom: 0; }

    /* La carte qui contient le sélecteur de projet doit autoriser le débordement
       du menu déroulant (sinon la liste des projets est coupée par overflow:hidden). */
    .form-card.allow-overflow {
        overflow: visible;
        position: relative;
        z-index: 5;
    }
    .form-card.allow-overflow.picker-active {
        z-index: 50;
    }

    .form-card-header {
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
        font-size: 0.88rem;
        flex-shrink: 0;
    }

    .icon-blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .icon-purple { background: rgba(139,92,246,.15);  color: #a78bfa; }
    .icon-green  { background: rgba(16,185,129,.15);  color: #34d399; }
    .icon-amber  { background: rgba(245,158,11,.15);  color: #fbbf24; }

    .card-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .card-sub {
        font-size: 0.67rem;
        color: var(--text-tertiary);
        margin-left: auto;
    }

    .form-card-body { padding: 1.1rem; }

    /* ── Grid helpers ── */
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

    .field label .req { color: #f87171; margin-left: 0.2rem; }
    .field-hint { font-size: 0.63rem; color: var(--text-disabled); line-height: 1.3; }

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

    .fi:hover  { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .fi:focus  { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi::placeholder { color: var(--text-disabled); font-size: 0.77rem; }

    textarea.fi { resize: vertical; min-height: 84px; max-height: 160px; line-height: 1.5; }
    select.fi   { cursor: pointer; }

    .fi.is-invalid { border-color: #f87171; background: rgba(239,68,68,.05); }
    .fi.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

    .invalid-msg { font-size: 0.65rem; color: #f87171; }

    /* ── Input icon prefix ── */
    .iw { position: relative; }
    .iw .fi { padding-left: 2.2rem; }
    .iw .ii {
        position: absolute; left: 0.72rem; top: 50%;
        transform: translateY(-50%);
        color: var(--text-disabled); font-size: 0.78rem; pointer-events: none;
    }

    /* ── Priority selector ── */
    .priority-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .priority-opt { display: none; }

    .priority-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        padding: 0.6rem 0.4rem;
        border-radius: 9px;
        border: 1.5px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-tertiary);
        text-align: center;
    }

    .priority-label:hover { border-color: var(--border-heavy); color: var(--text-secondary); }

    .priority-label .pico {
        font-size: 1rem;
        line-height: 1;
    }

    .priority-opt:checked + .priority-label {
        border-width: 2px;
    }

    .priority-opt[value="low"]:checked    + .priority-label { border-color: #34d399; background: rgba(16,185,129,.1);  color: #34d399; }
    .priority-opt[value="medium"]:checked + .priority-label { border-color: #60a5fa; background: rgba(59,130,246,.1);  color: #60a5fa; }
    .priority-opt[value="high"]:checked   + .priority-label { border-color: #fbbf24; background: rgba(245,158,11,.1);  color: #fbbf24; }
    .priority-opt[value="urgent"]:checked + .priority-label { border-color: #f87171; background: rgba(239,68,68,.1);   color: #f87171; }

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

    .alert-errors .ai { font-size: 1rem; color: #f87171; flex-shrink: 0; margin-top: 0.05rem; }
    .alert-errors .at { font-size: 0.78rem; font-weight: 700; color: #f87171; margin-bottom: 0.3rem; }
    .alert-errors ul  { margin: 0; padding-left: 1.1rem; font-size: 0.78rem; color: #fca5a5; line-height: 1.6; }

    /* ── Action bar ── */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.9rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: rgba(255,255,255,.015);
    }

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

    .btn-ghost:hover { background: var(--bg-tertiary); color: var(--text-primary); }

    .btn-create {
        background: var(--brand-success);
        color: #fff;
        box-shadow: 0 2px 8px rgba(16,185,129,.3);
    }

    .btn-create:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(16,185,129,.4);
    }

    .btn-create:disabled { opacity: .65; transform: none; cursor: not-allowed; }

    /* ─────────────────────────────────────────────
       PROJECT PICKER
    ───────────────────────────────────────────── */
    .project-picker-wrap { position: relative; }

    .picker-trigger {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.8rem;
        border-radius: 9px;
        border: 1.5px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        transition: all var(--transition-fast);
        min-height: 42px;
        user-select: none;
    }

    .picker-trigger:hover   { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .picker-trigger.open    { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); border-radius: 9px 9px 0 0; }
    .picker-trigger.invalid { border-color: #f87171; }

    .picker-placeholder { font-size: 0.78rem; color: var(--text-disabled); flex: 1; }

    .picker-selected-info {
        display: none;
        flex: 1;
        min-width: 0;
    }

    .picker-selected-info.visible { display: flex; align-items: center; gap: 0.55rem; }

    .picker-sel-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .picker-sel-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .picker-sel-num {
        font-size: 0.68rem;
        color: var(--text-tertiary);
        white-space: nowrap;
    }

    .picker-chevron {
        font-size: 0.72rem;
        color: var(--text-tertiary);
        transition: transform var(--transition-fast);
        flex-shrink: 0;
        margin-left: auto;
    }

    .picker-trigger.open .picker-chevron { transform: rotate(180deg); }

    /* Dropdown */
    .picker-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: var(--bg-elevated);
        border: 1.5px solid var(--brand-primary);
        border-top: none;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 12px 32px rgba(0,0,0,.45);
        overflow: hidden;
    }

    .picker-dropdown.open { display: block; }

    .picker-search-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .picker-search-wrap i { font-size: 0.78rem; color: var(--text-tertiary); flex-shrink: 0; }

    #pickerSearchInput {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: var(--text-primary);
        font-size: 0.82rem;
        font-family: inherit;
    }

    #pickerSearchInput::placeholder { color: var(--text-disabled); }

    .picker-filter-bar {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.75rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,.015);
        flex-wrap: wrap;
    }

    .picker-filter-lbl { font-size: 0.62rem; color: var(--text-tertiary); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.67rem;
        font-weight: 700;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-tertiary);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .filter-chip:hover   { border-color: var(--border-heavy); color: var(--text-secondary); }
    .filter-chip.active  { background: rgba(59,130,246,.15); border-color: rgba(59,130,246,.4); color: #60a5fa; }

    .picker-list {
        max-height: 240px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .picker-list::-webkit-scrollbar { width: 4px; }
    .picker-list::-webkit-scrollbar-track { background: transparent; }
    .picker-list::-webkit-scrollbar-thumb { background: var(--border-medium); border-radius: 999px; }

    .picker-item {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.6rem 0.85rem;
        cursor: pointer;
        transition: background var(--transition-fast);
        border-bottom: 1px solid rgba(255,255,255,.03);
    }

    .picker-item:last-child { border-bottom: none; }
    .picker-item:hover { background: rgba(59,130,246,.07); }
    .picker-item.selected { background: rgba(59,130,246,.12); }

    .picker-item-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .picker-item-main { flex: 1; min-width: 0; }

    .picker-item-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .picker-item-meta {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.12rem;
    }

    .picker-item-num {
        font-size: 0.65rem;
        color: var(--text-tertiary);
    }

    .picker-item-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.1rem 0.38rem;
        border-radius: 4px;
        font-size: 0.6rem;
        font-weight: 700;
    }

    .picker-item-progress {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex-shrink: 0;
    }

    .mini-bar {
        width: 40px;
        height: 4px;
        border-radius: 999px;
        background: var(--bg-secondary);
        overflow: hidden;
    }

    .mini-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent));
    }

    .mini-pct {
        font-size: 0.62rem;
        color: var(--text-tertiary);
        font-weight: 700;
        width: 26px;
        text-align: right;
    }

    .picker-empty {
        padding: 1.25rem;
        text-align: center;
        font-size: 0.78rem;
        color: var(--text-tertiary);
    }

    /* Status colors */
    .dot-planning    { background: #94a3b8; }
    .dot-in_progress { background: #60a5fa; }
    .dot-review      { background: #fbbf24; }
    .dot-completed   { background: #34d399; }
    .dot-cancelled   { background: #f87171; }

    .badge-planning    { background: rgba(100,116,139,.18); color: #94a3b8; }
    .badge-in_progress { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .badge-review      { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .badge-completed   { background: rgba(16,185,129,.15);  color: #34d399; }
    .badge-cancelled   { background: rgba(239,68,68,.15);   color: #f87171; }

    /* ── Selected project preview card ── */
    #projectPreview {
        display: none;
        margin-top: 0.7rem;
        padding: 0.8rem;
        border-radius: 10px;
        border: 1px solid var(--border-light);
        background: rgba(59,130,246,.04);
        gap: 1rem;
    }

    #projectPreview.visible { display: flex; }

    .preview-col { display: flex; flex-direction: column; gap: 0.2rem; flex: 1; }

    .preview-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: .06em; color: var(--text-tertiary); font-weight: 700; }

    .preview-val { font-size: 0.78rem; font-weight: 600; color: var(--text-primary); }
    .preview-val.muted { font-weight: 400; color: var(--text-tertiary); }

    .preview-progress-bar {
        height: 4px;
        border-radius: 999px;
        background: var(--bg-elevated);
        overflow: hidden;
        margin-top: 0.3rem;
    }

    .preview-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent));
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
        padding: 0.72rem 1rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--text-tertiary);
        background: rgba(255,255,255,.02);
    }

    .sidebar-body { padding: 0.85rem 1rem; }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        padding: 0.55rem 0;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.73rem;
        color: var(--text-tertiary);
        line-height: 1.5;
    }

    .tip-item:last-child  { border-bottom: none; padding-bottom: 0; }
    .tip-item:first-child { padding-top: 0; }

    .tip-item i {
        font-size: 0.82rem;
        color: var(--brand-primary);
        flex-shrink: 0;
        line-height: 1.5;          /* même line-height que le texte */
        margin-top: 1px;           /* micro-ajustement optique */
        width: 14px;
        text-align: center;
    }

    .tip-item > span,
    .tip-item-text {
        flex: 1;
        min-width: 0;
        word-wrap: break-word;
    }

    .tip-item strong { color: var(--text-secondary); font-weight: 700; }
    .tip-item em     { color: var(--text-secondary); font-style: italic; }

    .field-req-list { display: flex; flex-direction: column; gap: 0.3rem; margin-top: 0.2rem; }

    .req-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        color: var(--text-tertiary);
    }

    .req-row i { font-size: 0.62rem; width: 12px; text-align: center; }
    .req-row.done { color: var(--brand-success); }
    .req-row.done i { color: var(--brand-success); }

    /* ── Responsive ── */
    @media (max-width: 1050px) {
        .create-layout { grid-template-columns: 1fr; }
        .sidebar-card  { position: static; }
        .fg-3 { grid-template-columns: repeat(2, 1fr); }
        .priority-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 620px) {
        .fg-2, .fg-3 { grid-template-columns: 1fr; }
        .priority-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')

@php
    $hasProject   = isset($project) && $project !== null;
    $isEdit       = isset($task);
    $authUserId   = auth()->id();
    $canAssignAll = auth()->user()->can('tasks.view.all');
    $formAction   = $isEdit
        ? route('admin.tasks.update', $task)
        : ($hasProject ? route('admin.projects.tasks.store', $project) : route('admin.tasks.store'));

    $statusLabels = [
        'planning'    => 'Planification',
        'in_progress' => 'En cours',
        'review'      => 'En revue',
        'completed'   => 'Terminé',
        'cancelled'   => 'Annulé',
    ];

    $projectsJson = $projects->map(fn($p) => [
        'id'               => $p->id,
        'name'             => $p->name,
        'project_number'   => $p->project_number,
        'status'           => $p->status,
        'status_label'     => $statusLabels[$p->status] ?? $p->status,
        'type'             => $p->type,
        'progress'         => $p->progress_percentage ?? 0,
        'client'           => $p->client?->name,
        'manager'          => $p->projectManager?->name,
        'parent_url'       => route('admin.projects.tasks.parent-options', $p->id),
    ]);
@endphp

{{-- Page header --}}
<div class="page-header">
    <a href="{{ $hasProject ? route('admin.projects.tasks.index', $project) : route('admin.tasks.global-index') }}"
       class="back-btn" title="Retour">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1>{{ $isEdit ? 'Modifier la tâche' : 'Nouvelle tâche' }}</h1>
        <div class="header-sub">
            @if($hasProject)
                Rattachée au projet <strong style="color:var(--text-secondary)">{{ $project->name }}</strong>
            @else
                Choisissez un projet puis renseignez les détails
            @endif
        </div>
    </div>
</div>

{{-- Validation errors --}}
@if($errors->any())
<div class="alert-errors">
    <div class="ai"><i class="fas fa-triangle-exclamation"></i></div>
    <div>
        <div class="at">{{ $errors->count() }} erreur(s) à corriger</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
</div>
@endif

<form method="POST" action="{{ $formAction }}" id="taskForm" novalidate>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="create-layout">

        {{-- ══════════════ MAIN FORM ══════════════ --}}
        <div>

            {{-- Section 1 : Projet (seulement si pas de projet fixe) --}}
            @if(!$hasProject && !$isEdit)
            <div class="form-card allow-overflow" id="projectSection">
                <div class="form-card-header">
                    <div class="card-icon icon-blue"><i class="fas fa-folder-open"></i></div>
                    <span class="card-title">Projet rattaché</span>
                    @if($projects->isEmpty())
                        <span class="card-sub" style="color:#f87171;">Aucun projet disponible</span>
                    @else
                        <span class="card-sub">{{ $projects->count() }} projet(s) disponible(s)</span>
                    @endif
                </div>
                <div class="form-card-body" style="overflow: visible;">

                    @if($projects->isEmpty())
                    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.85rem 1rem;background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.25);border-radius:10px;margin-bottom:.75rem;">
                        <i class="fas fa-triangle-exclamation" style="color:#fbbf24;margin-top:.1rem;flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:.8rem;font-weight:700;color:#fbbf24;margin-bottom:.2rem;">Aucun projet accessible</div>
                            <div style="font-size:.73rem;color:var(--text-tertiary);line-height:1.5;">
                                Vous n'êtes chef de projet ou intervenant sur aucun projet actif.
                                Demandez à votre responsable de vous assigner à un projet.
                            </div>
                        </div>
                    </div>
                    @endif

                    <input type="hidden" name="project_id" id="projectIdHidden" value="{{ old('project_id') }}">

                    <div class="field" @if($projects->isEmpty()) style="opacity:.45;pointer-events:none;" @endif>
                        <label>Projet <span class="req">*</span></label>

                        {{-- Picker trigger --}}
                        <div class="project-picker-wrap" id="projectPickerWrap">
                            <div class="picker-trigger {{ $errors->has('project_id') ? 'invalid' : '' }}"
                                 id="pickerTrigger" tabindex="{{ $projects->isEmpty() ? '-1' : '0' }}" role="combobox" aria-expanded="false">
                                <div class="picker-placeholder" id="pickerPlaceholder">
                                    <i class="fas fa-search" style="font-size:.75rem;margin-right:.35rem;color:var(--text-disabled)"></i>
                                    Cliquez pour rechercher un projet…
                                </div>
                                <div class="picker-selected-info" id="pickerSelectedInfo">
                                    <div class="picker-sel-dot" id="pickerSelDot"></div>
                                    <span class="picker-sel-name" id="pickerSelName"></span>
                                    <span class="picker-sel-num" id="pickerSelNum"></span>
                                </div>
                                <i class="fas fa-chevron-down picker-chevron" id="pickerChevron"></i>
                            </div>

                            {{-- Dropdown --}}
                            <div class="picker-dropdown" id="pickerDropdown">
                                <div class="picker-search-wrap">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="pickerSearchInput"
                                           placeholder="Nom du projet, numéro…" autocomplete="off">
                                </div>
                                <div class="picker-filter-bar">
                                    <span class="picker-filter-lbl">Statut :</span>
                                    <span class="filter-chip active" data-filter="all">Tous</span>
                                    <span class="filter-chip" data-filter="in_progress">En cours</span>
                                    <span class="filter-chip" data-filter="planning">Planification</span>
                                    <span class="filter-chip" data-filter="review">En revue</span>
                                </div>
                                <div class="picker-list" id="pickerList"></div>
                            </div>
                        </div>

                        {{-- Project preview card --}}
                        <div id="projectPreview">
                            <div class="preview-col">
                                <div class="preview-label">Statut</div>
                                <div class="preview-val" id="pvStatus">—</div>
                            </div>
                            <div class="preview-col">
                                <div class="preview-label">Type</div>
                                <div class="preview-val" id="pvType">—</div>
                            </div>
                            <div class="preview-col">
                                <div class="preview-label">Client</div>
                                <div class="preview-val" id="pvClient">—</div>
                            </div>
                            <div class="preview-col" style="flex:1.5">
                                <div class="preview-label">Progression</div>
                                <div class="preview-val" id="pvPct">0%</div>
                                <div class="preview-progress-bar">
                                    <div class="preview-progress-fill" id="pvBar" style="width:0%"></div>
                                </div>
                            </div>
                        </div>

                        @error('project_id')
                            <div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @elseif($hasProject && !$isEdit)
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            @endif

            {{-- Section 2 : Informations de la tâche --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-purple"><i class="fas fa-clipboard-list"></i></div>
                    <span class="card-title">Informations de la tâche</span>
                    <span class="card-sub">Description & type</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Titre <span class="req">*</span></label>
                            <input type="text" name="title" class="fi {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title', $task->title ?? '') }}"
                                   placeholder="Ex : Développer l'API de connexion"
                                   required autofocus>
                            @error('title')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Type de tâche <span class="req">*</span></label>
                                <select name="task_type" id="taskTypeSelect"
                                        class="fi {{ $errors->has('task_type') ? 'is-invalid' : '' }}" required>
                                    <option value="">— Choisir —</option>
                                    @foreach($taskTypes as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('task_type', $task->task_type ?? '') == $val ? 'selected' : '' }}>
                                            {{ $lbl }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('task_type')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Tâche parente</label>
                                <select name="parent_id" id="parentTaskSelect" class="fi">
                                    <option value="">— Aucune (tâche principale) —</option>
                                    @foreach($parentTasks ?? [] as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $task->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint" id="parentHint">
                                    @if(!$hasProject && !$isEdit)
                                        Chargé automatiquement après sélection du projet
                                    @else
                                        Laissez vide si c'est une tâche principale
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" class="fi {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Décrivez les objectifs, les livrables et les critères de réussite…">{{ old('description', $task->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 3 : Priorité --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-amber"><i class="fas fa-flag"></i></div>
                    <span class="card-title">Priorité</span>
                    <span class="card-sub required" style="color:#f87171;font-size:.67rem;font-weight:700">Obligatoire</span>
                </div>
                <div class="form-card-body">
                    <div class="priority-grid">
                        @foreach(['low' => ['Basse','fa-arrow-down','#34d399'], 'medium' => ['Moyenne','fa-equals','#60a5fa'], 'high' => ['Haute','fa-arrow-up','#fbbf24'], 'urgent' => ['Urgente','fa-fire','#f87171']] as $val => [$lbl,$ico,$clr])
                        <div>
                            <input type="radio" name="priority" value="{{ $val }}" id="p_{{ $val }}"
                                   class="priority-opt"
                                   {{ old('priority', $task->priority ?? '') == $val ? 'checked' : '' }}>
                            <label for="p_{{ $val }}" class="priority-label">
                                <span class="pico" style="color:{{ $clr }}"><i class="fas {{ $ico }}"></i></span>
                                {{ $lbl }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('priority')<div class="invalid-msg" style="margin-top:.4rem"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Section 4 : Planning & Assignation --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-green"><i class="fas fa-calendar-days"></i></div>
                    <span class="card-title">Planning & Assignation</span>
                    <span class="card-sub">Qui, quand, combien</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="fg-2">
                            <div class="field">
                                <label>Assignée à</label>
                                <select name="assigned_to" class="fi {{ $errors->has('assigned_to') ? 'is-invalid' : '' }}">
                                    <option value="">— Non assignée —</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('assigned_to', $task->assigned_to ?? ($canAssignAll ? '' : $authUserId)) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                            @if($u->id === $authUserId) (moi) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$canAssignAll)
                                <div class="field-hint">Vous êtes pré-sélectionné — modifiez si nécessaire.</div>
                                @endif
                                @error('assigned_to')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Heures estimées</label>
                                <div class="iw">
                                    <span class="ii"><i class="fas fa-clock"></i></span>
                                    <input type="number" step="0.5" min="0" name="estimated_hours"
                                           class="fi {{ $errors->has('estimated_hours') ? 'is-invalid' : '' }}"
                                           value="{{ old('estimated_hours', $task->estimated_hours ?? '') }}"
                                           placeholder="0">
                                </div>
                                @error('estimated_hours')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Date de début</label>
                                <input type="datetime-local" name="start_date"
                                       class="fi {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('start_date', isset($task) && $task->start_date ? $task->start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('start_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Date d'échéance</label>
                                <input type="datetime-local" name="due_date"
                                       class="fi {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}">
                                @error('due_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="action-bar">
                    <a href="{{ $hasProject ? route('admin.projects.tasks.index', $project) : route('admin.tasks.global-index') }}"
                       class="btn btn-ghost">
                        <i class="fas fa-xmark"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-create" id="submitBtn"
                        @if(!$hasProject && !$isEdit && $projects->isEmpty()) disabled title="Aucun projet accessible" @endif>
                        <i class="fas fa-circle-plus"></i> Créer la tâche
                    </button>
                </div>
            </div>

        </div>{{-- end main --}}

        {{-- ══════════════ SIDEBAR ══════════════ --}}
        <div>

            {{-- Checklist de validation --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-circle-check" style="margin-right:.35rem"></i>Champs requis</div>
                <div class="sidebar-body">
                    <div class="field-req-list" id="reqList">
                        @if(!$hasProject && !$isEdit)
                        <div class="req-row" id="req-project">
                            <i class="fas fa-circle" style="font-size:.4rem;color:var(--text-disabled)"></i>
                            Projet sélectionné
                        </div>
                        @endif
                        <div class="req-row" id="req-title">
                            <i class="fas fa-circle" style="font-size:.4rem;color:var(--text-disabled)"></i>
                            Titre de la tâche
                        </div>
                        <div class="req-row" id="req-type">
                            <i class="fas fa-circle" style="font-size:.4rem;color:var(--text-disabled)"></i>
                            Type de tâche
                        </div>
                        <div class="req-row" id="req-priority">
                            <i class="fas fa-circle" style="font-size:.4rem;color:var(--text-disabled)"></i>
                            Priorité
                        </div>
                    </div>
                </div>
            </div>

            {{-- Conseils --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-lightbulb" style="margin-right:.35rem"></i>Conseils</div>
                <div class="sidebar-body">
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Utilisez un titre <strong>actionnable</strong> : commencez par un verbe (<em>Développer, Corriger, Rédiger…</em>).</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">La <strong>priorité "Urgente"</strong> doit rester rare — réservez-la aux blocages critiques.</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Assignez une date d'échéance réaliste pour faciliter le suivi dans le kanban.</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Liez les sous-tâches via <strong>Tâche parente</strong> pour hiérarchiser le travail.</span>
                    </div>
                </div>
            </div>

        </div>{{-- end sidebar --}}

    </div>{{-- end layout --}}
</form>

@endsection

@push('scripts')
<script>
(function () {
    /* ───────────────────────────────────────
       DATA
    ─────────────────────────────────────── */
    const projects    = @json($projectsJson ?? []);
    const hasProject  = @json($hasProject);
    const isEdit      = @json($isEdit);

    const typeLabels = {
        web:      'Site Web / App Web',
        mobile:   'App Mobile',
        software: 'Logiciel Desktop',
        other:    'Autre',
    };

    /* ───────────────────────────────────────
       LIVE CHECKLIST
    ─────────────────────────────────────── */
    function updateChecklist() {
        const title    = document.querySelector('input[name="title"]')?.value.trim();
        const type     = document.querySelector('select[name="task_type"]')?.value;
        const priority = document.querySelector('input[name="priority"]:checked');
        const projId   = document.getElementById('projectIdHidden')?.value;

        const mark = (id, done) => {
            const row = document.getElementById(id);
            if (!row) return;
            row.classList.toggle('done', done);
            const icon = row.querySelector('i');
            if (icon) {
                icon.className = done
                    ? 'fas fa-check-circle'
                    : 'fas fa-circle';
                icon.style.fontSize = done ? '.78rem' : '.4rem';
                icon.style.color    = done ? '' : 'var(--text-disabled)';
            }
        };

        if (!hasProject && !isEdit) mark('req-project', !!projId);
        mark('req-title',    !!title);
        mark('req-type',     !!type);
        mark('req-priority', !!priority);
    }

    /* ───────────────────────────────────────
       FORM VALIDATION ON SUBMIT
    ─────────────────────────────────────── */
    const form      = document.getElementById('taskForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function (e) {
            const title    = document.querySelector('input[name="title"]')?.value.trim();
            const type     = document.querySelector('select[name="task_type"]')?.value;
            const priority = document.querySelector('input[name="priority"]:checked');
            const projId   = document.getElementById('projectIdHidden')?.value;

            const errors = [];
            if (!hasProject && !isEdit && !projId) errors.push('Veuillez sélectionner un projet.');
            if (!title)    errors.push('Le titre de la tâche est obligatoire.');
            if (!type)     errors.push('Le type de tâche est obligatoire.');
            if (!priority) errors.push('La priorité est obligatoire.');

            if (errors.length) {
                e.preventDefault();
                alert(errors.join('\n'));
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création…';
        });
    }

    /* ───────────────────────────────────────
       WATCH INPUTS FOR CHECKLIST
    ─────────────────────────────────────── */
    document.querySelectorAll('#taskForm input, #taskForm select').forEach(el => {
        el.addEventListener('change', updateChecklist);
        el.addEventListener('input',  updateChecklist);
    });

    updateChecklist();

    /* ───────────────────────────────────────
       PROJECT PICKER
    ─────────────────────────────────────── */
    if (hasProject || isEdit) return; // no picker needed

    const trigger        = document.getElementById('pickerTrigger');
    const dropdown       = document.getElementById('pickerDropdown');
    const searchInput    = document.getElementById('pickerSearchInput');
    const pickerList     = document.getElementById('pickerList');
    const hiddenInput    = document.getElementById('projectIdHidden');
    const placeholder    = document.getElementById('pickerPlaceholder');
    const selectedInfo   = document.getElementById('pickerSelectedInfo');
    const selDot         = document.getElementById('pickerSelDot');
    const selName        = document.getElementById('pickerSelName');
    const selNum         = document.getElementById('pickerSelNum');
    const preview        = document.getElementById('projectPreview');
    const filterChips    = document.querySelectorAll('.filter-chip');

    let activeFilter = 'all';
    let selectedId   = null;

    // Pre-select if old() value exists
    const oldProjectId = '{{ old('project_id') }}';
    if (oldProjectId) {
        const found = projects.find(p => String(p.id) === String(oldProjectId));
        if (found) setTimeout(() => selectProject(found), 50);
    }

    /* Open / close */
    const projectSection = document.getElementById('projectSection');

    function openPicker() {
        trigger.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
        dropdown.classList.add('open');
        if (projectSection) projectSection.classList.add('picker-active');
        searchInput.value = '';
        renderList('');
        setTimeout(() => searchInput.focus(), 60);
    }

    function closePicker() {
        trigger.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
        dropdown.classList.remove('open');
        if (projectSection) projectSection.classList.remove('picker-active');
    }

    trigger.addEventListener('click', () => {
        dropdown.classList.contains('open') ? closePicker() : openPicker();
    });

    trigger.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openPicker(); }
        if (e.key === 'Escape') closePicker();
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('projectPickerWrap').contains(e.target)) closePicker();
    });

    /* Filter chips */
    filterChips.forEach(chip => {
        chip.addEventListener('click', () => {
            filterChips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            activeFilter = chip.dataset.filter;
            renderList(searchInput.value);
        });
    });

    /* Search */
    searchInput.addEventListener('input', () => renderList(searchInput.value));

    /* Render list */
    function renderList(query) {
        const q = query.toLowerCase().trim();

        const filtered = projects.filter(p => {
            const matchSearch = !q
                || p.name.toLowerCase().includes(q)
                || p.project_number.toLowerCase().includes(q)
                || (p.client && p.client.toLowerCase().includes(q))
                || (p.manager && p.manager.toLowerCase().includes(q));

            const matchFilter = activeFilter === 'all' || p.status === activeFilter;

            return matchSearch && matchFilter;
        });

        if (!filtered.length) {
            pickerList.innerHTML = `<div class="picker-empty"><i class="fas fa-folder-open" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.3"></i>Aucun projet ne correspond</div>`;
            return;
        }

        pickerList.innerHTML = filtered.map(p => {
            const isSelected = String(p.id) === String(selectedId);
            return `
            <div class="picker-item ${isSelected ? 'selected' : ''}" data-id="${p.id}">
                <div class="picker-item-dot dot-${p.status}"></div>
                <div class="picker-item-main">
                    <div class="picker-item-name">${esc(p.name)}</div>
                    <div class="picker-item-meta">
                        <span class="picker-item-num">${esc(p.project_number)}</span>
                        <span class="picker-item-badge badge-${p.status}">${esc(p.status_label)}</span>
                        ${p.client ? `<span class="picker-item-badge" style="background:rgba(255,255,255,.06);color:var(--text-tertiary)">${esc(p.client)}</span>` : ''}
                    </div>
                </div>
                <div class="picker-item-progress">
                    <div class="mini-bar"><div class="mini-bar-fill" style="width:${p.progress}%"></div></div>
                    <span class="mini-pct">${p.progress}%</span>
                </div>
            </div>`;
        }).join('');

        pickerList.querySelectorAll('.picker-item').forEach(item => {
            item.addEventListener('click', () => {
                const p = projects.find(x => String(x.id) === item.dataset.id);
                if (p) selectProject(p);
            });
        });
    }

    /* Select project */
    function selectProject(p) {
        selectedId = p.id;
        hiddenInput.value = p.id;

        // Trigger UI
        placeholder.style.display = 'none';
        selectedInfo.classList.add('visible');
        selDot.className  = `picker-sel-dot dot-${p.status}`;
        selName.textContent = p.name;
        selNum.textContent  = p.project_number;

        // Preview card
        document.getElementById('pvStatus').textContent = p.status_label;
        document.getElementById('pvType').textContent   = typeLabels[p.type] ?? p.type;
        document.getElementById('pvClient').textContent = p.client || '—';
        document.getElementById('pvPct').textContent    = p.progress + '%';
        document.getElementById('pvBar').style.width    = p.progress + '%';
        preview.classList.add('visible');

        closePicker();
        updateChecklist();

        // Load parent tasks
        loadParentTasks(p.parent_url, p.id);
    }

    /* Load parent tasks via fetch */
    function loadParentTasks(url, projectId) {
        const sel  = document.getElementById('parentTaskSelect');
        const hint = document.getElementById('parentHint');

        sel.innerHTML = '<option value="">— Chargement… —</option>';
        sel.disabled  = true;

        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(tasks => {
                sel.disabled  = false;
                hint.textContent = tasks.length ? 'Optionnel — laissez vide pour une tâche principale' : 'Aucune tâche parente disponible pour ce projet';

                const oldParent = '{{ old('parent_id') }}';
                sel.innerHTML = '<option value="">— Aucune (tâche principale) —</option>'
                    + tasks.map(t =>
                        `<option value="${t.id}" ${String(t.id) === oldParent ? 'selected' : ''}>${esc(t.title)} — ${esc(t.task_number)}</option>`
                    ).join('');
            })
            .catch(() => {
                sel.disabled = false;
                sel.innerHTML = '<option value="">— Aucune tâche disponible —</option>';
                hint.textContent = 'Impossible de charger les tâches parentes';
            });
    }

    /* HTML escape helper */
    function esc(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    renderList('');
})();
</script>
@endpush
