{{-- resources/views/admin/projects/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Projets - NovaTech Admin')
@section('page-title', 'Gestion des Projets')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; border: 1px solid var(--border-light); transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; }
    .stat-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); } .stat-icon.blue i { color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); } .stat-icon.green i { color: #10b981; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); } .stat-icon.purple i { color: #8b5cf6; }
    .stat-icon.orange { background: rgba(245, 158, 11, 0.1); } .stat-icon.orange i { color: #f59e0b; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; }
    .stat-label { font-size: 0.7rem; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.5px; }

    .filters-container { background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; border: 1px solid var(--border-light); margin-bottom: 1.5rem; }
    .filter-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; gap: 0.75rem; align-items: end; }
    .filter-input, .filter-select { width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-light); background: var(--bg-primary); color: var(--text-primary); font-size: 0.875rem; outline: none; transition: all 0.2s; }
    .filter-input:focus, .filter-select:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .filter-input::placeholder { color: var(--text-tertiary); }

    .table-container { background: var(--bg-secondary); border-radius: 0.75rem; border: 1px solid var(--border-light); overflow-x: auto; }
    .projects-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .projects-table thead { background: var(--bg-tertiary); }
    .projects-table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-tertiary); border-bottom: 1px solid var(--border-light); }
    .projects-table td { padding: 1rem; border-bottom: 1px solid var(--border-light); color: var(--text-primary); vertical-align: middle; }
    .projects-table tbody tr:hover { background: var(--bg-hover); }

    .project-color-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .project-name { font-weight: 600; color: var(--text-primary); margin-bottom: 0.2rem; }
    .project-number { font-size: 0.7rem; color: var(--text-tertiary); font-family: monospace; }

    .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; border-radius: 9999px; white-space: nowrap; }
    .badge-active { background: rgba(16,185,129,0.1); color: #10b981; }
    .badge-inactive { background: rgba(239,68,68,0.1); color: #ef4444; }
    .badge-info { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .badge-warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .badge-secondary { background: rgba(107,114,128,0.1); color: #9ca3af; }
    .badge-completed { background: rgba(139,92,246,0.1); color: #8b5cf6; }
    .badge-danger { background: rgba(239,68,68,0.1); color: #ef4444; }

    .progress-bar { width: 80px; height: 6px; background: var(--bg-tertiary); border-radius: 9999px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 9999px; background: var(--brand-primary); transition: width 0.3s; }

    .actions-cell { text-align: right; white-space: nowrap; }
    .action-btn { background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0.375rem; border-radius: 0.375rem; transition: all 0.2s; font-size: 1rem; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
    .action-btn:hover { color: var(--brand-primary); background: var(--bg-hover); }
    .action-btn.delete:hover { color: var(--brand-error); }

    .empty-state { text-align: center; padding: 3rem; color: var(--text-tertiary); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

    .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--brand-primary); color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .btn-secondary-sm { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.4rem 0.875rem; background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-light); border-radius: 0.5rem; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.2s; }
    .btn-secondary-sm:hover { background: var(--bg-hover); color: var(--brand-primary); border-color: var(--brand-primary); }
    .btn-reset { width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-light); background: var(--bg-primary); color: var(--text-primary); font-size: 0.875rem; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .btn-reset:hover { background: var(--bg-hover); border-color: var(--brand-primary); color: var(--brand-primary); }

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .page-title-section h1 { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.25rem 0; }
    .page-title-section p { color: var(--text-secondary); margin: 0; font-size: 0.875rem; }
    .header-actions { display: flex; gap: 0.75rem; }

    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal { background: var(--bg-elevated); border-radius: 0.75rem; border: 1px solid var(--border-medium); width: 90%; max-width: 450px; transform: scale(0.95); transition: transform 0.3s ease; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-overlay.active .modal { transform: scale(1); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; }
    .modal-header h3 { font-size: 1.125rem; font-weight: 600; margin: 0; color: var(--text-primary); }
    .modal-close { background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0.25rem; font-size: 1.125rem; transition: color 0.2s; }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body { padding: 1.5rem; }
    .modal-body p { margin: 0 0 0.5rem 0; line-height: 1.6; color: var(--text-secondary); }
    .modal-body .warning-text { color: #f59e0b; font-size: 0.875rem; margin-top: 0.75rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 0.75rem; }
    .btn { padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; border: none; }
    .btn-cancel { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-light); }
    .btn-cancel:hover { background: var(--bg-hover); color: var(--text-primary); }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }

    .pagination-wrapper { margin-top: 1.5rem; display: flex; justify-content: center; }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-row { grid-template-columns: 1fr; }
    }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .table-row { animation: fadeInUp 0.3s ease forwards; }
</style>
@endpush

@section('content')
@can('projects.view')

<div class="page-header">
    <div class="page-title-section">
        <h1>Projets</h1>
        <p>Gérez vos projets et suivez leur avancement</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.projects.dashboard') }}" class="btn-secondary-sm">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        @can('projects.create')
        <a href="{{ route('admin.projects.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouveau projet
        </a>
        @endcan
    </div>
</div>

{{-- Statistiques --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><i class="fas fa-folder-open"></i></div></div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total projets</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-play-circle"></i></div></div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon purple"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value">{{ $stats['completed'] }}</div>
        <div class="stat-label">Terminés</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon orange"><i class="fas fa-pause-circle"></i></div></div>
        <div class="stat-value">{{ $stats['on_hold'] }}</div>
        <div class="stat-label">En pause</div>
    </div>
</div>

{{-- Filtres --}}
<div class="filters-container">
    <form method="GET" action="{{ route('admin.projects.index') }}">
        <div class="filter-row">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un projet..." class="filter-input" autocomplete="off">
            </div>
            <div>
                <select name="status" class="filter-select">
                    <option value="">Tous statuts</option>
                    @foreach(\App\Models\Project::STATUSES as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="priority" class="filter-select">
                    <option value="">Toutes priorités</option>
                    @foreach(\App\Models\Project::PRIORITIES as $val => $label)
                        <option value="{{ $val }}" @selected(request('priority') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="type" class="filter-select">
                    <option value="">Tous types</option>
                    @foreach(\App\Models\Project::TYPES as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.projects.index') }}" class="btn-reset" style="flex:1;">
                    <i class="fas fa-undo-alt"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Tableau --}}
<div class="table-container">
    <table class="projects-table">
        <thead>
            <tr>
                <th>Projet</th>
                <th>Client</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Avancement</th>
                <th>Échéance</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $index => $project)
            <tr class="table-row" style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div style="display:flex; align-items:center; gap:0.625rem;">
                        <span class="project-color-dot" style="background:{{ $project->color }};"></span>
                        <div>
                            <div class="project-name">{{ $project->name }}</div>
                            <div class="project-number">{{ $project->project_number }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size:0.875rem; color:var(--text-secondary);">
                        {{ $project->client?->company_name ?? $project->client?->name ?? '-' }}
                    </span>
                </td>
                <td><span style="font-size:0.8125rem;">{{ $project->type_label }}</span></td>
                <td><span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span></td>
                <td>
                    <span class="badge {{ $project->priority_badge_class }}">
                        {{ $project->priority_label }}
                    </span>
                </td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $project->progress }}%;"></div>
                        </div>
                        <span style="font-size:0.75rem; color:var(--text-tertiary);">{{ $project->progress }}%</span>
                    </div>
                </td>
                <td>
                    @if($project->end_date)
                        <span style="font-size:0.8125rem; color:{{ $project->is_overdue ? '#ef4444' : 'var(--text-secondary)' }};">
                            @if($project->is_overdue)<i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i> @endif
                            {{ $project->end_date->format('d/m/Y') }}
                        </span>
                    @else
                        <span style="color:var(--text-tertiary);">-</span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                        <a href="{{ route('admin.projects.show', $project) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('projects.edit')
                        <a href="{{ route('admin.projects.edit', $project) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        <a href="{{ route('admin.projects.tasks.kanban', $project) }}" class="action-btn" title="Kanban">
                            <i class="fas fa-columns"></i>
                        </a>
                        @can('projects.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $project->id }}"
                                data-name="{{ $project->name }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Aucun projet trouvé</p>
                    @can('projects.create')
                    <a href="{{ route('admin.projects.create') }}" class="btn-primary" style="margin-top:1rem; display:inline-flex;">
                        <i class="fas fa-plus"></i> Créer un projet
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($projects->hasPages())
<div class="pagination-wrapper">{{ $projects->links() }}</div>
@endif

{{-- Modal de confirmation --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-trash" style="color:#ef4444; margin-right:0.5rem;"></i> Supprimer le projet</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p class="warning-text"><i class="fas fa-exclamation-triangle"></i> Toutes les tâches, réunions et activités associées seront également supprimées.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

@endcan

@cannot('projects.view')
<div class="empty-state" style="padding:3rem; text-align:center;">
    <i class="fas fa-lock" style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    let pendingDeleteId = null;

    function closeModal() {
        deleteModal.classList.remove('active');
        document.body.style.overflow = '';
        pendingDeleteId = null;
    }

    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingDeleteId = btn.dataset.id;
            modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer le projet « ${btn.dataset.name} » ?`;
            deleteModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    modalConfirmBtn.addEventListener('click', () => {
        if (!pendingDeleteId) { return; }
        modalConfirmBtn.disabled = true;
        modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

        fetch(`{{ url('admin/projects') }}/${pendingDeleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showNotification('Projet supprimé avec succès', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'Erreur lors de la suppression', 'error');
                modalConfirmBtn.disabled = false;
                modalConfirmBtn.innerHTML = '<i class="fas fa-trash"></i> Supprimer';
            }
        })
        .catch(() => {
            showNotification('Une erreur technique est survenue', 'error');
            modalConfirmBtn.disabled = false;
            modalConfirmBtn.innerHTML = '<i class="fas fa-trash"></i> Supprimer';
        });
    });

    function showNotification(message, type) {
        const el = document.createElement('div');
        el.style.cssText = `position:fixed;bottom:20px;right:20px;padding:12px 20px;background:${type==='success'?'#10b981':'#ef4444'};color:white;border-radius:8px;font-size:14px;z-index:10001;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideIn 0.3s ease;`;
        el.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(el);
        setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(() => el.remove(), 300); }, 3000);
    }

    const style = document.createElement('style');
    style.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(style);
</script>
@endpush
