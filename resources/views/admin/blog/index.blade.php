{{-- resources/views/admin/blog/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Blog - NovaTech Admin')
@section('page-title', 'Gestion du Blog')

@push('styles')
<style>
    /* Blog specific styles */
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
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); }
    .stat-icon.purple i { color: #8b5cf6; }

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
        z-index: 9999;
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
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
        font-size: 1.25rem;
        transition: color 0.2s ease;
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
        color: var(--text-secondary);
    }

    .modal-body .warning-text {
        color: #f59e0b;
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
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
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
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-success {
        background: #10b981;
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

    .blog-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .blog-table thead {
        background: var(--bg-tertiary);
    }

    .blog-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .blog-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .blog-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Article */
    .article-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .article-image {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        object-fit: cover;
        flex-shrink: 0;
        background: var(--bg-tertiary);
    }

    .article-placeholder {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .article-placeholder i {
        color: var(--text-tertiary);
        font-size: 1.25rem;
    }

    .article-info {
        flex: 1;
        min-width: 0;
    }

    .article-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .article-excerpt {
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
        transition: all 0.2s ease;
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
        color: #ef4444;
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

    /* Toast notification */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        border-left: 4px solid #10b981;
    }

    .toast-notification.error {
        border-left: 4px solid #ef4444;
    }

    .toast-notification i {
        font-size: 1.25rem;
    }

    .toast-notification.success i {
        color: #10b981;
    }

    .toast-notification.error i {
        color: #ef4444;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .article-excerpt {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-title-section">
        <h1>Blog</h1>
        <p>Gérez vos articles et publications</p>
    </div>
    <div>
        <a href="{{ route('admin.blog.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvel article
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
        <div class="stat-value" id="statTotal">{{ $posts->total() }}</div>
        <div class="stat-label">Total articles</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value" id="statPublished">{{ $posts->where('is_published', true)->count() }}</div>
        <div class="stat-label">Publiés</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-pen"></i>
            </div>
        </div>
        <div class="stat-value" id="statDraft">{{ $posts->where('is_published', false)->count() }}</div>
        <div class="stat-label">Brouillons</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="stat-value" id="statThisMonth">{{ $posts->filter(function($item) { return $item->created_at->month == now()->month; })->count() }}</div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher un article..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="category" class="filter-select">
                <option value="">Toutes catégories</option>
                <option value="Technologie">Technologie</option>
                <option value="Développement">Développement</option>
                <option value="Design">Design</option>
                <option value="Marketing">Marketing</option>
                <option value="Entreprise">Entreprise</option>
                <option value="Actualités">Actualités</option>
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
    <table class="blog-table">
        <thead>
            <tr>
                <th>Article</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Date de publication</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($posts as $index => $post)
            <tr class="table-row"
                data-id="{{ $post->id }}"
                data-title="{{ strtolower($post->title) }}"
                data-excerpt="{{ strtolower($post->excerpt ?? '') }}"
                data-category="{{ $post->category }}"
                data-status="{{ $post->is_published ? '1' : '0' }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div class="article-cell">
                        @if($post->image)
                            @php
                                $imagePath = $post->image;
                                if (!str_starts_with($imagePath, 'blog/') && !str_starts_with($imagePath, 'storage/')) {
                                    $imagePath = 'blog/' . $imagePath;
                                }
                                $imagePath = str_replace('storage/', '', $imagePath);
                            @endphp
                            <img src="{{ asset('storage/' . $imagePath) }}"
                                 alt="{{ $post->title }}"
                                 class="article-image"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/blog-placeholder.jpg') }}';">
                        @else
                            <div class="article-placeholder">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        @endif
                        <div class="article-info">
                            <div class="article-title">{{ $post->title }}</div>
                            <div class="article-excerpt">{{ Str::limit($post->excerpt ?? $post->content, 60) }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-category">
                        {{ $post->category }}
                    </span>
                </td>
                <td>
                    @if($post->is_published && $post->published_at)
                        <span class="badge badge-published">
                            <i class="fas fa-check-circle"></i> Publié
                        </span>
                    @else
                        <span class="badge badge-draft">
                            <i class="fas fa-pen"></i> Brouillon
                        </span>
                    @endif
                </td>
                <td class="date-cell" data-date="{{ $post->published_at?->format('Y-m-d') ?? $post->created_at->format('Y-m-d') }}">
                    @if($post->is_published && $post->published_at)
                        {{ $post->published_at->format('d/m/Y') }}
                    @else
                        <span style="color: var(--text-tertiary);">Non publié</span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="action-btn toggle-status-btn"
                                data-id="{{ $post->id }}"
                                data-title="{{ $post->title }}"
                                data-status="{{ $post->is_published ? '1' : '0' }}"
                                data-published="{{ $post->is_published ? 'true' : 'false' }}"
                                title="{{ $post->is_published ? 'Dépublier' : 'Publier' }}">
                            <i class="fas fa-{{ $post->is_published ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $post->id }}"
                                data-title="{{ $post->title }}"
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
                    <p>Aucun article trouvé</p>
                    <p style="font-size: 0.875rem;">Commencez par créer votre premier article de blog</p>
                    <a href="{{ route('admin.blog.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Créer un article
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($posts->hasPages())
<div class="pagination-wrapper">
    {{ $posts->links() }}
</div>
@endif

<!-- Modal de confirmation -->
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

<!-- Toast notification -->
<div id="toast" class="toast-notification">
    <i id="toastIcon" class="fas"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('status');
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');

    let currentAction = null;
    let currentItemId = null;

    // Fonction pour afficher une notification
    function showToast(message, type = 'success') {
        toastIcon.className = `fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}`;
        toastMessage.textContent = message;
        toast.className = `toast-notification ${type} show`;

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Fonction pour ouvrir le modal
    function openModal(title, message, warning, confirmText, confirmClass, onConfirm, itemId = null) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;
        modalConfirmBtn.className = `btn ${confirmClass}`;
        currentAction = onConfirm;
        currentItemId = itemId;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Fonction pour fermer le modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            currentAction = null;
            currentItemId = null;
        }, 300);
    }

    // Confirmer l'action
    function confirmAction() {
        if (currentAction) {
            currentAction();
        }
        closeModal();
    }

    // Fermer le modal en cliquant à l'extérieur
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeModal();
        }
    };

    // Écouteur pour le bouton de confirmation
    modalConfirmBtn.onclick = confirmAction;

    // Gestion de la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Toggle status (publier/dépublier)
    function initToggleButtons() {
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            // Supprimer l'ancien écouteur pour éviter les doublons
            btn.removeEventListener('click', handleToggleClick);
            btn.addEventListener('click', handleToggleClick);
        });
    }

    function handleToggleClick() {
        const id = this.dataset.id;
        const title = this.dataset.title;
        const isPublished = this.dataset.status === '1';

        openModal(
            `${isPublished ? 'Dépublier' : 'Publier'} l'article`,
            `Êtes-vous sûr de vouloir ${isPublished ? 'dépublier' : 'publier'} l'article "${title}" ?`,
            isPublished ? 'L\'article ne sera plus visible sur le site.' : 'L\'article sera visible sur le site une fois publié.',
            isPublished ? 'Dépublier' : 'Publier',
            isPublished ? 'btn-warning' : 'btn-success',
            () => togglePublish(id, isPublished),
            id
        );
    }

    function togglePublish(id, isCurrentlyPublished) {
        const url = `{{ url('admin/blog') }}/${id}/publish`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de l\'opération', 'error');
        });
    }

    // Suppression d'article
    function initDeleteButtons() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.removeEventListener('click', handleDeleteClick);
            btn.addEventListener('click', handleDeleteClick);
        });
    }

    function handleDeleteClick() {
        const id = this.dataset.id;
        const title = this.dataset.title;

        openModal(
            'Supprimer l\'article',
            `Êtes-vous sûr de vouloir supprimer l'article "${title}" ?`,
            'Action irréversible. Toutes les images seront supprimées.',
            'Supprimer',
            'btn-danger',
            () => deleteArticle(id),
            id
        );
    }

    function deleteArticle(id) {
        const url = `{{ url('admin/blog') }}/${id}`;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de la suppression', 'error');
        });
    }

    // Filtrage du tableau
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const categoryValue = categorySelect?.value || '';
        const statusValue = statusSelect?.value || '';

        let visibleCount = 0;
        let publishedCount = 0;
        let draftCount = 0;

        document.querySelectorAll('.blog-table tbody tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const excerpt = row.dataset.excerpt || '';
            const category = row.dataset.category || '';
            const status = row.dataset.status || '';

            if (searchTerm && !title.includes(searchTerm) && !excerpt.includes(searchTerm)) show = false;
            if (show && categoryValue && category !== categoryValue) show = false;
            if (show && statusValue && status !== statusValue) show = false;

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
                if (status === '1') {
                    publishedCount++;
                } else {
                    draftCount++;
                }
            }
        });

        // Mettre à jour les statistiques
        updateStats(visibleCount, publishedCount, draftCount);
    }

    function updateStats(total, published, draft) {
        const statTotal = document.getElementById('statTotal');
        const statPublished = document.getElementById('statPublished');
        const statDraft = document.getElementById('statDraft');

        if (statTotal) statTotal.textContent = total;
        if (statPublished) statPublished.textContent = published;
        if (statDraft) statDraft.textContent = draft;
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (categorySelect) categorySelect.value = '';
        if (statusSelect) statusSelect.value = '';
        filterTable();
    }

    // Debounce pour la recherche
    let timer;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (categorySelect) categorySelect.addEventListener('change', filterTable);
    if (statusSelect) statusSelect.addEventListener('change', filterTable);

    // Initialisation
    function init() {
        initToggleButtons();
        initDeleteButtons();
    }

    // Exposer les fonctions globalement
    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
    window.init = init;

    // Initialiser au chargement
    document.addEventListener('DOMContentLoaded', init);
</script>
@endpush
