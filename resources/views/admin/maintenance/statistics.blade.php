{{-- resources/views/admin/maintenance/statistics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Statistiques - Maintenance - NovaTech Admin')
@section('page-title', 'Statistiques de maintenance')

@push('styles')
<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
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

    /* Stats Grid */
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

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
    }

    /* Section Card */
    .section-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        padding: 0.875rem 1.25rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary);
    }

    .section-title i {
        color: var(--brand-primary);
    }

    .section-body {
        padding: 1.25rem;
    }

    /* Chart Containers */
    .chart-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .chart-wrapper {
        background: var(--bg-tertiary);
        border-radius: 0.75rem;
        padding: 1rem;
        height: 320px;
        position: relative;
    }

    .chart-wrapper canvas {
        max-height: 260px;
        width: 100% !important;
    }

    /* Data Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        background: var(--bg-tertiary);
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Progress Bar */
    .progress-bar {
        height: 0.5rem;
        background: var(--bg-tertiary);
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary));
        border-radius: 9999px;
        transition: width 0.3s;
    }

    /* Year Selector */
    .year-selector {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .year-select {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .year-select:hover {
        border-color: var(--brand-primary);
    }

    /* Problem Cards */
    .problems-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
    }

    .problem-card {
        text-align: center;
        padding: 1rem;
        background: var(--bg-tertiary);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .problem-card:hover {
        transform: translateY(-2px);
        background: var(--bg-hover);
    }

    .problem-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--brand-primary);
        margin-bottom: 0.25rem;
    }

    .problem-label {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-tertiary);
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    /* Technician Avatar */
    .tech-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .chart-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .chart-wrapper {
            height: auto;
            min-height: 300px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .problems-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h1>Statistiques</h1>
        <p>Analyse des interventions et performances</p>
    </div>
    <div class="year-selector">
        <label for="yearSelect" style="font-size: 0.875rem; color: var(--text-secondary);">Année :</label>
        <select id="yearSelect" class="year-select" onchange="window.location.href='{{ route('admin.maintenance.statistics') }}?year='+this.value">
            @for($y = now()->year - 2; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
</div>

<!-- Statistiques globales -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($interventionsByMonth->sum('total') ?? 0, 0, ',', ' ') }}</div>
        <div class="stat-label">Total interventions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($interventionsByMonth->sum('completed') ?? 0, 0, ',', ' ') }}</div>
        <div class="stat-label">Terminées</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($costsByMonth->sum('actual') ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Coût total réel</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">
            @php
                $ecart = ($costsByMonth->sum('actual') - $costsByMonth->sum('estimated')) ?? 0;
                $ecartClass = $ecart >= 0 ? 'text-red' : 'text-green';
            @endphp
            <span style="color: {{ $ecart >= 0 ? '#ef4444' : '#10b981' }};">
                {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} FCFA
            </span>
        </div>
        <div class="stat-label">Écart budget</div>
    </div>
</div>

<!-- Graphiques côte à côte -->
<div class="chart-row">
    <!-- Graphique des interventions par mois -->
    <div class="section-card" style="margin-bottom: 0;">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-line"></i> Évolution des interventions
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="interventionsChart"></canvas>
        </div>
    </div>

    <!-- Graphique des coûts -->
    <div class="section-card" style="margin-bottom: 0;">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-bar"></i> Coûts estimés vs réels
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="costsChart"></canvas>
        </div>
    </div>
</div>

<!-- Top techniciens -->
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-trophy"></i> Top techniciens
        </div>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Technicien</th>
                    <th>Interventions</th>
                    <th>Temps total</th>
                    <th>Temps moyen</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topTechnicians ?? [] as $technician)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="tech-avatar">
                                {{ strtoupper(substr($technician->name, 0, 2)) }}
                            </div>
                            <span style="font-weight: 500;">{{ $technician->name }}</span>
                        </div>
                    </td>
                    <td>{{ number_format($technician->interventions_count ?? 0) }}</td>
                    <td>
                        @php
                            $hours = floor(($technician->interventions_sum_duration_minutes ?? 0) / 60);
                            $minutes = ($technician->interventions_sum_duration_minutes ?? 0) % 60;
                        @endphp
                        {{ $hours }}h {{ $minutes }}min
                    </td>
                    <td>
                        @php
                            $avg = ($technician->interventions_count ?? 0) > 0
                                ? round(($technician->interventions_sum_duration_minutes ?? 0) / ($technician->interventions_count ?? 1))
                                : 0;
                            $avgHours = floor($avg / 60);
                            $avgMinutes = $avg % 60;
                        @endphp
                        {{ $avgHours }}h {{ $avgMinutes }}min
                    </td>
                    <td>
                        @php
                            $maxInterventions = $topTechnicians->max('interventions_count') ?? 1;
                            $percentage = ($technician->interventions_count / $maxInterventions) * 100;
                        @endphp
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <div style="font-size: 0.7rem; margin-top: 0.25rem; color: var(--text-tertiary);">{{ round($percentage) }}% du top</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="fas fa-chart-line"></i>
                        <p>Aucune donnée disponible</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Problèmes fréquents et Temps moyen -->
