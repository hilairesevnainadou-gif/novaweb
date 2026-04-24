{{-- resources/views/admin/tasks/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tâches - NovaTech Admin')
@section('page-title', isset($project) ? 'Tâches du projet' : 'Toutes les tâches')

@push('styles')
<style>
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-card { background:var(--bg-secondary); border-radius:0.75rem; padding:1rem; border:1px solid var(--border-light); transition:all 0.3s; }
    .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
    .stat-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
    .stat-icon { width:2rem; height:2rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; }
    .stat-value { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem; }
    .stat-label { font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); letter-spacing:0.5px; }

    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .page-title-section h1 { font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem; }
    .page-title-section p { color:var(--text-secondary); margin:0; font-size:0.875rem; }

    .filters-container { background:var(--bg-secondary); border-radius:0.75rem; padding:1rem; border:1px solid var(--border-light); margin-bottom:1.5rem; }
    .filter-row { display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr; gap:0.75rem; }
    .filter-input, .filter-select { width:100%; padding:0.625rem 1rem; border-radius:0.5rem; border:1px solid var(--border-light); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; outline:none; transition:all 0.2s; }
    .filter-input:focus, .filter-select:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    .filter-input::placeholder { color:var(--text-tertiary); }

    .table-container { background:var(--bg-secondary); border-radius:0.75rem; border:1px solid var(--border-light); overflow-x:auto; }
    .tasks-table { width:100%; border-collapse:collapse; min-width:900px; }
    .tasks-table thead { background:var(--bg-tertiary); }
    .tasks-table th { padding:0.875rem 1rem; text-align:left; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-tertiary); border-bottom:1px solid var(--border-light); }
    .tasks-table td { padding:0.875rem 1rem; border-bottom:1px solid var(--border-light); color:var(--text-primary); vertical-align:middle; }
    .tasks-table tbody tr:hover { background:var(--bg-hover); }

    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; font-size:0.75rem; font-weight:500; border-radius:9999px; white-space:nowrap; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-completed { background:rgba(139,92,246,0.1); color:#8b5cf6; }
    .badge-danger { background:rgba(239,68,68,0.1); color:#ef4444; }

    .action-btn { background:none; border:none; color:var(--text-tertiary); cursor:pointer; padding:0.375rem; border-radius:0.375rem; transition:all 0.2s; font-size:0.9375rem; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; }
    .action-btn:hover { color:var(--brand-primary); background:var(--bg-hover); }
    .action-btn.approve:hover { color:#10b981; }
    .action-btn.reject:hover { color:#f59e0b; }
    .action-btn.delete:hover { color:var(--brand-error); }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s; border:none; cursor:pointer; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }
    .btn-reset { width:100%; padding:0.625rem 1rem; border-radius:0.5rem; border:1px solid var(--border-light); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; cursor:pointer; transition:all 0.2s; display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; }
    .btn-reset:hover { background:var(--bg-hover); border-color:var(--brand-primary); color:var(--brand-primary); }

    .empty-state { text-align:center; padding:3rem; color:var(--text-tertiary); }
    .empty-state i { font-size:3rem; margin-bottom:1rem; opacity:0.5; }

    .pagination-wrapper { margin-top:1.5rem; display:flex; justify-content:center; }

    /* Modal */
    .modal-overlay { position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:10000; opacity:0; visibility:hidden; transition:all 0.3s; }
    .modal-overlay.active { opacity:1; visibility:visible; }
    .modal { background:var(--bg-elevated); border-radius:0.75rem; border:1px solid var(--border-medium); width:90%; max-width:450px; transform:scale(0.95); transition:transform 0.3s; }
    .modal-overlay.active .modal { transform:scale(1); }
    .modal-header { padding:1.25rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; }
    .modal-header h3 { font-size:1rem; font-weight:600; margin:0; color:var(--text-primary); }
    .modal-close { background:none; border:none; color:var(--text-tertiary); cursor:pointer; font-size:1rem; }
    .modal-body { padding:1.5rem; }
    .modal-body p { margin:0 0 0.5rem; color:var(--text-secondary); font-size:0.875rem; line-height:1.6; }
    .modal-footer { padding:1rem 1.5rem; border-top:1px solid var(--border-light); display:flex; justify-content:flex-end; gap:0.75rem; }
    .btn { padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:500; cursor:pointer; transition:all 0.2s; border:none; }
    .btn-cancel { background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); }
    .btn-danger { background:#ef4444; color:white; }
    .btn-danger:hover { background:#dc2626; }
    .btn-success { background:#10b981; color:white; }
    .btn-success:hover { background:#059669; }

    /* Rejection form */
    .form-control { width:100%; padding:0.5625rem 0.875rem; border-radius:0.5rem; border:1px solid var(--border-medium); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; font-family:inherit; outline:none; }
    .form-control:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    textarea.form-control { resize:vertical; }

    @media (max-width:768px) {
        .stats-grid { grid-template-columns:repeat(2,1fr); }
        .filter-row { grid-template-columns:1fr; }
    }
    @keyframes fadeInUp { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)} }
    .table-row { animation:fadeInUp 0.3s ease forwards; }
</style>
@endpush

@section('content')
@can('tasks.view')

<div class="page-header">
    <div class="page-title-section">
        <h1>{{ isset($project) ? 'Tâches — ' . $project->name : 'Toutes les tâches' }}</h1>
        <p>{{ isset($project) ? 'Gérez les tâches de ce projet' : 'Vue globale de toutes les tâches' }}</p>
    </div>
    <div style="display:flex; gap:0.625rem;">
        @isset($project)
        <a href="{{ route('admin.projects.tasks.kanban', $project) }}" class="btn-secondary-sm">
            <i class="fas fa-columns"></i> Kanban
        </a>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary-sm">
            <i class="fas fa-arrow-left"></i> Projet
        </a>
        @endisset
        @can('tasks.create')
        <a href="{{ isset($project) ? route('admin.projects.tasks.create', $project) : '#' }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouvelle tâche
        </a>
        @endcan
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon" style="background:rgba(59,130,246,0.1);"><i class="fas fa-tasks" style="color:#3b82f6;"></i></div></div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total tâches</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon" style="background:rgba(59,130,246,0.1);"><i class="fas fa-spinner" style="color:#3b82f6;"></i></div></div>
        <div class="stat-value" style="color:#3b82f6;">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon" style="background:rgba(245,158,11,0.1);"><i class="fas fa-eye" style="color:#f59e0b;"></i></div></div>
        <div class="stat-value" style="color:#f59e0b;">{{ $stats['review'] }}</div>
        <div class="stat-label">En révision</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon" style="background:rgba(239,68,68,0.1);"><i class="fas fa-clock" style="color:#ef4444;"></i></div></div>
        <div class="stat-value" style="color:#ef4444;">{{ $stats['overdue'] }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

{{-- Filtres --}}
<div class="filters-container">
    <form method="GET">
        <div class="filter-row">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une tâche..." class="filter-input">
            </div>
            <div>
                <select name="status" class="filter-select">
                    <option value="">Tous statuts</option>
                    @foreach(\App\Models\Task::STATUSES as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="priority" class="filter-select">
                    <option value="">Toutes priorités</option>
                    @foreach(\App\Models\Task::PRIORITIES as $val => $label)
                        <option value="{{ $val }}" @selected(request('priority') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @unless(isset($project))
            <div>
                <select name="project_id" class="filter-select">
                    <option value="">Tous projets</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" @selected(request('project_id') == $proj->id)>{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>
            @endunless
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;"><i class="fas fa-search"></i></button>
                <a href="{{ request()->url() }}" class="btn-reset" style="flex:1;"><i class="fas fa-undo-alt"></i></a>
            </div>
        </div>
    </form>
</div>

{{-- Tableau --}}
<div class="table-container">
    <table class="tasks-table">
        <thead>
            <tr>
                <th>Tâche</th>
                @unless(isset($project))<th>Projet</th>@endunless
                <th>Assignée à</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Échéance</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $index => $task)
            <tr class="table-row" style="animation-delay:{{ $index*0.03 }}s;">
                <td>
                    <div style="font-weight:500; color:var(--text-primary); margin-bottom:0.125rem;">{{ $task->title }}</div>
                    @if($task->parent)
                        <div style="font-size:0.7rem; color:var(--text-tertiary);"><i class="fas fa-level-up-alt fa-flip-horizontal"></i> {{ $task->parent->title }}</div>
                    @endif
                </td>
                @unless(isset($project))
                <td>
                    <a href="{{ route('admin.projects.show', $task->project) }}" style="font-size:0.8125rem; color:var(--brand-primary); text-decoration:none;">{{ $task->project->name }}</a>
                </td>
                @endunless
                <td>
                    <span style="font-size:0.8125rem; color:var(--text-secondary);">{{ $task->assignedTo?->name ?? '-' }}</span>
                </td>
                <td><span class="badge {{ $task->status_badge_class }}">{{ $task->status_label }}</span></td>
                <td>
                    <span class="badge {{ $task->priority_badge_class }}">
                        <i class="fas {{ $task->priority_icon }}"></i> {{ $task->priority_label }}
                    </span>
                </td>
                <td>
                    @if($task->due_date)
                        <span style="font-size:0.8125rem; color:{{ $task->is_overdue ? '#ef4444' : 'var(--text-secondary)' }};">
                            @if($task->is_overdue)<i class="fas fa-exclamation-triangle"></i> @endif
                            {{ $task->due_date->format('d/m/Y') }}
                        </span>
                    @else
                        <span style="color:var(--text-tertiary);">-</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <div style="display:flex; justify-content:flex-end; gap:0.25rem;">
                        <a href="{{ route('admin.projects.tasks.show', [$task->project, $task]) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('tasks.edit')
                        <a href="{{ route('admin.projects.tasks.edit', [$task->project, $task]) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('tasks.approve')
                        @if($task->status === 'review' && is_null($task->is_approved))
                        <button class="action-btn approve approve-btn" data-id="{{ $task->id }}" data-project="{{ $task->project_id }}" data-name="{{ $task->title }}" title="Approuver">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="action-btn reject reject-btn" data-id="{{ $task->id }}" data-project="{{ $task->project_id }}" data-name="{{ $task->title }}" title="Rejeter">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                        @endcan
                        @can('tasks.delete')
                        <button class="action-btn delete delete-btn" data-id="{{ $task->id }}" data-project="{{ $task->project_id }}" data-name="{{ $task->title }}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ isset($project) ? 6 : 7 }}" class="empty-state">
                    <i class="fas fa-tasks"></i>
                    <p>Aucune tâche trouvée</p>
                    @can('tasks.create')
                    @isset($project)
                    <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary" style="margin-top:1rem; display:inline-flex;">
                        <i class="fas fa-plus"></i> Créer une tâche
                    </a>
                    @endisset
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($tasks->hasPages())
<div class="pagination-wrapper">{{ $tasks->links() }}</div>
@endif

{{-- Modal Suppression --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-trash" style="color:#ef4444; margin-right:0.5rem;"></i> Supprimer la tâche</h3>
            <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMsg"></p>
            <p style="color:#f59e0b; font-size:0.8125rem;"><i class="fas fa-exclamation-triangle"></i> Les commentaires et entrées de temps associés seront également supprimés.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeDeleteModal()">Annuler</button>
            <button id="deleteConfirmBtn" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

{{-- Modal Rejet --}}
<div id="rejectModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-times-circle" style="color:#f59e0b; margin-right:0.5rem;"></i> Rejeter la tâche</h3>
            <button class="modal-close" onclick="closeRejectModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="rejectModalMsg" style="margin-bottom:1rem;"></p>
            <form id="rejectForm" method="POST">
                @csrf
                <label style="font-size:0.75rem; font-weight:600; text-transform:uppercase; color:var(--text-tertiary); display:block; margin-bottom:0.375rem;">Raison du rejet <span style="color:#ef4444;">*</span></label>
                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Expliquez pourquoi cette tâche est rejetée..." required></textarea>
                <div class="modal-footer" style="padding:1rem 0 0; border-top:none;">
                    <button type="button" class="btn btn-cancel" onclick="closeRejectModal()">Annuler</button>
                    <button type="submit" class="btn" style="background:#f59e0b; color:white;"><i class="fas fa-times-circle"></i> Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endcan
@cannot('tasks.view')
<div class="empty-state" style="padding:3rem; text-align:center;">
    <i class="fas fa-lock" style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');
    const rejectModal = document.getElementById('rejectModal');
    let pendingDelete = null;

    function closeDeleteModal() { deleteModal.classList.remove('active'); document.body.style.overflow = ''; pendingDelete = null; }
    function closeRejectModal() { rejectModal.classList.remove('active'); document.body.style.overflow = ''; }

    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });
    rejectModal.addEventListener('click', e => { if (e.target === rejectModal) closeRejectModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDeleteModal(); closeRejectModal(); } });

    // Suppression
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingDelete = { id: btn.dataset.id, project: btn.dataset.project };
            document.getElementById('deleteModalMsg').textContent = `Supprimer la tâche « ${btn.dataset.name} » ?`;
            deleteModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    document.getElementById('deleteConfirmBtn').addEventListener('click', () => {
        if (!pendingDelete) { return; }
        const btn = document.getElementById('deleteConfirmBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ...';

        fetch(`{{ url('admin/projects') }}/${pendingDelete.project}/tasks/${pendingDelete.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { showNotification('Tâche supprimée', 'success'); setTimeout(() => location.reload(), 1200); }
            else { showNotification(data.message || 'Erreur', 'error'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash"></i> Supprimer'; }
        })
        .catch(() => { showNotification('Erreur technique', 'error'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash"></i> Supprimer'; });
    });

    // Approbation
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm(`Approuver la tâche « ${btn.dataset.name} » ?`)) { return; }
            fetch(`{{ url('admin/projects') }}/${btn.dataset.project}/tasks/${btn.dataset.id}/approve`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => { if (data.success || true) { showNotification('Tâche approuvée', 'success'); setTimeout(() => location.reload(), 1200); } })
            .catch(() => showNotification('Erreur', 'error'));
        });
    });

    // Rejet
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('rejectModalMsg').textContent = `Rejeter la tâche « ${btn.dataset.name} » ?`;
            document.getElementById('rejectForm').action = `{{ url('admin/projects') }}/${btn.dataset.project}/tasks/${btn.dataset.id}/reject`;
            rejectModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function showNotification(message, type) {
        const el = document.createElement('div');
        el.style.cssText = `position:fixed;bottom:20px;right:20px;padding:12px 20px;background:${type==='success'?'#10b981':'#ef4444'};color:white;border-radius:8px;font-size:14px;z-index:10001;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideIn 0.3s ease;`;
        el.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(el);
        setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(() => el.remove(), 300); }, 3000);
    }
    const s = document.createElement('style');
    s.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(s);
</script>
@endpush
