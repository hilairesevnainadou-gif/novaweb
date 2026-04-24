{{-- resources/views/admin/tasks/kanban.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kanban - ' . $project->name . ' - NovaTech Admin')
@section('page-title', 'Tableau Kanban - ' . $project->name)

@push('styles')
<style>
    .kanban-container {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: 70vh;
    }

    .kanban-column {
        min-width: 320px;
        width: 320px;
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 250px);
    }

    .kanban-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-tertiary);
        border-radius: 0.75rem 0.75rem 0 0;
    }

    .kanban-header h3 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .task-count {
        background: var(--bg-secondary);
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.7rem;
    }

    .kanban-body {
        flex: 1;
        padding: 0.5rem;
        overflow-y: auto;
        min-height: 300px;
    }

    .kanban-card {
        background: var(--bg-primary);
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border: 1px solid var(--border-light);
        cursor: grab;
        transition: all 0.2s;
    }

    .kanban-card:active {
        cursor: grabbing;
    }

    .kanban-card.dragging {
        opacity: 0.5;
    }

    .kanban-card:hover {
        border-color: var(--brand-primary);
        box-shadow: var(--shadow-sm);
    }

    .card-title {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    .card-assignee {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .badge-low { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-medium { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-high { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-urgent { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .drop-zone {
        background: rgba(var(--brand-primary-rgb), 0.1);
        border: 2px dashed var(--brand-primary);
    }

    .empty-state-small {
        text-align: center;
        padding: 2rem;
        color: var(--text-tertiary);
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .kanban-container {
            flex-direction: column;
        }
        .kanban-column {
            width: 100%;
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')

<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour au projet
    </a>
    <a href="{{ route('admin.projects.tasks.create', $project) }}" class="btn-primary" style="margin-left: 0.5rem;">
        <i class="fas fa-plus"></i> Nouvelle tâche
    </a>
</div>

<div class="kanban-container" id="kanbanContainer">
    @foreach($statuses as $statusKey => $status)
    <div class="kanban-column" data-status="{{ $statusKey }}">
        <div class="kanban-header">
            <h3>
                {{ $status['label'] }}
                <span class="task-count">{{ $tasks[$statusKey]->count() ?? 0 }}</span>
            </h3>
        </div>
        <div class="kanban-body" data-status="{{ $statusKey }}">
            @foreach($tasks[$statusKey] ?? [] as $task)
            <div class="kanban-card" data-id="{{ $task->id }}" draggable="true">
                <div class="card-title">{{ $task->title }}</div>
                <div class="card-meta">
                    <div class="card-assignee">
                        <i class="fas fa-user"></i>
                        {{ $task->assignee->name ?? 'Non assigné' }}
                    </div>
                    <span class="badge badge-{{ $task->priority }}">
                        {{ $task->priority_label }}
                    </span>
                </div>
                @if($task->due_date)
                <div class="card-meta" style="margin-top: 0.5rem;">
                    <span>
                        <i class="far fa-calendar-alt"></i>
                        {{ $task->due_date->format('d/m/Y') }}
                    </span>
                </div>
                @endif
            </div>
            @endforeach
            @if(($tasks[$statusKey] ?? collect())->isEmpty())
            <div class="empty-state-small">
                <i class="fas fa-inbox"></i>
                <p>Aucune tâche</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
    let draggedItem = null;

    document.querySelectorAll('.kanban-card').forEach(card => {
        card.addEventListener('dragstart', (e) => {
            draggedItem = card;
            card.classList.add('dragging');
            e.dataTransfer.setData('text/plain', card.dataset.id);
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', () => {
            draggedItem = null;
            card.classList.remove('dragging');
            document.querySelectorAll('.kanban-body').forEach(body => {
                body.classList.remove('drop-zone');
            });
        });
    });

    document.querySelectorAll('.kanban-body').forEach(body => {
        body.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            body.classList.add('drop-zone');
        });

        body.addEventListener('dragleave', () => {
            body.classList.remove('drop-zone');
        });

        body.addEventListener('drop', async (e) => {
            e.preventDefault();
            body.classList.remove('drop-zone');

            const taskId = e.dataTransfer.getData('text/plain');
            const newStatus = body.dataset.status;

            if (!taskId || !newStatus) return;

            // Mettre à jour l'interface
            const draggedCard = document.querySelector(`.kanban-card[data-id="${taskId}"]`);
            if (draggedCard) {
                body.appendChild(draggedCard);

                // Mettre à jour le compteur
                updateTaskCounts();
            }

            // Appel API
            try {
                const response = await fetch('{{ route("admin.tasks.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        status: newStatus
                    })
                });

                if (!response.ok) {
                    throw new Error('Erreur lors de la mise à jour');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    function updateTaskCounts() {
        document.querySelectorAll('.kanban-column').forEach(column => {
            const body = column.querySelector('.kanban-body');
            const count = body.querySelectorAll('.kanban-card').length;
            const countSpan = column.querySelector('.task-count');
            if (countSpan) {
                countSpan.textContent = count;
            }
        });
    }
</script>
@endpush
