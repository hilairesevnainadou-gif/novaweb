{{-- resources/views/admin/testimonials/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Témoignages - NovaTech Admin')
@section('page-title', 'Gestion des Témoignages')

@push('styles')
<style>
    /* Testimonials specific styles */
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

    .testimonials-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .testimonials-table thead {
        background: var(--bg-tertiary);
    }

    .testimonials-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .testimonials-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .testimonials-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Avatar */
    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .testimonial-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        background: var(--bg-tertiary);
    }

    .avatar-placeholder {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-hover));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .testimonial-info {
        flex: 1;
        min-width: 0;
    }

    .testimonial-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .testimonial-position {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .testimonial-company {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        margin-top: 0.125rem;
    }

    /* Content cell */
    .content-cell {
        max-width: 300px;
    }

    .content-preview {
        font-size: 0.8125rem;
        line-height: 1.4;
        color: var(--text-secondary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Rating stars */
    .rating-stars {
        display: flex;
        gap: 0.25rem;
        color: #f59e0b;
        font-size: 0.875rem;
    }

    .rating-stars i {
        margin-right: 0.125rem;
    }

    .rating-value {
        margin-left: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
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

    .action-btn.toggle:hover {
        color: #f59e0b;
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

        .content-cell {
            max-width: 200px;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-title-section">
        <h1>Témoignages</h1>
        <p>Gérez les avis et témoignages de vos clients</p>
    </div>
    <div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouveau témoignage
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-comments"></i>
            </div>
        </div>
        <div class="stat-value" id="statTotal">{{ $testimonials->total() }}</div>
        <div class="stat-label">Total témoignages</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value" id="statActive">{{ $testimonials->where('is_active', true)->count() }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-eye-slash"></i>
            </div>
        </div>
        <div class="stat-value" id="statInactive">{{ $testimonials->where('is_active', false)->count() }}</div>
        <div class="stat-label">Inactifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-value" id="statAvgRating">
            @php
                $avgRating = $testimonials->where('rating', '>', 0)->avg('rating');
            @endphp
            {{ number_format($avgRating, 1) }}
        </div>
        <div class="stat-label">Note moyenne</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher un témoignage..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
            </select>
        </div>
        <div>
            <select id="rating" class="filter-select">
                <option value="">Toutes notes</option>
                <option value="5">5 étoiles</option>
                <option value="4">4 étoiles et +</option>
                <option value="3">3 étoiles et +</option>
                <option value="2">2 étoiles et +</option>
                <option value="1">1 étoile et +</option>
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
    <table class="testimonials-table">
        <thead>
            <tr>
                <th>Témoin</th>
                <th>Témoignage</th>
                <th>Note</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($testimonials as $index => $testimonial)
            <tr class="table-row"
                data-id="{{ $testimonial->id }}"
                data-name="{{ strtolower($testimonial->name) }}"
                data-position="{{ strtolower($testimonial->position ?? '') }}"
                data-company="{{ strtolower($testimonial->company ?? '') }}"
                data-content="{{ strtolower($testimonial->content) }}"
                data-status="{{ $testimonial->is_active ? '1' : '0' }}"
                data-rating="{{ $testimonial->rating ?? 0 }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td>
                    <div class="avatar-cell">
                        @if($testimonial->avatar)
                            <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                 alt="{{ $testimonial->name }}"
                                 class="testimonial-avatar"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/avatar-placeholder.jpg') }}';">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr($testimonial->name, 0, 2) }}
                            </div>
                        @endif
                        <div class="testimonial-info">
                            <div class="testimonial-name">{{ $testimonial->name }}</div>
                            @if($testimonial->position)
                            <div class="testimonial-position">{{ $testimonial->position }}</div>
                            @endif
                            @if($testimonial->company)
                            <div class="testimonial-company">{{ $testimonial->company }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="content-cell">
                    <div class="content-preview">{{ Str::limit($testimonial->content, 100) }}</div>
                </td>
                <td>
                    @if($testimonial->rating)
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $testimonial->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="rating-value">{{ $testimonial->rating }}/5</span>
                        </div>
                    @else
                        <span style="color: var(--text-tertiary); font-size: 0.75rem;">Non noté</span>
                    @endif
                </td>
                <td>
                    @if($testimonial->is_active)
                        <span class="badge badge-active">
                            <i class="fas fa-check-circle"></i> Actif
                        </span>
                    @else
                        <span class="badge badge-inactive">
                            <i class="fas fa-times-circle"></i> Inactif
                        </span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="action-btn toggle-status-btn"
                                data-id="{{ $testimonial->id }}"
                                data-name="{{ $testimonial->name }}"
                                data-status="{{ $testimonial->is_active ? '1' : '0' }}"
                                data-active="{{ $testimonial->is_active ? 'true' : 'false' }}"
                                title="{{ $testimonial->is_active ? 'Désactiver' : 'Activer' }}">
                            <i class="fas fa-{{ $testimonial->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $testimonial->id }}"
                                data-name="{{ $testimonial->name }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="5" class="empty-state">
                    <i class="fas fa-comment-slash"></i>
                    <p>Aucun témoignage trouvé</p>
                    <p style="font-size: 0.875rem;">Commencez par ajouter votre premier témoignage client</p>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Ajouter un témoignage
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($testimonials->hasPages())
<div class="pagination-wrapper">
    {{ $testimonials->links() }}
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
    const statusSelect = document.getElementById('status');
    const ratingSelect = document.getElementById('rating');
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
        toastIcon.className = `fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}`;
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

    // Toggle status (activer/désactiver)
    function initToggleButtons() {
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.removeEventListener('click', handleToggleClick);
            btn.addEventListener('click', handleToggleClick);
        });
    }

    function handleToggleClick() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const isActive = this.dataset.status === '1';

        openModal(
            `${isActive ? 'Désactiver' : 'Activer'} le témoignage`,
            `Êtes-vous sûr de vouloir ${isActive ? 'désactiver' : 'activer'} le témoignage de "${name}" ?`,
            isActive ? 'Ce témoignage ne sera plus affiché sur le site.' : 'Ce témoignage sera visible sur le site une fois activé.',
            isActive ? 'Désactiver' : 'Activer',
            isActive ? 'btn-warning' : 'btn-success',
            () => toggleStatus(id),
            id
        );
    }

    function toggleStatus(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = `{{ url('admin/testimonials') }}/${id}/toggle`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
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

    // Suppression d'un témoignage - VERSION CORRIGÉE
    function initDeleteButtons() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.removeEventListener('click', handleDeleteClick);
            btn.addEventListener('click', handleDeleteClick);
        });
    }

    function handleDeleteClick() {
        const id = this.dataset.id;
        const name = this.dataset.name;

        openModal(
            'Supprimer le témoignage',
            `Êtes-vous sûr de vouloir supprimer le témoignage de "${name}" ?`,
            'Action irréversible. L\'avatar sera également supprimé.',
            'Supprimer',
            'btn-danger',
            () => deleteTestimonial(id),
            id
        );
    }

    function deleteTestimonial(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = `{{ url('admin/testimonials') }}/${id}`;

        // Utilisation de POST avec _method=DELETE
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
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
        const statusValue = statusSelect?.value || '';
        const ratingValue = ratingSelect?.value || '';

        let visibleCount = 0;
        let activeCount = 0;
        let inactiveCount = 0;
        let totalRating = 0;
        let ratingCount = 0;

        document.querySelectorAll('.testimonials-table tbody tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const name = row.dataset.name || '';
            const position = row.dataset.position || '';
            const company = row.dataset.company || '';
            const content = row.dataset.content || '';
            const status = row.dataset.status || '';
            const rating = parseInt(row.dataset.rating) || 0;

            if (searchTerm && !name.includes(searchTerm) && !position.includes(searchTerm) && !company.includes(searchTerm) && !content.includes(searchTerm)) {
                show = false;
            }
            if (show && statusValue && status !== statusValue) show = false;
            if (show && ratingValue) {
                const minRating = parseInt(ratingValue);
                if (rating < minRating) show = false;
            }

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
                if (status === '1') {
                    activeCount++;
                } else {
                    inactiveCount++;
                }
                if (rating > 0) {
                    totalRating += rating;
                    ratingCount++;
                }
            }
        });

        const avgRating = ratingCount > 0 ? (totalRating / ratingCount).toFixed(1) : '0.0';
        updateStats(visibleCount, activeCount, inactiveCount, avgRating);
    }

    function updateStats(total, active, inactive, avgRating) {
        const statTotal = document.getElementById('statTotal');
        const statActive = document.getElementById('statActive');
        const statInactive = document.getElementById('statInactive');
        const statAvgRating = document.getElementById('statAvgRating');

        if (statTotal) statTotal.textContent = total;
        if (statActive) statActive.textContent = active;
        if (statInactive) statInactive.textContent = inactive;
        if (statAvgRating) statAvgRating.textContent = avgRating;
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        if (ratingSelect) ratingSelect.value = '';
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
    if (statusSelect) statusSelect.addEventListener('change', filterTable);
    if (ratingSelect) ratingSelect.addEventListener('change', filterTable);

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
