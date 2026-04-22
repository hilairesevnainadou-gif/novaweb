{{-- resources/views/admin/maintenance/devices/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Appareils - Maintenance - NovaTech Admin')
@section('page-title', 'Gestion des appareils')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
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

    .filter-input:focus, .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .grid-filters {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .grid-filters { grid-template-columns: 1fr; }
    }

    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .devices-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .devices-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-tertiary);
    }

    .devices-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .devices-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .device-logo {
        width: 45px;
        height: 45px;
        border-radius: 0.5rem;
        object-fit: cover;
        background: var(--bg-tertiary);
    }

    .device-logo-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    /* ===== BADGES STATUT ===== */
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

    .badge-operational {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-maintenance {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .badge-repair {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .badge-out_of_service {
        background: rgba(107, 114, 128, 0.15);
        color: #9ca3af;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    /* ===== CATEGORY BADGES ===== */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 9999px;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .category-badge i {
        font-size: 0.7rem;
    }

    /* ===== WARRANTY ===== */
    .warranty-active {
        color: #10b981;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .warranty-expired {
        color: #ef4444;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .warranty-expiring {
        color: #f59e0b;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .warranty-date {
        font-size: 0.65rem;
        color: var(--text-tertiary);
        margin-top: 2px;
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
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
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

    .client-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .client-info {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        margin-top: 2px;
    }

    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
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
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('super-admin');
    $isAdmin = $user->hasRole('admin');
    $isSupport = $user->hasRole('support');
    $isTechnician = $user->hasRole('technician');

    $canViewAll = $isSuperAdmin || $isAdmin || $isSupport;
    $canEdit = $isSuperAdmin || $isAdmin || $isSupport;
    $canCreate = $isSuperAdmin || $isAdmin || $isSupport;
@endphp

<div class="page-header">
    <div class="page-title-section">
        <h1>Appareils</h1>
        <p>Gérez votre parc d'appareils</p>
    </div>
    @if($canCreate)
    <div>
        <a href="{{ route('admin.maintenance.devices.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvel appareil
        </a>
    </div>
    @endif
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $devices->total() }}</div>
        <div class="stat-label">Total appareils</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $devices->where('status', 'operational')->count() }}</div>
        <div class="stat-label">Opérationnels</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $devices->where('status', 'maintenance')->count() }}</div>
        <div class="stat-label">En maintenance</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $devices->where('status', 'repair')->count() }}</div>
        <div class="stat-label">En réparation</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $devices->where('status', 'out_of_service')->count() }}</div>
        <div class="stat-label">Hors service</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid-filters">
        <div>
            <input type="text" id="search" placeholder="Rechercher par nom ou référence..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="operational">Opérationnel</option>
                <option value="maintenance">En maintenance</option>
                <option value="repair">En réparation</option>
                <option value="out_of_service">Hors service</option>
            </select>
        </div>
        <div>
            <select id="category" class="filter-select">
                <option value="">Toutes catégories</option>
                <option value="computer">Ordinateur</option>
                <option value="printer">Imprimante</option>
                <option value="network">Réseau</option>
                <option value="phone">Téléphonie</option>
                <option value="other">Autre</option>
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="btn-reset">
                <i class="fas fa-undo-alt"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Tableau -->
