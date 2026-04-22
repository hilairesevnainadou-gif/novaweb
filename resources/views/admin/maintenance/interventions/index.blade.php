{{-- resources/views/admin/maintenance/interventions/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Interventions - Maintenance - NovaTech Admin')
@section('page-title', 'Gestion des interventions')

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

    .filter-input:focus, .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .grid-filters {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .grid-filters { grid-template-columns: repeat(2, 1fr); }
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

    .interventions-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .interventions-table th {
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

    .interventions-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .interventions-table tbody tr:hover {
        background: var(--bg-hover);
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

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
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

    .priority-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }

    .priority-low { background: #6b7280; box-shadow: 0 0 4px #6b7280; }
    .priority-medium { background: #3b82f6; box-shadow: 0 0 4px #3b82f6; }
    .priority-high { background: #f59e0b; box-shadow: 0 0 4px #f59e0b; }
    .priority-urgent { background: #ef4444; box-shadow: 0 0 4px #ef4444; }
    .priority-critical { background: #8b5cf6; box-shadow: 0 0 4px #8b5cf6; }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-elevated);
        border-radius: 0.75rem;
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 450px;
        transform: scale(0.95);
        transition: transform 0.3s ease;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-overlay.active .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1.125rem;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: var(--text-primary);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .required {
        color: var(--brand-error);
    }

    .client-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .client-short {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();

    // Vérification des permissions individuelles
    $canViewAllInterventions = $user->can('interventions.view.all');
    $canViewInterventions = $user->can('interventions.view');
    $canViewCosts = $user->can('billing.view') || $canViewAllInterventions;
    $canAssignTechnician = $user->can('interventions.assign');
    $canCreateIntervention = $user->can('interventions.create');
    $canEditIntervention = $user->can('interventions.edit');
    $canViewStatistics = $user->can('maintenance.statistics');

    // Pour le technicien : ne peut modifier que ses propres interventions
    $canEditOwnIntervention = $canEditIntervention && !$canViewAllInterventions;

    // Déterminer le rôle pour l'affichage du texte
    $isTechnician = $user->hasRole('technician');
@endphp

@if($canViewInterventions)
<div class="page-header">
    <div class="page-title-section">
        <h1>Interventions</h1>
        <p>
            @if(!$canViewAllInterventions && $isTechnician)
                Interventions qui vous sont assignées
            @elseif($canViewAllInterventions)
                Gérez toutes les interventions techniques
            @else
                Liste des interventions
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

<!-- Statistiques -->
@if($canViewStatistics || $canViewAllInterventions)
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total'] ?? $interventions->total() }}</div>
        <div class="stat-label">Total interventions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
        <div class="stat-label">En attente</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['completed_this_month'] ?? 0 }}</div>
        <div class="stat-label">Terminées ce mois</div>
    </div>
    @if($canViewCosts)
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['total_cost'] ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Coût total</div>
    </div>
    @endif
</div>
@endif

<!-- Filtres -->
<div class="filters-container">
    <div class="grid-filters">
        <div>
            <input type="text" id="search" placeholder="Rechercher..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="pending">En attente</option>
                <option value="approved">Approuvée</option>
                <option value="in_progress">En cours</option>
                <option value="completed">Terminée</option>
                <option value="cancelled">Annulée</option>
            </select>
        </div>
        <div>
            <select id="priority" class="filter-select">
                <option value="">Toutes priorités</option>
                <option value="low">Basse</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
                <option value="urgent">Urgente</option>
                <option value="critical">Critique</option>
            </select>
        </div>
        @if($canViewAllInterventions)
        <div>
            <select id="technician_id" class="filter-select">
                <option value="">Tous techniciens</option>
                @foreach($technicians ?? [] as $technician)
                    <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div>
            <div style="padding: 0.625rem 1rem; background: var(--bg-tertiary); border-radius: 0.5rem; color: var(--text-tertiary); text-align: center;">
                <i class="fas fa-user"></i> Filtré par vos interventions
            </div>
        </div>
        @endif
        <div>
            <button onclick="resetFilters()" class="btn-reset">
                <i class="fas fa-undo-alt"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Tableau -->
<div class="table-container">
    <table class="interventions-table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Appareil</th>
                <th>Client</th>
                @if($canViewAllInterventions)
                <th>Technicien</th>
                @endif
                <th>Priorité</th>
                <th>Niveau</th>
                <th>Statut</th>
                @if($canViewCosts)
                <th>Coût</th>
                @endif
                <th>Date</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody id="interventionsTableBody">
            @forelse($interventions as $index => $intervention)
            <tr class="table-row"
                data-id="{{ $intervention->id }}"
                data-number="{{ $intervention->intervention_number }}"
                data-status="{{ $intervention->status }}"
                data-priority="{{ $intervention->priority }}"
                data-technician="{{ $intervention->technician_id }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <strong>{{ $intervention->intervention_number }}</strong>
                </td>
                <td>
                    <div>
                        <div style="font-weight: 500;">{{ $intervention->device->name ?? 'N/A' }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ Str::limit($intervention->title, 40) }}</div>
                    </div>
                </td>
                <td>
                    @if($intervention->client)
                        <div class="client-name">{{ $intervention->client->name ?? 'N/A' }}</div>
                        @if($intervention->client->short_name)
                        <div class="client-short">{{ $intervention->client->short_name }}</div>
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                @if($canViewAllInterventions)
                <td>
                    @if($intervention->technician)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-user-circle" style="color: var(--brand-primary);"></i>
                            {{ $intervention->technician->name }}
                        </div>
                    @else
                        <span style="color: var(--text-tertiary);">Non assigné</span>
                    @endif
                </td>
                @endif
                <td>
                    <span class="priority-indicator priority-{{ $intervention->priority }}"></span>
                    <span class="badge badge-{{ $intervention->priority }}">
                        {{ $intervention->priority_label }}
                    </span>
                </td>
                <td>
                    <span class="badge" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        Niveau {{ $intervention->evolution_level }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ str_replace('_', '', $intervention->status) }}">
                        <i class="fas
                            @switch($intervention->status)
                                @case('pending') fa-clock @break
                                @case('approved') fa-check-circle @break
                                @case('in_progress') fa-spinner fa-pulse @break
                                @case('completed') fa-check-circle @break
                                @case('cancelled') fa-times-circle @break
                            @endswitch
                        "></i>
                        {{ $intervention->status_label }}
                    </span>
                </td>
                @if($canViewCosts)
                <td>
                    @if($intervention->actual_cost > 0)
                        <span style="font-weight: 600; color: var(--text-primary);">{{ number_format($intervention->actual_cost, 0, ',', ' ') }} FCFA</span>
                    @elseif($intervention->estimated_cost > 0)
                        <span style="color: var(--text-tertiary);">{{ number_format($intervention->estimated_cost, 0, ',', ' ') }} FCFA (est.)</span>
                    @else
                        -
                    @endif
                </td>
                @endif
                <td>{{ $intervention->created_at->format('d/m/Y') }}</td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 0.25rem; justify-content: center;">
                        {{-- Voir - tout le monde peut voir --}}
                        <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- Modifier - selon permissions --}}
                        @if($canEditIntervention && $canViewAllInterventions)
                            <a href="{{ route('admin.maintenance.interventions.edit', $intervention) }}" class="action-btn" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                        @elseif($canEditOwnIntervention && $intervention->technician_id == $user->id)
                            <a href="{{ route('admin.maintenance.interventions.edit', $intervention) }}" class="action-btn" title="Modifier (limité)">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif

                        {{-- Assigner un technicien --}}
                        @if($canAssignTechnician && $intervention->status === 'pending' && $canViewAllInterventions)
                            <button type="button" class="action-btn assign-btn"
                                    data-id="{{ $intervention->id }}"
                                    data-number="{{ $intervention->intervention_number }}"
                                    title="Assigner un technicien">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $canViewAllInterventions ? ($canViewCosts ? 9 : 8) : ($canViewCosts ? 8 : 7) }}" class="empty-state">
                    <i class="fas fa-tools"></i>
                    <p>
                        @if(!$canViewAllInterventions && $isTechnician)
                            Aucune intervention ne vous est assignée
                        @else
                            Aucune intervention trouvée
                        @endif
                    </p>
                    @if($canCreateIntervention)
                    <a href="{{ route('admin.maintenance.interventions.create') }}" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Créer une intervention
                    </a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($interventions->hasPages())
<div class="pagination-wrapper" style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $interventions->links() }}
</div>
@endif

