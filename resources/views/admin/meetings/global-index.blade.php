{{-- resources/views/admin/meetings/global-index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Toutes les réunions - NovaTech Admin')
@section('page-title', 'Toutes les réunions')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        border: 1px solid var(--border-light);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        text-transform: uppercase;
    }

    .filters-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .grid-filters {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .grid-filters {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

@php
    // Calcul des statistiques avec des requêtes séparées pour éviter whereDate sur Collection
    $totalMeetings = $meetings->total();

    // Compter les réunions à venir (status scheduled et date future)
    $upcomingCount = \App\Models\Meeting::where('status', 'scheduled')
        ->where('meeting_date', '>', now())
        ->count();

    // Compter les réunions d'aujourd'hui
    $todayCount = \App\Models\Meeting::whereDate('meeting_date', today())
        ->count();

    $stats = [
        'total' => $totalMeetings,
        'upcoming' => $upcomingCount,
        'today' => $todayCount,
    ];
@endphp

<div class="page-header">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Toutes les réunions</h1>
        <p style="color: var(--text-secondary); margin-top: 0.25rem;">Vue d'ensemble de toutes les réunions de l'entreprise</p>
    </div>
    @if(auth()->user()->can('meetings.create'))
    <div>
        <a href="{{ route('admin.projects.meetings.create', $projects->first()?->id ?? 0) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Planifier une réunion
        </a>
    </div>
    @endif
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total réunions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['upcoming'] }}</div>
        <div class="stat-label">À venir</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['today'] }}</div>
        <div class="stat-label">Aujourd'hui</div>
    </div>
</div>

<div class="filters-container">
    <div class="grid-filters">
        <div>
            <select id="project_id" class="filter-select">
                <option value="">Tous les projets</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
        <div>
            <select id="period" class="filter-select">
                <option value="">Toutes périodes</option>
                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>Passées</option>
            </select>
        </div>
        <div>
            <button onclick="applyFilters()" class="btn-primary" style="width: 100%;">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </div>
    </div>
</div>

@include('admin.meetings.partials.meeting-list', ['meetings' => $meetings])

<script>
    function applyFilters() {
        const projectId = document.getElementById('project_id')?.value;
        const status = document.getElementById('status')?.value;
        const period = document.getElementById('period')?.value;

        let url = '{{ route("admin.meetings.global-index") }}';
        const params = new URLSearchParams();
        if (projectId && projectId !== '') params.append('project_id', projectId);
        if (status && status !== '') params.append('status', status);
        if (period && period !== '') params.append('period', period);

        if (params.toString()) {
            url += '?' + params.toString();
        }
        window.location.href = url;
    }

    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', applyFilters);
    });
</script>

@endsection