<div class="table-container">
    <table class="devices-table">
        <thead>
            <tr>
                <th>Appareil</th>
                <th>Référence</th>
                <th>Marque / Modèle</th>
                <th>Client</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Garantie</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody id="devicesTableBody">
            @forelse($devices as $index => $device)
            <tr class="table-row"
                data-id="{{ $device->id }}"
                data-name="{{ strtolower($device->name) }}"
                data-status="{{ $device->status }}"
                data-category="{{ $device->category }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($device->image)
                            <img src="{{ asset('storage/' . $device->image) }}" alt="{{ $device->name }}" class="device-logo">
                        @else
                            <div class="device-logo-placeholder">
                                <i class="fas fa-microchip"></i>
                            </div>
                        @endif
                        <div>
                            <div class="client-name">{{ $device->name }}</div>
                            @if($device->serial_number)
                            <div class="client-info">SN: {{ $device->serial_number }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-family: monospace; font-size: 0.75rem;">{{ $device->reference }}</span>
                </td>
                <td>
                    @if($device->brand || $device->model)
                        {{ $device->brand ?: '' }} {{ $device->model ?: '' }}
                    @else
                        <span style="color: var(--text-tertiary);">-</span>
                    @endif
                </td>
                <td>
                    @if($device->client)
                        <div class="client-name">{{ $device->client->name }}</div>
                        @if($device->client->short_name)
                        <div class="client-info">{{ $device->client->short_name }}</div>
                        @endif
                    @else
                        <span style="color: var(--text-tertiary);">-</span>
                    @endif
                </td>
                <td>
                    <span class="category-badge">
                        @switch($device->category)
                            @case('computer') <i class="fas fa-laptop"></i> Ordinateur @break
                            @case('printer') <i class="fas fa-print"></i> Imprimante @break
                            @case('network') <i class="fas fa-network-wired"></i> Réseau @break
                            @case('phone') <i class="fas fa-phone"></i> Téléphonie @break
                            @default <i class="fas fa-microchip"></i> Autre
                        @endswitch
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $device->status }}">
                        @switch($device->status)
                            @case('operational') <i class="fas fa-check-circle"></i> Opérationnel @break
                            @case('maintenance') <i class="fas fa-tools"></i> En maintenance @break
                            @case('repair') <i class="fas fa-wrench"></i> En réparation @break
                            @case('out_of_service') <i class="fas fa-ban"></i> Hors service @break
                        @endswitch
                    </span>
                </td>
                <td>
                    @php
                        $warrantyEnd = $device->warranty_end_date;
                        $daysLeft = $warrantyEnd ? now()->diffInDays($warrantyEnd, false) : null;
                    @endphp
                    @if($device->warranty_active && $warrantyEnd)
                        @if($daysLeft !== null && $daysLeft <= 30)
                            <span class="warranty-expiring">
                                <i class="fas fa-exclamation-triangle"></i> Expire bientôt
                            </span>
                        @else
                            <span class="warranty-active">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @endif
                        <div class="warranty-date">jusqu'au {{ $warrantyEnd->format('d/m/Y') }}</div>
                    @elseif($warrantyEnd)
                        <span class="warranty-expired">
                            <i class="fas fa-times-circle"></i> Expirée
                        </span>
                        <div class="warranty-date">depuis le {{ $warrantyEnd->format('d/m/Y') }}</div>
                    @else
                        <span style="color: var(--text-tertiary);">-</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; justify-content: center; gap: 0.5rem;">
                        <a href="{{ route('admin.maintenance.devices.show', $device) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($canEdit)
                        <a href="{{ route('admin.maintenance.devices.edit', $device) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        @if($canCreate)
                        <a href="{{ route('admin.maintenance.interventions.create', ['device_id' => $device->id]) }}" class="action-btn" title="Nouvelle intervention">
                            <i class="fas fa-tools"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-state">
                    <i class="fas fa-microchip"></i>
                    <p>Aucun appareil trouvé</p>
                    @if($canCreate)
                    <a href="{{ route('admin.maintenance.devices.create') }}" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Ajouter un appareil
                    </a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($devices->hasPages())
<div class="pagination-wrapper">
    {{ $devices->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
    (function() {
        'use strict';

        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const categorySelect = document.getElementById('category');

        function filterTable() {
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const statusValue = statusSelect?.value || '';
            const categoryValue = categorySelect?.value || '';

            const rows = document.querySelectorAll('#devicesTableBody tr');
            let hasVisibleRows = false;

            rows.forEach(row => {
                if (row.querySelector('.empty-state')) return;

                let show = true;
                const name = row.dataset.name || '';
                const status = row.dataset.status || '';
                const category = row.dataset.category || '';

                if (searchTerm && !name.includes(searchTerm)) {
                    show = false;
                }
                if (show && statusValue && status !== statusValue) {
                    show = false;
                }
                if (show && categoryValue && category !== categoryValue) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
                if (show) hasVisibleRows = true;
            });

            const tbody = document.getElementById('devicesTableBody');
            let noResultsRow = document.getElementById('noResultsRow');

            if (!hasVisibleRows && rows.length > 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResultsRow';
                    noResultsRow.innerHTML = `<td colspan="8" class="empty-state">
                        <i class="fas fa-search"></i>
                        <p>Aucun résultat ne correspond à vos critères</p>
                        <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-undo-alt"></i> Réinitialiser
                        </button>
                    </td>`;
                    tbody.appendChild(noResultsRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        window.resetFilters = function() {
            if (searchInput) searchInput.value = '';
            if (statusSelect) statusSelect.value = '';
            if (categorySelect) categorySelect.value = '';
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
        if (categorySelect) categorySelect.addEventListener('change', filterTable);
    })();
</script>
@endpush
