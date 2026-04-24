{{-- resources/views/admin/tasks/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $task->title . ' - NovaTech Admin')
@section('page-title', 'Détail de la tâche')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; flex-wrap:wrap; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; transition:color 0.2s; }
    .breadcrumb a:hover { color:var(--brand-primary); }

    .task-hero { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; padding:1.5rem; margin-bottom:1.5rem; }
    .task-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .task-title { font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 0.75rem; }
    .task-meta { display:flex; flex-wrap:wrap; gap:0.5rem; }
    .hero-actions { display:flex; gap:0.625rem; flex-wrap:wrap; }

    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; font-size:0.75rem; font-weight:500; border-radius:9999px; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-completed { background:rgba(139,92,246,0.1); color:#8b5cf6; }
    .badge-danger { background:rgba(239,68,68,0.1); color:#ef4444; }

    .grid-layout { display:grid; grid-template-columns:2fr 1fr; gap:1rem; }
    @media (max-width:768px) { .grid-layout { grid-template-columns:1fr; } }

    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; margin-bottom:1rem; }
    .card-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; }
    .card-header h3 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    .tabs { display:flex; gap:0; border-bottom:2px solid var(--border-light); margin-bottom:1.5rem; overflow-x:auto; }
    .tab-btn { padding:0.75rem 1.25rem; font-size:0.875rem; font-weight:500; color:var(--text-tertiary); background:none; border:none; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all 0.2s; }
    .tab-btn:hover { color:var(--text-primary); }
    .tab-btn.active { color:var(--brand-primary); border-bottom-color:var(--brand-primary); }
    .tab-content { display:none; }
    .tab-content.active { display:block; }

    .info-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
    @media (max-width:480px) { .info-grid { grid-template-columns:1fr; } }
    .info-item { }
    .info-label { font-size:0.6875rem; text-transform:uppercase; font-weight:600; color:var(--text-tertiary); letter-spacing:0.4px; margin-bottom:0.25rem; }
    .info-value { font-size:0.875rem; color:var(--text-primary); font-weight:500; }

    /* Comments */
    .comment-item { display:flex; gap:0.75rem; margin-bottom:1.25rem; }
    .comment-avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.75rem; font-weight:700; flex-shrink:0; }
    .comment-content { flex:1; background:var(--bg-tertiary); border-radius:0.5rem; padding:0.875rem 1rem; }
    .comment-header { display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem; flex-wrap:wrap; }
    .comment-author { font-weight:600; font-size:0.875rem; color:var(--text-primary); }
    .comment-time { font-size:0.75rem; color:var(--text-tertiary); }
    .comment-body { font-size:0.875rem; color:var(--text-secondary); line-height:1.6; }

    /* Time entries */
    .time-entry-row { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0; border-bottom:1px solid var(--border-light); }
    .time-entry-row:last-child { border-bottom:none; }

    /* Subtasks */
    .subtask-row { display:flex; align-items:center; gap:0.75rem; padding:0.625rem 0; border-bottom:1px solid var(--border-light); }
    .subtask-row:last-child { border-bottom:none; }

    /* Forms */
    .form-control { width:100%; padding:0.5625rem 0.875rem; border-radius:0.5rem; border:1px solid var(--border-medium); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; font-family:inherit; outline:none; transition:border-color 0.2s; }
    .form-control:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    textarea.form-control { resize:vertical; }
    .form-label { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-tertiary); display:block; margin-bottom:0.375rem; }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }

    /* Approval banner */
    .approval-banner { padding:1rem 1.25rem; border-radius:0.625rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .approval-banner.pending { background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2); }
    .approval-banner.approved { background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); }
    .approval-banner.rejected { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ Str::limit($task->title, 40) }}</span>
</div>

@if(session('success'))
<div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:0.5rem; padding:0.875rem 1.25rem; margin-bottom:1rem; color:#10b981; display:flex; align-items:center; gap:0.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Approval banner --}}
@if($task->status === 'review' && is_null($task->is_approved))
<div class="approval-banner pending">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <i class="fas fa-clock" style="color:#f59e0b; font-size:1.25rem;"></i>
        <div>
            <div style="font-weight:600; color:#f59e0b;">En attente d'approbation</div>
            <div style="font-size:0.8125rem; color:var(--text-secondary);">Cette tâche est prête pour révision.</div>
        </div>
    </div>
    @can('tasks.approve')
    <div style="display:flex; gap:0.5rem;">
        <form method="POST" action="{{ route('admin.projects.tasks.approve', [$project, $task]) }}" style="display:inline;">
            @csrf
            <button type="submit" style="padding:0.5rem 1rem; background:#10b981; color:white; border:none; border-radius:0.5rem; cursor:pointer; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-check"></i> Approuver
            </button>
        </form>
        <button onclick="document.getElementById('rejectForm').style.display='block'" style="padding:0.5rem 1rem; background:#f59e0b; color:white; border:none; border-radius:0.5rem; cursor:pointer; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-times"></i> Rejeter
        </button>
    </div>
    @endcan
