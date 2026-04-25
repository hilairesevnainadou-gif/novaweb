{{-- resources/views/admin/meetings/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle réunion · NovaTech Admin')
@section('page-title', 'Nouvelle réunion')

@push('styles')
<style>
    /* ── Layout ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.74rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .breadcrumb a { color: var(--text-tertiary); text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.55rem; }

    /* ── Cards ── */
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

    .form-card-title { font-size: 0.8rem; font-weight: 700; color: var(--text-primary); }
    .form-card-desc  { font-size: 0.67rem; color: var(--text-tertiary); margin-left: auto; }
    .form-card-body  { padding: 1.1rem; }

    /* ── Grid ── */
    .fg-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.8rem; }
    .fg-full { grid-column: 1 / -1; }

    /* ── Field ── */
    .field { display: flex; flex-direction: column; gap: 0.32rem; }

    .field label {
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
    }

    .field label .req { color: #f87171; margin-left: 0.2rem; }
    .field-hint { font-size: 0.63rem; color: var(--text-disabled); line-height: 1.3; }

    /* ── Inputs ── */
    .fi {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-height: 38px;
        transition: border-color .18s, box-shadow .18s, background .18s;
        outline: none;
        width: 100%;
    }

    .fi:hover  { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .fi:focus  { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi::placeholder { color: var(--text-disabled); font-size: 0.77rem; }
    textarea.fi { resize: vertical; min-height: 80px; max-height: 140px; line-height: 1.5; }
    select.fi   { cursor: pointer; }

    .fi.is-invalid { border-color: #f87171; background: rgba(239,68,68,.05); }
    .fi.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
    .invalid-msg { font-size: 0.65rem; color: #f87171; margin-top: 0.1rem; }

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
        transition: all .18s;
        white-space: nowrap;
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }
    .btn-ghost:hover { background: var(--bg-tertiary); color: var(--text-primary); }

    .btn-save {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { background: var(--brand-primary-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(59,130,246,.4); }

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
    .alert-errors .alert-icon { font-size: 1rem; color: #f87171; margin-top: 0.05rem; flex-shrink: 0; }
    .alert-errors ul { margin: 0; padding-left: 1.1rem; font-size: 0.78rem; color: #fca5a5; line-height: 1.6; }
    .alert-errors .alert-title { font-size: 0.78rem; font-weight: 700; color: #f87171; margin-bottom: 0.3rem; }

    /* ── Participants multi-select ── */
    .participants-wrap {
        position: relative;
    }

    .participants-input-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem;
        min-height: 42px;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: text;
        transition: border-color .18s, box-shadow .18s;
    }
    .participants-input-box:focus-within {
        border-color: var(--brand-primary);
        background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    .participant-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        background: rgba(59,130,246,.15);
        border: 1px solid rgba(59,130,246,.3);
        color: #60a5fa;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .participant-chip .remove-chip {
        cursor: pointer;
        opacity: 0.7;
        font-size: 0.6rem;
        background: none;
        border: none;
        color: inherit;
        padding: 0;
        line-height: 1;
        transition: opacity .15s;
    }
    .participant-chip .remove-chip:hover { opacity: 1; }

    #participant-search {
        border: none;
        outline: none;
        background: transparent;
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-width: 140px;
        flex: 1;
        padding: 0.1rem 0;
    }
    #participant-search::placeholder { color: var(--text-disabled); font-size: 0.77rem; }

    .participants-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 300;
        background: var(--bg-primary);
        border: 1px solid var(--border-medium);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        max-height: 220px;
        overflow-y: auto;
    }
    .participants-dropdown.open { display: block; animation: ddIn .15s ease; }

    @keyframes ddIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pd-option {
        display: flex;
        flex-direction: column;
        padding: 0.5rem 0.85rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border-light);
        transition: background .13s;
    }
    .pd-option:last-child { border-bottom: none; }
    .pd-option:hover { background: var(--bg-hover); }
    .pd-option .pd-name  { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .pd-option .pd-email { font-size: 0.68rem; color: var(--text-tertiary); }
    .pd-no-result { padding: 0.65rem 0.85rem; font-size: 0.78rem; color: var(--text-tertiary); text-align: center; }

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
        width: 28px; height: 28px;
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
    .info-row-label { font-size: 0.62rem; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.06em; font-weight: 700; }
    .info-row-value { font-size: 0.78rem; font-weight: 600; color: var(--text-primary); margin-top: 0.08rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ── Duration options ── */
    .duration-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .duration-option { display: none; }
    .duration-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.55rem 0.3rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        text-align: center;
        line-height: 1.3;
    }
    .duration-label span { font-size: 0.62rem; color: var(--text-tertiary); }
    .duration-option:checked + .duration-label {
        background: rgba(59,130,246,.15);
        border-color: rgba(59,130,246,.4);
        color: #60a5fa;
    }
    .duration-option:checked + .duration-label span { color: #93c5fd; }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .create-layout { grid-template-columns: 1fr; }
        .sidebar-card  { position: static; }
    }
    @media (max-width: 640px) {
        .fg-2 { grid-template-columns: 1fr; }
        .duration-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* ── Project picker ── */
    .picker-trigger {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.55rem 0.8rem; border-radius: 9px;
        border: 1.5px solid var(--border-medium);
        background: var(--bg-tertiary); cursor: pointer;
        transition: all .18s; min-height: 42px; user-select: none;
    }
    .picker-trigger:hover   { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .picker-trigger.open    { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); border-radius: 9px 9px 0 0; }
    .picker-trigger.invalid { border-color: #f87171; }
    .picker-placeholder { font-size: 0.78rem; color: var(--text-disabled); flex: 1; }
    .picker-selected-info { display: none; flex: 1; min-width: 0; }
    .picker-selected-info.visible { display: flex; align-items: center; gap: 0.55rem; }
    .picker-sel-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .picker-sel-name { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .picker-sel-num  { font-size: 0.68rem; color: var(--text-tertiary); white-space: nowrap; }
    .picker-chevron  { font-size: 0.72rem; color: var(--text-tertiary); transition: transform .18s; flex-shrink: 0; margin-left: auto; }
    .picker-trigger.open .picker-chevron { transform: rotate(180deg); }

    .picker-dropdown {
        display: none; position: absolute;
        top: 100%; left: 0; right: 0; z-index: 1000;
        background: var(--bg-elevated);
        border: 1.5px solid var(--brand-primary); border-top: none;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 12px 32px rgba(0,0,0,.45); overflow: hidden;
    }
    .picker-dropdown.open { display: block; }
    .picker-search-wrap { display: flex; align-items: center; gap: .5rem; padding: .6rem .75rem; border-bottom: 1px solid var(--border-light); background: var(--bg-secondary); }
    .picker-search-wrap i { font-size: .78rem; color: var(--text-tertiary); flex-shrink: 0; }
    #pickerSearchInput { flex: 1; background: transparent; border: none; outline: none; color: var(--text-primary); font-size: .82rem; font-family: inherit; }
    #pickerSearchInput::placeholder { color: var(--text-disabled); }
    .picker-filter-bar { display: flex; align-items: center; gap: .35rem; padding: .45rem .75rem; border-bottom: 1px solid var(--border-light); background: rgba(255,255,255,.015); flex-wrap: wrap; }
    .picker-filter-lbl { font-size: .62rem; color: var(--text-tertiary); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
    .filter-chip { display: inline-flex; align-items: center; gap: .25rem; padding: .15rem .5rem; border-radius: 999px; font-size: .67rem; font-weight: 700; border: 1px solid var(--border-medium); background: var(--bg-tertiary); color: var(--text-tertiary); cursor: pointer; transition: all .18s; }
    .filter-chip:hover  { border-color: var(--border-heavy); color: var(--text-secondary); }
    .filter-chip.active { background: rgba(59,130,246,.15); border-color: rgba(59,130,246,.4); color: #60a5fa; }
    .picker-list { max-height: 240px; overflow-y: auto; overscroll-behavior: contain; }
    .picker-list::-webkit-scrollbar { width: 4px; }
    .picker-list::-webkit-scrollbar-thumb { background: var(--border-medium); border-radius: 999px; }
    .picker-item { display: flex; align-items: center; gap: .7rem; padding: .6rem .85rem; cursor: pointer; transition: background .13s; border-bottom: 1px solid rgba(255,255,255,.03); }
    .picker-item:last-child { border-bottom: none; }
    .picker-item:hover { background: rgba(59,130,246,.07); }
    .picker-item.selected { background: rgba(59,130,246,.12); }
    .picker-item-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .picker-item-main { flex: 1; min-width: 0; }
    .picker-item-name { font-size: .8rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .picker-item-meta { display: flex; align-items: center; gap: .4rem; margin-top: .12rem; }
    .picker-item-num  { font-size: .65rem; color: var(--text-tertiary); }
    .picker-item-badge { display: inline-flex; align-items: center; padding: .1rem .38rem; border-radius: 4px; font-size: .6rem; font-weight: 700; }
    .picker-item-progress { display: flex; align-items: center; gap: .35rem; flex-shrink: 0; }
    .mini-bar { width: 40px; height: 4px; border-radius: 999px; background: var(--bg-secondary); overflow: hidden; }
    .mini-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent)); }
    .mini-pct { font-size: .62rem; color: var(--text-tertiary); font-weight: 700; width: 26px; text-align: right; }
    .picker-empty { padding: 1.25rem; text-align: center; font-size: .78rem; color: var(--text-tertiary); }
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
    #projectPreview.visible { display: flex !important; }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@php
    $hasProject = isset($project) && $project !== null;
    $authUserId = auth()->id();

    $formAction = $hasProject
        ? route('admin.projects.meetings.store', $project)
        : route('admin.meetings.store');

$backUrl = $hasProject
        ? route('admin.projects.meetings.index', $project)
        : route('admin.projects.index');

    $statusLabels = [
        'planning'    => 'Planification',
        'in_progress' => 'En cours',
        'review'      => 'En revue',
        'completed'   => 'Terminé',
        'cancelled'   => 'Annulé',
    ];

    $projectsJson = ($projects ?? collect())->map(fn($p) => [
        'id'             => $p->id,
        'name'           => $p->name,
        'project_number' => $p->project_number,
        'status'         => $p->status,
        'status_label'   => $statusLabels[$p->status] ?? $p->status,
        'type'           => $p->type,
        'progress'       => $p->progress_percentage ?? 0,
        'client'         => $p->client?->name,
        'manager'        => $p->projectManager?->name,
    ]);
@endphp

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    @if($hasProject)
        <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.projects.meetings.index', $project) }}">Réunions</a>
    @else
        <a href="{{ route('admin.meetings.global-index') }}">Réunions</a>
    @endif
    <i class="fas fa-chevron-right"></i>
    <span>Nouvelle réunion</span>
</nav>

{{-- Validation errors --}}
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

<form action="{{ $formAction }}" method="POST" id="meeting-form">
    @csrf

    {{-- Hidden attendees[] inputs (populated by JS) --}}
    <div id="attendees-container">
        @foreach(old('attendees', []) as $aid)
            <input type="hidden" name="attendees[]" value="{{ $aid }}">
        @endforeach
    </div>

    <div class="create-layout">

        {{-- ══════ MAIN ══════ --}}
        <div>

            {{-- Section 0 : Projet (seulement si pas de projet fixe) --}}
            @if(!$hasProject)
            <div class="form-card" id="projectSection" style="overflow:visible;position:relative;z-index:5;">
                <div class="form-card-header">
                    <div class="form-card-icon icon-blue"><i class="fas fa-folder-open"></i></div>
                    <span class="form-card-title">Projet rattaché</span>
                    @if($projectsJson->isEmpty())
                        <span class="form-card-desc" style="color:#f87171;">Aucun projet disponible</span>
                    @else
                        <span class="form-card-desc">{{ $projectsJson->count() }} projet(s) disponible(s)</span>
                    @endif
                </div>
                <div class="form-card-body" style="overflow:visible;">

                    @if($projectsJson->isEmpty())
                    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.85rem 1rem;background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.25);border-radius:10px;margin-bottom:.75rem;">
                        <i class="fas fa-triangle-exclamation" style="color:#fbbf24;margin-top:.1rem;flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:.8rem;font-weight:700;color:#fbbf24;margin-bottom:.2rem;">Aucun projet accessible</div>
                            <div style="font-size:.73rem;color:var(--text-tertiary);line-height:1.5;">
                                Vous n'êtes chef de projet ou intervenant sur aucun projet actif. Demandez à votre responsable de vous assigner à un projet.
                            </div>
                        </div>
                    </div>
                    @endif

                    <input type="hidden" name="project_id" id="projectIdHidden" value="{{ old('project_id') }}">

                    <div class="field" @if($projectsJson->isEmpty()) style="opacity:.45;pointer-events:none;" @endif>
                        <label>Projet <span class="req">*</span></label>

                        {{-- Picker trigger --}}
                        <div style="position:relative;" id="projectPickerWrap">
                            <div class="picker-trigger {{ $errors->has('project_id') ? 'invalid' : '' }}"
                                 id="pickerTrigger" tabindex="{{ $projectsJson->isEmpty() ? '-1' : '0' }}"
                                 role="combobox" aria-expanded="false">
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

                            <div class="picker-dropdown" id="pickerDropdown">
                                <div class="picker-search-wrap">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="pickerSearchInput" placeholder="Nom du projet, numéro…" autocomplete="off">
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

                        {{-- Aperçu projet sélectionné --}}
                        <div id="projectPreview" style="display:none;margin-top:.7rem;padding:.8rem;border-radius:10px;border:1px solid var(--border-light);background:rgba(59,130,246,.04);gap:1rem;">
                            <div style="display:flex;flex-direction:column;gap:.2rem;flex:1;">
                                <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary);font-weight:700;">Statut</div>
                                <div style="font-size:.78rem;font-weight:600;color:var(--text-primary);" id="pvStatus">—</div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.2rem;flex:1;">
                                <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary);font-weight:700;">Client</div>
                                <div style="font-size:.78rem;font-weight:600;color:var(--text-primary);" id="pvClient">—</div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.2rem;flex:1;">
                                <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary);font-weight:700;">Chef de projet</div>
                                <div style="font-size:.78rem;font-weight:600;color:var(--text-primary);" id="pvManager">—</div>
                            </div>
                        </div>

                        @error('project_id')
                            <div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @elseif($hasProject)
                <input type="hidden" name="project_id" value="{{ $project->id }}">
            @endif

            {{-- Section 1: Détails --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-blue"><i class="fas fa-calendar-alt"></i></div>
                    <span class="form-card-title">Détails de la réunion</span>
                    <span class="form-card-desc">Informations principales</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Titre <span class="req">*</span></label>
                            <input type="text" name="title" class="fi {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title') }}"
                                   placeholder="Ex : Revue technique hebdomadaire" required autofocus>
                            @error('title')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Description / Ordre du jour</label>
                            <textarea name="description" class="fi {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Points à aborder durant la réunion…">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Date et heure <span class="req">*</span></label>
                                <input type="text" id="meeting_date" name="meeting_date"
                                       class="fi {{ $errors->has('meeting_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('meeting_date') }}"
                                       placeholder="Sélectionnez une date…" required>
                                @error('meeting_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Lieu</label>
                                <input type="text" name="location" class="fi {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                       value="{{ old('location') }}"
                                       placeholder="Salle de conf., Bureau 12…">
                                @error('location')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label>Lien de réunion</label>
                            <input type="url" name="meeting_link" class="fi {{ $errors->has('meeting_link') ? 'is-invalid' : '' }}"
                                   value="{{ old('meeting_link') }}"
                                   placeholder="https://meet.google.com/ ou https://zoom.us/…">
                            <div class="field-hint">Pour les réunions à distance (Google Meet, Zoom, Teams…)</div>
                            @error('meeting_link')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 2: Durée --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-amber"><i class="fas fa-clock"></i></div>
                    <span class="form-card-title">Durée</span>
                    <span class="form-card-desc">Temps estimé</span>
                </div>
                <div class="form-card-body">
                    <div class="field">
                        <label>Durée de la réunion <span class="req">*</span></label>
                        <div class="duration-grid">
                            @php
                                $durations = [
                                    15  => ['15 min',   ''],
                                    30  => ['30 min',   '½ h'],
                                    45  => ['45 min',   '¾ h'],
                                    60  => ['1 h',      ''],
                                    90  => ['1 h 30',   ''],
                                    120 => ['2 h',      ''],
                                    180 => ['3 h',      ''],
                                    240 => ['4 h',      'demi-journée'],
                                ];
                                $oldDuration = old('duration_minutes', 60);
                            @endphp
                            @foreach($durations as $minutes => [$label, $sub])
                                <div>
                                    <input type="radio" name="duration_minutes" id="dur_{{ $minutes }}"
                                           class="duration-option" value="{{ $minutes }}"
                                           {{ $oldDuration == $minutes ? 'checked' : '' }}>
                                    <label for="dur_{{ $minutes }}" class="duration-label">
                                        {{ $label }}
                                        @if($sub)<span>{{ $sub }}</span>@endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('duration_minutes')<div class="invalid-msg" style="margin-top:.4rem"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Participants --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-purple"><i class="fas fa-users"></i></div>
                    <span class="form-card-title">Participants</span>
                    <span class="form-card-desc">Personnes invitées</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Ajouter des participants</label>
                            <div class="participants-wrap">
                                <div class="participants-input-box" id="participants-box" onclick="document.getElementById('participant-search').focus()">
                                    <div id="chips-container"></div>
                                    <input type="text" id="participant-search"
                                           placeholder="{{ old('attendees') ? 'Ajouter…' : 'Rechercher un participant…' }}"
                                           autocomplete="off">
                                </div>
                                <div class="participants-dropdown" id="participants-dropdown"></div>
                            </div>
                            <div class="field-hint">
                                <i class="fas fa-star" style="color:#fbbf24;margin-right:.2rem;font-size:.55rem;"></i>
                                Les membres du projet apparaissent en priorité
                            </div>
                            @error('attendees')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Notes supplémentaires</label>
                            <textarea name="notes" class="fi" placeholder="Informations complémentaires…">{{ old('notes') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Action bar --}}
                <div class="action-bar">
                    <div>
                        <a href="{{ $backUrl }}" class="btn btn-ghost">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-calendar-plus"></i> Planifier la réunion
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end main --}}

        {{-- ══════ SIDEBAR ══════ --}}
        <div>
            @if($hasProject)
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Projet associé</div>
                <div class="sidebar-body">

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Projet</div>
                            <div class="info-row-value">{{ $project->name }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-hashtag"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Référence</div>
                            <div class="info-row-value">{{ $project->project_number }}</div>
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

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Réunions planifiées</div>
                            <div class="info-row-value">{{ $project->meetings()->count() }}</div>
                        </div>
                    </div>

                </div>
            </div>
            @else
            <div class="sidebar-card" id="sidebarProjectCard">
                <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Projet sélectionné</div>
                <div class="sidebar-body" id="sidebarProjectBody">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:.55rem;padding:.6rem 0;opacity:.45;">
                        <i class="fas fa-folder-open" style="font-size:1.6rem;color:var(--text-tertiary)"></i>
                        <span style="font-size:.75rem;color:var(--text-tertiary);text-align:center;line-height:1.4;">Sélectionnez un projet<br>pour voir ses informations</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-lightbulb" style="margin-right:.35rem"></i>Conseils</div>
                <div class="sidebar-body">
                    <p style="font-size:.74rem;color:var(--text-secondary);line-height:1.55;margin:0 0 .65rem">
                        Définissez un ordre du jour clair pour maximiser l'efficacité de la réunion.
                    </p>
                    <p style="font-size:.74rem;color:var(--text-secondary);line-height:1.55;margin:0">
                        Ajoutez tous les participants pour qu'ils reçoivent la notification de la réunion.
                    </p>
                </div>
            </div>
        </div>

    </div>{{-- end layout --}}
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
(function () {

    /* ── Flatpickr date/time ── */
    flatpickr('#meeting_date', {
        locale: 'fr',
        enableTime: true,
        dateFormat: 'Y-m-d H:i:s',
        time_24hr: true,
        minuteIncrement: 15,
        minDate: 'today',
        allowInput: false,
    });

    /* ── Participants multi-select ── */
    @php
        $usersJson        = $users->map(fn($u) => [
            'id'        => $u->id,
            'name'      => $u->name,
            'email'     => $u->email,
            'isMember'  => in_array($u->id, $projectMemberIds ?? []),
        ])->values();
        $oldAttendeeIds   = array_map('intval', old('attendees', []));
        $authUserId       = auth()->id();
    @endphp

    const ALL_USERS       = @json($usersJson);
    const OLD_IDS         = @json($oldAttendeeIds);
    const AUTH_USER_ID    = @json($authUserId);

    const searchInput     = document.getElementById('participant-search');
    const dropdown        = document.getElementById('participants-dropdown');
    const chipsContainer  = document.getElementById('chips-container');
    const attendeesCont   = document.getElementById('attendees-container');

    let selected = [];

    /* Pré-sélectionner l'organisateur s'il n'y a pas de valeurs old() */
    if (OLD_IDS.length === 0) {
        const organizer = ALL_USERS.find(u => u.id === AUTH_USER_ID);
        if (organizer) selected.push(organizer);
    } else {
        OLD_IDS.forEach(id => {
            const user = ALL_USERS.find(u => u.id === id);
            if (user) selected.push(user);
        });
    }
    renderAll();

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    function syncHiddenInputs() {
        attendeesCont.innerHTML = '';
        selected.forEach(u => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'attendees[]';
            inp.value = u.id;
            attendeesCont.appendChild(inp);
        });
    }

    function renderChips() {
        chipsContainer.innerHTML = selected.map(u =>
            `<span class="participant-chip" style="${u.id === AUTH_USER_ID ? 'background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.35);color:#34d399;' : ''}">
                ${escHtml(u.name)}${u.id === AUTH_USER_ID ? ' <span style="font-size:.6rem;opacity:.7">(vous)</span>' : ''}
                <button type="button" class="remove-chip" data-id="${u.id}">&times;</button>
            </span>`
        ).join('');

        chipsContainer.querySelectorAll('.remove-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                selected = selected.filter(u => u.id !== id);
                renderAll();
            });
        });

        searchInput.placeholder = selected.length ? 'Ajouter…' : 'Rechercher un participant…';
    }

    function renderDropdown(q = '') {
        const lq      = q.toLowerCase().trim();
        const avail   = ALL_USERS.filter(u =>
            !selected.some(s => s.id === u.id) &&
            (!lq || u.name.toLowerCase().includes(lq) || u.email.toLowerCase().includes(lq))
        );

        const members = avail.filter(u => u.isMember);
        const others  = avail.filter(u => !u.isMember);

        if (avail.length === 0) {
            dropdown.innerHTML = '<div class="pd-no-result">Aucun résultat</div>';
            dropdown.classList.add('open');
            return;
        }

        let html = '';

        if (members.length) {
            html += `<div style="padding:.3rem .85rem .2rem;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--brand-primary);border-bottom:1px solid var(--border-light);">
                <i class="fas fa-star" style="margin-right:.25rem;font-size:.5rem;"></i>Équipe projet
            </div>`;
            html += members.map(u => optionHtml(u)).join('');
        }

        if (others.length) {
            if (members.length) {
                html += `<div style="padding:.3rem .85rem .2rem;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary);border-top:1px solid var(--border-light);border-bottom:1px solid var(--border-light);">
                    Autres utilisateurs
                </div>`;
            }
            html += others.map(u => optionHtml(u)).join('');
        }

        dropdown.innerHTML = html;

        dropdown.querySelectorAll('.pd-option').forEach(opt => {
            opt.addEventListener('click', () => {
                const u = ALL_USERS.find(x => x.id === parseInt(opt.dataset.id));
                if (u) selected.push(u);
                searchInput.value = '';
                dropdown.classList.remove('open');
                renderAll();
            });
        });

        dropdown.classList.add('open');
    }

    function optionHtml(u) {
        return `<div class="pd-option" data-id="${u.id}">
            <span class="pd-name">${escHtml(u.name)}${u.id === AUTH_USER_ID ? ' <span style="font-size:.65rem;color:var(--brand-primary)">(vous)</span>' : ''}</span>
            <span class="pd-email">${escHtml(u.email)}</span>
        </div>`;
    }

    function renderAll() {
        renderChips();
        syncHiddenInputs();
    }

    searchInput.addEventListener('focus', () => renderDropdown(searchInput.value));
    searchInput.addEventListener('input', () => renderDropdown(searchInput.value));

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });

})();
</script>
@endpush
