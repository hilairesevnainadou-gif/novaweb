@extends('admin.layouts.app')

@section('title', 'Sauvegardes - NovaTech Admin')
@section('page-title', 'Gestion des sauvegardes')

@push('styles')
<style>
    /* Stats Grid */
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

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        letter-spacing: 0.5px;
    }

    /* Cards */
    .card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .card-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: var(--brand-primary);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Form */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        background: var(--bg-tertiary);
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--bg-hover);
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

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--brand-primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
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
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        background: #ef4444;
        color: white;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .action-btn:hover {
        color: var(--brand-primary);
        background: var(--bg-hover);
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
        max-width: 500px;
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
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

    .modal-close:hover {
        color: var(--text-primary);
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

    /* Progress */
    .progress-bar {
        height: 0.5rem;
        background: var(--bg-tertiary);
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--brand-primary);
        border-radius: 9999px;
        transition: width 0.3s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card, .card {
        animation: fadeIn 0.3s ease forwards;
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $canCreateBackup = $user->can('backups.create');
    $canDeleteBackup = $user->can('backups.delete');
    $canRestoreBackup = $user->can('backups.restore');
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats->total_backups ?? 0 }}</div>
        <div class="stat-label">Total sauvegardes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats->successful_backups ?? 0 }}</div>
        <div class="stat-label">Sauvegardes réussies</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats->failed_backups ?? 0 }}</div>
        <div class="stat-label">Sauvegardes échouées</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats->total_size ?? '0 B' }}</div>
        <div class="stat-label">Espace utilisé</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-database"></i> Créer une sauvegarde
        </div>
    </div>
    <div class="card-body">
        <div class="row" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            @if($canCreateBackup)
            <form method="POST" action="{{ route('admin.backup.create') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="type" value="full">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Sauvegarde complète
                </button>
            </form>
            <form method="POST" action="{{ route('admin.backup.create') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="type" value="database">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-database"></i> Base de données uniquement
                </button>
            </form>
            <form method="POST" action="{{ route('admin.backup.create') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="type" value="files">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-file"></i> Fichiers uniquement
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Liste des sauvegardes
        </div>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom du fichier</th>
                    <th>Taille</th>
                    <th>Date de création</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td>
                        <i class="fas fa-file-archive"></i> {{ $backup->name }}
                    </td>
                    <td>{{ $backup->size }}</td>
                    <td>{{ $backup->date }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                            <a href="{{ route('admin.backup.download', $backup->name) }}" class="action-btn" title="Télécharger">
                                <i class="fas fa-download"></i>
                            </a>
                            @if($canRestoreBackup)
                            <button type="button" class="action-btn restore-btn" data-filename="{{ $backup->name }}" title="Restaurer">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            @endif
                            @if($canDeleteBackup)
                            <button type="button" class="action-btn delete-btn" data-filename="{{ $backup->name }}" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state" style="text-align: center; padding: 2rem;">
                        <i class="fas fa-database" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                        <p>Aucune sauvegarde disponible</p>
                        @if($canCreateBackup)
                        <button type="button" id="createFirstBackupBtn" class="btn btn-primary" style="margin-top: 0.5rem;">
                            <i class="fas fa-plus"></i> Créer votre première sauvegarde
                        </button>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-sliders-h"></i> Paramètres de sauvegarde
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.backup.settings') }}" id="settingsForm">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Email de notification</label>
                    <input type="email" name="backup_email" class="form-control" value="{{ $settings->backup_email ?? '' }}" placeholder="email@exemple.com">
                    <small class="form-help">Les backups seront envoyés à cette adresse</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Type de sauvegarde</label>
                    <select name="backup_type" class="form-select">
                        <option value="full" {{ ($settings->backup_type ?? 'full') == 'full' ? 'selected' : '' }}>Complète</option>
                        <option value="database" {{ ($settings->backup_type ?? '') == 'database' ? 'selected' : '' }}>Base de données uniquement</option>
                        <option value="files" {{ ($settings->backup_type ?? '') == 'files' ? 'selected' : '' }}>Fichiers uniquement</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fréquence</label>
                    <select name="backup_frequency" class="form-select">
                        <option value="daily" {{ ($settings->backup_frequency ?? 'daily') == 'daily' ? 'selected' : '' }}>Quotidienne</option>
                        <option value="weekly" {{ ($settings->backup_frequency ?? '') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                        <option value="monthly" {{ ($settings->backup_frequency ?? '') == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Heure de sauvegarde</label>
                    <input type="time" name="backup_time" class="form-control" value="{{ $settings->backup_time ?? '02:00' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Conservation (jours)</label>
                    <input type="number" name="auto_clean_days" class="form-control" value="{{ $settings->auto_clean_days ?? 30 }}" min="1" max="90">
                    <small class="form-help">Supprimer automatiquement les backups après X jours</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Dernière sauvegarde</label>
                    <input type="text" class="form-control" value="{{ $settings->last_backup_at ? \Carbon\Carbon::parse($settings->last_backup_at)->format('d/m/Y H:i:s') : 'Jamais' }}" readonly disabled>
                </div>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les paramètres
                </button>
                <button type="button" id="testEmailBtn" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i> Tester l'email
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-history"></i> Historique des sauvegardes
        </div>
        @if($logs->count() > 0)
        <button type="button" id="clearLogsBtn" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i> Effacer les logs
        </button>
        @endif
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fichier</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Taille</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->filename }}</td>
                    <td>{{ $log->type === 'manual' ? 'Manuelle' : 'Automatique' }}</td>
                    <td>
                        <span class="badge {{ $log->status === 'success' ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas {{ $log->status === 'success' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $log->status === 'success' ? 'Succès' : 'Échec' }}
                        </span>
                        @if($log->error_message)
                        <div class="form-help" style="color: var(--brand-error);">{{ $log->error_message }}</div>
                        @endif
                    </td>
                    <td>{{ $log->size ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state" style="text-align: center; padding: 2rem;">
                        <i class="fas fa-history" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                        <p>Aucun historique disponible</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de confirmation suppression -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-trash-alt"></i> Confirmer la suppression</h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cette sauvegarde ?</p>
            <p><strong id="deleteFilename"></strong></p>
            <p class="form-help" style="color: var(--brand-error);">Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation restauration -->
<div id="restoreModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-undo-alt"></i> Confirmer la restauration</h3>
            <button type="button" class="modal-close" onclick="closeRestoreModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir restaurer cette sauvegarde ?</p>
            <p><strong id="restoreFilename"></strong></p>
            <p class="form-help" style="color: var(--brand-warning);">
                <i class="fas fa-exclamation-triangle"></i> Attention : La restauration remplacera les données actuelles.
                Cette action ne peut pas être annulée.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeRestoreModal()">Annuler</button>
            <form id="restoreForm" method="POST" action="">
                @csrf
                <button type="submit" class="btn btn-warning">Restaurer</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de création de première sauvegarde -->
<div id="firstBackupModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-database"></i> Créer une sauvegarde</h3>
            <button type="button" class="modal-close" onclick="closeFirstBackupModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Choisissez le type de sauvegarde à créer :</p>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;">
                <form method="POST" action="{{ route('admin.backup.create') }}" id="fullBackupForm">
                    @csrf
                    <input type="hidden" name="type" value="full">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-database"></i> Sauvegarde complète
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.backup.create') }}" id="dbBackupForm">
                    @csrf
                    <input type="hidden" name="type" value="database">
                    <button type="submit" class="btn btn-secondary" style="width: 100%;">
                        <i class="fas fa-database"></i> Base de données uniquement
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.backup.create') }}" id="filesBackupForm">
                    @csrf
                    <input type="hidden" name="type" value="files">
                    <button type="submit" class="btn btn-secondary" style="width: 100%;">
                        <i class="fas fa-file"></i> Fichiers uniquement
                    </button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeFirstBackupModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- Modal de test email -->
<div id="testEmailModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-envelope"></i> Test d'envoi d'email</h3>
            <button type="button" class="modal-close" onclick="closeTestEmailModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="testEmailResult"></div>
            <div class="progress-bar" style="margin-top: 1rem; display: none;" id="testEmailProgress">
                <div class="progress-fill" style="width: 100%; animation: progress 2s linear infinite;"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeTestEmailModal()">Fermer</button>
        </div>
    </div>
</div>

<style>
    @keyframes progress {
        0% { width: 0%; }
        100% { width: 100%; }
    }
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    .btn-warning:hover {
        background: #d97706;
    }
</style>

@endsection

@push('scripts')
<script>
    // Gestion de la suppression
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    let currentDeleteFilename = null;

    function openDeleteModal(filename) {
        currentDeleteFilename = filename;
        document.getElementById('deleteFilename').textContent = filename;
        deleteForm.action = "{{ url('admin/backup') }}/" + filename;
        deleteModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('active');
        document.body.style.overflow = '';
        currentDeleteFilename = null;
    }

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filename = this.dataset.filename;
            openDeleteModal(filename);
        });
    });

    // Gestion de la restauration
    const restoreModal = document.getElementById('restoreModal');
    const restoreForm = document.getElementById('restoreForm');
    let currentRestoreFilename = null;

    function openRestoreModal(filename) {
        currentRestoreFilename = filename;
        document.getElementById('restoreFilename').textContent = filename;
        restoreForm.action = "{{ url('admin/backup') }}/" + filename + "/restore";
        restoreModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeRestoreModal() {
        restoreModal.classList.remove('active');
        document.body.style.overflow = '';
        currentRestoreFilename = null;
    }

    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filename = this.dataset.filename;
            openRestoreModal(filename);
        });
    });

    // Première sauvegarde
    const firstBackupModal = document.getElementById('firstBackupModal');
    const createFirstBackupBtn = document.getElementById('createFirstBackupBtn');

    if (createFirstBackupBtn) {
        createFirstBackupBtn.addEventListener('click', function() {
            firstBackupModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    function closeFirstBackupModal() {
        firstBackupModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Test email
    const testEmailModal = document.getElementById('testEmailModal');
    const testEmailResult = document.getElementById('testEmailResult');
    const testEmailProgress = document.getElementById('testEmailProgress');
    const testEmailBtn = document.getElementById('testEmailBtn');

    if (testEmailBtn) {
        testEmailBtn.addEventListener('click', async function() {
            testEmailModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            testEmailProgress.style.display = 'block';
            testEmailResult.innerHTML = '<p>Envoi en cours...</p>';

            try {
                const response = await fetch('{{ route("admin.backup.test-email") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    testEmailResult.innerHTML = '<div class="badge badge-success" style="padding: 0.75rem;">Email de test envoyé avec succès !</div>';
                } else {
                    testEmailResult.innerHTML = '<div class="badge badge-danger" style="padding: 0.75rem;">❌ Erreur : ' + data.message + '</div>';
                }
            } catch (error) {
                testEmailResult.innerHTML = '<div class="badge badge-danger" style="padding: 0.75rem;">❌ Erreur lors de l\'envoi de l\'email</div>';
            } finally {
                testEmailProgress.style.display = 'none';
            }
        });
    }

    function closeTestEmailModal() {
        testEmailModal.classList.remove('active');
        document.body.style.overflow = '';
        testEmailResult.innerHTML = '';
    }

    // Effacer les logs
    const clearLogsBtn = document.getElementById('clearLogsBtn');
    if (clearLogsBtn) {
        clearLogsBtn.addEventListener('click', async function() {
            if (confirm('Êtes-vous sûr de vouloir effacer tous les logs de sauvegarde ?')) {
                try {
                    const response = await fetch('{{ route("admin.backup.logs.clear") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });
                    if (response.ok) {
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                }
            }
        });
    }

    // Fermer les modals en cliquant à l'extérieur
    window.onclick = function(event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
        if (event.target === restoreModal) {
            closeRestoreModal();
        }
        if (event.target === firstBackupModal) {
            closeFirstBackupModal();
        }
        if (event.target === testEmailModal) {
            closeTestEmailModal();
        }
    }

    // Soumission des formulaires
    const fullBackupForm = document.getElementById('fullBackupForm');
    const dbBackupForm = document.getElementById('dbBackupForm');
    const filesBackupForm = document.getElementById('filesBackupForm');

    function disableSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
        }
    }

    if (fullBackupForm) {
        fullBackupForm.addEventListener('submit', () => disableSubmit(fullBackupForm));
    }
    if (dbBackupForm) {
        dbBackupForm.addEventListener('submit', () => disableSubmit(dbBackupForm));
    }
    if (filesBackupForm) {
        filesBackupForm.addEventListener('submit', () => disableSubmit(filesBackupForm));
    }

    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
        });
    }
</script>
@endpush