</div>

@can('tasks.approve')
<div id="rejectForm" style="display:none; background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; padding:1.25rem; margin-bottom:1rem;">
    <form method="POST" action="{{ route('admin.projects.tasks.reject', [$project, $task]) }}">
        @csrf
        <label class="form-label">Raison du rejet <span style="color:#ef4444;">*</span></label>
        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Expliquez pourquoi cette tâche est rejetée..." required style="margin-bottom:0.75rem;"></textarea>
        <button type="submit" class="btn-primary">
            <i class="fas fa-times-circle"></i> Confirmer le rejet
        </button>
    </form>
</div>
@endcan
@endif

@if($task->is_approved === true)
<div class="approval-banner approved">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <i class="fas fa-check-circle" style="color:#10b981; font-size:1.25rem;"></i>
        <div>
            <div style="font-weight:600; color:#10b981;">Tâche approuvée</div>
            <div style="font-size:0.8125rem; color:var(--text-secondary);">Approuvée par {{ $task->approvedBy?->name }} le {{ $task->approved_at?->format('d/m/Y') }}</div>
        </div>
    </div>
</div>
@endif

@if($task->is_approved === false)
<div class="approval-banner rejected">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <i class="fas fa-times-circle" style="color:#ef4444; font-size:1.25rem;"></i>
        <div>
            <div style="font-weight:600; color:#ef4444;">Tâche rejetée</div>
            <div style="font-size:0.8125rem; color:var(--text-secondary);">{{ $task->rejection_reason }}</div>
        </div>
    </div>
</div>
@endif

{{-- Hero --}}
<div class="task-hero">
    <div class="task-hero-top">
        <div style="flex:1;">
            <h1 class="task-title">{{ $task->title }}</h1>
            <div class="task-meta">
                <span class="badge {{ $task->status_badge_class }}">{{ $task->status_label }}</span>
                <span class="badge {{ $task->priority_badge_class }}">
                    <i class="fas {{ $task->priority_icon }}"></i> {{ $task->priority_label }}
                </span>
                @if($task->category)
                    <span class="badge badge-secondary">{{ $task->category }}</span>
                @endif
                @if($task->is_overdue)
                    <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> En retard</span>
                @endif
            </div>
        </div>
        <div class="hero-actions">
            @can('tasks.edit')
            <a href="{{ route('admin.projects.tasks.edit', [$project, $task]) }}" class="btn-secondary-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endcan
        </div>
    </div>
    @if($task->description)
        <p style="margin:1rem 0 0; color:var(--text-secondary); font-size:0.875rem; line-height:1.6;">{{ $task->description }}</p>
    @endif
</div>

