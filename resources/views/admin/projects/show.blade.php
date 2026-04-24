{{-- resources/views/admin/projects/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $project->name . ' - NovaTech Admin')
@section('page-title', 'Détail du projet')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; transition:color 0.2s; }
    .breadcrumb a:hover { color:var(--brand-primary); }

    /* Hero */
    .project-hero { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; padding:1.5rem; margin-bottom:1.5rem; }
    .project-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .project-title-group { display:flex; align-items:center; gap:1rem; }
    .project-color-badge { width:48px; height:48px; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .project-hero-title { font-size:1.375rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem; }
    .project-meta { display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.75rem; }
    .hero-actions { display:flex; gap:0.625rem; flex-wrap:wrap; }

    /* Stats row */
    .stats-row { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-mini { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; padding:1rem; text-align:center; }
    .stat-mini-value { font-size:1.75rem; font-weight:700; color:var(--text-primary); }
    .stat-mini-label { font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); letter-spacing:0.5px; margin-top:0.25rem; }

    /* Tabs */
    .tabs { display:flex; gap:0; border-bottom:2px solid var(--border-light); margin-bottom:1.5rem; overflow-x:auto; }
    .tab-btn { padding:0.75rem 1.25rem; font-size:0.875rem; font-weight:500; color:var(--text-tertiary); background:none; border:none; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all 0.2s; }
    .tab-btn:hover { color:var(--text-primary); }
    .tab-btn.active { color:var(--brand-primary); border-bottom-color:var(--brand-primary); }
    .tab-content { display:none; }
    .tab-content.active { display:block; }

    /* Cards */
    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; margin-bottom:1rem; }
    .card-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; }
    .card-header h3 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    /* Table */
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th { padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-tertiary); border-bottom:1px solid var(--border-light); }
    .data-table td { padding:0.875rem 1rem; border-bottom:1px solid var(--border-light); color:var(--text-primary); vertical-align:middle; }
    .data-table tbody tr:last-child td { border-bottom:none; }
    .data-table tbody tr:hover { background:var(--bg-hover); }

    /* Badges */
    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; font-size:0.75rem; font-weight:500; border-radius:9999px; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-completed { background:rgba(139,92,246,0.1); color:#8b5cf6; }
    .badge-danger { background:rgba(239,68,68,0.1); color:#ef4444; }

    /* Progress */
    .progress-bar { width:100%; height:8px; background:var(--bg-tertiary); border-radius:9999px; overflow:hidden; }
    .progress-fill { height:100%; border-radius:9999px; background:var(--brand-primary); transition:width 0.3s; }

    /* Info grid */
    .info-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:1rem; }
    .info-item { }
    .info-label { font-size:0.6875rem; text-transform:uppercase; font-weight:600; color:var(--text-tertiary); letter-spacing:0.4px; margin-bottom:0.25rem; }
    .info-value { font-size:0.875rem; color:var(--text-primary); font-weight:500; }

    /* Activity */
    .activity-list { display:flex; flex-direction:column; gap:0; }
    .activity-item { display:flex; gap:0.75rem; padding:0.875rem 0; border-bottom:1px solid var(--border-light); }
    .activity-item:last-child { border-bottom:none; }
    .activity-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.75rem; }
    .activity-content { flex:1; }
    .activity-desc { font-size:0.875rem; color:var(--text-primary); margin-bottom:0.25rem; }
    .activity-time { font-size:0.75rem; color:var(--text-tertiary); }

    /* Buttons */
    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s; border:none; cursor:pointer; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; font-weight:500; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }
    .btn-danger-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.2); border-radius:0.5rem; font-size:0.8125rem; font-weight:500; text-decoration:none; transition:all 0.2s; cursor:pointer; }
    .btn-danger-sm:hover { background:#ef4444; color:white; }

    /* Member chips */
    .member-chips { display:flex; flex-wrap:wrap; gap:0.5rem; }
    .member-chip { display:inline-flex; align-items:center; gap:0.5rem; padding:0.375rem 0.75rem; background:var(--bg-tertiary); border:1px solid var(--border-light); border-radius:9999px; font-size:0.8125rem; color:var(--text-secondary); }
    .member-avatar-sm { width:22px; height:22px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.6rem; font-weight:700; }

    /* Meetings */
    .meeting-item { display:flex; align-items:flex-start; gap:0.75rem; padding:0.875rem 0; border-bottom:1px solid var(--border-light); }
    .meeting-item:last-child { border-bottom:none; }
    .meeting-date-box { background:var(--bg-tertiary); border-radius:0.5rem; padding:0.5rem 0.75rem; text-align:center; flex-shrink:0; min-width:52px; }
    .meeting-date-day { font-size:1.25rem; font-weight:700; color:var(--text-primary); line-height:1; }
    .meeting-date-month { font-size:0.65rem; text-transform:uppercase; color:var(--text-tertiary); margin-top:0.125rem; }

    @media (max-width:768px) {
        .stats-row { grid-template-columns:repeat(2, 1fr); }
        .info-grid { grid-template-columns:1fr; }
        .project-hero-top { flex-direction:column; }
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $project->name }}</span>
</div>

