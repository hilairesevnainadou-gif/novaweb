{{-- resources/views/admin/services/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Services - NovaTech Admin')
@section('page-title', 'Gestion des Services')

@push('styles')
<style>
    /* ========== STYLES EXISTANTS (conservés) ========== */
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
        outline: none;
    }
    .filter-input:focus, .filter-select:focus {
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
        transition: all 0.2s;
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
    .table-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }
    .services-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .services-table thead {
        background: var(--bg-tertiary);
    }
    .services-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .services-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .services-table tbody tr {
        cursor: move;
        transition: background 0.2s;
    }
    .services-table tbody tr:hover {
        background: var(--bg-hover);
    }
    .services-table tbody tr.dragging {
        opacity: 0.5;
        background: var(--bg-tertiary);
    }
    /* Drag handle */
    .drag-handle {
        cursor: grab;
        color: var(--text-tertiary);
        font-size: 1.1rem;
        text-align: center;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .service-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .service-icon i {
        font-size: 1.25rem;
    }
    .service-info {
        flex: 1;
        min-width: 0;
    }
    .service-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    .service-description {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }
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
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
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
        .service-description {
            max-width: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title-section">
        <h1>Services</h1>
        <p>Gérez les services proposés par votre agence – glissez-déposez pour réorganiser l'ordre</p>
    </div>
    <div>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nouveau service
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon indigo"><i class="fas fa-cogs"></i></div></div>
        <div class="stat-value" id="statTotal">{{ $services->total() }}</div>
        <div class="stat-label">Total services</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value" id="statActive">{{ $services->where('is_active', true)->count() }}</div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon yellow"><i class="fas fa-pause-circle"></i></div></div>
        <div class="stat-value" id="statInactive">{{ $services->where('is_active', false)->count() }}</div>
        <div class="stat-label">Inactifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div></div>
        <div class="stat-value" id="statThisMonth">{{ $services->filter(fn($item) => $item->created_at->month == now()->month)->count() }}</div>
        <div class="stat-label">Ce mois</div>
    </div>
</div>

<!-- Filtres -->
<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div><input type="text" id="search" placeholder="Rechercher un service..." class="filter-input" autocomplete="off"></div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
            </select>
        </div>
        <div></div>
        <div><button onclick="resetFilters()" class="btn-reset"><i class="fas fa-undo-alt"></i> Réinitialiser</button></div>
    </div>
</div>

<!-- Tableau avec drag & drop -->
<div class="table-container">
    <table class="services-table" id="services-table">
        <thead>
            <tr>
                <th style="width: 40px;"><i class="fas fa-grip-vertical"></i></th>
                <th>Service</th>
                <th>Icône</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody id="sortable-list">
            @forelse($services as $index => $service)
            <tr class="table-row" data-id="{{ $service->id }}"
                data-title="{{ strtolower($service->title) }}"
                data-description="{{ strtolower($service->description) }}"
                data-status="{{ $service->is_active ? '1' : '0' }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td class="drag-handle" style="cursor: grab; text-align: center;">
                    <i class="fas fa-grip-vertical"></i>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="service-icon" style="background: rgba(99, 102, 241, 0.1);">
                            <i class="fas fa-{{ $service->icon }}" style="color: var(--brand-primary);"></i>
                        </div>
                        <div class="service-info">
                            <div class="service-title">{{ $service->title }}</div>
                            <div class="service-description">{{ Str::limit($service->description, 60) }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="service-icon" style="background: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-{{ $service->icon }}"></i>
                    </div>
                </td>
                <td>
                    @if($service->is_active)
                        <span class="badge badge-active"><i class="fas fa-check-circle"></i> Actif</span>
                    @else
                        <span class="badge badge-inactive"><i class="fas fa-pause-circle"></i> Inactif</span>
                    @endif
                </td>
                <td>{{ $service->created_at->format('d/m/Y') }}</td>
                <td class="actions-cell">
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <a href="{{ route('admin.services.edit', $service) }}" class="action-btn" title="Modifier"><i class="fas fa-edit"></i></a>
                        <button type="button" class="action-btn toggle-status-btn"
                                data-id="{{ $service->id }}"
                                data-title="{{ $service->title }}"
                                data-status="{{ $service->is_active ? '1' : '0' }}">
                            <i class="fas fa-{{ $service->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                        <button type="button" class="action-btn delete delete-btn"
                                data-id="{{ $service->id }}"
                                data-title="{{ $service->title }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="6" class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Aucun service trouvé</p>
                    <a href="{{ route('admin.services.create') }}" class="btn-primary" style="margin-top: 1rem;">Créer un service</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($services->hasPages())
<div class="pagination-wrapper">{{ $services->links() }}</div>
@endif

<!-- Modals (confirmation) -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header"><h3 id="modalTitle">Confirmation</h3><button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button></div>
        <div class="modal-body"><p id="modalMessage"></p><p id="modalWarning" class="warning-text"></p></div>
        <div class="modal-footer"><button class="btn btn-secondary" onclick="closeModal()">Annuler</button><button id="modalConfirmBtn" class="btn">Confirmer</button></div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="toast-notification"><i id="toastIcon" class="fas"></i><span id="toastMessage"></span></div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
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

    // Toast
    function showToast(message, type = 'success') {
        toastIcon.className = `fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}`;
        toastMessage.textContent = message;
        toast.className = `toast-notification ${type} show`;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // Modal
    function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        modalConfirmBtn.textContent = confirmText;
        modalConfirmBtn.className = `btn ${confirmClass}`;
        currentAction = onConfirm;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentAction = null;
    }
    function confirmAction() { if (currentAction) currentAction(); closeModal(); }
    modal.onclick = (e) => { if (e.target === modal) closeModal(); };
    modalConfirmBtn.onclick = confirmAction;
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('active')) closeModal(); });

    // Drag & Drop (SortableJS)
    let sortable = null;
    function initDragAndDrop() {
        const tbody = document.getElementById('sortable-list');
        if (!tbody) return;
        sortable = new Sortable(tbody, {
            handle: '.drag-handle',
            animation: 300,
            ghostClass: 'dragging',
            onEnd: function() {
                // Récupérer l'ordre des IDs
                const rows = document.querySelectorAll('#sortable-list tr');
                const order = Array.from(rows).map(row => row.dataset.id);
                // Envoyer au serveur
                fetch('{{ route("admin.services.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        // Optionnel : recharger la page ou mettre à jour les ordres
                        setTimeout(() => location.reload(), 500);
                    } else {
                        showToast(data.message || 'Erreur lors de la réorganisation', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Une erreur est survenue', 'error');
                });
            }
        });
    }

// ==================== ACTIVATION / DÉSACTIVATION ====================
function initToggleButtons() {
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.removeEventListener('click', handleToggleClick);
        btn.addEventListener('click', handleToggleClick);
    });
}

