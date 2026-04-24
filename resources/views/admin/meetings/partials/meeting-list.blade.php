{{-- resources/views/admin/meetings/partials/meeting-list.blade.php --}}

@push('styles')
<style>
    .meetings-grid {
        display: grid;
        gap: 1rem;
    }

    .meeting-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .meeting-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .meeting-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .meeting-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .meeting-project {
        font-size: 0.75rem;
        color: var(--brand-primary);
        margin-top: 0.25rem;
    }

    .meeting-date {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .meeting-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0.75rem 0;
    }

    .meeting-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
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

    .badge-scheduled { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-in_progress { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-completed { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
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

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        border-radius: 0.5rem;
        cursor: pointer;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
    }

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
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .help-text {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        margin-top: 0.5rem;
    }
</style>
@endpush

<div class="meetings-grid" id="meetingsContainer">
    @forelse($meetings as $meeting)
    <div class="meeting-card"
         data-id="{{ $meeting->id }}"
         data-title="{{ $meeting->title }}"
         data-status="{{ $meeting->status }}"
         data-date="{{ $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : '' }}">

        <div class="meeting-header">
            <div>
                <div class="meeting-title">{{ $meeting->title }}</div>
                <div class="meeting-project">
                    <i class="fas fa-project-diagram"></i> {{ $meeting->project->name ?? 'N/A' }}
                </div>
                <div class="meeting-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $meeting->meeting_date ? $meeting->meeting_date->format('d/m/Y à H:i') : 'Date non définie' }}
                    <span class="badge badge-{{ $meeting->status }}">
                        {{ $meeting->status_label }}
                    </span>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.meetings.show', $meeting) }}" class="action-btn" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                @if(auth()->user()->can('meetings.edit'))
                <a href="{{ route('admin.meetings.edit', $meeting) }}" class="action-btn" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                @endif
                @if(auth()->user()->can('meetings.delete'))
                <button type="button" class="action-btn delete-btn"
                        data-id="{{ $meeting->id }}"
                        data-title="{{ $meeting->title }}"
                        title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                @endif
            </div>
        </div>

        @if($meeting->description)
        <div class="meeting-description">
            {{ Str::limit($meeting->description, 150) }}
        </div>
        @endif

        <div class="meeting-meta">
            <span class="meta-tag">
                <i class="fas fa-user"></i>
                Organisé par {{ $meeting->organizer->name ?? 'N/A' }}
            </span>
            <span class="meta-tag">
                <i class="far fa-clock"></i>
                Durée: {{ $meeting->formatted_duration ?? $meeting->duration_minutes . 'min' }}
            </span>
            @if($meeting->location)
            <span class="meta-tag">
                <i class="fas fa-map-marker-alt"></i>
                {{ $meeting->location }}
            </span>
            @endif
            @if($meeting->meeting_link)
            <span class="meta-tag">
                <i class="fas fa-video"></i>
                <a href="{{ $meeting->meeting_link }}" target="_blank" style="color: var(--brand-primary);">Lien de réunion</a>
            </span>
            @endif
            @if($meeting->attendees && count($meeting->attendees) > 0)
            <span class="meta-tag">
                <i class="fas fa-users"></i>
                {{ count($meeting->attendees) }} participant(s)
            </span>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-calendar-alt"></i>
        <p>Aucune réunion trouvée</p>
        @if(auth()->user()->can('meetings.create') && isset($projects) && $projects->count() > 0)
        <a href="{{ route('admin.projects.meetings.create', $projects->first()->id) }}" class="btn-primary" style="margin-top: 1rem;">
            <i class="fas fa-plus"></i> Planifier une réunion
        </a>
        @endif
    </div>
    @endforelse
</div>

@if(isset($meetings) && $meetings->hasPages())
<div class="pagination-wrapper">
    {{ $meetings->links() }}
</div>
@endif

<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Supprimer la réunion</h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer la réunion <strong id="deleteMeetingTitle"></strong> ?</p>
            <p class="help-text">Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <button type="submit" class="btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(id, title) {
        const modal = document.getElementById('deleteModal');
        const titleSpan = document.getElementById('deleteMeetingTitle');
        const form = document.getElementById('deleteForm');

        if (titleSpan) titleSpan.textContent = title;
        if (form) form.action = "{{ url('admin/meetings') }}/" + id;
        if (modal) modal.classList.add('active');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.remove('active');
    }

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const title = this.dataset.title;
            if (id && title) {
                openDeleteModal(id, title);
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
</script>
@endpush