{{-- Session alerts --}}
@if(session('success'))
    <div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:0.5rem; padding:0.875rem 1.25rem; margin-bottom:1rem; color:#10b981; display:flex; align-items:center; gap:0.5rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- Hero --}}
<div class="project-hero">
    <div class="project-hero-top">
        <div class="project-title-group">
            <div class="project-color-badge" style="background:{{ $project->color }}20;">
                <i class="fas fa-folder-open" style="color:{{ $project->color }}; font-size:1.25rem;"></i>
            </div>
            <div>
                <h1 class="project-hero-title">{{ $project->name }}</h1>
                <div style="font-size:0.75rem; color:var(--text-tertiary); font-family:monospace;">{{ $project->project_number }}</div>
                <div class="project-meta">
                    <span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span>
                    <span class="badge {{ $project->priority_badge_class }}">{{ $project->priority_label }}</span>
                    <span class="badge badge-secondary">{{ $project->type_label }}</span>
                    @if($project->is_overdue)
                        <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> En retard</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="hero-actions">
            @can('tasks.create')
            <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-secondary-sm">
                <i class="fas fa-plus"></i> Tâche
            </a>
            @endcan
            @can('meetings.create')
            <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-secondary-sm">
                <i class="fas fa-calendar-plus"></i> Réunion
            </a>
            @endcan
            @can('projects.edit')
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endcan
        </div>
    </div>

    @if($project->description)
        <p style="margin:1rem 0 0; color:var(--text-secondary); font-size:0.875rem; line-height:1.6;">{{ $project->description }}</p>
    @endif

    {{-- Progress bar --}}
    <div style="margin-top:1.25rem;">
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
            <span style="font-size:0.75rem; color:var(--text-tertiary);">Avancement global</span>
            <span style="font-size:0.875rem; font-weight:600; color:var(--text-primary);">{{ $project->progress }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width:{{ $project->progress }}%; background:{{ $project->color }};"></div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-mini">
        <div class="stat-mini-value">{{ $taskStats['total'] }}</div>
        <div class="stat-mini-label">Tâches totales</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color:#3b82f6;">{{ $taskStats['in_progress'] }}</div>
        <div class="stat-mini-label">En cours</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color:#f59e0b;">{{ $taskStats['review'] }}</div>
        <div class="stat-mini-label">En révision</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color:#10b981;">{{ $taskStats['done'] }}</div>
        <div class="stat-mini-label">Terminées</div>
    </div>
</div>

{{-- Onglets --}}
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('overview', this)">
        <i class="fas fa-home"></i> Vue d'ensemble
    </button>
    <button class="tab-btn" onclick="switchTab('tasks', this)">
        <i class="fas fa-tasks"></i> Tâches ({{ $taskStats['total'] }})
    </button>
    <button class="tab-btn" onclick="switchTab('meetings', this)">
        <i class="fas fa-video"></i> Réunions ({{ $project->meetings->count() }})
    </button>
    <button class="tab-btn" onclick="switchTab('activity', this)">
        <i class="fas fa-history"></i> Activités
    </button>
</div>

{{-- Onglet Vue d'ensemble --}}
<div id="tab-overview" class="tab-content active">
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:1rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Informations</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Client</div>
                            <div class="info-value">{{ $project->client?->company_name ?? $project->client?->name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Chef de projet</div>
                            <div class="info-value">{{ $project->manager?->name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de début</div>
                            <div class="info-value">{{ $project->start_date?->format('d/m/Y') ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de fin prévue</div>
                            <div class="info-value" style="{{ $project->is_overdue ? 'color:#ef4444;' : '' }}">
                                {{ $project->end_date?->format('d/m/Y') ?? '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Budget</div>
                            <div class="info-value">{{ $project->budget ? number_format($project->budget, 0, ',', ' ') . ' FCFA' : '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dépensé</div>
                            <div class="info-value">{{ number_format($project->spent_amount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        @if($project->technologies)
                        <div class="info-item" style="grid-column:span 2;">
                            <div class="info-label">Technologies</div>
                            <div style="display:flex; flex-wrap:wrap; gap:0.375rem; margin-top:0.375rem;">
                                @foreach($project->technologies as $tech)
                                    <span style="padding:0.2rem 0.625rem; background:rgba(59,130,246,0.1); color:var(--brand-primary); border-radius:9999px; font-size:0.75rem;">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Équipe --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-users" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Équipe ({{ $project->members->count() }} membre(s))</h3>
                </div>
                <div class="card-body">
                    @if($project->members->isEmpty())
                        <p style="color:var(--text-tertiary); font-size:0.875rem;">Aucun membre assigné.</p>
                    @else
                        <div class="member-chips">
                            @foreach($project->members as $member)
                                <div class="member-chip">
                                    <div class="member-avatar-sm">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                                    {{ $member->name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            {{-- Réunions à venir --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Prochaines réunions</h3>
                    @can('meetings.create')
                    <a href="{{ route('admin.projects.meetings.create', $project) }}" style="font-size:0.75rem; color:var(--brand-primary); text-decoration:none;">+ Planifier</a>
                    @endcan
                </div>
                <div class="card-body" style="padding:1rem;">
                    @forelse($upcomingMeetings as $meeting)
                        <div class="meeting-item">
                            <div class="meeting-date-box">
                                <div class="meeting-date-day">{{ $meeting->scheduled_at->format('d') }}</div>
                                <div class="meeting-date-month">{{ $meeting->scheduled_at->translatedFormat('M') }}</div>
                            </div>
                            <div>
                                <div style="font-size:0.875rem; font-weight:500; color:var(--text-primary);">{{ $meeting->title }}</div>
                                <div style="font-size:0.75rem; color:var(--text-tertiary);">{{ $meeting->scheduled_at->format('H:i') }}</div>
                                <span class="badge badge-secondary" style="margin-top:0.25rem; font-size:0.7rem;">{{ $meeting->type_label }}</span>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--text-tertiary); font-size:0.875rem; text-align:center; padding:1rem 0;">Aucune réunion planifiée</p>
                    @endforelse
                </div>
            </div>

            {{-- Notes --}}
            @if($project->notes)
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-sticky-note" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Notes</h3>
                </div>
                <div class="card-body">
                    <p style="font-size:0.875rem; color:var(--text-secondary); line-height:1.6; margin:0;">{{ $project->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Onglet Tâches --}}
<div id="tab-tasks" class="tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.75rem;">
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('admin.projects.tasks.index', $project) }}" class="btn-secondary-sm"><i class="fas fa-list"></i> Liste</a>
            <a href="{{ route('admin.projects.tasks.kanban', $project) }}" class="btn-secondary-sm"><i class="fas fa-columns"></i> Kanban</a>
        </div>
        @can('tasks.create')
        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouvelle tâche
        </a>
        @endcan
    </div>

    <div class="card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tâche</th>
                        <th>Assignée à</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Échéance</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->tasks->take(10) as $task)
                    <tr>
                        <td>
                            <div style="font-weight:500;">{{ $task->title }}</div>
                        </td>
                        <td>
                            <span style="font-size:0.8125rem; color:var(--text-secondary);">{{ $task->assignedTo?->name ?? '-' }}</span>
                        </td>
                        <td><span class="badge {{ $task->status_badge_class }}">{{ $task->status_label }}</span></td>
                        <td><span class="badge {{ $task->priority_badge_class }}">{{ $task->priority_label }}</span></td>
                        <td>
                            <span style="font-size:0.8125rem; color:{{ $task->is_overdue ? '#ef4444' : 'var(--text-secondary)' }};">
                                {{ $task->due_date?->format('d/m/Y') ?? '-' }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.projects.tasks.show', [$project, $task]) }}" style="color:var(--text-tertiary); padding:0.375rem; border-radius:0.375rem; transition:color 0.2s;" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('tasks.edit')
                            <a href="{{ route('admin.projects.tasks.edit', [$project, $task]) }}" style="color:var(--text-tertiary); padding:0.375rem; border-radius:0.375rem; transition:color 0.2s;" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-tertiary);">
                            <i class="fas fa-tasks" style="font-size:2rem; margin-bottom:0.75rem; opacity:0.5; display:block;"></i>
                            Aucune tâche pour ce projet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($project->tasks->count() > 10)
        <div style="text-align:center; margin-top:0.75rem;">
            <a href="{{ route('admin.projects.tasks.index', $project) }}" class="btn-secondary-sm">
                Voir toutes les tâches ({{ $project->tasks->count() }})
            </a>
        </div>
    @endif
</div>

{{-- Onglet Réunions --}}
<div id="tab-meetings" class="tab-content">
    <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
        @can('meetings.create')
        <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Planifier une réunion
        </a>
        @endcan
    </div>

    <div class="card">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Statut</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->meetings as $meeting)
                    <tr>
                        <td style="font-weight:500;">{{ $meeting->title }}</td>
                        <td><span style="font-size:0.8125rem; color:var(--text-secondary);">{{ $meeting->type_label }}</span></td>
                        <td><span style="font-size:0.8125rem;">{{ $meeting->scheduled_at->format('d/m/Y H:i') }}</span></td>
                        <td><span style="font-size:0.8125rem; color:var(--text-secondary);">{{ $meeting->mode_label }}</span></td>
                        <td><span class="badge {{ $meeting->status_badge_class }}">{{ $meeting->status_label }}</span></td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.projects.meetings.show', [$project, $meeting]) }}" style="color:var(--text-tertiary); padding:0.375rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('meetings.edit')
                            <a href="{{ route('admin.projects.meetings.edit', [$project, $meeting]) }}" style="color:var(--text-tertiary); padding:0.375rem;">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-tertiary);">
                            <i class="fas fa-video" style="font-size:2rem; margin-bottom:0.75rem; opacity:0.5; display:block;"></i>
                            Aucune réunion planifiée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Onglet Activités --}}
<div id="tab-activity" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Historique des activités</h3>
        </div>
        <div class="card-body">
            @forelse($project->activities->take(20) as $activity)
            <div class="activity-item">
                <div class="activity-icon" style="background:{{ $activity->action_color }}20;">
                    <i class="fas {{ $activity->action_icon }}" style="color:{{ $activity->action_color }};"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-desc">{{ $activity->description }}</div>
                    <div class="activity-time">
                        {{ $activity->user?->name ?? 'Système' }} · {{ $activity->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @empty
            <p style="text-align:center; color:var(--text-tertiary); padding:1.5rem 0;">Aucune activité enregistrée</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        btn.classList.add('active');
    }
</script>
@endpush
