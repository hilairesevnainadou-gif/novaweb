{{-- resources/views/admin/meetings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Réunions - NovaTech Admin')
@section('page-title', 'Gestion des Réunions')

@push('styles')
<style>
    /* ========== STATS GRID ========== */
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

    /* ========== FILTERS CONTAINER ========== */
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

    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .gap-3 {
        gap: 0.75rem;
    }

    @media (min-width: 640px) {
        .sm\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-5 {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }

    /* ========== BUTTONS ========== */
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
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

    /* ========== TABLE ========== */
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .meetings-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .meetings-table thead {
        background: var(--bg-tertiary);
    }

    .meetings-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .meetings-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .meetings-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .meeting-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .meeting-project {
        font-size: 0.7rem;
        color: var(--text-tertiary);
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

    .badge-scheduled { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-in_progress { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-completed { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    /* Actions */
    .actions-cell {
        text-align: right;
        white-space: nowrap;
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .action-btn.delete:hover {
        color: var(--brand-error);
    }

    /* Empty state */
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

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    /* Page header */
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

    /* Animations */
    .table-row {
        animation: fadeInUp 0.3s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    /* Participants list */
    .participants-list {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    .participant-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--brand-primary);
    }

    .participant-count {
        font-size: 0.7rem;
        background: var(--bg-tertiary);
        padding: 0.125rem 0.375rem;
        border-radius: 9999px;
        color: var(--text-tertiary);
    }

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

    .modal-body p {
        margin: 0 0 0.5rem 0;
        line-height: 1.6;
        color: var(--text-secondary);
    }

    .modal-body .warning-text {
        color: #f59e0b;
        font-size: 0.875rem;
        margin-top: 0.75rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }
</style>
@endpush

@section('content')
@can('meetings.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>Réunions</h1>
        <p>Gérez toutes les réunions
            @if(isset($project))
                du projet {{ $project->name }}
            @endif
        </p>
    </div>
    @can('meetings.create')
    <div>
        <a href="{{ isset($project) ? route('admin.projects.meetings.create', $project) : route('admin.meetings.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Planifier une réunion
        </a>
    </div>
    @endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total'] ?? $meetings->total() }}</div>
        <div class="stat-label">Total réunions</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['upcoming'] ?? 0 }}</div>
        <div class="stat-label">À venir</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['today'] ?? 0 }}</div>
        <div class="stat-label">Aujourd'hui</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total_participants'] ?? 0 }}</div>
        <div class="stat-label">Participants total</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher une réunion..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="scheduled">Planifiée</option>
                <option value="in_progress">En cours</option>
                <option value="completed">Terminée</option>
                <option value="cancelled">Annulée</option>
            </select>
        </div>
        <div>
            <select id="period" class="filter-select">
                <option value="">Toutes périodes</option>
                <option value="today">Aujourd'hui</option>
                <option value="upcoming">À venir</option>
                <option value="past">Passées</option>
            </select>
        </div>
        @if(!isset($project))
        <div>
            <select id="project_id" class="filter-select">
                <option value="">Tous projets</option>
                @foreach($projects ?? [] as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <button onclick="resetFilters()" class="btn-reset">
                <i class="fas fa-undo-alt"></i>
                Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Tableau -->
<div class="table-container">
    <table class="meetings-table">
        <thead>
            <tr>
                <th>Réunion</th>
                <th>Projet</th>
                <th>Organisateur</th>
                <th>Participants</th>
                <th>Date & Heure</th>
                <th>Durée</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </table>
        </thead>
        <tbody id="meetingsTableBody">
            @forelse($meetings as $index => $meeting)
            <tr class="table-row"
                data-id="{{ $meeting->id }}"
                data-title="{{ strtolower($meeting->title) }}"
                data-status="{{ $meeting->status }}"
                data-date="{{ $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : '' }}"
                data-project="{{ $meeting->project_id }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div>
                        <div class="meeting-title">{{ $meeting->title }}</div>
                        @if($meeting->description)
                            <div class="meeting-project">{{ Str::limit($meeting->description, 50) }}</div>
                        @endif
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.projects.show', $meeting->project) }}" style="color: var(--text-primary); text-decoration: none;">
                        {{ $meeting->project->name }}
                    </a>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-circle" style="color: var(--brand-primary);"></i>
                        {{ $meeting->organizer->name ?? 'N/A' }}
                    </div>
                </td>
                <td>
                    <div class="participants-list">
                        @php
                            $attendees = $meeting->attendees_list;
                            $displayCount = min(3, $attendees->count());
                            $remaining = $attendees->count() - $displayCount;
                        @endphp
                        @foreach($attendees->take(3) as $attendee)
                            <span class="participant-avatar" title="{{ $attendee->name }}">
                                {{ substr($attendee->name, 0, 1) }}
                            </span>
                        @endforeach
                        @if($remaining > 0)
                            <span class="participant-count">+{{ $remaining }}</span>
                        @elseif($attendees->count() === 0)
                            <span class="participant-count">Aucun</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($meeting->meeting_date)
                        <div>{{ $meeting->meeting_date->format('d/m/Y') }}</div>
                        <div class="meeting-project">{{ $meeting->meeting_date->format('H:i') }}</div>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <span class="meeting-project">{{ $meeting->formatted_duration ?? $meeting->duration_minutes . 'min' }}</span>
                </td>
                <td>
                    <span class="badge badge-{{ $meeting->status }}">
                        <i class="fas
                            @switch($meeting->status)
                                @case('scheduled') fa-calendar-check @break
                                @case('in_progress') fa-spinner fa-pulse @break
                                @case('completed') fa-check-circle @break
                                @case('cancelled') fa-times-circle @break
                            @endswitch
                        "></i>
                        {{ $meeting->status_label }}
                    </span>
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('meetings.edit')
                        <a href="{{ route('admin.meetings.edit', $meeting) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('meetings.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $meeting->id }}"
                                data-title="{{ $meeting->title }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="8" class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Aucune réunion trouvée</p>
                    <p style="font-size: 0.875rem;">Commencez par planifier votre première réunion</p>
                    @can('meetings.create')
                    <a href="{{ isset($project) ? route('admin.projects.meetings.create', $project) : route('admin.meetings.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Planifier une réunion
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($meetings->hasPages())
<div class="pagination-wrapper">
    {{ $meetings->links() }}
</div>
@endif

<!-- Modal de suppression -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Supprimer la réunion</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p class="warning-text">Attention : Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-danger">Supprimer</button>
        </div>
    </div>
</div>
@endcan

@cannot('meetings.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const periodSelect = document.getElementById('period');
    const projectSelect = document.getElementById('project_id');

    // Éléments du modal
    const modal = document.getElementById('confirmationModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    let currentDeleteId = null;

    // Fonctions du modal
    function openModal(id, title) {
        currentDeleteId = id;
        modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer la réunion "${title}" ?`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentDeleteId = null;
    }

    function confirmDelete() {
        if (currentDeleteId) {
            modalConfirmBtn.disabled = true;
            modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

            fetch(`{{ url('admin/meetings') }}/${currentDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success || data.message || data.success === undefined) {
                    // Recharger la page après suppression
                    location.reload();
                } else {
                    alert('Une erreur est survenue');
                    modalConfirmBtn.disabled = false;
                    modalConfirmBtn.innerHTML = 'Supprimer';
                    closeModal();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur technique est survenue');
                modalConfirmBtn.disabled = false;
                modalConfirmBtn.innerHTML = 'Supprimer';
                closeModal();
            });
        }
    }

    modalConfirmBtn.onclick = confirmDelete;

    // Fermer en cliquant sur l'overlay
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeModal();
        }
    };

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Boutons de suppression
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            openModal(id, title);
        });
    });

    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';
        const periodValue = periodSelect?.value || '';
        const projectValue = projectSelect?.value || '';
        const today = new Date().toISOString().split('T')[0];

        const rows = document.querySelectorAll('#meetingsTableBody tr:not(#emptyStateRow)');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const status = row.dataset.status || '';
            const date = row.dataset.date || '';
            const project = row.dataset.project || '';

            if (searchTerm && !title.includes(searchTerm)) {
                show = false;
            }
            if (show && statusValue && status !== statusValue) {
                show = false;
            }
            if (show && projectValue && project !== projectValue) {
                show = false;
            }
            if (show && periodValue) {
                if (periodValue === 'today' && date !== today) {
                    show = false;
                } else if (periodValue === 'upcoming' && date <= today) {
                    show = false;
                } else if (periodValue === 'past' && date >= today) {
                    show = false;
                }
            }

            row.style.display = show ? '' : 'none';
            if (show) hasVisibleRows = true;
        });

        // Afficher un message si aucun résultat
        const noResultsRow = document.getElementById('noResultsRow');
        if (!hasVisibleRows && rows.length > 0) {
            if (!noResultsRow) {
                const tbody = document.getElementById('meetingsTableBody');
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="8" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                    <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-undo-alt"></i> Réinitialiser les filtres
                    </button>
                <\/td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        if (periodSelect) periodSelect.value = '';
        if (projectSelect) projectSelect.value = '';
        filterTable();
    }

    let timer;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (statusSelect) statusSelect.addEventListener('change', filterTable);
    if (periodSelect) periodSelect.addEventListener('change', filterTable);
    if (projectSelect) projectSelect.addEventListener('change', filterTable);

    // Exposer les fonctions globalement
    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
