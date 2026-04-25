{{-- resources/views/admin/tasks/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($canViewAll) && $canViewAll ? 'Toutes les tâches - NovaTech Admin' : 'Mes tâches - NovaTech Admin')
@section('page-title', isset($canViewAll) && $canViewAll ? 'Toutes les tâches' : 'Mes tâches')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); }
    .stat-icon.blue i { color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.green i { color: #10b981; }
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }
    .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); }
    .stat-icon.purple i { color: #8b5cf6; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        letter-spacing: 0.5px;
    }

    .filters-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.625rem 1rem;
        margin-bottom: 0.75rem;
    }

    .filter-group label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        margin-bottom: 0.3rem;
    }

    .filters-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-light);
    }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        flex: 1;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.2rem 0.6rem 0.2rem 0.75rem;
        background: rgba(99, 102, 241, 0.1);
        color: var(--brand-primary);
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .filter-tag a {
        color: inherit;
        text-decoration: none;
        opacity: 0.6;
        line-height: 1;
    }

    .filter-tag a:hover { opacity: 1; }

    .filters-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-shrink: 0;
    }

    .badge-role-assigned { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-role-creator  { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .badge-role-manager  { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .btn-reset {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .tasks-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .tasks-table thead {
        background: var(--bg-tertiary);
    }

    .tasks-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .tasks-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .tasks-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .task-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .task-number {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
        white-space: nowrap;
    }

    .badge-todo { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-in_progress { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-review { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .badge-completed { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .badge-low { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-medium { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-high { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-urgent { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .priority-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }
    .priority-low { background: #6b7280; }
    .priority-medium { background: #3b82f6; }
    .priority-high { background: #f59e0b; }
    .priority-urgent { background: #ef4444; }

    .actions-cell {
        text-align: right;
        white-space: nowrap;
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

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

    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .page-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 640px) {
        .page-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .page-title-section h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .page-title-section p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    .table-row {
        animation: fadeInUp 0.3s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    .overdue-text {
        color: #ef4444;
        font-size: 0.7rem;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 9999;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        width: min(100%, 520px);
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-light);
        border-radius: 0.75rem;
        box-shadow: var(--shadow-lg);
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem 1.25rem;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
    }

    .modal-body {
        border-bottom: 1px solid var(--border-light);
    }

    .modal-body p {
        margin: 0;
    }

    .warning-text {
        margin-top: 0.5rem !important;
        color: #ef4444;
        font-size: 0.85rem;
    }

    .modal-close {
        border: none;
        background: transparent;
        color: var(--text-tertiary);
        font-size: 1rem;
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .modal-close:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        border: none;
        border-radius: 0.5rem;
        padding: 0.55rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-secondary {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: 1px solid var(--border-light);
    }

    .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .btn-danger:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@php
    $userId       = auth()->id();
    $canViewAll   = $canViewAll ?? false;

    // Libellés des filtres actifs
    $filterLabels = [];
    if (!empty($filters['search']))    { $filterLabels['search']      = 'Recherche : ' . $filters['search']; }
    if (!empty($filters['status']))    { $filterLabels['status']      = 'Statut : ' . ($statuses[$filters['status']] ?? $filters['status']); }
    if (!empty($filters['priority']))  { $filterLabels['priority']    = 'Priorité : ' . ($priorities[$filters['priority']] ?? $filters['priority']); }
    if (!empty($filters['project_id'])) {
        $fp = ($projects ?? collect())->firstWhere('id', $filters['project_id']);
        $filterLabels['project_id'] = 'Projet : ' . ($fp?->name ?? $filters['project_id']);
    }
    if (!empty($filters['client_id'])) {
        $fc = ($clients ?? collect())->firstWhere('id', $filters['client_id']);
        $filterLabels['client_id'] = 'Client : ' . ($fc?->name ?? $filters['client_id']);
    }
    if (!empty($filters['assigned_to']) && $canViewAll) {
        $fa = ($users ?? collect())->firstWhere('id', $filters['assigned_to']);
        $filterLabels['assigned_to'] = 'Assigné : ' . ($fa?->name ?? $filters['assigned_to']);
    }
@endphp
@can('tasks.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>{{ $canViewAll ? 'Toutes les tâches' : 'Mes tâches' }}</h1>
        <p>
            @if($canViewAll)
                Vue globale de toutes les tâches
            @else
                Tâches qui vous concernent (assignées, créées ou en tant que chef de projet)
            @endif
            @if(isset($project))
                — {{ $project->name }}
            @endif
        </p>
    </div>
    @can('tasks.create')
    <div>
        @if(isset($project))
            <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary">
                <i class="fas fa-plus"></i> Nouvelle tâche
            </a>
        @else
            <a href="{{ route('admin.tasks.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Nouvelle tâche
            </a>
        @endif
    </div>
    @endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-tasks"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-label">Total tâches</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow"><i class="fas fa-spinner fa-pulse"></i></div>
        </div>
        <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
        <div class="stat-label">Terminées</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['overdue'] ?? 0 }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <form method="GET" action="{{ url()->current() }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label for="search"><i class="fas fa-search" style="margin-right:0.25rem;"></i>Recherche</label>
                <input type="text" id="search" name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="Titre, n° tâche..."
                    class="filter-input" autocomplete="off">
            </div>

            <div class="filter-group">
                <label for="status"><i class="fas fa-circle-dot" style="margin-right:0.25rem;"></i>Statut</label>
                <select id="status" name="status" class="filter-select">
                    <option value="">Tous</option>
                    @foreach(($statuses ?? []) as $statusKey => $statusLabel)
                        <option value="{{ $statusKey }}" {{ (($filters['status'] ?? '') === $statusKey) ? 'selected' : '' }}>
                            {{ $statusLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="priority"><i class="fas fa-flag" style="margin-right:0.25rem;"></i>Priorité</label>
                <select id="priority" name="priority" class="filter-select">
                    <option value="">Toutes</option>
                    @foreach(($priorities ?? []) as $priorityKey => $priorityLabel)
                        <option value="{{ $priorityKey }}" {{ (($filters['priority'] ?? '') === $priorityKey) ? 'selected' : '' }}>
                            {{ $priorityLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="client_id"><i class="fas fa-building" style="margin-right:0.25rem;"></i>Client</label>
                <select id="client_id" name="client_id" class="filter-select">
                    <option value="">Tous</option>
                    @foreach(($clients ?? collect()) as $client)
                        <option value="{{ $client->id }}" {{ (($filters['client_id'] ?? '') == (string) $client->id) ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="project_id"><i class="fas fa-folder" style="margin-right:0.25rem;"></i>Projet</label>
                <select id="project_id" name="project_id" class="filter-select">
                    <option value="">Tous</option>
                    @foreach(($projects ?? collect()) as $p)
                        <option value="{{ $p->id }}" data-client-id="{{ $p->client_id }}"
                            {{ (($filters['project_id'] ?? '') == (string) $p->id) ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($canViewAll)
            <div class="filter-group">
                <label for="assigned_to"><i class="fas fa-user" style="margin-right:0.25rem;"></i>Assigné à</label>
                <select id="assigned_to" name="assigned_to" class="filter-select">
                    <option value="">Tous</option>
                    @foreach(($users ?? collect()) as $u)
                        <option value="{{ $u->id }}" {{ (($filters['assigned_to'] ?? '') == (string) $u->id) ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <div class="filters-footer">
            <div class="active-filters">
                @if(count($filterLabels) > 0)
                    <span style="font-size:0.75rem; color:var(--text-tertiary); margin-right:0.25rem;">Filtres actifs :</span>
                    @foreach($filterLabels as $key => $label)
                    @php
                        $removeParams = array_merge(array_filter($filters, fn($v) => $v !== ''), ['page' => null]);
                        unset($removeParams[$key]);
                        $removeUrl = url()->current() . '?' . http_build_query(array_filter($removeParams, fn($v) => $v !== null));
                    @endphp
                    <span class="filter-tag">
                        {{ $label }}
                        <a href="{{ $removeUrl }}" title="Retirer ce filtre"><i class="fas fa-times"></i></a>
                    </span>
                    @endforeach
                @else
                    <span style="font-size:0.75rem; color:var(--text-tertiary);">Aucun filtre actif</span>
                @endif
            </div>
            <div class="filters-actions">
                @if(count($filterLabels) > 0)
                <a href="{{ url()->current() }}" style="font-size:0.8rem; color:var(--text-tertiary); text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem;">
                    <i class="fas fa-undo-alt"></i> Tout effacer
                </a>
                @endif
                <button type="submit" class="btn-primary" style="padding:0.5rem 1rem; font-size:0.8rem;">
                    <i class="fas fa-filter"></i> Appliquer
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tableau -->
<div class="table-container">
    <table class="tasks-table">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Projet</th>
                @if(!$canViewAll)
                <th>Mon rôle</th>
                @endif
                <th>Assignée à</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Heures</th>
                <th>Date échéance</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="tasksTableBody">
            @forelse($tasks as $index => $task)
            @php
                $myRoleLabel = null;
                $myRoleClass = null;
                if (!$canViewAll) {
                    if ($task->assigned_to == $userId) {
                        $myRoleLabel = 'Assigné';
                        $myRoleClass = 'badge-role-assigned';
                    } elseif ($task->created_by == $userId) {
                        $myRoleLabel = 'Créateur';
                        $myRoleClass = 'badge-role-creator';
                    } elseif (($task->project->project_manager_id ?? null) == $userId) {
                        $myRoleLabel = 'Chef projet';
                        $myRoleClass = 'badge-role-manager';
                    }
                }
            @endphp
            <tr class="table-row"
                data-id="{{ $task->id }}"
                data-title="{{ strtolower($task->title) }}"
                data-number="{{ strtolower($task->task_number) }}"
                data-project="{{ strtolower($task->project->name) }}"
                data-project-id="{{ $task->project_id }}"
                data-client="{{ strtolower($task->project->client->name ?? 'sans client') }}"
                data-client-id="{{ $task->project->client_id }}"
                data-assignee="{{ strtolower($task->assignee?->name ?? 'non assignee') }}"
                data-status="{{ $task->status }}"
                data-priority="{{ $task->priority }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div>
                        <div class="task-title">{{ $task->title }}</div>
                        <div class="task-number">{{ $task->task_number }}</div>
                    </div>
                </td>
                <td>
                    <div>
                        <a href="{{ route('admin.projects.show', $task->project) }}" style="color: var(--text-primary); text-decoration: none;">
                            {{ $task->project->name }}
                        </a>
                        <div class="task-number">
                            {{ $task->project->client->name ?? 'Client non défini' }} • {{ $task->type_label }}
                        </div>
                    </div>
                </td>
                @if(!$canViewAll)
                <td>
                    @if($myRoleLabel)
                        <span class="badge {{ $myRoleClass }}">{{ $myRoleLabel }}</span>
                    @else
                        <span style="color:var(--text-tertiary);">—</span>
                    @endif
                </td>
                @endif
                <td>
                    @if($task->assignee)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-user-circle" style="color: var(--brand-primary);"></i>
                            {{ $task->assignee->name }}
                        </div>
                    @else
                        <span style="color: var(--text-tertiary);">Non assignée</span>
                    @endif
                </td>
                <td>
                    <span class="priority-indicator priority-{{ $task->priority }}"></span>
                    <span class="badge badge-{{ $task->priority }}">
                        {{ $task->priority_label }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $task->status }}">
                        <i class="fas
                            @switch($task->status)
                                @case('todo') fa-circle @break
                                @case('in_progress') fa-spinner fa-pulse @break
                                @case('review') fa-eye @break
                                @case('approved') fa-check-circle @break
                                @case('rejected') fa-times-circle @break
                                @case('completed') fa-check-double @break
                            @endswitch
                        "></i>
                        {{ $task->status_label }}
                    </span>
                </td>
                <td>
                    <div class="task-number">
                        {{ $task->estimated_hours }}h / {{ $task->actual_hours }}h
                    </div>
                </td>
                <td>
                    @if($task->due_date)
                        <div>{{ $task->due_date->format('d/m/Y') }}</div>
                        @if($task->is_overdue)
                            <div class="overdue-text"><i class="fas fa-clock"></i> En retard</div>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('tasks.edit')
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('tasks.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $task->id }}"
                                data-title="{{ $task->title }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="{{ $canViewAll ? 8 : 9 }}" class="empty-state">
                    <i class="fas fa-tasks"></i>
                    <p>Aucune tâche trouvée</p>
                    <p style="font-size: 0.875rem;">
                        @if($canViewAll)
                            Aucune tâche ne correspond aux critères sélectionnés.
                        @else
                            Vous n'avez pas encore de tâches qui vous concernent.
                        @endif
                    </p>
                    @can('tasks.create')
                    <a href="{{ isset($project) ? route('admin.projects.tasks.create', $project) : route('admin.tasks.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i> Créer une tâche
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($tasks->hasPages())
<div class="pagination-wrapper">
    {{ $tasks->links() }}
</div>
@endif

<!-- Modal de suppression -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Supprimer la tâche</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p class="warning-text">Attention : Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-danger">Supprimer</button>
        </div>
    </div>
</div>
@endcan

@cannot('tasks.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const prioritySelect = document.getElementById('priority');
    const projectSelect = document.getElementById('project_id');
    const clientSelect = document.getElementById('client_id');

    const modal = document.getElementById('confirmationModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const defaultConfirmBtnHtml = modalConfirmBtn ? modalConfirmBtn.innerHTML : 'Supprimer';
    let currentDeleteId = null;
    let isDeleting = false;

    function resetConfirmButton() {
        if (!modalConfirmBtn) return;
        modalConfirmBtn.disabled = false;
        modalConfirmBtn.innerHTML = defaultConfirmBtnHtml;
    }

    function openModal(id, title) {
        if (!modal || !modalMessage || !modalConfirmBtn) return;

        const safeTitle = (title || '').trim() || 'cette tâche';
        currentDeleteId = id;
        isDeleting = false;
        resetConfirmButton();

        modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer la tâche "${safeTitle}" ?`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(force = false) {
        if (!modal) return;
        if (isDeleting && !force) return;

        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentDeleteId = null;
        isDeleting = false;
        resetConfirmButton();
    }

    function confirmDelete() {
        if (!currentDeleteId || isDeleting || !modalConfirmBtn) return;

        isDeleting = true;
        modalConfirmBtn.disabled = true;
        modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

        fetch(`{{ url('admin/tasks') }}/${currentDeleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(async (res) => {
            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                return res.json();
            }

            if (res.redirected || res.ok) {
                return { success: true, message: 'Tâche supprimée avec succès' };
            }

            throw new Error('Réponse serveur inattendue');
        })
        .then(data => {
            if (data.success || data.message) {
                closeModal(true);
                location.reload();
                return;
            }

            throw new Error(data?.message || 'Une erreur est survenue');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert(error?.message || 'Une erreur technique est survenue');
            isDeleting = false;
            resetConfirmButton();
        });
    }

    if (modalConfirmBtn) {
        modalConfirmBtn.onclick = confirmDelete;
    }

    if (modal) {
        modal.onclick = function(e) {
            if (e.target === modal) closeModal();
        };
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal?.classList.contains('active')) closeModal();
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            openModal(id, title);
        });
    });

    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';
        const priorityValue = prioritySelect?.value || '';
        const projectIdValue = projectSelect?.value || '';
        const clientIdValue = clientSelect?.value || '';

        const rows = document.querySelectorAll('#tasksTableBody tr:not(#emptyStateRow)');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const number = row.dataset.number || '';
            const project = row.dataset.project || '';
            const projectId = row.dataset.projectId || '';
            const client = row.dataset.client || '';
            const clientId = row.dataset.clientId || '';
            const assignee = row.dataset.assignee || '';
            const status = row.dataset.status || '';
            const priority = row.dataset.priority || '';

            if (
                searchTerm &&
                !title.includes(searchTerm) &&
                !number.includes(searchTerm) &&
                !project.includes(searchTerm) &&
                !client.includes(searchTerm) &&
                !assignee.includes(searchTerm)
            ) {
                show = false;
            }

            if (show && statusValue && status !== statusValue) show = false;
            if (show && priorityValue && priority !== priorityValue) show = false;
            if (show && projectIdValue && projectId !== projectIdValue) show = false;
            if (show && clientIdValue && clientId !== clientIdValue) show = false;

            row.style.display = show ? '' : 'none';
            if (show) hasVisibleRows = true;
        });

        const noResultsRow = document.getElementById('noResultsRow');
        if (!hasVisibleRows && rows.length > 0) {
            if (!noResultsRow) {
                const tbody = document.getElementById('tasksTableBody');
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="{{ $canViewAll ? 8 : 9 }}" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                    <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-undo-alt"></i> Réinitialiser les filtres
                    </button>
                <\/td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        if (prioritySelect) prioritySelect.value = '';
        if (projectSelect) projectSelect.value = '';
        if (clientSelect) clientSelect.value = '';
        filterTable();
    };

    let timer;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (statusSelect) statusSelect.addEventListener('change', filterTable);
    if (prioritySelect) prioritySelect.addEventListener('change', filterTable);
    if (projectSelect) projectSelect.addEventListener('change', filterTable);
    if (clientSelect) {
        clientSelect.addEventListener('change', function() {
            const selectedClientId = clientSelect.value;
            if (projectSelect) {
                Array.from(projectSelect.options).forEach((option) => {
                    if (!option.value) return;
                    const optionClientId = option.dataset.clientId || '';
                    option.hidden = !!selectedClientId && optionClientId !== selectedClientId;
                });

                const currentProjectOption = projectSelect.options[projectSelect.selectedIndex];
                if (currentProjectOption && currentProjectOption.hidden) {
                    projectSelect.value = '';
                }
            }
            filterTable();
        });

        clientSelect.dispatchEvent(new Event('change'));
    }

    window.closeModal = closeModal;
</script>
@endpush
