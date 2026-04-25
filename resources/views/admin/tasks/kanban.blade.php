{{-- resources/views/admin/tasks/kanban.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Flux de travail - ' . $project->name . ' - NovaTech Admin')
@section('page-title', 'Flux de travail — ' . $project->name)

@push('styles')
<style>
    /* ---------- Toolbar ---------- */
    .kanban-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: 0.85rem 1rem;
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .kanban-toolbar .left,
    .kanban-toolbar .right {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        min-width: 0;
    }

    .kanban-toolbar .right {
        flex: 1 1 auto;
        justify-content: flex-end;
    }

    /* --- Boutons toolbar --- */
    .kanban-toolbar .kb-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.1s, box-shadow 0.2s;
        white-space: nowrap;
    }

    .kanban-toolbar .kb-btn i { font-size: 0.8rem; }

    .kanban-toolbar .kb-btn-ghost {
        background: var(--bg-secondary);
        color: var(--text-secondary);
        border-color: var(--border-light);
    }
    .kanban-toolbar .kb-btn-ghost:hover {
        background: var(--bg-tertiary);
        color: var(--text-primary);
        border-color: var(--brand-primary);
    }

    .kanban-toolbar .kb-btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }
    .kanban-toolbar .kb-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }
    .kanban-toolbar .kb-btn-primary:active {
        transform: translateY(0);
    }

    /* --- Barre de recherche adaptative --- */
    .kanban-toolbar .search-wrapper {
        position: relative;
        flex: 1 1 260px;
        min-width: 180px;
        max-width: 420px;
    }

    .kanban-toolbar .search-wrapper i.search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        color: var(--text-tertiary);
        pointer-events: none;
    }

    .kanban-toolbar .search-input {
        width: 100%;
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 0.5rem;
        padding: 0.55rem 0.75rem 0.55rem 2.1rem;
        font-size: 0.85rem;
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .kanban-toolbar .search-input::placeholder {
        color: var(--text-tertiary);
    }

    .kanban-toolbar .search-input:focus {
        outline: none;
        background: var(--bg-primary);
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    @media (max-width: 900px) {
        .kanban-toolbar { padding: 0.75rem; }
        .kanban-toolbar .left,
        .kanban-toolbar .right { width: 100%; justify-content: flex-start; }
        .kanban-toolbar .search-wrapper { max-width: 100%; flex: 1 1 100%; }
    }

    /* ---------- Conteneur Kanban ---------- */
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
        transition: border-color 0.2s;
    }

    .kanban-column.is-over {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .kanban-header {
        padding: 0.75rem 1rem;
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
        color: var(--text-primary);
    }

    .kanban-header .title-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-dot {
        width: 0.6rem;
        height: 0.6rem;
        border-radius: 9999px;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-dot.gray   { background: #6b7280; }
    .status-dot.blue   { background: #3b82f6; }
    .status-dot.yellow { background: #f59e0b; }
    .status-dot.green  { background: #10b981; }
    .status-dot.red    { background: #ef4444; }
    .status-dot.purple { background: #8b5cf6; }

    .task-count {
        background: var(--bg-secondary);
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        color: var(--text-secondary);
        min-width: 1.6rem;
        text-align: center;
    }

    .kanban-body {
        flex: 1;
        padding: 0.5rem;
        overflow-y: auto;
        min-height: 300px;
    }

    .kanban-body.drop-zone {
        background: rgba(59, 130, 246, 0.06);
        border: 2px dashed var(--brand-primary);
        border-radius: 0 0 0.75rem 0.75rem;
    }

    /* ---------- Cartes ---------- */
    .kanban-card {
        background: var(--bg-primary);
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border: 1px solid var(--border-light);
        cursor: grab;
        transition: box-shadow 0.2s, transform 0.15s, border-color 0.2s, opacity 0.2s;
        user-select: none;
        position: relative;
    }

    .kanban-card:hover {
        border-color: var(--brand-primary);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }

    .kanban-card:active { cursor: grabbing; }
    .kanban-card.dragging { opacity: 0.45; transform: rotate(-1deg); }

    .kanban-card.readonly {
        cursor: default;
    }
    .kanban-card.readonly:hover {
        transform: none;
    }

    .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.4rem;
    }

    .card-number {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-tertiary);
        background: var(--bg-secondary);
        padding: 0.15rem 0.4rem;
        border-radius: 0.35rem;
        letter-spacing: 0.02em;
    }

    .card-title {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.35;
        text-decoration: none;
        display: block;
        word-break: break-word;
    }
    .card-title:hover { color: var(--brand-primary); }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    .card-meta + .card-meta {
        margin-top: 0.4rem;
    }

    .card-assignee {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        min-width: 0;
    }
    .card-assignee .name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 140px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: 9999px;
        white-space: nowrap;
    }

    .badge-low     { background: rgba(107, 114, 128, 0.12); color: #6b7280; }
    .badge-medium  { background: rgba(59, 130, 246, 0.12);  color: #3b82f6; }
    .badge-high    { background: rgba(245, 158, 11, 0.12);  color: #d97706; }
    .badge-urgent  { background: rgba(239, 68, 68, 0.15);   color: #ef4444; }

    .badge-type    { background: rgba(139, 92, 246, 0.12);  color: #8b5cf6; }

    .badge-overdue {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
    }

    .card-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .empty-state-small {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--text-tertiary);
        font-size: 0.75rem;
    }

    .empty-state-small i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
        display: block;
    }

    /* ---------- Toast ---------- */
    .kanban-toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.25s, transform 0.25s;
        pointer-events: none;
        max-width: 320px;
    }
    .kanban-toast.show { opacity: 1; transform: translateY(0); }
    .kanban-toast.success { background: #10b981; color: #fff; }
    .kanban-toast.error   { background: #ef4444; color: #fff; }

    /* ---------- Responsive ---------- */
    @media (max-width: 768px) {
        .kanban-container { flex-direction: column; }
        .kanban-column { width: 100%; min-width: auto; max-height: none; }
        .kanban-body { max-height: 60vh; }
    }

    /* Désactiver le pointeur sur les enfants pendant le drag
       pour éviter le flicker sur dragleave. */
    .kanban-card.dragging * { pointer-events: none; }
</style>
@endpush

@section('content')

<div class="kanban-toolbar">
    <div class="left">
        <a href="{{ route('admin.projects.show', $project) }}" class="kb-btn kb-btn-ghost">
            <i class="fas fa-arrow-left"></i> Retour au projet
        </a>
        <a href="{{ route('admin.projects.tasks.index', $project) }}" class="kb-btn kb-btn-ghost">
            <i class="fas fa-list"></i> Vue liste
        </a>
    </div>
    <div class="right">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="search"
                   id="kanbanSearch"
                   class="search-input"
                   placeholder="Rechercher une tâche (titre, numéro)…"
                   aria-label="Rechercher une tâche">
        </div>
        @can('tasks.create')
        <a href="{{ route('admin.projects.tasks.create', $project) }}" class="kb-btn kb-btn-primary">
            <i class="fas fa-plus"></i> Nouvelle tâche
        </a>
        @endcan
    </div>
</div>

@php
    $canEdit = auth()->user()->can('tasks.edit');
@endphp

<div class="kanban-container"
     id="kanbanContainer"
     data-update-url="{{ route('admin.tasks.update-order') }}"
     data-can-edit="{{ $canEdit ? '1' : '0' }}">

    @foreach($statuses as $statusKey => $status)
        @php
            $columnTasks = $tasks[$statusKey] ?? collect();
            $color       = $status['color'] ?? 'gray';
        @endphp
        <div class="kanban-column" data-status="{{ $statusKey }}">
            <div class="kanban-header">
                <h3>
                    <span class="title-left">
                        <span class="status-dot {{ $color }}"></span>
                        {{ $status['label'] }}
                    </span>
                    <span class="task-count" data-count>{{ $columnTasks->count() }}</span>
                </h3>
            </div>

            <div class="kanban-body" data-status="{{ $statusKey }}">
                @forelse($columnTasks as $task)
                    <div class="kanban-card {{ $canEdit ? '' : 'readonly' }}"
                         data-id="{{ $task->id }}"
                         data-title="{{ strtolower($task->title) }}"
                         data-number="{{ strtolower($task->task_number ?? '') }}"
                         @if($canEdit) draggable="true" @endif>

                        <div class="card-top">
                            @if($task->task_number)
                                <span class="card-number">{{ $task->task_number }}</span>
                            @else
                                <span></span>
                            @endif
                            <span class="badge badge-{{ $task->priority }}">
                                <i class="fas fa-flag"></i>
                                {{ $task->priority_label }}
                            </span>
                        </div>

                        <a href="{{ route('admin.tasks.show', $task) }}"
                           class="card-title"
                           title="{{ $task->title }}">
                            {{ $task->title }}
                        </a>

                        <div class="card-meta">
                            <div class="card-assignee" title="{{ $task->assignee->name ?? 'Non assigné' }}">
                                <i class="fas fa-user"></i>
                                <span class="name">{{ $task->assignee->name ?? 'Non assigné' }}</span>
                            </div>
                            @if($task->task_type)
                                <span class="badge badge-type">
                                    {{ $task->type_label }}
                                </span>
                            @endif
                        </div>

                        @if($task->due_date)
                            <div class="card-footer-row">
                                <span class="card-meta" style="display:inline-flex;align-items:center;gap:.3rem;">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $task->due_date->format('d/m/Y') }}
                                </span>
                                @if($task->is_overdue)
                                    <span class="badge badge-overdue">
                                        <i class="fas fa-exclamation-triangle"></i> En retard
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-state-small" data-empty>
                        <i class="fas fa-inbox"></i>
                        <p>Aucune tâche</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<div id="kanbanToast" class="kanban-toast" role="status" aria-live="polite"></div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const container   = document.getElementById('kanbanContainer');
    if (!container) return;

    const updateUrl   = container.dataset.updateUrl;
    const canEdit     = container.dataset.canEdit === '1';
    const csrfToken   = '{{ csrf_token() }}';
    const toastEl     = document.getElementById('kanbanToast');

    let draggedCard   = null;
    let sourceBody    = null;

    /* ---------------- Toast ---------------- */
    let toastTimer = null;
    function showToast(message, type) {
        if (!toastEl) return;
        toastEl.textContent = message;
        toastEl.className = 'kanban-toast show ' + (type || 'success');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toastEl.classList.remove('show');
        }, 3000);
    }

    /* ---------------- Helpers ---------------- */
    function updateCounts() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const body  = col.querySelector('.kanban-body');
            const count = body.querySelectorAll('.kanban-card').length;
            const span  = col.querySelector('[data-count]');
            if (span) span.textContent = count;

            // Empty-state
            const empty = body.querySelector('[data-empty]');
            if (count === 0 && !empty) {
                const div = document.createElement('div');
                div.className = 'empty-state-small';
                div.dataset.empty = '';
                div.innerHTML = '<i class="fas fa-inbox"></i><p>Aucune tâche</p>';
                body.appendChild(div);
            } else if (count > 0 && empty) {
                empty.remove();
            }
        });
    }

    function getColumnTaskIds(body) {
        return Array.from(body.querySelectorAll('.kanban-card'))
            .map(c => c.dataset.id);
    }

    async function sendUpdate(payload) {
        const response = await fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type'    : 'application/json',
                'Accept'          : 'application/json',
                'X-CSRF-TOKEN'    : csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        let data = null;
        try { data = await response.json(); } catch (e) { /* ignore */ }

        if (!response.ok || !data || data.success === false) {
            const msg = (data && data.message) ? data.message : 'Erreur lors de la mise à jour';
            throw new Error(msg);
        }
        return data;
    }

    /* ---------------- Drag & Drop ---------------- */
    if (canEdit) {
        container.addEventListener('dragstart', (e) => {
            const card = e.target.closest('.kanban-card');
            if (!card || card.classList.contains('readonly')) return;

            draggedCard = card;
            sourceBody  = card.closest('.kanban-body');
            card.classList.add('dragging');

            e.dataTransfer.effectAllowed = 'move';
            try { e.dataTransfer.setData('text/plain', card.dataset.id); } catch (_) {}
        });

        container.addEventListener('dragend', () => {
            if (draggedCard) draggedCard.classList.remove('dragging');
            draggedCard = null;
            sourceBody  = null;
            document.querySelectorAll('.kanban-body.drop-zone').forEach(b => b.classList.remove('drop-zone'));
            document.querySelectorAll('.kanban-column.is-over').forEach(c => c.classList.remove('is-over'));
        });

        document.querySelectorAll('.kanban-body').forEach(body => {
            const column = body.closest('.kanban-column');

            body.addEventListener('dragover', (e) => {
                if (!draggedCard) return;
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                body.classList.add('drop-zone');
                if (column) column.classList.add('is-over');

                // Insertion point visuelle : déplacer physiquement le card
                const afterCard = getCardAfter(body, e.clientY);
                if (afterCard == null) {
                    body.appendChild(draggedCard);
                } else if (afterCard !== draggedCard) {
                    body.insertBefore(draggedCard, afterCard);
                }
            });

            body.addEventListener('dragleave', (e) => {
                // Ne supprimer le style que si on quitte réellement le body
                if (e.relatedTarget && body.contains(e.relatedTarget)) return;
                body.classList.remove('drop-zone');
                if (column) column.classList.remove('is-over');
            });

            body.addEventListener('drop', async (e) => {
                e.preventDefault();
                body.classList.remove('drop-zone');
                if (column) column.classList.remove('is-over');

                if (!draggedCard) return;

                const taskId    = draggedCard.dataset.id;
                const newStatus = body.dataset.status;
                const oldStatus = sourceBody ? sourceBody.dataset.status : null;
                const orderIds  = getColumnTaskIds(body);
                const newOrder  = orderIds.indexOf(taskId);

                updateCounts();

                // Sauvegarder état avant appel (pour revert)
                const prevParent = sourceBody;
                const prevSibling = null; // recalculé à partir de orderIds si besoin

                try {
                    if (oldStatus !== newStatus) {
                        // Changement de colonne : maj statut + order
                        await sendUpdate({
                            task_id: parseInt(taskId, 10),
                            status : newStatus,
                            order  : newOrder,
                            tasks  : orderIds
                        });
                        showToast('Tâche déplacée vers « ' + getStatusLabel(newStatus) + ' »', 'success');
                    } else {
                        // Réordonnancement dans la même colonne
                        await sendUpdate({
                            status: newStatus,
                            tasks : orderIds
                        });
                        showToast('Ordre mis à jour', 'success');
                    }
                } catch (err) {
                    console.error(err);
                    // Revert visuel : remettre la carte dans la colonne d'origine
                    if (prevParent && draggedCard) {
                        prevParent.appendChild(draggedCard);
                        updateCounts();
                    }
                    showToast(err.message || 'Erreur lors de la mise à jour', 'error');
                }
            });
        });
    }

    function getCardAfter(body, y) {
        const cards = [...body.querySelectorAll('.kanban-card:not(.dragging)')];
        return cards.reduce((closest, card) => {
            const box    = card.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: card };
            }
            return closest;
        }, { offset: Number.NEGATIVE_INFINITY, element: null }).element;
    }

    function getStatusLabel(statusKey) {
        const col = document.querySelector('.kanban-column[data-status="' + statusKey + '"] .title-left');
        return col ? col.textContent.trim() : statusKey;
    }

    /* ---------------- Recherche ---------------- */
    const searchInput = document.getElementById('kanbanSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const q = (e.target.value || '').trim().toLowerCase();
            document.querySelectorAll('.kanban-card').forEach(card => {
                const title  = card.dataset.title  || '';
                const number = card.dataset.number || '';
                const match  = !q || title.includes(q) || number.includes(q);
                card.style.display = match ? '' : 'none';
            });
        });
    }
})();
</script>
@endpush
