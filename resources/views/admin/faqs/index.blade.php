@extends('admin.layouts.app')

@section('title', 'FAQ - NovaTech Admin')
@section('page-title', 'Gestion des FAQ')

@push('styles')
<style>
    /* FAQ specific styles */
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

    .stat-icon.indigo { background: rgba(59, 130, 246, 0.1); }
    .stat-icon.indigo i { color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.green i { color: #10b981; }
    .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }
    .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.cyan { background: rgba(6, 182, 212, 0.1); }
    .stat-icon.cyan i { color: #06b6d4; }

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

    /* Filtres */
    .filters-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        outline: none;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
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
        transition: all var(--transition-fast);
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Modal */
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
        z-index: var(--z-modal);
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition-base);
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-elevated);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 450px;
        box-shadow: var(--shadow-2xl);
        transform: scale(0.95);
        transition: transform var(--transition-base);
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
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
    }

    .modal-close:hover {
        color: var(--text-primary);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body p {
        margin: 0 0 0.5rem 0;
        line-height: 1.6;
    }

    .modal-body .warning-text {
        color: var(--brand-warning);
        font-size: 0.875rem;
        margin-top: 0.75rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: none;
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-danger {
        background: var(--brand-error);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-warning {
        background: var(--brand-warning);
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-success {
        background: var(--brand-success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    /* Grid */
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

    /* Table */
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .faqs-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .faqs-table thead {
        background: var(--bg-tertiary);
    }

    .faqs-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .faqs-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .faqs-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* FAQ Item */
    .faq-cell {
        min-width: 250px;
    }

    .faq-question {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .faq-answer {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 350px;
    }

    /* Badges */
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

    .badge-category {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .badge-category.general { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-category.services { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-category.tarifs { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-category.technique { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .badge-category.support { background: rgba(236, 72, 153, 0.1); color: #ec4899; }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Actions */
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
        transition: all var(--transition-fast);
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .action-btn.delete:hover {
        color: var(--brand-error);
    }

    /* Drag and Drop */
    .drag-handle {
        cursor: move;
        color: var(--text-tertiary);
        margin-right: 0.5rem;
    }

    .drag-handle:hover {
        color: var(--brand-primary);
    }

    .dragging {
        opacity: 0.5;
        background: var(--bg-hover);
    }

    .drag-over {
        border-top: 2px solid var(--brand-primary);
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

    .table-row {
        animation: fadeInUp 0.3s ease forwards;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        background: var(--bg-tertiary);
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .faq-answer {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
@can('faqs.view')
<!-- Header -->
<div class="page-header">
    <div class="page-title-section">
        <h1>FAQ</h1>
        <p>Gérez les questions fréquemment posées</p>
    </div>
    @can('faqs.create')
    <div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle FAQ
        </a>
    </div>
    @endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $faqs->total() }}</div>
        <div class="stat-label">Total FAQ</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $faqs->where('is_active', true)->count() }}</div>
        <div class="stat-label">Actives</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-eye-slash"></i>
            </div>
        </div>
        <div class="stat-value">{{ $faqs->where('is_active', false)->count() }}</div>
        <div class="stat-label">Inactives</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
        <div class="stat-value">{{ $categories->count() }}</div>
        <div class="stat-label">Catégories</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher une question..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="category" class="filter-select">
                <option value="">Toutes catégories</option>
                <option value="general">Général</option>
                <option value="services">Services</option>
                <option value="tarifs">Tarifs</option>
                <option value="technique">Technique</option>
                <option value="support">Support</option>
            </select>
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
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
    <table class="faqs-table">
        <thead>
            <tr>
                @can('faqs.edit')
                <th style="width: 40px;">Ordre</th>
                @endcan
                <th>Question</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="faqsTableBody">
            @forelse($faqs as $index => $faq)
            <tr class="table-row" data-id="{{ $faq->id }}" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower($faq->answer ?? '') }}" data-category="{{ $faq->category }}" data-status="{{ $faq->is_active ? '1' : '0' }}" style="animation-delay: {{ $index * 0.03 }}s;">
                @can('faqs.edit')
                <td style="cursor: move;">
                    <div class="drag-handle" data-id="{{ $faq->id }}">
                        <i class="fas fa-grip-vertical"></i>
                        <span class="order-badge ml-1">{{ $faq->order ?? $index + 1 }}</span>
                    </div>
                 </td>
                @endcan
                <td class="faq-cell">
                    <div class="faq-question">{{ $faq->question }}</div>
                    <div class="faq-answer">{{ Str::limit($faq->answer ?? 'Aucune réponse', 80) }}</div>
                </td>
                <td>
                    @php
                        $categoryLabels = [
                            'general' => 'Général',
                            'services' => 'Services',
                            'tarifs' => 'Tarifs',
                            'technique' => 'Technique',
                            'support' => 'Support'
                        ];
                        $categoryClass = $faq->category ?? 'general';
                    @endphp
                    <span class="badge badge-category {{ $categoryClass }}">
                        <i class="fas
                            @if($faq->category == 'general') fa-globe
                            @elseif($faq->category == 'services') fa-cogs
                            @elseif($faq->category == 'tarifs') fa-tag
                            @elseif($faq->category == 'technique') fa-microchip
                            @elseif($faq->category == 'support') fa-headset
                            @else fa-tag
                            @endif
                        "></i>
                        {{ $categoryLabels[$faq->category] ?? $faq->category }}
                    </span>
                </td>
                <td>
                    @if($faq->is_active)
                        <span class="badge badge-active">
                            <i class="fas fa-check-circle"></i> Actif
                        </span>
                    @else
                        <span class="badge badge-inactive">
                            <i class="fas fa-ban"></i> Inactif
                        </span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        @can('faqs.edit')
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('faqs.edit')
                        <button type="button" class="action-btn toggle-status-btn {{ $faq->is_active ? 'deactivate' : 'activate' }}"
                                data-id="{{ $faq->id }}"
                                data-question="{{ $faq->question }}"
                                data-status="{{ $faq->is_active ? '1' : '0' }}"
                                title="{{ $faq->is_active ? 'Désactiver' : 'Activer' }}">
                            <i class="fas fa-{{ $faq->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        @endcan
                        @can('faqs.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $faq->id }}"
                                data-question="{{ $faq->question }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="{{ Auth::user()->can('faqs.edit') ? '5' : '4' }}" class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <p>Aucune FAQ trouvée</p>
                    <p style="font-size: 0.875rem;">Commencez par créer votre première FAQ</p>
                    @can('faqs.create')
                    <a href="{{ route('admin.faqs.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Créer une FAQ
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($faqs->hasPages())
<div class="pagination-wrapper">
    {{ $faqs->links() }}
</div>
@endif

<!-- Modal -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Confirmation</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p id="modalWarning" class="warning-text"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn">Confirmer</button>
        </div>
    </div>
</div>
@endcan

@cannot('faqs.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('status');
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    let currentAction = null;
    let draggedItem = null;

    function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;
        modalConfirmBtn.className = `btn ${confirmClass}`;
        currentAction = onConfirm;
        modal.classList.add('active');
    }

    function closeModal() {
        modal.classList.remove('active');
        currentAction = null;
    }

    function confirmAction() {
        if (currentAction) currentAction();
        closeModal();
    }

    modalConfirmBtn.onclick = confirmAction;
    modal.onclick = function(e) { if (e.target === modal) closeModal(); };

    // Toggle status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const question = this.dataset.question;
            const isActive = this.dataset.status === '1';

            openModal(
                `${isActive ? 'Désactiver' : 'Activer'} la FAQ`,
                `Êtes-vous sûr de vouloir ${isActive ? 'désactiver' : 'activer'} la FAQ "${question}" ?`,
                isActive ? 'La FAQ ne sera plus visible sur le site.' : 'La FAQ sera visible sur le site une fois activée.',
                isActive ? 'Désactiver' : 'Activer',
                isActive ? 'btn-warning' : 'btn-success',
                () => {
                    fetch(`{{ url('admin/faqs') }}/${id}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
                }
            );
        });
    });

    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const question = this.dataset.question;

            openModal(
                'Supprimer la FAQ',
                `Êtes-vous sûr de vouloir supprimer la FAQ "${question}" ?`,
                'Action irréversible.',
                'Supprimer',
                'btn-danger',
                () => {
                    fetch(`{{ url('admin/faqs') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
                }
            );
        });
    });

    // Drag and Drop pour le réordonnancement
    @can('faqs.edit')
    const tbody = document.getElementById('faqsTableBody');
    let dragSrcElement = null;

    function handleDragStart(e) {
        dragSrcElement = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        this.classList.add('dragging');
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }

        if (dragSrcElement !== this) {
            const order = [];
            const rows = Array.from(tbody.querySelectorAll('tr:not(#emptyStateRow)'));
            const draggedId = dragSrcElement.dataset.id;
            const targetId = this.dataset.id;
            const draggedIndex = rows.indexOf(dragSrcElement);
            const targetIndex = rows.indexOf(this);

            if (draggedIndex < targetIndex) {
                this.parentNode.insertBefore(dragSrcElement, this.nextSibling);
            } else {
                this.parentNode.insertBefore(dragSrcElement, this);
            }

            // Mettre à jour l'ordre
            const updatedRows = Array.from(tbody.querySelectorAll('tr:not(#emptyStateRow)'));
            updatedRows.forEach((row, index) => {
                order.push(row.dataset.id);
            });

            // Envoyer le nouvel ordre au serveur
            fetch('{{ route("admin.faqs.reorder") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order: order })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour les badges d'ordre
                    updatedRows.forEach((row, index) => {
                        const orderBadge = row.querySelector('.order-badge');
                        if (orderBadge) {
                            orderBadge.textContent = index + 1;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erreur lors du réordonnancement:', error);
                location.reload();
            });
        }

        this.classList.remove('drag-over');
        return false;
    }

    function handleDragEnd(e) {
        document.querySelectorAll('.drag-over').forEach(elem => {
            elem.classList.remove('drag-over');
        });
        document.querySelectorAll('.dragging').forEach(elem => {
            elem.classList.remove('dragging');
        });
    }

    function initDragAndDrop() {
        const rows = tbody.querySelectorAll('tr:not(#emptyStateRow)');
        rows.forEach(row => {
            row.addEventListener('dragstart', handleDragStart);
            row.addEventListener('dragover', handleDragOver);
            row.addEventListener('dragenter', handleDragEnter);
            row.addEventListener('dragleave', handleDragLeave);
            row.addEventListener('drop', handleDrop);
            row.addEventListener('dragend', handleDragEnd);
            row.setAttribute('draggable', 'true');
        });
    }

    if (tbody && tbody.querySelectorAll('tr:not(#emptyStateRow)').length > 1) {
        initDragAndDrop();
    }
    @endcan

    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const categoryValue = categorySelect?.value || '';
        const statusValue = statusSelect?.value || '';

        document.querySelectorAll('.faqs-table tbody tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const question = row.dataset.question || '';
            const answer = row.dataset.answer || '';
            const category = row.dataset.category || '';
            const status = row.dataset.status || '';

            if (searchTerm && !question.includes(searchTerm) && !answer.includes(searchTerm)) show = false;
            if (show && categoryValue && category !== categoryValue) show = false;
            if (show && statusValue && status !== statusValue) show = false;

            row.style.display = show ? '' : 'none';
        });

        // Vérifier si le tableau est vide après filtrage
        const visibleRows = document.querySelectorAll('.faqs-table tbody tr:not(#emptyStateRow)[style="display: none;"]');
        const allRows = document.querySelectorAll('.faqs-table tbody tr:not(#emptyStateRow)');

        if (allRows.length > 0 && allRows.length === visibleRows.length) {
            if (!document.getElementById('noResultsRow')) {
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="{{ Auth::user()->can('faqs.edit') ? '5' : '4' }}" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                </td>`;
                tbody.appendChild(emptyRow);
            }
        } else {
            const noResultsRow = document.getElementById('noResultsRow');
            if (noResultsRow) noResultsRow.remove();
        }
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (categorySelect) categorySelect.value = '';
        if (statusSelect) statusSelect.value = '';
        filterTable();
    }

    let timer;
    if (searchInput) searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(filterTable, 300);
    });
    if (categorySelect) categorySelect.addEventListener('change', filterTable);
    if (statusSelect) statusSelect.addEventListener('change', filterTable);

    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
