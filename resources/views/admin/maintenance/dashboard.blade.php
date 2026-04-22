{{-- resources/views/admin/maintenance/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Maintenance - NovaTech Admin')
@section('page-title', 'Tableau de bord maintenance')

@push('styles')
<style>
    /* ========== STATS GRID - Responsive ========== */
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
    .stat-icon.red { background: rgba(239, 68, 68, 0.1); }
    .stat-icon.red i { color: #ef4444; }
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

    /* ========== SECTION CARDS ========== */
    .section-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .section-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--brand-primary);
        font-size: 1rem;
    }

    .section-body {
        padding: 1.5rem;
    }

    /* ========== TECHNICIAN GRID ========== */
    .technician-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .technician-card {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        border-radius: 0.75rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }

    .technician-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--brand-primary);
    }

    .technician-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-primary), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .technician-info {
        flex: 1;
        min-width: 0;
    }

    .technician-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .technician-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
    }

    .technician-stats i {
        margin-right: 0.25rem;
    }

    .technician-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.6875rem;
        font-weight: 500;
    }

    .technician-badge.busy {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .technician-badge.free {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* ========== TABLE ========== */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .data-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        background: var(--bg-tertiary);
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .data-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .data-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* ========== BADGES ========== */
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

    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-approved { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-in_progress { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .badge-completed { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-cancelled { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-low { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-medium { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-high { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-urgent { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .badge-critical { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    /* ========== ACTION BUTTONS ========== */
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

    /* ========== EMPTY STATE ========== */
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

    /* ========== BUTTONS ========== */
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

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    /* ========== PAGE HEADER ========== */
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

    /* ========== STATUS CARD ========== */
    .status-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .status-item {
        flex: 1;
        min-width: 100px;
        text-align: center;
        padding: 1rem;
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
    }

    .status-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .status-label {
        font-size: 0.75rem;
        color: var(--text-tertiary);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .technician-grid {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();

    // Vérification des permissions individuelles
    $canViewMaintenance = $user->can('maintenance.view');
    $canViewDevices = $user->can('devices.view');
    $canViewInterventions = $user->can('interventions.view');
    $canViewAllInterventions = $user->can('interventions.view.all');
    $canViewStatistics = $user->can('maintenance.statistics');
    $canCreateIntervention = $user->can('interventions.create');
    $canViewCosts = $user->can('billing.view') || $canViewAllInterventions;

    // Déterminer si l'utilisateur peut voir toutes les interventions
    $canViewAll = $canViewAllInterventions;

    // Filtrer les interventions pour le technicien (ceux qui n'ont pas la vue globale)
    $filteredInterventions = $recentInterventions ?? collect();
    if (!$canViewAll && $canViewInterventions) {
        $filteredInterventions = $filteredInterventions->where('technician_id', $user->id);
    }

    // Stats pour utilisateur sans vue globale (technicien)
    $myStats = [
        'pending_interventions' => 0,
        'in_progress_interventions' => 0,
        'completed_this_month' => 0,
    ];
    if (!$canViewAll && $canViewInterventions) {
        $myStats['pending_interventions'] = \App\Models\Intervention::where('technician_id', $user->id)->pending()->count();
        $myStats['in_progress_interventions'] = \App\Models\Intervention::where('technician_id', $user->id)->inProgress()->count();
        $myStats['completed_this_month'] = \App\Models\Intervention::where('technician_id', $user->id)
            ->completed()
            ->whereMonth('end_date', now()->month)
            ->count();
    }
@endphp

@if($canViewMaintenance)
<div class="page-header">
    <div class="page-title-section">
        <h1>Maintenance</h1>
        <p>
            @if(!$canViewAll && $canViewInterventions)
                Vos interventions et votre activité
            @else
                Gestion des interventions et appareils
            @endif
        </p>
    </div>
    @if($canCreateIntervention)
    <div>
        <a href="{{ route('admin.maintenance.interventions.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle intervention
        </a>
    </div>
    @endif
</div>

<!-- Première ligne de statistiques -->
<div class="stats-grid">
    @if($canViewAll)
    <!-- Vue Admin/Support - avec appareils et coûts -->
    @if($canViewDevices)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-microchip"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_devices'] ?? 0 }}</div>
        <div class="stat-label">Appareils</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow"><i class="fas fa-tools"></i></div>
        </div>
        <div class="stat-value">{{ $stats['under_maintenance'] ?? 0 }}</div>
        <div class="stat-label">En maintenance</div>
    </div>
    @endif
    @if($canViewInterventions)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['urgent_interventions'] ?? 0 }}</div>
        <div class="stat-label">Urgentes</div>
    </div>
    @endif
    @if($canViewCosts)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_cost_this_month'] ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Coût mensuel</div>
    </div>
    @endif
    @else
    <!-- Vue Technicien - SANS COÛTS, SANS APPARELS -->
    @if($canViewInterventions)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $myStats['pending_interventions'] }}</div>
        <div class="stat-label">En attente</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-play-circle"></i></div>
        </div>
        <div class="stat-value">{{ $myStats['in_progress_interventions'] }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $myStats['completed_this_month'] }}</div>
        <div class="stat-label">Terminées ce mois</div>
    </div>
    @endif
    @if($canViewStatistics)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow"><i class="fas fa-star"></i></div>
        </div>
        <div class="stat-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }} / 5</div>
        <div class="stat-label">Note moyenne</div>
    </div>
    @endif
    @endif
</div>

<!-- Deuxième ligne de statistiques (uniquement pour ceux qui voient tout) -->
@if($canViewAll && $canViewInterventions)
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending_interventions'] ?? 0 }}</div>
        <div class="stat-label">En attente</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-play-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['in_progress_interventions'] ?? 0 }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['completed_this_month'] ?? 0 }}</div>
        <div class="stat-label">Terminées ce mois</div>
    </div>
    @if($canViewStatistics)
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow"><i class="fas fa-star"></i></div>
        </div>
        <div class="stat-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }} / 5</div>
        <div class="stat-label">Note moyenne</div>
    </div>
    @endif
</div>
@endif

<!-- Interventions récentes -->
@if($canViewInterventions && $filteredInterventions->count() > 0)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-list"></i>
            @if(!$canViewAll)
                Mes interventions récentes
            @else
                Interventions récentes
            @endif
        </div>
        <a href="{{ route('admin.maintenance.interventions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-right"></i> Voir toutes
        </a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Appareil</th>
                    <th>Client</th>
                    @if($canViewAll)
                    <th>Technicien</th>
                    @endif
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredInterventions->take(5) as $intervention)
                <tr>
                    <td><strong>{{ $intervention->intervention_number }}</strong></td>
                    <td>{{ $intervention->device->name ?? 'N/A' }}</td>
                    <td>{{ $intervention->client->short_name ?? 'N/A' }}</td>
                    @if($canViewAll)
                    <td>{{ $intervention->technician->name ?? 'Non assigné' }}</td>
                    @endif
                    <td>
                        <span class="badge badge-{{ $intervention->priority }}">
                            {{ $intervention->priority_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '', $intervention->status) }}">
                            {{ $intervention->status_label }}
                        </span>
                    </td>
                    <td>{{ $intervention->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $canViewAll ? '8' : '7' }}" class="empty-state">
                        <i class="fas fa-tools"></i>
                        <p>Aucune intervention récente</p>
                        @if($canCreateIntervention)
                        <a href="{{ route('admin.maintenance.interventions.create') }}" class="btn-primary" style="margin-top: 0.5rem;">
                            <i class="fas fa-plus"></i> Créer une intervention
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Techniciens (uniquement pour ceux qui voient tout) -->
@if($canViewAll && isset($technicians) && $technicians->count() > 0 && $canViewInterventions)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-users"></i> Techniciens
        </div>
    </div>
    <div class="section-body">
        <div class="technician-grid">
            @forelse($technicians as $technician)
            <div class="technician-card">
                <div class="technician-avatar">
                    {{ strtoupper(substr($technician->name, 0, 2)) }}
                </div>
                <div class="technician-info">
                    <div class="technician-name">{{ $technician->name }}</div>
                    <div class="technician-stats">
                        <span><i class="fas fa-tools"></i> {{ $technician->interventions_count ?? 0 }} intervention(s)</span>
                        <span class="technician-badge {{ ($technician->interventions_count ?? 0) > 0 ? 'busy' : 'free' }}">
                            {{ ($technician->interventions_count ?? 0) > 0 ? 'Occupé' : 'Disponible' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-user-cog"></i>
                <p>Aucun technicien enregistré</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endif

<!-- État des appareils (uniquement pour ceux qui voient les appareils) -->
@if($canViewDevices && $canViewAll)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-microchip"></i> État des appareils
        </div>
        <a href="{{ route('admin.maintenance.devices.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-right"></i> Gérer
        </a>
    </div>
    <div class="section-body">
        <div class="status-cards">
            @php
                $statusConfig = [
                    'operational' => ['label' => 'Opérationnels', 'icon' => 'fa-check-circle', 'color' => '#10b981'],
                    'maintenance' => ['label' => 'En maintenance', 'icon' => 'fa-tools', 'color' => '#f59e0b'],
                    'repair' => ['label' => 'En réparation', 'icon' => 'fa-wrench', 'color' => '#ef4444'],
                    'out_of_service' => ['label' => 'Hors service', 'icon' => 'fa-ban', 'color' => '#6b7280']
                ];
            @endphp
            @foreach($statusConfig as $statusKey => $config)
                @php $count = $devicesByStatus[$statusKey] ?? 0; @endphp
                <div class="status-item">
                    <div class="status-count">{{ $count }}</div>
                    <div class="status-label">
                        <i class="fas {{ $config['icon'] }}" style="color: {{ $config['color'] }};"></i>
                        {{ $config['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Message pour utilisateur sans interventions -->
@if(!$canViewAll && $canViewInterventions && $filteredInterventions->count() == 0)
<div class="section-card">
    <div class="section-body" style="text-align: center; padding: 2rem;">
        <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--brand-success); margin-bottom: 1rem; display: block;"></i>
        <h3 style="margin-bottom: 0.5rem;">Aucune intervention</h3>
        <p style="color: var(--text-secondary);">Vous n'avez aucune intervention en cours ou récente.</p>
    </div>
</div>
@endif

@else
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection
