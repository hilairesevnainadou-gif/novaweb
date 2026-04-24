{{-- resources/views/admin/tasks/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $task->title . ' - NovaTech Admin')
@section('page-title', $task->title)

@push('styles')
<style>
    .task-header {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .task-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .meta-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .meta-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
    }

    .meta-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-sm {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.5rem;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }

    .btn-primary-sm {
        background: var(--brand-primary);
        color: white;
    }

    .btn-primary-sm:hover {
        background: var(--brand-primary-hover);
    }

    .btn-warning-sm {
        background: #f59e0b;
        color: white;
    }

    .btn-warning-sm:hover {
        background: #d97706;
    }

    .btn-danger-sm {
        background: #ef4444;
        color: white;
    }

    .btn-danger-sm:hover {
        background: #dc2626;
    }

    .comment-item {
        background: var(--bg-tertiary);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    .comment-author {
        font-weight: 600;
        font-size: 0.875rem;
    }

    .comment-date {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    .comment-content {
        color: var(--text-primary);
        line-height: 1.5;
    }

    .reply-btn {
        margin-top: 0.5rem;
        background: none;
        border: none;
        color: var(--brand-primary);
        cursor: pointer;
        font-size: 0.75rem;
    }

    .reply-form {
        margin-top: 1rem;
        display: none;
    }

    .reply-form.active {
        display: block;
    }

    .time-entry {
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .task-meta { grid-template-columns: 1fr; }
        .action-buttons { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $canEdit = $user->can('tasks.edit');
    $canApprove = $user->can('tasks.approve');
    $isAssignee = $task->assigned_to == $user->id;
    $isProjectManager = $task->project->project_manager_id == $user->id;
    $canMarkComplete = ($isAssignee && $task->status === 'in_progress') || ($isProjectManager && $task->status === 'in_progress');
    $canReview = $isProjectManager && $task->status === 'review';
@endphp

<div class="task-header">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">{{ $task->title }}</h1>
                <span class="badge badge-{{ str_replace('_', '', $task->status) }}">
                    {{ $task->status_label }}
                </span>
                <span class="badge badge-{{ $task->priority }}">
                    {{ $task->priority_label }}
                </span>
            </div>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">{{ $task->task_number }}</p>
        </div>
    </div>

    <div class="task-meta">
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-project-diagram"></i></div>
            <div>
                <div class="meta-label">Projet</div>
                <div class="meta-value">{{ $task->project->name }}</div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-user"></i></div>
            <div>
                <div class="meta-label">Assignée à</div>
                <div class="meta-value">{{ $task->assignee->name ?? 'Non assigné' }}</div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="meta-label">Date d'échéance</div>
                <div class="meta-value">
                    @if($task->due_date)
                        {{ $task->due_date->format('d/m/Y H:i') }}
                        @if($task->is_overdue)
                            <span style="color: #ef4444;">(En retard)</span>
                        @endif
                    @else
                        Non définie
                    @endif
                </div>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="meta-label">Heures estimées / réalisées</div>
                <div class="meta-value">{{ $task->estimated_hours }}h / {{ $task->actual_hours }}h</div>
            </div>
        </div>
    </div>

    @if($task->description)
    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
        <div class="meta-label" style="margin-bottom: 0.5rem;">Description</div>
        <p style="color: var(--text-primary); margin: 0;">{{ $task->description }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="action-buttons">
        @if($canEdit)
            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn-sm btn-primary-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
        @endif

        @if($canMarkComplete)
            <button class="btn-sm btn-warning-sm" onclick="openCompleteModal()">
                <i class="fas fa-check-circle"></i> Marquer comme terminée
            </button>
        @endif

        @if($canReview)
            <button class="btn-sm btn-primary-sm" onclick="openApproveModal()">
                <i class="fas fa-thumbs-up"></i> Approuver
            </button>
            <button class="btn-sm btn-danger-sm" onclick="openRejectModal()">
                <i class="fas fa-thumbs-down"></i> Rejeter
            </button>
        @endif
    </div>
</div>

<!-- Tabs -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; background: var(--bg-secondary); border-radius: 0.75rem; padding: 0.5rem;">
    <button class="tab-btn active" data-tab="comments" style="flex: 1; padding: 0.5rem; background: transparent; border: none; cursor: pointer; border-radius: 0.5rem;">Commentaires</button>
    <button class="tab-btn" data-tab="time" style="flex: 1; padding: 0.5rem; background: transparent; border: none; cursor: pointer; border-radius: 0.5rem;">Suivi du temps</button>
    <button class="tab-btn" data-tab="subtasks" style="flex: 1; padding: 0.5rem; background: transparent; border: none; cursor: pointer; border-radius: 0.5rem;">Sous-tâches</button>
</div>

<!-- Tab Comments -->
<div class="tab-content active" id="tab-comments">
    <!-- Ajouter un commentaire -->
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
        <form action="{{ route('admin.tasks.comments.store', $task) }}" method="POST">
            @csrf
            <textarea name="comment" class="form-control" rows="3" placeholder="Écrire un commentaire..." style="width: 100%; margin-bottom: 0.5rem;"></textarea>
            <button type="submit" class="btn-primary-sm">Envoyer</button>
        </form>
    </div>

    <!-- Liste des commentaires -->
    @forelse($task->comments->where('parent_id', null) as $comment)
    <div class="comment-item">
        <div class="comment-header">
            <div class="comment-author">
                <i class="fas fa-user-circle"></i> {{ $comment->user->name }}
            </div>
            <div class="comment-date">{{ $comment->created_at->diffForHumans() }}</div>
        </div>
        <div class="comment-content">{{ nl2br(e($comment->comment)) }}</div>
        <button class="reply-btn" onclick="toggleReplyForm({{ $comment->id }})">
            <i class="fas fa-reply"></i> Répondre
        </button>

        <!-- Formulaire de réponse -->
        <div id="reply-form-{{ $comment->id }}" class="reply-form">
            <form action="{{ route('admin.tasks.comments.reply', [$task, $comment]) }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <textarea name="comment" class="form-control" rows="2" placeholder="Votre réponse..." style="width: 100%; margin-bottom: 0.5rem;"></textarea>
                <button type="submit" class="btn-primary-sm">Répondre</button>
            </form>
        </div>

        <!-- Réponses -->
        @foreach($comment->replies as $reply)
        <div class="comment-item" style="margin-left: 2rem; margin-top: 1rem;">
            <div class="comment-header">
                <div class="comment-author">
                    <i class="fas fa-user-circle"></i> {{ $reply->user->name }}
                </div>
                <div class="comment-date">{{ $reply->created_at->diffForHumans() }}</div>
            </div>
            <div class="comment-content">{{ nl2br(e($reply->comment)) }}</div>
        </div>
        @endforeach
    </div>
    @empty
    <div class="empty-state" style="text-align: center; padding: 2rem;">
        <i class="fas fa-comments"></i>
        <p>Aucun commentaire</p>
    </div>
    @endforelse
</div>

<!-- Tab Time Tracking -->
<div class="tab-content" id="tab-time">
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
        <h3 style="margin-bottom: 1rem;">Ajouter du temps</h3>
        <form action="{{ route('admin.tasks.time.store', $task) }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem;">
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="form-label">Heures</label>
                    <input type="number" step="0.5" name="hours" class="form-control" required>
                </div>
                <div style="display: flex; align-items: end;">
                    <button type="submit" class="btn-primary-sm">Ajouter</button>
                </div>
            </div>
            <div style="margin-top: 0.5rem;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Description du travail effectué..."></textarea>
            </div>
        </form>
    </div>

    <!-- Total des heures -->
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between;">
            <span>Total des heures enregistrées:</span>
            <strong>{{ $totalTimeSpent }}h</strong>
        </div>
    </div>

    <!-- Liste des entrées de temps -->
    @forelse($task->timeEntries as $entry)
    <div class="time-entry">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <strong>{{ $entry->date->format('d/m/Y') }}</strong> - {{ $entry->hours }}h
                @if($entry->description)
                <div style="font-size: 0.75rem; color: var(--text-tertiary); margin-top: 0.25rem;">{{ $entry->description }}</div>
                @endif
            </div>
            <div style="font-size: 0.7rem; color: var(--text-tertiary);">
                par {{ $entry->user->name }}
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="text-align: center; padding: 2rem;">
        <i class="fas fa-clock"></i>
        <p>Aucune entrée de temps</p>
    </div>
    @endforelse
</div>

<!-- Tab Subtasks -->
<div class="tab-content" id="tab-subtasks">
    @if($canEdit)
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('admin.projects.tasks.create', $task->project) }}?parent_id={{ $task->id }}" class="btn-primary-sm">
            <i class="fas fa-plus"></i> Ajouter une sous-tâche
        </a>
    </div>
    @endif

    @forelse($task->subtasks as $subtask)
    <div style="background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; margin-bottom: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-weight: 600;">
                    <a href="{{ route('admin.tasks.show', $subtask) }}" style="color: var(--text-primary); text-decoration: none;">
                        {{ $subtask->title }}
                    </a>
                </div>
                <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ $subtask->task_number }}</div>
            </div>
            <div>
                <span class="badge badge-{{ str_replace('_', '', $subtask->status) }}">
                    {{ $subtask->status_label }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="text-align: center; padding: 2rem;">
        <i class="fas fa-tasks"></i>
        <p>Aucune sous-tâche</p>
    </div>
    @endforelse
</div>

<!-- Modals -->
<div id="completeModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Marquer comme terminée</h3>
            <button class="modal-close" onclick="closeCompleteModal()">&times;</button>
        </div>
        <form action="{{ route('admin.tasks.complete', $task) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p>Souhaitez-vous soumettre cette tâche pour revue ?</p>
                <div class="form-group">
                    <label class="form-label">Notes de complétion (optionnel)</label>
                    <textarea name="completion_notes" class="form-control" rows="3" placeholder="Décrivez le travail effectué..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeCompleteModal()">Annuler</button>
                <button type="submit" class="btn-primary">Soumettre</button>
            </div>
        </form>
    </div>
</div>

<div id="approveModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Approuver la tâche</h3>
            <button class="modal-close" onclick="closeApproveModal()">&times;</button>
        </div>
        <form action="{{ route('admin.tasks.approve', $task) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir approuver cette tâche ?</p>
                <div class="form-group">
                    <label class="form-label">Notes de revue (optionnel)</label>
                    <textarea name="review_notes" class="form-control" rows="3" placeholder="Commentaires supplémentaires..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeApproveModal()">Annuler</button>
                <button type="submit" class="btn-primary">Approuver</button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Rejeter la tâche</h3>
            <button class="modal-close" onclick="closeRejectModal()">&times;</button>
        </div>
        <form action="{{ route('admin.tasks.reject', $task) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p>Veuillez indiquer la raison du rejet :</p>
                <div class="form-group">
                    <label class="form-label">Raison <span class="required">*</span></label>
                    <textarea name="review_notes" class="form-control" rows="3" required placeholder="Expliquez pourquoi la tâche est rejetée..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeRejectModal()">Annuler</button>
                <button type="submit" class="btn-danger-sm">Rejeter</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(`tab-${tabId}`).classList.add('active');
        });
    });

    // Reply form toggle
    window.toggleReplyForm = function(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        form.classList.toggle('active');
    };

    // Complete modal
    window.openCompleteModal = function() {
        document.getElementById('completeModal').classList.add('active');
    };
    window.closeCompleteModal = function() {
        document.getElementById('completeModal').classList.remove('active');
    };

    // Approve modal
    window.openApproveModal = function() {
        document.getElementById('approveModal').classList.add('active');
    };
    window.closeApproveModal = function() {
        document.getElementById('approveModal').classList.remove('active');
    };

    // Reject modal
    window.openRejectModal = function() {
        document.getElementById('rejectModal').classList.add('active');
    };
    window.closeRejectModal = function() {
        document.getElementById('rejectModal').classList.remove('active');
    };

    // Close modals on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCompleteModal();
            closeApproveModal();
            closeRejectModal();
        }
    });
</script>
@endpush
