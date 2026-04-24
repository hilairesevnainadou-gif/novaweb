{{-- resources/views/admin/meetings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Réunions — ' . $project->name . ' - NovaTech Admin')
@section('page-title', 'Réunions du projet')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; } .breadcrumb a:hover { color:var(--brand-primary); }

    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-card { background:var(--bg-secondary); border-radius:0.75rem; padding:1rem; border:1px solid var(--border-light); }
    .stat-icon { width:2rem; height:2rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; margin-bottom:0.5rem; }
    .stat-value { font-size:1.5rem; font-weight:700; color:var(--text-primary); }
    .stat-label { font-size:0.7rem; text-transform:uppercase; color:var(--text-tertiary); letter-spacing:0.5px; }

    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .page-title-section h1 { font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem; }
    .page-title-section p { color:var(--text-secondary); margin:0; font-size:0.8125rem; }

    .filters-container { background:var(--bg-secondary); border-radius:0.75rem; padding:1rem; border:1px solid var(--border-light); margin-bottom:1.5rem; }
    .filter-row { display:grid; grid-template-columns:2fr 1fr 1fr; gap:0.75rem; }
    .filter-select { width:100%; padding:0.625rem 1rem; border-radius:0.5rem; border:1px solid var(--border-light); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; outline:none; }
    .filter-select:focus { border-color:var(--brand-primary); }

    .table-container { background:var(--bg-secondary); border-radius:0.75rem; border:1px solid var(--border-light); overflow-x:auto; }
    .meetings-table { width:100%; border-collapse:collapse; min-width:700px; }
    .meetings-table thead { background:var(--bg-tertiary); }
    .meetings-table th { padding:0.875rem 1rem; text-align:left; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-tertiary); border-bottom:1px solid var(--border-light); }
    .meetings-table td { padding:0.875rem 1rem; border-bottom:1px solid var(--border-light); color:var(--text-primary); vertical-align:middle; }
    .meetings-table tbody tr:hover { background:var(--bg-hover); }

    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.625rem; font-size:0.75rem; font-weight:500; border-radius:9999px; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }
    .action-btn { background:none; border:none; color:var(--text-tertiary); cursor:pointer; padding:0.375rem; border-radius:0.375rem; transition:all 0.2s; text-decoration:none; display:inline-flex; align-items:center; }
    .action-btn:hover { color:var(--brand-primary); background:var(--bg-hover); }
    .action-btn.delete:hover { color:#ef4444; }

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
    .modal-body p { margin:0 0 0.5rem; color:var(--text-secondary); font-size:0.875rem; }
    .modal-footer { padding:1rem 1.5rem; border-top:1px solid var(--border-light); display:flex; justify-content:flex-end; gap:0.75rem; }
    .btn { padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:500; cursor:pointer; border:none; }
    .btn-cancel { background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); }
    .btn-danger { background:#ef4444; color:white; }
    .btn-danger:hover { background:#dc2626; }

    @media (max-width:768px) { .stats-grid { grid-template-columns:1fr 1fr; } .filter-row { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@can('meetings.view')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Réunions</span>
</div>

<div class="page-header">
    <div class="page-title-section">
        <h1>Réunions — {{ $project->name }}</h1>
        <p>Gérez les réunions de ce projet</p>
    </div>
    <div style="display:flex; gap:0.625rem;">
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary-sm">
            <i class="fas fa-arrow-left"></i> Projet
        </a>
        @can('meetings.create')
        <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Planifier une réunion
        </a>
        @endcan
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,0.1);"><i class="fas fa-video" style="color:#3b82f6;"></i></div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total réunions</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,0.1);"><i class="fas fa-calendar-days" style="color:#f59e0b;"></i></div>
        <div class="stat-value" style="color:#f59e0b;">{{ $stats['upcoming'] }}</div>
        <div class="stat-label">À venir</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.1);"><i class="fas fa-check-circle" style="color:#10b981;"></i></div>
        <div class="stat-value" style="color:#10b981;">{{ $stats['done'] }}</div>
        <div class="stat-label">Terminées</div>
    </div>
</div>

<div class="filters-container">
    <form method="GET">
        <div class="filter-row">
            <div>
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tous statuts</option>
                    @foreach(\App\Models\Meeting::STATUSES as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="type" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tous types</option>
                    @foreach(\App\Models\Meeting::TYPES as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <a href="{{ route('admin.projects.meetings.index', $project) }}" class="btn-secondary-sm" style="height:100%; justify-content:center;">
                    <i class="fas fa-undo-alt"></i> Réinitialiser
                </a>
            </div>
        </div>
    </form>
</div>

<div class="table-container">
    <table class="meetings-table">
        <thead>
            <tr>
                <th>Réunion</th>
                <th>Type</th>
                <th>Date & Heure</th>
                <th>Mode</th>
                <th>Statut</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($meetings as $meeting)
            <tr>
                <td>
                    <div style="font-weight:500;">{{ $meeting->title }}</div>
                    @if($meeting->organizer)
                        <div style="font-size:0.75rem; color:var(--text-tertiary);">Par {{ $meeting->organizer->name }}</div>
                    @endif
                </td>
                <td><span class="badge badge-secondary">{{ $meeting->type_label }}</span></td>
                <td>
                    <div style="font-size:0.875rem;">{{ $meeting->scheduled_at->format('d/m/Y') }}</div>
                    <div style="font-size:0.75rem; color:var(--text-tertiary);">{{ $meeting->scheduled_at->format('H:i') }}
                        @if($meeting->duration_minutes) · {{ $meeting->duration_formatted }} @endif
                    </div>
                </td>
                <td><span style="font-size:0.8125rem; color:var(--text-secondary);">{{ $meeting->mode_label }}</span></td>
                <td><span class="badge {{ $meeting->status_badge_class }}">{{ $meeting->status_label }}</span></td>
                <td style="text-align:right;">
                    <div style="display:flex; justify-content:flex-end; gap:0.25rem;">
                        <a href="{{ route('admin.projects.meetings.show', [$project, $meeting]) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('meetings.edit')
                        <a href="{{ route('admin.projects.meetings.edit', [$project, $meeting]) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('meetings.delete')
                        <button class="action-btn delete delete-btn" data-id="{{ $meeting->id }}" data-project="{{ $project->id }}" data-name="{{ $meeting->title }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-state">
                    <i class="fas fa-video"></i>
                    <p>Aucune réunion pour ce projet</p>
                    @can('meetings.create')
                    <a href="{{ route('admin.projects.meetings.create', $project) }}" class="btn-primary" style="margin-top:1rem; display:inline-flex;">
                        <i class="fas fa-plus"></i> Planifier une réunion
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($meetings->hasPages())
<div class="pagination-wrapper">{{ $meetings->links() }}</div>
@endif

<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-trash" style="color:#ef4444; margin-right:0.5rem;"></i> Supprimer la réunion</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body"><p id="deleteMsg"></p></div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal()">Annuler</button>
            <button id="confirmDeleteBtn" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

@endcan
@cannot('meetings.view')
<div style="text-align:center; padding:3rem; color:var(--text-tertiary);">
    <i class="fas fa-lock" style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('deleteModal');
    let pendingId = null, pendingProject = null;

    function closeModal() { modal.classList.remove('active'); document.body.style.overflow = ''; pendingId = null; }
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingId = btn.dataset.id;
            pendingProject = btn.dataset.project;
            document.getElementById('deleteMsg').textContent = `Supprimer la réunion « ${btn.dataset.name} » ?`;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
        if (!pendingId) { return; }
        const btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        fetch(`{{ url('admin/projects') }}/${pendingProject}/meetings/${pendingId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { location.reload(); }
            else { alert(data.message); btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash"></i> Supprimer'; }
        });
    });
</script>
@endpush
