{{-- resources/views/admin/tasks/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tâches - NovaTech Admin')
@section('page-title', 'Gestion des Tâches')

@push('styles')
<style>
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
        .lg\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
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

    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .tasks-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .tasks-table thead {
        background: var(--bg-tertiary);
    }

    .tasks-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .tasks-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .tasks-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .task-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .task-number {
        font-size: 0.7rem;
        color: var(--text-tertiary);
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

    .badge-todo { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-in_progress { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-review { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .badge-completed { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .badge-low { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-medium { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-high { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-urgent { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .priority-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }
    .priority-low { background: #6b7280; }
    .priority-medium { background: #3b82f6; }
    .priority-high { background: #f59e0b; }
    .priority-urgent { background: #ef4444; }

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

    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
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

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    .overdue-text {
        color: #ef4444;
        font-size: 0.7rem;
    }
</style>
@endpush

@section('content')
@can('tasks.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>Tâches</h1>
        <p>Gérez toutes les tâches des projets
            @if(isset($project))
                - {{ $project->name }}
            @endif
        </p>
    </div>
    @can('tasks.create')
<div>
    @if(isset($project))
        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle tâche
        </a>
    @else
        <a href="{{ route('admin.tasks.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle tâche
        </a>
    @endif
</div>
@endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
        <div class="stat-value">{{ $tasks->total() }}</div>
        <div class="stat-label">Total tâches</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-spinner fa-pulse"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
        <div class="stat-label">Terminées</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['overdue'] ?? 0 }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher une tâche..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="todo">À faire</option>
                <option value="in_progress">En cours</option>
                <option value="review">En revue</option>
                <option value="approved">Approuvée</option>
                <option value="rejected">Rejetée</option>
                <option value="completed">Terminée</option>
            </select>
        </div>
        <div>
            <select id="priority" class="filter-select">
                <option value="">Toutes priorités</option>
                <option value="low">Basse</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
                <option value="urgent">Urgente</option>
            </select>
        </div>
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
    <table class="tasks-table">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Projet</th>
                <th>Assignée à</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Heures</th>
                <th>Date échéance</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="tasksTableBody">
            @forelse($tasks as $index => $task)
            <tr class="table-row"
                data-id="{{ $task->id }}"
                data-title="{{ strtolower($task->title) }}"
                data-number="{{ $task->task_number }}"
                data-status="{{ $task->status }}"
                data-priority="{{ $task->priority }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div>
                        <div class="task-title">{{ $task->title }}</div>
                        <div class="task-number">{{ $task->task_number }}</div>
                    </div>
                </td>
                <td>
                    <div>
                        <a href="{{ route('admin.projects.show', $task->project) }}" style="color: var(--text-primary); text-decoration: none;">
                            {{ $task->project->name }}
                        </a>
                        <div class="task-number">{{ $task->task_type_label }}</div>
                    </div>
                </td>
                <td>
                    @if($task->assignee)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-user-circle" style="color: var(--brand-primary);"></i>
                            {{ $task->assignee->name }}
                        </div>
                    @else
                        <span style="color: var(--text-tertiary);">Non assignée</span>
                    @endif
                </td>
                <td>
                    <span class="priority-indicator priority-{{ $task->priority }}"></span>
                    <span class="badge badge-{{ $task->priority }}">
                        {{ $task->priority_label }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ str_replace('_', '', $task->status) }}">
                        <i class="fas
                            @switch($task->status)
                                @case('todo') fa-circle @break
                                @case('in_progress') fa-spinner fa-pulse @break
                                @case('review') fa-eye @break
                                @case('approved') fa-check-circle @break
                                @case('rejected') fa-times-circle @break
                                @case('completed') fa-check-double @break
                            @endswitch
                        "></i>
                        {{ $task->status_label }}
                    </span>
                </td>
                <td>
                    <div class="task-number">
                        {{ $task->estimated_hours }}h / {{ $task->actual_hours }}h
                    </div>
                </td>
                <td>
                    @if($task->due_date)
                        <div>{{ $task->due_date->format('d/m/Y') }}</div>
                        @if($task->is_overdue)
                            <div class="overdue-text"><i class="fas fa-clock"></i> En retard</div>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('tasks.edit')
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('tasks.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $task->id }}"
                                data-title="{{ $task->title }}"
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
                    <i class="fas fa-tasks"></i>
                    <p>Aucune tâche trouvée</p>
                    <p style="font-size: 0.875rem;">Commencez par créer votre première tâche</p>
                    @can('tasks.create')
                    <a href="{{ isset($project) ? route('admin.projects.tasks.create', $project) : route('admin.tasks.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Créer une tâche
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($tasks->hasPages())
<div class="pagination-wrapper">
    {{ $tasks->links() }}
</div>
@endif

<!-- Modal de suppression -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Supprimer la tâche</h3>
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

@cannot('tasks.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const prioritySelect = document.getElementById('priority');

    const modal = document.getElementById('confirmationModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    let currentDeleteId = null;

    function openModal(id, title) {
        currentDeleteId = id;
        modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer la tâche "${title}" ?`;
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

            fetch(`{{ url('admin/tasks') }}/${currentDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success || data.message) {
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

    modal.onclick = function(e) {
        if (e.target === modal) closeModal();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            openModal(id, title);
        });
    });

    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';
        const priorityValue = prioritySelect?.value || '';

        const rows = document.querySelectorAll('#tasksTableBody tr:not(#emptyStateRow)');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const number = row.dataset.number || '';
            const status = row.dataset.status || '';
            const priority = row.dataset.priority || '';

            if (searchTerm && !title.includes(searchTerm) && !number.includes(searchTerm)) {
                show = false;
            }
            if (show && statusValue && status !== statusValue) show = false;
            if (show && priorityValue && priority !== priorityValue) show = false;

            row.style.display = show ? '' : 'none';
            if (show) hasVisibleRows = true;
        });

        const noResultsRow = document.getElementById('noResultsRow');
        if (!hasVisibleRows && rows.length > 0) {
            if (!noResultsRow) {
                const tbody = document.getElementById('tasksTableBody');
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

    window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        if (prioritySelect) prioritySelect.value = '';
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

    window.closeModal = closeModal;
</script>
@endpush
