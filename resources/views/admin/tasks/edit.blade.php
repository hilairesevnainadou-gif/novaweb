{{-- resources/views/admin/tasks/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier la tâche · NovaTech Admin')
@section('page-title', 'Modifier la tâche')

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
    .icon-teal   { background: rgba(20,184,166,.15);  color: #2dd4bf; }
    .icon-rose   { background: rgba(244,63,94,.15);   color: #fb7185; }

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

    .priority-opt:checked + .priority-label { border-width: 2px; }

    .priority-opt[value="low"]:checked    + .priority-label { border-color: #34d399; background: rgba(16,185,129,.1);  color: #34d399; }
    .priority-opt[value="medium"]:checked + .priority-label { border-color: #60a5fa; background: rgba(59,130,246,.1);  color: #60a5fa; }
    .priority-opt[value="high"]:checked   + .priority-label { border-color: #fbbf24; background: rgba(245,158,11,.1);  color: #fbbf24; }
    .priority-opt[value="urgent"]:checked + .priority-label { border-color: #f87171; background: rgba(239,68,68,.1);   color: #f87171; }

    /* ── Status grid ── */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }

    .status-opt { display: none; }

    .status-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.28rem;
        padding: 0.55rem 0.4rem;
        border-radius: 9px;
        border: 1.5px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.69rem;
        font-weight: 700;
        color: var(--text-tertiary);
        text-align: center;
    }

    .status-label:hover { border-color: var(--border-heavy); color: var(--text-secondary); }
    .status-label .sico { font-size: 0.9rem; line-height: 1; }
    .status-opt:checked + .status-label { border-width: 2px; }

    .status-opt[value="todo"]:checked        + .status-label { border-color: #94a3b8; background: rgba(100,116,139,.1); color: #94a3b8; }
    .status-opt[value="in_progress"]:checked + .status-label { border-color: #60a5fa; background: rgba(59,130,246,.1);  color: #60a5fa; }
    .status-opt[value="review"]:checked      + .status-label { border-color: #fbbf24; background: rgba(245,158,11,.1);  color: #fbbf24; }
    .status-opt[value="approved"]:checked    + .status-label { border-color: #34d399; background: rgba(16,185,129,.1);  color: #34d399; }
    .status-opt[value="rejected"]:checked    + .status-label { border-color: #f87171; background: rgba(239,68,68,.1);   color: #f87171; }
    .status-opt[value="completed"]:checked   + .status-label { border-color: #a78bfa; background: rgba(139,92,246,.1);  color: #a78bfa; }
    .status-opt[value="cancelled"]:checked   + .status-label { border-color: #6b7280; background: rgba(107,114,128,.1); color: #6b7280; }

    /* ── Project info card ── */
    .project-info-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-light);
        background: rgba(59,130,246,.04);
        margin-bottom: 0;
    }

    .project-info-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .project-info-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .project-info-num {
        font-size: 0.68rem;
        color: var(--text-tertiary);
    }

    .project-info-link {
        margin-left: auto;
        font-size: 0.68rem;
        color: var(--brand-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        flex-shrink: 0;
    }

    .project-info-link:hover { text-decoration: underline; }

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

    .btn-save {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59,130,246,.3);
    }

    .btn-save:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(59,130,246,.4);
    }

    .btn-save:disabled { opacity: .65; transform: none; cursor: not-allowed; }

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

    /* ── Task meta info sidebar ── */
    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.45rem 0;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.72rem;
    }

    .meta-row:last-child { border-bottom: none; padding-bottom: 0; }
    .meta-row:first-child { padding-top: 0; }

    .meta-label { color: var(--text-tertiary); font-weight: 600; }
    .meta-val   { color: var(--text-secondary); font-weight: 700; }

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
        line-height: 1.5;
        margin-top: 1px;
        width: 14px;
        text-align: center;
    }

    .tip-item > span,
    .tip-item-text { flex: 1; min-width: 0; word-wrap: break-word; }
    .tip-item strong { color: var(--text-secondary); font-weight: 700; }
    .tip-item em     { color: var(--text-secondary); font-style: italic; }

    /* Checklist */
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

    /* Status colors helpers */
    .dot-planning    { background: #94a3b8; }
    .dot-in_progress { background: #60a5fa; }
    .dot-review      { background: #fbbf24; }
    .dot-completed   { background: #34d399; }
    .dot-cancelled   { background: #f87171; }
    .dot-todo        { background: #94a3b8; }
    .dot-approved    { background: #34d399; }
    .dot-rejected    { background: #f87171; }

    /* ── Responsive ── */
    @media (max-width: 1050px) {
        .create-layout { grid-template-columns: 1fr; }
        .sidebar-card  { position: static; }
        .fg-3 { grid-template-columns: repeat(2, 1fr); }
        .priority-grid { grid-template-columns: repeat(2, 1fr); }
        .status-grid   { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 620px) {
        .fg-2, .fg-3 { grid-template-columns: 1fr; }
        .priority-grid { grid-template-columns: repeat(2, 1fr); }
        .status-grid   { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')

@php
    $project = $task->project;
@endphp

{{-- Page header --}}
<div class="page-header">
    <a href="{{ route('admin.tasks.show', $task) }}" class="back-btn" title="Retour à la tâche">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1>Modifier la tâche</h1>
        <div class="header-sub">
            <span style="font-family:monospace;font-size:.7rem;color:var(--text-disabled)">{{ $task->task_number }}</span>
            &nbsp;·&nbsp;
            Projet&nbsp;<strong style="color:var(--text-secondary)">{{ $project?->name ?? '—' }}</strong>
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

<form method="POST" action="{{ route('admin.tasks.update', $task) }}" id="taskForm" novalidate>
    @csrf
    @method('PUT')

    <div class="create-layout">

        {{-- ══════════════ MAIN FORM ══════════════ --}}
        <div>

            {{-- Projet lié (lecture seule) --}}
            @if($project)
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-blue"><i class="fas fa-folder-open"></i></div>
                    <span class="card-title">Projet rattaché</span>
                    <span class="card-sub">Lecture seule</span>
                </div>
                <div class="form-card-body">
                    <div class="project-info-card">
                        <div class="project-info-dot dot-{{ $project->status }}"></div>
                        <div>
                            <div class="project-info-name">{{ $project->name }}</div>
                            <div class="project-info-num">{{ $project->project_number }}</div>
                        </div>
                        <a href="{{ route('admin.projects.tasks.index', $project) }}" class="project-info-link">
                            <i class="fas fa-external-link-alt"></i> Voir les tâches
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Section : Informations de la tâche --}}
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
                            <input type="text" name="title" id="fieldTitle"
                                   class="fi {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title', $task->title) }}"
                                   placeholder="Ex : Développer l'API de connexion"
                                   required autofocus>
                            @error('title')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Type de tâche <span class="req">*</span></label>
                                <select name="task_type" id="fieldType"
                                        class="fi {{ $errors->has('task_type') ? 'is-invalid' : '' }}" required>
                                    <option value="">— Choisir —</option>
                                    @foreach($taskTypes as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('task_type', $task->task_type) == $val ? 'selected' : '' }}>
                                            {{ $lbl }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('task_type')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Tâche parente</label>
                                <select name="parent_id" class="fi">
                                    <option value="">— Aucune (tâche principale) —</option>
                                    @foreach($parentTasks as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $task->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint">Laissez vide si c'est une tâche principale</div>
                            </div>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" class="fi {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Décrivez les objectifs, les livrables et les critères de réussite…">{{ old('description', $task->description) }}</textarea>
                            @error('description')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section : Priorité --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-amber"><i class="fas fa-flag"></i></div>
                    <span class="card-title">Priorité</span>
                    <span style="color:#f87171;font-size:.67rem;font-weight:700;margin-left:auto">Obligatoire</span>
                </div>
                <div class="form-card-body">
                    <div class="priority-grid">
                        @foreach(['low' => ['Basse','fa-arrow-down','#34d399'], 'medium' => ['Moyenne','fa-equals','#60a5fa'], 'high' => ['Haute','fa-arrow-up','#fbbf24'], 'urgent' => ['Urgente','fa-fire','#f87171']] as $val => [$lbl,$ico,$clr])
                        <div>
                            <input type="radio" name="priority" value="{{ $val }}" id="p_{{ $val }}"
                                   class="priority-opt"
                                   {{ old('priority', $task->priority) == $val ? 'checked' : '' }}>
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

            {{-- Section : Statut --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-teal"><i class="fas fa-circle-dot"></i></div>
                    <span class="card-title">Statut</span>
                    <span style="color:#f87171;font-size:.67rem;font-weight:700;margin-left:auto">Obligatoire</span>
                </div>
                <div class="form-card-body">
                    <div class="status-grid">
                        @php
                            $statusConfig = [
                                'todo'        => ['À faire',       'fa-circle',         '#94a3b8'],
                                'in_progress' => ['En cours',      'fa-spinner',        '#60a5fa'],
                                'review'      => ['En revue',      'fa-magnifying-glass','#fbbf24'],
                                'approved'    => ['Approuvée',     'fa-circle-check',   '#34d399'],
                                'rejected'    => ['Rejetée',       'fa-circle-xmark',   '#f87171'],
                                'completed'   => ['Terminée',      'fa-check-double',   '#a78bfa'],
                                'cancelled'   => ['Annulée',       'fa-ban',            '#6b7280'],
                            ];
                        @endphp
                        @foreach($statusConfig as $val => [$lbl, $ico, $clr])
                        <div>
                            <input type="radio" name="status" value="{{ $val }}" id="s_{{ $val }}"
                                   class="status-opt"
                                   {{ old('status', $task->status) == $val ? 'checked' : '' }}>
                            <label for="s_{{ $val }}" class="status-label">
                                <span class="sico" style="color:{{ $clr }}"><i class="fas {{ $ico }}"></i></span>
                                {{ $lbl }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('status')<div class="invalid-msg" style="margin-top:.4rem"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror

                    {{-- Avertissement pour les statuts sensibles --}}
                    @if(!auth()->user()->can('tasks.approve'))
                    <div style="margin-top:.75rem;padding:.5rem .7rem;border-radius:8px;background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.2);font-size:.67rem;color:#fbbf24;display:flex;align-items:center;gap:.4rem">
                        <i class="fas fa-triangle-exclamation"></i>
                        Les statuts <strong>Approuvée</strong> et <strong>Rejetée</strong> nécessitent normalement la permission <em>tasks.approve</em>.
                    </div>
                    @endif
                </div>
            </div>

            {{-- Section : Planning & Assignation --}}
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
                                        <option value="{{ $u->id }}" {{ old('assigned_to', $task->assigned_to) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Heures estimées</label>
                                <div class="iw">
                                    <span class="ii"><i class="fas fa-clock"></i></span>
                                    <input type="number" step="0.5" min="0" name="estimated_hours"
                                           class="fi {{ $errors->has('estimated_hours') ? 'is-invalid' : '' }}"
                                           value="{{ old('estimated_hours', $task->estimated_hours) }}"
                                           placeholder="0">
                                </div>
                                @error('estimated_hours')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Heures réelles</label>
                                <div class="iw">
                                    <span class="ii"><i class="fas fa-stopwatch"></i></span>
                                    <input type="number" step="0.5" min="0" name="actual_hours"
                                           class="fi {{ $errors->has('actual_hours') ? 'is-invalid' : '' }}"
                                           value="{{ old('actual_hours', $task->actual_hours) }}"
                                           placeholder="0">
                                </div>
                                @error('actual_hours')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div></div>{{-- spacer --}}
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Date de début</label>
                                <input type="datetime-local" name="start_date"
                                       class="fi {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('start_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Date d'échéance</label>
                                <input type="datetime-local" name="due_date"
                                       class="fi {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}">
                                @error('due_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="action-bar">
                    <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-ghost">
                        <i class="fas fa-xmark"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-save" id="submitBtn">
                        <i class="fas fa-floppy-disk"></i> Mettre à jour
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
                        <div class="req-row" id="req-status">
                            <i class="fas fa-circle" style="font-size:.4rem;color:var(--text-disabled)"></i>
                            Statut
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informations actuelles --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-info-circle" style="margin-right:.35rem"></i>Infos actuelles</div>
                <div class="sidebar-body">
                    <div class="meta-row">
                        <span class="meta-label">N° tâche</span>
                        <span class="meta-val" style="font-family:monospace;font-size:.7rem">{{ $task->task_number }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Créée par</span>
                        <span class="meta-val">{{ $task->creator?->name ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Créée le</span>
                        <span class="meta-val">{{ $task->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Modifiée le</span>
                        <span class="meta-val">{{ $task->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($task->completed_at)
                    <div class="meta-row">
                        <span class="meta-label">Terminée le</span>
                        <span class="meta-val" style="color:#34d399">{{ $task->completed_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Conseils --}}
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-lightbulb" style="margin-right:.35rem"></i>Conseils</div>
                <div class="sidebar-body">
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Passez en <strong>En revue</strong> pour demander une validation par le chef de projet.</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Renseignez les <strong>heures réelles</strong> pour suivre la rentabilité du projet.</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="tip-item-text">Le statut <strong>Annulée</strong> conserve la tâche mais la retire du kanban actif.</span>
                    </div>
                    @if(auth()->user()->can('tasks.approve'))
                    <div class="tip-item">
                        <i class="fas fa-shield-halved" style="color:#34d399"></i>
                        <span class="tip-item-text">Vous avez la permission d'<strong>approuver</strong> ou <strong>rejeter</strong> les tâches directement.</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- end sidebar --}}

    </div>{{-- end layout --}}
</form>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Checklist live ── */
    function updateChecklist() {
        const title    = document.getElementById('fieldTitle')?.value.trim();
        const type     = document.getElementById('fieldType')?.value;
        const priority = document.querySelector('input[name="priority"]:checked');
        const status   = document.querySelector('input[name="status"]:checked');

        const mark = (id, done) => {
            const row = document.getElementById(id);
            if (!row) return;
            row.classList.toggle('done', done);
            const icon = row.querySelector('i');
            if (icon) {
                icon.className = done ? 'fas fa-check-circle' : 'fas fa-circle';
                icon.style.fontSize = done ? '.78rem' : '.4rem';
                icon.style.color    = done ? '' : 'var(--text-disabled)';
            }
        };

        mark('req-title',    !!title);
        mark('req-type',     !!type);
        mark('req-priority', !!priority);
        mark('req-status',   !!status);
    }

    /* ── Form submit ── */
    const form      = document.getElementById('taskForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function (e) {
            const title    = document.getElementById('fieldTitle')?.value.trim();
            const type     = document.getElementById('fieldType')?.value;
            const priority = document.querySelector('input[name="priority"]:checked');
            const status   = document.querySelector('input[name="status"]:checked');

            const errors = [];
            if (!title)    errors.push('Le titre de la tâche est obligatoire.');
            if (!type)     errors.push('Le type de tâche est obligatoire.');
            if (!priority) errors.push('La priorité est obligatoire.');
            if (!status)   errors.push('Le statut est obligatoire.');

            if (errors.length) {
                e.preventDefault();
                alert(errors.join('\n'));
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement…';
        });
    }

    /* ── Watch inputs ── */
    document.querySelectorAll('#taskForm input, #taskForm select').forEach(el => {
        el.addEventListener('change', updateChecklist);
        el.addEventListener('input',  updateChecklist);
    });

    updateChecklist();
})();
</script>
@endpush