<div class="chart-row">
    <!-- Problèmes fréquents -->
    <div class="section-card" style="margin-bottom: 0;">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-pie"></i> Types de problèmes fréquents
            </div>
        </div>
        <div class="section-body">
            <div class="problems-grid">
                @forelse($commonProblems ?? [] as $problem)
                <div class="problem-card">
                    <div class="problem-number">{{ $problem->total }}</div>
                    <div class="problem-label">
                        @switch($problem->problem_type)
                            @case('hardware') <i class="fas fa-microchip"></i> Matériel @break
                            @case('software') <i class="fas fa-code"></i> Logiciel @break
                            @case('network') <i class="fas fa-network-wired"></i> Réseau @break
                            @case('electrical') <i class="fas fa-bolt"></i> Électrique @break
                            @case('mechanical') <i class="fas fa-cogs"></i> Mécanique @break
                            @default <i class="fas fa-question"></i> Autre
                        @endswitch
                    </div>
                </div>
                @empty
                <div class="empty-state" style="grid-column: span 3;">
                    <i class="fas fa-chart-pie"></i>
                    <p>Aucune donnée disponible</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Temps moyen par type de problème -->
    <div class="section-card" style="margin-bottom: 0;">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-hourglass-half"></i> Temps moyen par type
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Type de problème</th>
                        <th>Temps moyen</th>
                        <th>Progression</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avgDurationByProblem ?? [] as $problem)
                    <tr>
                        <td>
                            @switch($problem->problem_type)
                                @case('hardware') <i class="fas fa-microchip"></i> Matériel @break
                                @case('software') <i class="fas fa-code"></i> Logiciel @break
                                @case('network') <i class="fas fa-network-wired"></i> Réseau @break
                                @case('electrical') <i class="fas fa-bolt"></i> Électrique @break
                                @case('mechanical') <i class="fas fa-cogs"></i> Mécanique @break
                                @default <i class="fas fa-question"></i> Autre
                            @endswitch
                        </td>
                        <td>
                            @php
                                $hours = floor($problem->avg_duration / 60);
                                $minutes = round($problem->avg_duration % 60);
                            @endphp
                            {{ $hours }}h {{ $minutes }}min
                        </td>
                        <td>
                            @php
                                $maxDuration = $avgDurationByProblem->max('avg_duration') ?? 1;
                                $percentage = ($problem->avg_duration / $maxDuration) * 100;
                            @endphp
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="empty-state">
                            <i class="fas fa-hourglass"></i>
                            <p>Aucune donnée disponible</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        'use strict';

        // Données pour le graphique des interventions
        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        const interventionsData = [];
        const completedData = [];

        @for($i = 1; $i <= 12; $i++)
            interventionsData.push({{ $interventionsByMonth[$i]->total ?? 0 }});
            completedData.push({{ $interventionsByMonth[$i]->completed ?? 0 }});
        @endfor

        // Détecter le thème
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#ededed' : '#1a1a2e';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

        // Graphique des interventions
        const interventionsCtx = document.getElementById('interventionsChart')?.getContext('2d');
        if (interventionsCtx) {
            new Chart(interventionsCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Total interventions',
                            data: interventionsData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: isDark ? '#1a1a1e' : '#ffffff',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Terminées',
                            data: completedData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: isDark ? '#1a1a1e' : '#ffffff',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            labels: { color: textColor, font: { size: 11 } },
                            position: 'top'
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1a1a1e' : '#ffffff',
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: gridColor,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor, stepSize: 1 },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        // Données pour le graphique des coûts
        const estimatedData = [];
        const actualData = [];

        @for($i = 1; $i <= 12; $i++)
            estimatedData.push({{ $costsByMonth[$i]->estimated ?? 0 }});
            actualData.push({{ $costsByMonth[$i]->actual ?? 0 }});
        @endfor

        const costsCtx = document.getElementById('costsChart')?.getContext('2d');
        if (costsCtx) {
            new Chart(costsCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Coût estimé',
                            data: estimatedData,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: '#3b82f6',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Coût réel',
                            data: actualData,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: '#ef4444',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            labels: { color: textColor, font: { size: 11 } },
                            position: 'top'
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1a1a1e' : '#ffffff',
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: gridColor,
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toLocaleString('fr-FR') + ' FCFA';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M FCFA';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'k FCFA';
                                    return value + ' FCFA';
                                }
                            },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        // Observer les changements de thème
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-theme') {
                    window.location.reload();
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });
    })();
</script>
@endpush
