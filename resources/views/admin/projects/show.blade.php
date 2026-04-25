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

    @if(session('success') && request()->fragment === 'tab-documents')
    <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#34d399;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Upload form --}}
    @if($canManageDocs)
    <div style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:0.9rem;padding:1.1rem;margin-bottom:1rem;">
        <h3 style="font-size:.85rem;font-weight:700;margin:0 0 .85rem;display:flex;align-items:center;gap:.5rem;">
            <i class="fas fa-cloud-arrow-up" style="color:var(--brand-primary)"></i> Ajouter un document
        </h3>
        <form action="{{ route('admin.projects.documents.store', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <div style="display:flex;flex-direction:column;gap:.28rem;">
                    <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Titre <span style="color:#f87171">*</span></label>
                    <input type="text" name="title" required placeholder="Ex : Cahier des charges v2"
                           style="padding:.45rem .7rem;border-radius:7px;border:1px solid var(--border-medium);background:var(--bg-tertiary);color:var(--text-primary);font-size:.8rem;outline:none;width:100%;">
                </div>
                <div style="display:flex;flex-direction:column;gap:.28rem;">
                    <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Fichier <span style="color:#f87171">*</span></label>
                    <input type="file" name="file" required
                           style="padding:.38rem .7rem;border-radius:7px;border:1px solid var(--border-medium);background:var(--bg-tertiary);color:var(--text-primary);font-size:.78rem;width:100%;cursor:pointer;">
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:.28rem;margin-bottom:.75rem;">
                <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Description (optionnel)</label>
                <input type="text" name="description" placeholder="Brève description du document…"
                       style="padding:.45rem .7rem;border-radius:7px;border:1px solid var(--border-medium);background:var(--bg-tertiary);color:var(--text-primary);font-size:.8rem;outline:none;width:100%;">
            </div>
            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn-primary" style="font-size:.78rem;padding:.45rem .9rem;">
                    <i class="fas fa-upload"></i> Uploader
                </button>
            </div>
        </form>
    </div>
    @endif

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
                <form action="{{ route('admin.projects.documents.destroy', [$project, $doc]) }}" method="POST"
                      onsubmit="return confirm('Supprimer ce document ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn" style="color:#f87171;border-color:rgba(239,68,68,.2);" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:2.5rem 1rem;">
            <i class="fas fa-folder-open"></i>
            <p>Aucun document pour ce projet</p>
            @if($canManageDocs)
            <p style="font-size:.78rem;color:var(--text-disabled);">Utilisez le formulaire ci-dessus pour ajouter le premier document.</p>
            @endif
        </div>
        @endforelse
    </div>
</div>

<!-- Tab Équipe -->
<div class="tab-content" id="tab-team">

    {{-- Ajouter un membre --}}
    @if($canManageMembers && $nonMembers->count())
    <div style="background:var(--bg-secondary);border:1px solid var(--border-light);border-radius:0.9rem;padding:1.1rem;margin-bottom:1rem;">
        <h3 style="font-size:.85rem;font-weight:700;margin:0 0 .85rem;display:flex;align-items:center;gap:.5rem;">
            <i class="fas fa-user-plus" style="color:var(--brand-primary)"></i> Ajouter un membre
        </h3>
        <form action="{{ route('admin.projects.members.store', $project) }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:.75rem;align-items:flex-end;">
                <div style="display:flex;flex-direction:column;gap:.28rem;">
                    <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Utilisateur <span style="color:#f87171">*</span></label>
                    <select name="user_id" required style="padding:.45rem .7rem;border-radius:7px;border:1px solid var(--border-medium);background:var(--bg-tertiary);color:var(--text-primary);font-size:.8rem;cursor:pointer;">
                        <option value="">Sélectionner…</option>
                        @foreach($nonMembers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:.28rem;">
                    <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Rôle dans le projet</label>
                    <input type="text" name="role" placeholder="Ex : Développeur, Designer…"
                           style="padding:.45rem .7rem;border-radius:7px;border:1px solid var(--border-medium);background:var(--bg-tertiary);color:var(--text-primary);font-size:.8rem;outline:none;width:100%;">
                </div>
                <button type="submit" class="btn-primary" style="font-size:.78rem;padding:.45rem .9rem;white-space:nowrap;">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
    @endif

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
            <form action="{{ route('admin.projects.members.destroy', [$project, $member]) }}" method="POST"
                  onsubmit="return confirm('Retirer {{ $member->name }} du projet ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" style="color:#f87171;border-color:rgba(239,68,68,.2);" title="Retirer">
                    <i class="fas fa-user-minus"></i>
                </button>
            </form>
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
    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">
        <i class="fas fa-history"></i> Historique des activités
    </h3>

    @forelse($project->activities as $activity)
    <div class="activity-item" style="padding: 0.75rem 0;">
        <div class="activity-icon" style="width: 36px; height: 36px;">
            <i class="{{ $activity->activity_icon }}"></i>
        </div>
        <div class="activity-content">
            <div class="activity-description">{{ $activity->description }}</div>
            <div class="activity-time">
                <i class="far fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                par {{ $activity->user_name }}
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="padding: 2rem;">
        <i class="fas fa-history"></i>
        <p>Aucune activité enregistrée</p>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            document.getElementById(`tab-${tabId}`).classList.add('active');
        });
    });
</script>
@endpush
