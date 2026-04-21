{{-- resources/views/admin/team/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Équipe - NovaTech Admin')
@section('page-title', 'Gestion de l\'équipe')

@push('styles')
<style>
    /* ========== STATS GRID ========== */
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

    /* ========== FILTERS CONTAINER ========== */
    .filters-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .filter-input:focus, .filter-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-input::placeholder {
        color: var(--text-tertiary);
    }

    /* Grid system */
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

    /* ========== BUTTONS ========== */
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
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
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
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* ========== TABLE ========== */
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .team-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .team-table thead {
        background: var(--bg-tertiary);
    }

    .team-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .team-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .team-table tbody tr:hover {
        background: var(--bg-hover);
    }

    /* Member photo */
    .member-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--bg-tertiary);
    }

    .member-photo-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .member-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .member-position {
        font-size: 0.75rem;
        color: var(--text-secondary);
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

    .badge-featured {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* Skills */
    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .skill-tag {
        background: var(--bg-tertiary);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.65rem;
        color: var(--text-secondary);
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
        transition: all 0.2s;
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
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
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

    /* Empty state */
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

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    /* Page header */
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

    /* Animations */
    .table-row {
        animation: fadeInUp 0.3s ease forwards;
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

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    /* ========== MODAL STYLES ========== */
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
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
        transition: color 0.2s;
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
        transition: all 0.2s;
        border: none;
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
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
</style>
@endpush

@section('content')
@can('team.view')
<div class="page-header">
    <div class="page-title-section">
        <h1>Équipe</h1>
        <p>Gérez les membres de votre équipe</p>
    </div>
    @can('team.create')
    <div>
        <a href="{{ route('admin.team.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouveau membre
        </a>
    </div>
    @endcan
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon indigo">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalMembers }}</div>
        <div class="stat-label">Total membres</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $activeMembers }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-value">{{ $featuredMembers }}</div>
        <div class="stat-label">À la une</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-tachometer-alt"></i>
            </div>
        </div>
        <div class="stat-value">{{ $teamMembers->total() }}</div>
        <div class="stat-label">Affichés</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input type="text" id="search" placeholder="Rechercher un membre..." class="filter-input" autocomplete="off">
        </div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
            </select>
        </div>
        <div>
            <select id="featured" class="filter-select">
                <option value="">Tous</option>
                <option value="1">À la une</option>
                <option value="0">Non à la une</option>
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
    <table class="team-table">
        <thead>
            <tr>
                @can('team.edit')
                <th style="width: 50px;">Ordre</th>
                @endcan
                <th>Membre</th>
                <th>Poste</th>
                <th>Compétences</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="teamTableBody">
            @forelse($teamMembers as $index => $member)
            <tr class="table-row" data-id="{{ $member->id }}" data-name="{{ strtolower($member->name) }}" data-position="{{ strtolower($member->position) }}" data-status="{{ $member->is_active ? '1' : '0' }}" data-featured="{{ $member->is_featured ? '1' : '0' }}" style="animation-delay: {{ $index * 0.03 }}s;">
                @can('team.edit')
                <td style="cursor: move;">
                    <div class="drag-handle" data-id="{{ $member->id }}">
                        <i class="fas fa-grip-vertical"></i>
                        <span class="order-badge">{{ $member->order ?? $index + 1 }}</span>
                    </div>
                </td>
                @endcan
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="member-photo">
                        @else
                            <div class="member-photo-placeholder">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="member-name">{{ $member->name }}</div>
                            <div class="member-position">{{ $member->position }}</div>
                            @if($member->email)
                            <div class="member-position" style="font-size: 0.7rem;">{{ $member->email }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>{{ $member->position }}</td>
                <td>
                    @if($member->skills && is_array($member->skills) && count($member->skills) > 0)
                    <div class="skills-list">
                        @foreach(array_slice($member->skills, 0, 2) as $skill)
                        <span class="skill-tag">{{ $skill }}</span>
                        @endforeach
                        @if(count($member->skills) > 2)
                        <span class="skill-tag">+{{ count($member->skills) - 2 }}</span>
                        @endif
                    </div>
                    @else
                    <span class="skill-tag">-</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        @if($member->is_active)
                            <span class="badge badge-active">
                                <i class="fas fa-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="badge badge-inactive">
                                <i class="fas fa-ban"></i> Inactif
                            </span>
                        @endif
                        @if($member->is_featured)
                            <span class="badge badge-featured">
                                <i class="fas fa-star"></i> À la une
                            </span>
                        @endif
                    </div>
                </td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.team.show', $member) }}" class="action-btn" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('team.edit')
                        <a href="{{ route('admin.team.edit', $member) }}" class="action-btn" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('team.edit')
                        <button type="button" class="action-btn toggle-status-btn"
                                data-id="{{ $member->id }}"
                                data-name="{{ $member->name }}"
                                data-status="{{ $member->is_active ? '1' : '0' }}"
                                title="{{ $member->is_active ? 'Désactiver' : 'Activer' }}">
                            <i class="fas fa-{{ $member->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        @endcan
                        @can('team.edit')
                        <button type="button" class="action-btn toggle-featured-btn"
                                data-id="{{ $member->id }}"
                                data-name="{{ $member->name }}"
                                data-featured="{{ $member->is_featured ? '1' : '0' }}"
                                title="{{ $member->is_featured ? 'Retirer de la une' : 'Mettre à la une' }}">
                            <i class="fas fa-star"></i>
                        </button>
                        @endcan
                        @can('team.delete')
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $member->id }}"
                                data-name="{{ $member->name }}"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="{{ Auth::user()->can('team.edit') ? '6' : '5' }}" class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Aucun membre trouvé</p>
                    <p style="font-size: 0.875rem;">Commencez par ajouter votre premier membre</p>
                    @can('team.create')
                    <a href="{{ route('admin.team.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                        <i class="fas fa-plus"></i>
                        Ajouter un membre
                    </a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($teamMembers->hasPages())
<div class="pagination-wrapper">
    {{ $teamMembers->links() }}
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
            <button id="modalConfirmBtn" class="btn btn-danger">Confirmer</button>
        </div>
    </div>
</div>
@endcan

@cannot('team.view')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const featuredSelect = document.getElementById('featured');

    // Éléments du modal
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    let currentAction = null;

    // Fonctions du modal
    function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;

        // Réinitialiser les classes du bouton
        modalConfirmBtn.className = 'btn';
        modalConfirmBtn.classList.add(confirmClass);

        currentAction = onConfirm;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentAction = null;
    }

    function confirmAction() {
        if (currentAction && typeof currentAction === 'function') {
            currentAction();
        }
        closeModal();
    }

    // Écouteurs du modal
    modalConfirmBtn.onclick = confirmAction;

    // Fermer en cliquant sur l'overlay
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeModal();
        }
    };

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            font-size: 14px;
            z-index: 10001;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Styles pour l'animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

    // Toggle status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const isActive = this.dataset.status === '1';

            openModal(
                `${isActive ? 'Désactiver' : 'Activer'} le membre`,
                `Êtes-vous sûr de vouloir ${isActive ? 'désactiver' : 'activer'} le membre "${name}" ?`,
                isActive ? 'Le membre ne sera plus visible sur le site.' : 'Le membre sera visible sur le site une fois activé.',
                isActive ? 'Désactiver' : 'Activer',
                isActive ? 'btn-warning' : 'btn-success',
                () => {
                    modalConfirmBtn.disabled = true;
                    modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';

                    fetch(`{{ url('admin/team') }}/${id}/toggle`, {
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
                            showNotification(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Une erreur est survenue', 'error');
                            modalConfirmBtn.disabled = false;
                            modalConfirmBtn.innerHTML = isActive ? 'Désactiver' : 'Activer';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Une erreur technique est survenue', 'error');
                        modalConfirmBtn.disabled = false;
                        modalConfirmBtn.innerHTML = isActive ? 'Désactiver' : 'Activer';
                    });
                }
            );
        });
    });

    // Toggle featured
    document.querySelectorAll('.toggle-featured-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const isFeatured = this.dataset.featured === '1';

            openModal(
                `${isFeatured ? 'Retirer de la une' : 'Mettre à la une'}`,
                `Êtes-vous sûr de vouloir ${isFeatured ? 'retirer de la une' : 'mettre à la une'} le membre "${name}" ?`,
                isFeatured ? 'Le membre ne sera plus affiché en vedette.' : 'Le membre sera affiché en vedette sur le site.',
                isFeatured ? 'Retirer' : 'Mettre à la une',
                isFeatured ? 'btn-warning' : 'btn-primary',
                () => {
                    modalConfirmBtn.disabled = true;
                    modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';

                    fetch(`{{ url('admin/team') }}/${id}/featured`, {
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
                            showNotification(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Une erreur est survenue', 'error');
                            modalConfirmBtn.disabled = false;
                            modalConfirmBtn.innerHTML = isFeatured ? 'Retirer' : 'Mettre à la une';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Une erreur technique est survenue', 'error');
                        modalConfirmBtn.disabled = false;
                        modalConfirmBtn.innerHTML = isFeatured ? 'Retirer' : 'Mettre à la une';
                    });
                }
            );
        });
    });

    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            openModal(
                'Supprimer le membre',
                `Êtes-vous sûr de vouloir supprimer le membre "${name}" ?`,
                'Action irréversible.',
                'Supprimer',
                'btn-danger',
                () => {
                    modalConfirmBtn.disabled = true;
                    modalConfirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

                    fetch(`{{ url('admin/team') }}/${id}`, {
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
                            showNotification(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Une erreur est survenue', 'error');
                            modalConfirmBtn.disabled = false;
                            modalConfirmBtn.innerHTML = 'Supprimer';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Une erreur technique est survenue', 'error');
                        modalConfirmBtn.disabled = false;
                        modalConfirmBtn.innerHTML = 'Supprimer';
                    });
                }
            );
        });
    });

    // Drag and Drop
    @can('team.edit')
    const tbody = document.getElementById('teamTableBody');
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

            if (dragSrcElement && this.parentNode) {
                if (rows.indexOf(dragSrcElement) < rows.indexOf(this)) {
                    this.parentNode.insertBefore(dragSrcElement, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(dragSrcElement, this);
                }
            }

            const updatedRows = Array.from(tbody.querySelectorAll('tr:not(#emptyStateRow)'));
            updatedRows.forEach((row, index) => {
                order.push(row.dataset.id);
            });

            fetch('{{ route("admin.team.reorder") }}', {
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
                    updatedRows.forEach((row, index) => {
                        const orderBadge = row.querySelector('.order-badge');
                        if (orderBadge) {
                            orderBadge.textContent = index + 1;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
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
            row.setAttribute('draggable', 'true');
            row.addEventListener('dragstart', handleDragStart);
            row.addEventListener('dragover', handleDragOver);
            row.addEventListener('dragenter', handleDragEnter);
            row.addEventListener('dragleave', handleDragLeave);
            row.addEventListener('drop', handleDrop);
            row.addEventListener('dragend', handleDragEnd);
        });
    }

    if (tbody && tbody.querySelectorAll('tr:not(#emptyStateRow)').length > 1) {
        initDragAndDrop();
    }
    @endcan

    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';
        const featuredValue = featuredSelect?.value || '';

        const rows = document.querySelectorAll('.team-table tbody tr:not(#emptyStateRow)');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let show = true;
            const name = row.dataset.name || '';
            const position = row.dataset.position || '';
            const status = row.dataset.status || '';
            const featured = row.dataset.featured || '';

            if (searchTerm && !name.includes(searchTerm) && !position.includes(searchTerm)) {
                show = false;
            }
            if (show && statusValue && status !== statusValue) {
                show = false;
            }
            if (show && featuredValue && featured !== featuredValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
            if (show) hasVisibleRows = true;
        });

        // Afficher un message si aucun résultat
        const noResultsRow = document.getElementById('noResultsRow');
        if (!hasVisibleRows && rows.length > 0) {
            if (!noResultsRow) {
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'noResultsRow';
                emptyRow.innerHTML = `<td colspan="{{ Auth::user()->can('team.edit') ? '6' : '5' }}" class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat ne correspond à vos critères</p>
                    <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-undo-alt"></i> Réinitialiser les filtres
                    </button>
                </td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        if (featuredSelect) featuredSelect.value = '';
        filterTable();
    }

    let timer;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(filterTable, 300);
        });
    }
    if (statusSelect) statusSelect.addEventListener('change', filterTable);
    if (featuredSelect) featuredSelect.addEventListener('change', filterTable);

    // Exposer les fonctions globalement
    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
