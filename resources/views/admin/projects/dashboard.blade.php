{{-- resources/views/admin/projects/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard Projets - NovaTech Admin')
@section('page-title', 'Dashboard Projets')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .page-title-section h1 { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem; }
    .page-title-section p { color:var(--text-secondary); margin:0; font-size:0.875rem; }

    .stats-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-card { background:var(--bg-secondary); border-radius:0.75rem; padding:1.25rem; border:1px solid var(--border-light); transition:all 0.3s; }
    .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
    .stat-icon { width:2.5rem; height:2.5rem; border-radius:0.625rem; display:flex; align-items:center; justify-content:center; margin-bottom:0.75rem; }
    .stat-value { font-size:2rem; font-weight:700; color:var(--text-primary); line-height:1; margin-bottom:0.25rem; }
    .stat-label { font-size:0.75rem; text-transform:uppercase; color:var(--text-tertiary); letter-spacing:0.5px; }

    .grid-2 { display:grid; grid-template-columns:repeat(2, 1fr); gap:1rem; }
    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; }
    .card-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; }
    .card-header h3 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    .project-item { display:flex; align-items:center; gap:0.875rem; padding:0.875rem 0; border-bottom:1px solid var(--border-light); }
    .project-item:last-child { border-bottom:none; }
    .project-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
    .project-info { flex:1; min-width:0; }
    .project-name { font-weight:500; color:var(--text-primary); font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .project-sub { font-size:0.75rem; color:var(--text-tertiary); margin-top:0.125rem; }
    .project-progress-mini { width:60px; height:5px; background:var(--bg-tertiary); border-radius:9999px; overflow:hidden; }
    .project-progress-fill { height:100%; border-radius:9999px; }

    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.2rem 0.625rem; font-size:0.7rem; font-weight:500; border-radius:9999px; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-completed { background:rgba(139,92,246,0.1); color:#8b5cf6; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }
    .badge-danger { background:rgba(239,68,68,0.1); color:#ef4444; }

    /* Status breakdown */
    .status-breakdown { display:flex; flex-direction:column; gap:0.75rem; }
    .status-row { display:flex; align-items:center; gap:0.75rem; }
    .status-label { font-size:0.8125rem; color:var(--text-secondary); min-width:100px; }
    .status-bar { flex:1; height:8px; background:var(--bg-tertiary); border-radius:9999px; overflow:hidden; }
    .status-bar-fill { height:100%; border-radius:9999px; transition:width 0.5s; }
    .status-count { font-size:0.8125rem; font-weight:600; color:var(--text-primary); min-width:20px; text-align:right; }

    .deadline-item { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0; border-bottom:1px solid var(--border-light); }
    .deadline-item:last-child { border-bottom:none; }
    .deadline-days { font-size:1.25rem; font-weight:700; min-width:40px; text-align:center; }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }

    @media (max-width:768px) {
        .stats-grid { grid-template-columns:repeat(2,1fr); }
        .grid-2 { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h1>Dashboard Projets</h1>
        <p>Vue d'ensemble de l'activité des projets</p>
    </div>
    <div style="display:flex; gap:0.75rem;">
        <a href="{{ route('admin.projects.index') }}" class="btn-secondary-sm">
            <i class="fas fa-list"></i> Tous les projets
        </a>
        @can('projects.create')
        <a href="{{ route('admin.projects.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouveau projet
        </a>
        @endcan
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,0.1);">
            <i class="fas fa-folder-open" style="color:#3b82f6;"></i>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total projets</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.1);">
            <i class="fas fa-play-circle" style="color:#10b981;"></i>
        </div>
        <div class="stat-value" style="color:#10b981;">{{ $stats['active'] }}</div>
        <div class="stat-label">Projets actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(139,92,246,0.1);">
            <i class="fas fa-check-circle" style="color:#8b5cf6;"></i>
        </div>
        <div class="stat-value" style="color:#8b5cf6;">{{ $stats['completed'] }}</div>
        <div class="stat-label">Terminés</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,0.1);">
            <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
        </div>
        <div class="stat-value" style="color:#ef4444;">{{ $stats['overdue'] }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

<div class="grid-2">
    {{-- Projets récents --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Projets récents</h3>
            <a href="{{ route('admin.projects.index') }}" style="font-size:0.75rem; color:var(--brand-primary); text-decoration:none;">Voir tout</a>
        </div>
        <div class="card-body">
            @forelse($recentProjects as $project)
            <div class="project-item">
                <div class="project-dot" style="background:{{ $project->color }};"></div>
                <div class="project-info">
                    <div class="project-name">
                        <a href="{{ route('admin.projects.show', $project) }}" style="color:inherit; text-decoration:none;">{{ $project->name }}</a>
                    </div>
                    <div class="project-sub">{{ $project->client?->company_name ?? 'Sans client' }} · {{ $project->tasks->count() }} tâches</div>
                </div>
                <div>
                    <div class="project-progress-mini">
                        <div class="project-progress-fill" style="width:{{ $project->progress }}%; background:{{ $project->color }};"></div>
                    </div>
                    <div style="font-size:0.65rem; text-align:right; margin-top:0.2rem; color:var(--text-tertiary);">{{ $project->progress }}%</div>
                </div>
                <span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span>
            </div>
            @empty
            <p style="text-align:center; color:var(--text-tertiary); padding:1.5rem 0;">Aucun projet</p>
            @endforelse
        </div>
    </div>

    <div>
        {{-- Répartition par statut --}}
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Répartition</h3>
            </div>
            <div class="card-body">
                @php
                    $total = $statusBreakdown->sum() ?: 1;
                    $statColors = ['planning'=>'#3b82f6','active'=>'#10b981','on_hold'=>'#f59e0b','completed'=>'#8b5cf6','cancelled'=>'#ef4444'];
                    $statLabels = ['planning'=>'Planification','active'=>'Actif','on_hold'=>'En pause','completed'=>'Terminé','cancelled'=>'Annulé'];
                @endphp
                <div class="status-breakdown">
                    @foreach($statColors as $key => $color)
                    @php $count = $statusBreakdown[$key] ?? 0; @endphp
                    <div class="status-row">
                        <div class="status-label">{{ $statLabels[$key] }}</div>
                        <div class="status-bar">
                            <div class="status-bar-fill" style="width:{{ round($count/$total*100) }}%; background:{{ $color }};"></div>
                        </div>
                        <div class="status-count">{{ $count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Deadlines à venir --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-exclamation" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Échéances proches</h3>
            </div>
            <div class="card-body">
                @forelse($upcomingDeadlines as $project)
                @php $daysLeft = now()->diffInDays($project->end_date, false); @endphp
                <div class="deadline-item">
                    <div class="deadline-days" style="color:{{ $daysLeft <= 7 ? '#ef4444' : ($daysLeft <= 14 ? '#f59e0b' : '#10b981') }};">
                        {{ $daysLeft }}
                        <div style="font-size:0.6rem; font-weight:400; color:var(--text-tertiary);">jours</div>
                    </div>
                    <div class="project-info">
                        <div class="project-name">
                            <a href="{{ route('admin.projects.show', $project) }}" style="color:inherit; text-decoration:none;">{{ $project->name }}</a>
                        </div>
                        <div class="project-sub">{{ $project->end_date->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span>
                </div>
                @empty
                <p style="text-align:center; color:var(--text-tertiary); padding:1rem 0; font-size:0.875rem;">Aucune échéance proche</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