function handleToggleClick() {
    const id = this.dataset.id;
    const title = this.dataset.title;
    const isActive = this.dataset.status === '1';
    openModal(
        `${isActive ? 'Désactiver' : 'Activer'} le service`,
        `Êtes-vous sûr de vouloir ${isActive ? 'désactiver' : 'activer'} le service "${title}" ?`,
        isActive ? 'Le service ne sera plus visible sur le site.' : 'Le service sera visible sur le site une fois activé.',
        isActive ? 'Désactiver' : 'Activer',
        isActive ? 'btn-warning' : 'btn-success',
        () => toggleStatus(id)
    );
}

function toggleStatus(id) {
    const url = `/admin/services/${id}/toggle`;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue lors de l\'opération', 'error');
    });
}

// ==================== SUPPRESSION ====================
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
        'Supprimer le service',
        `Êtes-vous sûr de vouloir supprimer le service "${title}" ?`,
        'Action irréversible. Toutes les données associées seront supprimées.',
        'Supprimer',
        'btn-danger',
        () => deleteService(id)
    );
}

function deleteService(id) {
    const url = `/admin/services/${id}`;
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue lors de la suppression', 'error');
    });
}
    // Filtres
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusValue = statusSelect?.value || '';
        let visibleCount = 0, activeCount = 0, inactiveCount = 0;
        document.querySelectorAll('#sortable-list tr:not(#emptyStateRow)').forEach(row => {
            let show = true;
            const title = row.dataset.title || '';
            const desc = row.dataset.description || '';
            const status = row.dataset.status || '';
            if (searchTerm && !title.includes(searchTerm) && !desc.includes(searchTerm)) show = false;
            if (show && statusValue && status !== statusValue) show = false;
            row.style.display = show ? '' : 'none';
            if (show) {
                visibleCount++;
                status === '1' ? activeCount++ : inactiveCount++;
            }
        });
        document.getElementById('statTotal').textContent = visibleCount;
        document.getElementById('statActive').textContent = activeCount;
        document.getElementById('statInactive').textContent = inactiveCount;
    }
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = '';
        filterTable();
    }
    let timer;
    if (searchInput) searchInput.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(filterTable, 300); });
    if (statusSelect) statusSelect.addEventListener('change', filterTable);

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        initDragAndDrop();
        initToggleButtons();
        initDeleteButtons();
    });

    window.resetFilters = resetFilters;
    window.closeModal = closeModal;
</script>
@endpush
