{{-- resources/views/admin/tasks/kanban.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kanban — ' . $project->name . ' - NovaTech Admin')
@section('page-title', 'Vue Kanban')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; transition:color 0.2s; }
    .breadcrumb a:hover { color:var(--brand-primary); }

    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .page-title-section h1 { font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem; }
    .page-title-section p { color:var(--text-secondary); margin:0; font-size:0.8125rem; }

    .kanban-board { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; align-items:start; overflow-x:auto; }
    @media (max-width:1024px) { .kanban-board { grid-template-columns:repeat(2, 1fr); } }
    @media (max-width:640px) { .kanban-board { grid-template-columns:1fr; } }

    .kanban-column { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; overflow:hidden; min-height:400px; }
    .column-header { padding:0.875rem 1rem; display:flex; align-items:center; justify-content:space-between; border-bottom:2px solid; }
    .column-header.todo { border-color:#9ca3af; background:rgba(107,114,128,0.05); }
    .column-header.in_progress { border-color:#3b82f6; background:rgba(59,130,246,0.05); }
    .column-header.review { border-color:#f59e0b; background:rgba(245,158,11,0.05); }
    .column-header.done { border-color:#10b981; background:rgba(16,185,129,0.05); }
    .column-title { font-size:0.8125rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; display:flex; align-items:center; gap:0.5rem; }
    .column-header.todo .column-title { color:#9ca3af; }
    .column-header.in_progress .column-title { color:#3b82f6; }
    .column-header.review .column-title { color:#f59e0b; }
    .column-header.done .column-title { color:#10b981; }
    .column-count { font-size:0.7rem; font-weight:600; padding:0.125rem 0.5rem; border-radius:9999px; }
    .column-header.todo .column-count { background:rgba(107,114,128,0.15); color:#9ca3af; }
    .column-header.in_progress .column-count { background:rgba(59,130,246,0.15); color:#3b82f6; }
    .column-header.review .column-count { background:rgba(245,158,11,0.15); color:#f59e0b; }
    .column-header.done .column-count { background:rgba(16,185,129,0.15); color:#10b981; }

    .column-body { padding:0.75rem; min-height:350px; }
    .kanban-card { background:var(--bg-elevated); border:1px solid var(--border-light); border-radius:0.625rem; padding:0.875rem; margin-bottom:0.625rem; cursor:grab; transition:all 0.2s; box-shadow:var(--shadow-xs); }
    .kanban-card:hover { border-color:var(--brand-primary); box-shadow:var(--shadow-md); transform:translateY(-1px); }
    .kanban-card.dragging { opacity:0.5; cursor:grabbing; }
    .kanban-card.drag-over { border:2px dashed var(--brand-primary); background:var(--bg-active); }
    .column-body.drag-over { background:rgba(59,130,246,0.05); }

    .card-priority-bar { height:3px; border-radius:9999px; margin-bottom:0.75rem; }
    .card-priority-low { background:#9ca3af; }
    .card-priority-medium { background:#3b82f6; }
    .card-priority-high { background:#f59e0b; }
    .card-priority-urgent { background:#ef4444; }

    .card-title { font-size:0.875rem; font-weight:600; color:var(--text-primary); margin-bottom:0.5rem; line-height:1.4; }
    .card-title a { color:inherit; text-decoration:none; }
    .card-title a:hover { color:var(--brand-primary); }
    .card-meta { display:flex; align-items:center; justify-content:space-between; margin-top:0.75rem; }
    .card-assignee { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; color:var(--text-tertiary); }
    .assignee-avatar { width:22px; height:22px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.6rem; font-weight:700; flex-shrink:0; }
    .card-due { font-size:0.7rem; padding:0.15rem 0.5rem; border-radius:9999px; }
    .card-due.overdue { background:rgba(239,68,68,0.1); color:#ef4444; }
    .card-due.ok { background:rgba(107,114,128,0.1); color:#9ca3af; }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.8125rem; font-weight:500; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }

    .drop-zone-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:200px; color:var(--text-tertiary); font-size:0.8125rem; gap:0.5rem; }
    .drop-zone-empty i { font-size:1.5rem; opacity:0.4; }

    /* Notification */
    .status-changed { animation:flash 0.5s ease; }
    @keyframes flash { 0%,100%{background:var(--bg-elevated)} 50%{background:rgba(59,130,246,0.2)} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Kanban</span>
</div>

<div class="page-header">
    <div class="page-title-section">
        <h1>Kanban — {{ $project->name }}</h1>
        <p>Glissez-déposez les tâches pour changer leur statut</p>
    </div>
    <div style="display:flex; gap:0.625rem;">
        <a href="{{ route('admin.projects.tasks.index', $project) }}" class="btn-secondary-sm">
            <i class="fas fa-list"></i> Liste
        </a>
        @can('tasks.create')
        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouvelle tâche
        </a>
        @endcan
    </div>
</div>

<div class="kanban-board" id="kanbanBoard">
    @foreach(['todo' => ['À faire', 'fa-circle', '#9ca3af'], 'in_progress' => ['En cours', 'fa-spinner', '#3b82f6'], 'review' => ['En révision', 'fa-eye', '#f59e0b'], 'done' => ['Terminé', 'fa-check-circle', '#10b981']] as $status => [$label, $icon, $color])
    <div class="kanban-column" id="col-{{ $status }}">
        <div class="column-header {{ $status }}">
            <div class="column-title">
                <i class="fas {{ $icon }}"></i>
                {{ $label }}
            </div>
            <span class="column-count">{{ $columns[$status]->count() }}</span>
        </div>
        <div class="column-body" data-status="{{ $status }}" id="body-{{ $status }}">
            @forelse($columns[$status] as $task)
            <div class="kanban-card"
                 draggable="true"
                 data-task-id="{{ $task->id }}"
                 data-project-id="{{ $project->id }}"
                 data-status="{{ $task->status }}"
                 id="task-card-{{ $task->id }}">
                <div class="card-priority-bar card-priority-{{ $task->priority }}"></div>
                <div class="card-title">
                    <a href="{{ route('admin.projects.tasks.show', [$project, $task]) }}">{{ $task->title }}</a>
                </div>
                @if($task->due_date)
                <div>
                    <span class="card-due {{ $task->is_overdue ? 'overdue' : 'ok' }}">
                        <i class="fas fa-calendar"></i>
                        {{ $task->due_date->format('d/m') }}
                        @if($task->is_overdue) · Retard @endif
                    </span>
                </div>
                @endif
                <div class="card-meta">
                    @if($task->assignedTo)
                    <div class="card-assignee">
                        <div class="assignee-avatar">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</div>
                        {{ Str::limit($task->assignedTo->name, 15) }}
                    </div>
                    @else
                        <span style="font-size:0.75rem; color:var(--text-tertiary);">Non assignée</span>
                    @endif
                    <span style="font-size:0.7rem; color:var(--text-tertiary);">
                        {{ $task->comments->count() > 0 ? '💬 ' . $task->comments->count() : '' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="drop-zone-empty" id="empty-{{ $status }}">
                <i class="fas fa-inbox"></i>
                <span>Déposez ici</span>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const projectId = {{ $project->id }};

    let draggedCard = null;

    // Drag events
    document.querySelectorAll('.kanban-card').forEach(card => {
        initDragEvents(card);
    });

    function initDragEvents(card) {
        card.addEventListener('dragstart', e => {
            draggedCard = card;
            card.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', card.dataset.taskId);
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            draggedCard = null;
            document.querySelectorAll('.column-body').forEach(b => b.classList.remove('drag-over'));
        });
    }

    document.querySelectorAll('.column-body').forEach(body => {
        body.addEventListener('dragover', e => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            body.classList.add('drag-over');
        });
        body.addEventListener('dragleave', e => {
            if (!body.contains(e.relatedTarget)) body.classList.remove('drag-over');
        });
        body.addEventListener('drop', e => {
            e.preventDefault();
            body.classList.remove('drag-over');

            if (!draggedCard) { return; }
            const newStatus = body.dataset.status;
            const oldStatus = draggedCard.dataset.status;

            if (newStatus === oldStatus) { return; }

            const taskId = draggedCard.dataset.taskId;

            // Remove empty state if present
            const emptyEl = body.querySelector('.drop-zone-empty');
            if (emptyEl) emptyEl.remove();

            // Move card to new column
            body.appendChild(draggedCard);
            draggedCard.dataset.status = newStatus;
            draggedCard.classList.add('status-changed');

            // Update counters
            updateColumnCount(oldStatus, -1);
            updateColumnCount(newStatus, 1);

            // Restore empty state in old column if empty
            const oldBody = document.getElementById('body-' + oldStatus);
            if (!oldBody.querySelector('.kanban-card')) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'drop-zone-empty';
                emptyDiv.id = 'empty-' + oldStatus;
                emptyDiv.innerHTML = '<i class="fas fa-inbox"></i><span>Déposez ici</span>';
                oldBody.appendChild(emptyDiv);
            }

            // API call
            fetch(`/admin/projects/${projectId}/tasks/${taskId}/status`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) showNotification('Erreur lors de la mise à jour', 'error');
            })
            .catch(() => showNotification('Erreur réseau', 'error'));
        });
    });

    function updateColumnCount(status, delta) {
        const col = document.getElementById('col-' + status);
        const countEl = col?.querySelector('.column-count');
        if (countEl) {
            countEl.textContent = Math.max(0, parseInt(countEl.textContent) + delta);
        }
    }

    function showNotification(message, type) {
        const el = document.createElement('div');
        el.style.cssText = `position:fixed;bottom:20px;right:20px;padding:12px 20px;background:${type==='success'?'#10b981':'#ef4444'};color:white;border-radius:8px;font-size:14px;z-index:10001;animation:slideIn 0.3s ease;`;
        el.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(el);
        setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(() => el.remove(), 300); }, 3000);
    }

    const s = document.createElement('style');
    s.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(s);
</script>
@endpush
