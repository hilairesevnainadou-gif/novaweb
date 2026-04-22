{{-- resources/views/admin/maintenance/devices/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Appareil - ' . $device->name . ' - NovaTech Admin')
@section('page-title', 'Détail de l\'appareil')

@push('styles')
<style>
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
    }
    .breadcrumb a { color: var(--text-tertiary); transition: color var(--transition-fast); }
    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.625rem; }

    /* Device Header */
    .device-header {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .device-image {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
    }

    .device-image-placeholder {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .device-info {
        flex: 1;
    }

    .device-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: var(--text-primary);
    }

    .device-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .device-meta i {
        width: 1rem;
        margin-right: 0.375rem;
        color: var(--text-tertiary);
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot-operational { background: #10b981; box-shadow: 0 0 4px #10b981; }
    .status-dot-maintenance { background: #f59e0b; box-shadow: 0 0 4px #f59e0b; }
    .status-dot-repair { background: #ef4444; box-shadow: 0 0 4px #ef4444; }
    .status-dot-out_of_service { background: #6b7280; box-shadow: 0 0 4px #6b7280; }

    /* Buttons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--brand-primary);
        color: white;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-fast);
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
        padding: 0.5rem 1rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-fast);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
        border-color: var(--brand-primary);
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
        border-radius: var(--radius-lg);
        padding: 1rem 1.25rem;
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
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
    }

    /* Section Card */
    .section-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
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
    }

    .section-body {
        padding: 1.5rem;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .info-item.col-span-2 {
        grid-column: span 2;
    }

    .info-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .info-value {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    /* Badges */
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

    .badge-operational { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-maintenance { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-repair { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .badge-out_of_service { background: rgba(107, 114, 128, 0.15); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3); }

    .badge-low { background: rgba(107, 114, 128, 0.15); color: #9ca3af; }
    .badge-medium { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .badge-high { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .badge-urgent { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .badge-critical { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }

    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .badge-in-progress { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .badge-completed { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .badge-cancelled { background: rgba(107, 114, 128, 0.15); color: #6b7280; }

    /* Warranty */
    .warranty-active {
        color: #10b981;
        font-weight: 500;
    }

    .warranty-expired {
        color: #ef4444;
        font-weight: 500;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
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
    }

    .data-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Action Button */
    .action-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    /* Empty State */
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

    /* Technical Specs List */
    .specs-list {
        margin: 0;
        padding-left: 1rem;
        list-style: none;
    }

    .specs-list li {
        padding: 0.25rem 0;
        border-bottom: 1px dashed var(--border-light);
    }

    .specs-list li:last-child {
        border-bottom: none;
    }

    .specs-list li strong {
        color: var(--text-secondary);
        min-width: 120px;
        display: inline-block;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid var(--border-light);
    }

    .pagination-wrapper nav {
        display: inline-flex;
        gap: 0.25rem;
    }

    .pagination-wrapper .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        color: var(--text-secondary);
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: var(--brand-primary);
    }

    .pagination-wrapper .active .page-link {
        background: var(--brand-primary);
        color: white;
        border-color: var(--brand-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .info-grid { grid-template-columns: 1fr; }
        .info-item.col-span-2 { grid-column: span 1; }
        .device-header { flex-direction: column; text-align: center; }
        .device-meta { justify-content: center; }
        .specs-list li strong { display: block; margin-bottom: 0.25rem; }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('super-admin');
    $isAdmin = $user->hasRole('admin');
    $isSupport = $user->hasRole('support');
    $isTechnician = $user->hasRole('technician');

    $canEdit = $isSuperAdmin || $isAdmin || $isSupport;
    $canCreateIntervention = $isSuperAdmin || $isAdmin || $isSupport || $isTechnician;
@endphp

<nav class="breadcrumb">
    <a href="{{ route('admin.maintenance.devices.index') }}">Appareils</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $device->name }}</span>
</nav>

<div class="device-header">
    @if($device->image)
        <img src="{{ asset('storage/' . $device->image) }}" alt="{{ $device->name }}" class="device-image">
    @else
        <div class="device-image-placeholder">
            <i class="fas fa-microchip"></i>
        </div>
    @endif

    <div class="device-info">
        <h2>
            {{ $device->name }}
            @if($device->brand || $device->model)
                <span style="font-size: 0.875rem; color: var(--text-tertiary); font-weight: normal;">
                    ({{ trim($device->brand . ' ' . $device->model) }})
                </span>
            @endif
        </h2>
        <div class="device-meta">
            <span><i class="fas fa-barcode"></i> Réf: {{ $device->reference }}</span>
            @if($device->serial_number)
                <span><i class="fas fa-microchip"></i> S/N: {{ $device->serial_number }}</span>
            @endif
            @if($device->client)
                <span><i class="fas fa-building"></i> Client: {{ $device->client->name }}</span>
            @endif
            <span class="status-indicator">
                <span class="status-dot status-dot-{{ $device->status }}"></span>
                {{ $device->status_label }}
            </span>
        </div>
    </div>

    @if($canEdit || $canCreateIntervention)
    <div class="header-actions" style="display: flex; gap: 0.75rem;">
        @if($canEdit)
        <a href="{{ route('admin.maintenance.devices.edit', $device) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
        @endif
        @if($canCreateIntervention)
        <a href="{{ route('admin.maintenance.interventions.create', ['device_id' => $device->id]) }}" class="btn-secondary">
            <i class="fas fa-tools"></i> Nouvelle intervention
        </a>
        @endif
    </div>
    @endif
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $interventions->total() }}</div>
        <div class="stat-label">Total interventions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $interventions->where('status', 'completed')->count() }}</div>
        <div class="stat-label">Terminées</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $interventions->whereIn('status', ['pending', 'approved', 'in_progress'])->count() }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($interventions->sum('actual_cost'), 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Coût total</div>
    </div>
</div>

<!-- Informations techniques -->
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-info-circle"></i> Informations techniques
        </div>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><i class="fas fa-calendar-alt"></i> Date d'achat</span>
                <span class="info-value">{{ $device->purchase_date?->format('d/m/Y') ?? 'Non renseignée' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-shield-alt"></i> Garantie</span>
                <span class="info-value">
                    @if($device->warranty_end_date)
                        jusqu'au {{ $device->warranty_end_date->format('d/m/Y') }}
                        @if($device->warranty_active)
                            <span class="warranty-active">(Active)</span>
                        @else
                            <span class="warranty-expired">(Expirée)</span>
                        @endif
                    @else
                        Non renseignée
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-map-marker-alt"></i> Emplacement</span>
                <span class="info-value">{{ $device->location ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-tag"></i> Catégorie</span>
                <span class="info-value">{{ $device->category_label }}</span>
            </div>
            @if($device->technical_specs && count($device->technical_specs) > 0)
                <div class="info-item col-span-2">
                    <span class="info-label"><i class="fas fa-microchip"></i> Spécifications techniques</span>
                    <span class="info-value">
                        <ul class="specs-list">
                            @foreach($device->technical_specs as $key => $value)
                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Historique des interventions -->
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-history"></i> Historique des interventions
        </div>
        @if($canCreateIntervention)
        <a href="{{ route('admin.maintenance.interventions.create', ['device_id' => $device->id]) }}" class="btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
            <i class="fas fa-plus"></i> Nouvelle intervention
        </a>
        @endif
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Titre</th>
                    <th>Technicien</th>
                    <th>Priorité</th>
                    <th>Niveau</th>
                    <th>Statut</th>
                    <th>Coût</th>
                    <th>Date</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interventions as $intervention)
                <tr>
                    <td><strong>{{ $intervention->intervention_number }}</strong></td>
                    <td>{{ Str::limit($intervention->title, 40) }}</td>
                    <td>
                        @if($intervention->technician)
                            {{ $intervention->technician->name }}
                        @else
                            <span style="color: var(--text-tertiary);">Non assigné</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $intervention->priority }}">
                            {{ $intervention->priority_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(139, 92, 246, 0.15); color: #8b5cf6;">
                            Niveau {{ $intervention->evolution_level }}
                        </span>
                    </td>
                    <td>
                        <span class="badge
                            @switch($intervention->status)
                                @case('pending') badge-pending @break
                                @case('in_progress') badge-in-progress @break
                                @case('completed') badge-completed @break
                                @case('cancelled') badge-cancelled @break
                                @default badge-pending
                            @endswitch
                        ">
                            <i class="fas
                                @switch($intervention->status)
                                    @case('pending') fa-clock @break
                                    @case('in_progress') fa-spinner fa-pulse @break
                                    @case('completed') fa-check-circle @break
                                    @case('cancelled') fa-times-circle @break
                                    @default fa-clock
                                @endswitch
                            "></i>
                            {{ $intervention->status_label }}
                        </span>
                    </td>
                    <td>
                        @if($intervention->actual_cost > 0)
                            {{ number_format($intervention->actual_cost, 0, ',', ' ') }} FCFA
                        @elseif($intervention->estimated_cost > 0)
                            <span style="color: var(--text-tertiary);">{{ number_format($intervention->estimated_cost, 0, ',', ' ') }} FCFA (est.)</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $intervention->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="action-btn" title="Voir l'intervention">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="empty-state">
                        <i class="fas fa-tools"></i>
                        <p>Aucune intervention pour cet appareil</p>
                        @if($canCreateIntervention)
                        <a href="{{ route('admin.maintenance.interventions.create', ['device_id' => $device->id]) }}" class="btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Créer une intervention
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($interventions->hasPages())
    <div class="pagination-wrapper">
        {{ $interventions->links() }}
    </div>
    @endif
</div>

@endsection
