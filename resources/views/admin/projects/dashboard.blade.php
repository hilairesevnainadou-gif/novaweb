{{-- resources/views/admin/projects/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard Projets - NovaTech Admin')
@section('page-title', 'Dashboard Projets')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        position: relative;
        background: linear-gradient(160deg, var(--bg-secondary) 0%, rgba(59, 130, 246, 0.03) 100%);
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--border-light);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 45%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(2, 6, 23, 0.14);
        border-color: rgba(59, 130, 246, 0.25);
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .stat-icon.blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-icon.red { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }

    .stat-value {
        font-size: clamp(1.45rem, 2.8vw, 1.95rem);
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.45rem;
    }

    .stat-label {
        font-size: 0.78rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .two-columns {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .card {
        background: var(--bg-secondary);
        border-radius: 1rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
    }

    .card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: linear-gradient(180deg, rgba(148, 163, 184, 0.04) 0%, transparent 100%);
    }

    .card-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body {
        padding: 1.1rem 1.25rem;
    }

    .activity-item {
        display: flex;
        gap: 0.9rem;
        padding: 0.9rem 0;
        border-bottom: 1px dashed var(--border-light);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        color: var(--brand-primary);
    }

    .activity-content { flex: 1; }

    .activity-description {
        font-size: 0.86rem;
        color: var(--text-primary);
        margin-bottom: 0.3rem;
        font-weight: 500;
        line-height: 1.35;
    }

    .activity-time {
        font-size: 0.72rem;
        color: var(--text-tertiary);
    }

    .task-item,
    .project-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.8rem;
        padding: 0.8rem 0;
        border-bottom: 1px dashed var(--border-light);
    }

    .task-item:last-child,
    .project-item:last-child {
        border-bottom: none;
    }

    .task-info { flex: 1; }

    .task-title,
    .project-name {
        font-size: 0.87rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .task-project,
    .project-progress {
        font-size: 0.72rem;
        color: var(--text-tertiary);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
        padding: 0.24rem 0.68rem;
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: 9999px;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .badge-review {
        background: rgba(245, 158, 11, 0.14);
        color: #f59e0b;
        border-color: rgba(245, 158, 11, 0.3);
    }

    .btn-link {
        color: var(--brand-primary);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .project-list {
        max-height: 380px;
        overflow: auto;
        padding-right: 0.25rem;
    }

    @media (max-width: 1180px) {
        .dashboard-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .two-columns { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="page-header" style="margin-bottom: 1.5rem;">
    <div class="page-title-section">
        <h1>Dashboard Projets</h1>
        <p>Vue d'ensemble de l'activité projets</p>
    </div>
</div>

<!-- Statistiques -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div class="stat-value">{{ $stats['total_projects'] }}</div>
        <div class="stat-label">Projets actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
        <div class="stat-value">{{ $stats['in_progress_projects'] }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['completed_projects'] }}</div>
        <div class="stat-label">Terminés</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-value">{{ $stats['overdue_projects'] }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-value">{{ $stats['total_tasks'] }}</div>
        <div class="stat-label">Tâches totales</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-value">{{ $stats['completed_tasks'] }}</div>
        <div class="stat-label">Tâches terminées</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $stats['meetings_today'] }}</div>
        <div class="stat-label">Réunions aujourd'hui</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $projects->count() }}</div>
        <div class="stat-label">Projets suivis</div>
    </div>
</div>

<div class="two-columns">
    <!-- Projets récents -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-project-diagram"></i> Projets actifs</h3>
            <a href="{{ route('admin.projects.index') }}" class="btn-link">Voir tous →</a>
        </div>
        <div class="card-body">
            <div class="project-list">
                @forelse($projects as $project)
                <div class="project-item">
                    <div>
                        <div class="project-name">{{ $project->name }}</div>
                        <div class="project-progress">Progression: {{ $project->progress_percentage }}%</div>
                    </div>
                    <div>
                        <span class="badge badge-{{ str_replace('_', '', $project->status) }}">
                            {{ $project->status_label }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-tertiary);">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun projet actif</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tâches en attente de revue -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clipboard-list"></i> Tâches en attente de revue</h3>
            <a href="{{ route('admin.tasks.global-index') }}?status=review" class="btn-link">Voir toutes →</a>
        </div>
        <div class="card-body">
            @forelse($pendingReviewTasks as $task)
            <div class="task-item">
                <div class="task-info">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-project">
                        {{ $task->project->name }} - Assignée à {{ $task->assignee->name ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-review">En revue</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-tertiary);">
                <i class="fas fa-check-circle"></i>
                <p>Aucune tâche en attente de revue</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Activités récentes -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> Activités récentes</h3>
    </div>
    <div class="card-body">
        @forelse($recentActivities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                <i class="{{ $activity->activity_icon }}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">{{ $activity->description }}</div>
                <div class="activity-time">
                    <i class="far fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                    @if($activity->project)
                        • Projet: {{ $activity->project->name }}
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: var(--text-tertiary);">
            <i class="fas fa-inbox"></i>
            <p>Aucune activité récente</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