<!-- Modal d'assignation -->
@if($canAssignTechnician && $canViewAllInterventions)
<div id="assignModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Assigner un technicien</h3>
            <button type="button" class="modal-close" id="closeModalBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="assignForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <p>Intervention : <strong id="assignInterventionNumber"></strong></p>
                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Technicien <span class="required">*</span></label>
                    <select name="technician_id" id="technicianSelect" class="form-control" required>
                        <option value="">Sélectionner un technicien</option>
                        @foreach($technicians ?? [] as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelModalBtn">Annuler</button>
                <button type="submit" class="btn-primary" id="confirmAssignBtn">Assigner</button>
            </div>
        </form>
    </div>
</div>
@endif

@else
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function() {
        'use strict';

        @php
            $user = auth()->user();
            $canViewAllInterventions = $user->can('interventions.view.all');
            $canViewInterventions = $user->can('interventions.view');
        @endphp

        const canViewAllInterventions = @json($canViewAllInterventions);
        const canViewInterventions = @json($canViewInterventions);

        if (canViewInterventions) {
            // Filtres
            const searchInput = document.getElementById('search');
            const statusSelect = document.getElementById('status');
            const prioritySelect = document.getElementById('priority');
            const technicianSelect = document.getElementById('technician_id');

            function filterTable() {
                const searchTerm = searchInput?.value.toLowerCase() || '';
                const statusValue = statusSelect?.value || '';
                const priorityValue = prioritySelect?.value || '';
                const technicianValue = technicianSelect?.value || '';

                const rows = document.querySelectorAll('#interventionsTableBody tr');
                let hasVisibleRows = false;

                rows.forEach(row => {
                    if (row.querySelector('.empty-state')) return;

                    let show = true;
                    const number = row.dataset.number || '';
                    const status = row.dataset.status || '';
                    const priority = row.dataset.priority || '';
                    const technician = row.dataset.technician || '';

                    if (searchTerm && !number.toLowerCase().includes(searchTerm)) {
                        show = false;
                    }
                    if (show && statusValue && status !== statusValue) {
                        show = false;
                    }
                    if (show && priorityValue && priority !== priorityValue) {
                        show = false;
                    }
                    if (show && technicianValue && technician !== technicianValue) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';
                    if (show) hasVisibleRows = true;
                });

                const tbody = document.getElementById('interventionsTableBody');
                let noResultsRow = document.getElementById('noResultsRow');

                if (!hasVisibleRows && rows.length > 0) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.id = 'noResultsRow';
                        const colCount = canViewAllInterventions ? 9 : 8;
                        noResultsRow.innerHTML = `<td colspan="${colCount}" class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>Aucun résultat ne correspond à vos critères</p>
                            <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-undo-alt"></i> Réinitialiser
                            </button>
                        <\/td>`;
                        tbody.appendChild(noResultsRow);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            }

            window.resetFilters = function() {
                if (searchInput) searchInput.value = '';
                if (statusSelect) statusSelect.value = '';
                if (prioritySelect) prioritySelect.value = '';
                if (technicianSelect && canViewAllInterventions) technicianSelect.value = '';
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
            if (technicianSelect && canViewAllInterventions) {
                technicianSelect.addEventListener('change', filterTable);
            }

            // Modal d'assignation
            @if($canAssignTechnician && $canViewAllInterventions)
            const assignModal = document.getElementById('assignModal');
            const assignForm = document.getElementById('assignForm');
            const assignInterventionNumber = document.getElementById('assignInterventionNumber');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            let currentInterventionId = null;

            function openAssignModal(interventionId, interventionNumber) {
                currentInterventionId = interventionId;
                assignInterventionNumber.textContent = interventionNumber;
                assignForm.action = "{{ url('admin/maintenance/interventions') }}/" + interventionId + "/assign";
                assignModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeAssignModal() {
                assignModal.classList.remove('active');
                document.body.style.overflow = '';
                currentInterventionId = null;
                const select = document.getElementById('technicianSelect');
                if (select) select.value = '';
            }

            document.querySelectorAll('.assign-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = this.dataset.id;
                    const number = this.dataset.number;
                    openAssignModal(id, number);
                });
            });

            if (closeModalBtn) closeModalBtn.addEventListener('click', closeAssignModal);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeAssignModal);

            assignModal.addEventListener('click', function(e) {
                if (e.target === assignModal) {
                    closeAssignModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && assignModal.classList.contains('active')) {
                    closeAssignModal();
                }
            });

            if (assignForm) {
                assignForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('confirmAssignBtn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assignation...';
                });
            }
            @endif
        }
    })();
</script>
@endpush
