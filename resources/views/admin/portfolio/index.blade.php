@extends('admin.layouts.app')

@section('title', 'Portfolio - NovaTech Admin')
@section('page-title', 'Gestion du Portfolio')

@push('styles')
<style>
    /* Portfolio specific styles */
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

    .portfolio-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .portfolio-table thead {
        background: var(--bg-tertiary);
    }

    .portfolio-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .portfolio-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .portfolio-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Project */
    .project-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .project-image {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        object-fit: cover;
        flex-shrink: 0;
        background: var(--bg-tertiary);
    }

    .project-placeholder {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .project-placeholder i {
        color: var(--text-tertiary);
        font-size: 1.25rem;
    }

    .project-info {
        flex: 1;
        min-width: 0;
    }

    .project-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .project-description {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
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

    .badge-published {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-draft {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
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

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .project-description {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-title-section">
        <h1>Portfolio</h1>
        <p>Gérez vos projets et réalisations</p>
    </div>
    <div>
        <a href="{{ route('admin.portfolio.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouveau projet
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <div class="stat-value" id="statTotal">{{ $portfolios->total() }}</div>
        <div class="stat-label">Total projets</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value" id="statPublished">{{ $portfolios->where('is_active', true)->count() }}</div>
        <div class="stat-label">Publiés</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-pen"></i>
            </div>
        </div>
        <div class="stat-value" id="statDraft">{{ $portfolios->where('is_active', false)->count() }}</div>
        <div class="stat-label">Brouillons</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="stat-value" id="statThisMonth">{{ $portfolios->filter(function($item) { return $item->created_at->month == now()->month; })->count() }}</div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher un projet..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="category" class="filter-select">
                <option value="">Toutes catégories</option>
                <option value="site-vitrine">Site Vitrine</option>
                <option value="e-commerce">E-commerce</option>
                <option value="application-web">Application Web</option>
                <option value="maintenance">Maintenance</option>
                <option value="autre">Autre</option>
            </select>
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Publié</option>
                <option value="0">Brouillon</option>
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
    <table class="portfolio-table">
        <thead>
            <tr>
                <th>Projet</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($portfolios as $index => $portfolio)
            <tr class="table-row" data-id="{{ $portfolio->id }}" data-title="{{ strtolower($portfolio->title) }}" data-description="{{ strtolower($portfolio->description ?? '') }}" data-category="{{ $portfolio->category_label ?? $portfolio->category }}" data-status="{{ $portfolio->is_active ? '1' : '0' }}" style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div class="project-cell">
                        @if($portfolio->image)
                            @php
                                // Nettoyer le chemin de l'image
                                $imagePath = $portfolio->image;
                                // Si le chemin commence par 'portfolio/', on garde tel quel
                                if (!str_starts_with($imagePath, 'portfolio/') && !str_starts_with($imagePath, 'storage/')) {
                                    $imagePath = 'portfolio/' . $imagePath;
                                }
                                // Enlever 'storage/' si présent car asset ajoute le bon chemin
                                $imagePath = str_replace('storage/', '', $imagePath);
                            @endphp
                            <img src="{{ asset('storage/' . $imagePath) }}"
                                 alt="{{ $portfolio->title }}"
                                 class="project-image"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/portfolio-placeholder.jpg') }}';">
                        @else
                            <div class="project-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="project-info">
                            <div class="project-title">{{ $portfolio->title }}</div>
                            <div class="project-description">{{ Str::limit($portfolio->description ?? 'Aucune description', 50) }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-category">
                        {{ $portfolio->category_label ?? $portfolio->category }}
                    </span>
                </td>
                <td>
                    @if($portfolio->is_active)
                        <span class="badge badge-published">
                            <i class="fas fa-check-circle"></i> Publié
                        </span>
                    @else
                        <span class="badge badge-draft">
                            <i class="fas fa-pen"></i> Brouillon
                        </span>
                    @endif
                </td>
                <td class="date-cell" data-date="{{ $portfolio->created_at->format('Y-m-d') }}">
                    {{ $portfolio->created_at->format('d/m/Y') }}
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.portfolio.edit', $portfolio) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="action-btn toggle-status-btn {{ $portfolio->is_active ? 'unpublish' : 'publish' }}"
                                data-id="{{ $portfolio->id }}"
                                data-title="{{ $portfolio->title }}"
                                data-status="{{ $portfolio->is_active ? '1' : '0' }}"
                                title="{{ $portfolio->is_active ? 'Dépublier' : 'Publier' }}">
                            <i class="fas fa-{{ $portfolio->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $portfolio->id }}"
                                data-title="{{ $portfolio->title }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="5" class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Aucun projet trouvé</p>
                    <p style="font-size: 0.875rem;">Commencez par créer votre premier projet portfolio</p>
                    <a href="{{ route('admin.portfolio.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Créer un projet
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($portfolios->hasPages())
<div class="pagination-wrapper">
    {{ $portfolios->links() }}
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
            const title = this.dataset.title;
            const isPublished = this.dataset.status === '1';

            openModal(
                `${isPublished ? 'Dépublier' : 'Publier'} le projet`,
                `Êtes-vous sûr de vouloir ${isPublished ? 'dépublier' : 'publier'} le projet "${title}" ?`,
                isPublished ? 'Le projet ne sera plus visible sur le site.' : 'Le projet sera visible sur le site une fois publié.',
                isPublished ? 'Dépublier' : 'Publier',
                isPublished ? 'btn-warning' : 'btn-success',
                () => {
                    fetch(`{{ url('admin/portfolio') }}/${id}/toggle-status`, {
                        method: 'PATCH',
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
            const title = this.dataset.title;

            openModal(
                'Supprimer le projet',
                `Êtes-vous sûr de vouloir supprimer le projet "${title}" ?`,
                'Action irréversible. Toutes les images seront supprimées.',
                'Supprimer',
                'btn-danger',
                () => {
                    fetch(`{{ url('admin/portfolio') }}/${id}`, {
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

    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const categoryValue = categorySelect?.value || '';
        const statusValue = statusSelect?.value || '';

        document.querySelectorAll('.portfolio-table tbody tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const desc = row.dataset.description || '';
            const category = row.dataset.category || '';
            const status = row.dataset.status || '';

            if (searchTerm && !title.includes(searchTerm) && !desc.includes(searchTerm)) show = false;
            if (show && categoryValue && category !== categoryValue) show = false;
            if (show && statusValue && status !== statusValue) show = false;

            row.style.display = show ? '' : 'none';
        });

        // Mettre à jour les statistiques après filtrage
        updateStats();
    }

    function updateStats() {
        const visibleRows = document.querySelectorAll('.portfolio-table tbody tr:not(#emptyStateRow)[style="display: none;"]');
        // Logique de mise à jour des stats si nécessaire
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