<div class="grid-layout">
    <div>
        {{-- Onglets --}}
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('comments', this)">
                <i class="fas fa-comments"></i> Commentaires ({{ $task->comments->count() }})
            </button>
            <button class="tab-btn" onclick="switchTab('time', this)">
                <i class="fas fa-clock"></i> Temps ({{ $task->total_logged_hours }}h)
            </button>
            <button class="tab-btn" onclick="switchTab('subtasks', this)">
                <i class="fas fa-list-check"></i> Sous-tâches ({{ $task->subtasks->count() }})
            </button>
        </div>

        {{-- Commentaires --}}
        <div id="tab-comments" class="tab-content active">
            @foreach($task->comments as $comment)
            <div class="comment-item">
                <div class="comment-avatar">{{ strtoupper(substr($comment->user?->name ?? '?', 0, 2)) }}</div>
                <div class="comment-content">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user?->name ?? 'Inconnu' }}</span>
                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->is_internal)
                            <span class="badge badge-secondary" style="font-size:0.65rem;">Interne</span>
                        @endif
                    </div>
                    <div class="comment-body">{{ $comment->content }}</div>
                </div>
            </div>
            @endforeach

            {{-- Formulaire --}}
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.tasks.comments.store', [$project, $task]) }}">
                        @csrf
                        <label class="form-label">Ajouter un commentaire</label>
                        <textarea name="content" class="form-control" rows="3" placeholder="Votre commentaire..." required style="margin-bottom:0.75rem;"></textarea>
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--text-secondary); cursor:pointer;">
                                <input type="checkbox" name="is_internal" value="1" style="accent-color:var(--brand-primary);">
                                Commentaire interne
                            </label>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Temps --}}
        <div id="tab-time" class="tab-content">
            @foreach($task->timeEntries as $entry)
            <div class="time-entry-row">
                <div style="width:36px; height:36px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-clock" style="color:#3b82f6; font-size:0.875rem;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.875rem; font-weight:500; color:var(--text-primary);">{{ $entry->duration_formatted }}</div>
                    <div style="font-size:0.75rem; color:var(--text-tertiary);">{{ $entry->user?->name }} · {{ $entry->date->format('d/m/Y') }}</div>
                    @if($entry->description)
                        <div style="font-size:0.8125rem; color:var(--text-secondary); margin-top:0.25rem;">{{ $entry->description }}</div>
                    @endif
                </div>
                @if(!$entry->is_billable)
                    <span class="badge badge-secondary" style="font-size:0.65rem;">Non facturable</span>
                @endif
            </div>
            @endforeach

            {{-- Formulaire temps --}}
            <div class="card" style="margin-top:1rem;">
                <div class="card-header"><h3>Enregistrer du temps</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.tasks.time-entries.store', [$project, $task]) }}">
                        @csrf
                        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                            <div>
                                <label class="form-label">Date <span style="color:#ef4444;">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ today()->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Minutes <span style="color:#ef4444;">*</span></label>
                                <input type="number" name="minutes" class="form-control" min="1" placeholder="60" required>
                            </div>
                            <div>
                                <label class="form-label">Facturable</label>
                                <div style="padding-top:0.5rem;">
                                    <input type="checkbox" name="is_billable" value="1" checked style="accent-color:var(--brand-primary);"> Oui
                                </div>
                            </div>
                        </div>
                        <input type="text" name="description" class="form-control" placeholder="Description (optionnel)" style="margin-bottom:0.75rem;">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sous-tâches --}}
        <div id="tab-subtasks" class="tab-content">
            @forelse($task->subtasks as $subtask)
            <div class="subtask-row">
                <span class="badge {{ $subtask->status_badge_class }}" style="font-size:0.7rem;">{{ $subtask->status_label }}</span>
                <div style="flex:1;">
                    <a href="{{ route('admin.projects.tasks.show', [$project, $subtask]) }}" style="color:var(--text-primary); text-decoration:none; font-size:0.875rem; font-weight:500;">{{ $subtask->title }}</a>
                    <div style="font-size:0.75rem; color:var(--text-tertiary);">{{ $subtask->assignedTo?->name ?? 'Non assignée' }}</div>
                </div>
                @if($subtask->due_date)
                    <span style="font-size:0.75rem; color:{{ $subtask->is_overdue ? '#ef4444' : 'var(--text-tertiary)' }};">{{ $subtask->due_date->format('d/m/Y') }}</span>
                @endif
            </div>
            @empty
            <p style="text-align:center; color:var(--text-tertiary); padding:1.5rem 0;">Aucune sous-tâche</p>
            @endforelse
            @can('tasks.create')
            <div style="margin-top:1rem;">
                <a href="{{ route('admin.projects.tasks.create', $project) }}?parent_id={{ $task->id }}" class="btn-secondary-sm">
                    <i class="fas fa-plus"></i> Ajouter une sous-tâche
                </a>
            </div>
            @endcan
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-info-circle" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Détails</h3></div>
            <div class="card-body">
                <div style="display:flex; flex-direction:column; gap:0.875rem;">
                    <div>
                        <div class="info-label">Projet</div>
                        <div class="info-value">
                            <a href="{{ route('admin.projects.show', $project) }}" style="color:var(--brand-primary); text-decoration:none;">{{ $project->name }}</a>
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Assignée à</div>
                        <div class="info-value">{{ $task->assignedTo?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Créée par</div>
                        <div class="info-value">{{ $task->createdBy?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Créée le</div>
                        <div class="info-value">{{ $task->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="info-label">Échéance</div>
                        <div class="info-value" style="{{ $task->is_overdue ? 'color:#ef4444;' : '' }}">
                            {{ $task->due_date?->format('d/m/Y') ?? '-' }}
                        </div>
                    </div>
                    @if($task->estimated_hours)
                    <div>
                        <div class="info-label">Estimation</div>
                        <div class="info-value">{{ $task->estimated_hours }}h ({{ $task->total_logged_hours }}h logué)</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($task->attachments->isNotEmpty())
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-paperclip" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Pièces jointes</h3></div>
            <div class="card-body">
                @foreach($task->attachments as $attachment)
                <div style="display:flex; align-items:center; gap:0.625rem; padding:0.5rem 0; border-bottom:1px solid var(--border-light);">
                    <i class="fas {{ $attachment->icon }}" style="color:var(--brand-primary);"></i>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.8125rem; font-weight:500; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $attachment->original_name }}</div>
                        <div style="font-size:0.7rem; color:var(--text-tertiary);">{{ $attachment->size_formatted }}</div>
                    </div>
                    <a href="{{ $attachment->url }}" target="_blank" style="color:var(--brand-primary); font-size:0.875rem;"><i class="fas fa-download"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        btn.classList.add('active');
    }
</script>
@endpush
