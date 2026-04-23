@extends('admin.layouts.app')

@section('title', 'Sauvegardes - NovaTech Admin')
@section('page-title', 'Sauvegardes')

@push('styles')
<style>
    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }

    .stat-card:hover {
        border-color: var(--border-medium);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .stat-icon.blue   { background: rgba(59,130,246,.12); color: var(--brand-primary); }
    .stat-icon.green  { background: rgba(16,185,129,.12); color: var(--brand-success); }
    .stat-icon.red    { background: rgba(239,68,68,.12);  color: var(--brand-error); }
    .stat-icon.purple { background: rgba(139,92,246,.12); color: var(--brand-secondary); }

    .stat-body {}
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--text-tertiary);
        margin-top: 3px;
    }

    /* ── Card ── */
    .card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        background: var(--bg-tertiary);
    }

    .card-title {
        font-size: .9375rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .card-title i { color: var(--brand-primary); font-size: 14px; }

    .card-body { padding: 1.25rem 1.5rem; }

    /* ── Backup type buttons ── */
    .backup-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .backup-type-btn {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .625rem 1.125rem;
        border-radius: var(--radius-md);
        font-size: .8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-secondary);
        text-decoration: none;
    }

    .backup-type-btn:hover {
        border-color: var(--brand-primary);
        color: var(--brand-primary);
        background: rgba(59,130,246,.06);
        transform: translateY(-1px);
    }

    .backup-type-btn.primary {
        background: var(--brand-primary);
        color: white;
        border-color: var(--brand-primary);
    }

    .backup-type-btn.primary:hover {
        background: var(--brand-primary-hover);
        color: white;
        border-color: var(--brand-primary-hover);
    }

    .backup-type-btn i { font-size: 13px; }

    /* ── Table ── */
    .table-wrap { overflow-x: auto; }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: .75rem 1.25rem;
        text-align: left;
        font-size: .6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--text-tertiary);
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
        background: var(--bg-tertiary);
    }

    .data-table td {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
        font-size: .875rem;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td { border-bottom: none; }

    .data-table tbody tr {
        transition: background var(--transition-fast);
    }

    .data-table tbody tr:hover { background: var(--bg-hover); }

    .file-name {
        display: flex;
        align-items: center;
        gap: .625rem;
        font-weight: 500;
    }

    .file-name i {
        color: var(--brand-primary);
        font-size: 15px;
        flex-shrink: 0;
    }

    .file-size-badge {
        display: inline-block;
        padding: 2px 8px;
        background: var(--bg-tertiary);
        border-radius: var(--radius-full);
        font-size: .75rem;
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    /* ── Action buttons ── */
    .actions-cell {
        display: flex;
        gap: .25rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        background: none;
        color: var(--text-tertiary);
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        font-size: 13px;
    }

    .action-btn:hover         { background: var(--bg-hover); color: var(--brand-primary); }
    .action-btn.btn-restore:hover { background: rgba(245,158,11,.1); color: #f59e0b; }
    .action-btn.btn-del:hover     { background: rgba(239,68,68,.1);  color: var(--brand-error); }

    /* ── Badges ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .65rem;
        font-size: .7rem;
        font-weight: 600;
        border-radius: var(--radius-full);
        white-space: nowrap;
    }
    .badge-success { background: rgba(16,185,129,.12); color: #10b981; }
    .badge-danger  { background: rgba(239,68,68,.12);  color: #ef4444; }
    .badge-warning { background: rgba(245,158,11,.12); color: #f59e0b; }
    .badge-info    { background: rgba(59,130,246,.12); color: #3b82f6; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem 1rem;
        border-radius: var(--radius-md);
        font-size: .8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: none;
        text-decoration: none;
    }

    .btn-primary   { background: var(--brand-primary); color: white; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }
    .btn-secondary:hover { background: var(--bg-hover); color: var(--text-primary); }

    .btn-danger {
        background: rgba(239,68,68,.1);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,.2);
    }
    .btn-danger:hover { background: #ef4444; color: white; }

    .btn-sm { padding: .35rem .75rem; font-size: .75rem; }

    /* ── Form ── */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-group { display: flex; flex-direction: column; gap: .35rem; }

    .form-label {
        font-size: .6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--text-secondary);
    }

    .form-control, .form-select {
        padding: .5rem .75rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: .875rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        outline: none;
        width: 100%;
        font-family: inherit;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }

    .form-control:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .form-hint {
        font-size: .75rem;
        color: var(--text-tertiary);
    }

    .form-actions {
        display: flex;
        gap: .75rem;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-light);
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-lg);
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--text-tertiary);
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: .9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: .375rem;
    }

    .empty-text {
        font-size: .8125rem;
        color: var(--text-tertiary);
        margin-bottom: 1rem;
    }

    /* ── Log type pill ── */
    .log-type {
        font-size: .75rem;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .log-type i { font-size: 11px; }

    /* ── Modal ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.6);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: opacity var(--transition-base), visibility var(--transition-base);
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-elevated);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 460px;
        transform: scale(.96) translateY(8px);
        transition: transform var(--transition-base);
        box-shadow: var(--shadow-2xl);
    }

    .modal-overlay.active .modal { transform: scale(1) translateY(0); }

    .modal-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .modal-title i { font-size: 15px; color: var(--brand-primary); }

    .modal-close {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        background: none;
        color: var(--text-tertiary);
        cursor: pointer;
        font-size: 14px;
        transition: all var(--transition-fast);
    }
    .modal-close:hover { background: var(--bg-hover); color: var(--text-primary); }

    .modal-body { padding: 1.25rem; }

    .modal-footer {
        padding: .875rem 1.25rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: .625rem;
    }

    /* Warning box inside modal */
    .warn-box {
        display: flex;
        gap: .75rem;
        padding: .875rem 1rem;
        background: rgba(245,158,11,.08);
        border: 1px solid rgba(245,158,11,.2);
        border-radius: var(--radius-md);
        margin-top: .875rem;
    }

    .warn-box i { color: #f59e0b; font-size: 15px; margin-top: 2px; flex-shrink: 0; }
    .warn-box p { font-size: .8125rem; color: var(--text-secondary); margin: 0; line-height: 1.5; }

    .danger-box {
        display: flex;
        gap: .75rem;
        padding: .875rem 1rem;
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: var(--radius-md);
        margin-top: .875rem;
    }

    .danger-box i { color: #ef4444; font-size: 15px; margin-top: 2px; flex-shrink: 0; }
    .danger-box p { font-size: .8125rem; color: var(--text-secondary); margin: 0; line-height: 1.5; }

    .file-highlight {
        font-family: 'Courier New', monospace;
        font-size: .8rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: .375rem .75rem;
        color: var(--brand-primary);
        display: block;
        margin-top: .5rem;
        word-break: break-all;
    }

    /* Progress bar */
    .progress-bar {
        height: 3px;
        background: var(--bg-tertiary);
        border-radius: var(--radius-full);
        overflow: hidden;
        margin-top: 1rem;
    }

    .progress-fill {
        height: 100%;
        background: var(--brand-primary);
        border-radius: var(--radius-full);
        animation: progressAnim 1.5s ease-in-out infinite;
    }

    @keyframes progressAnim {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(300%); }
    }

    /* Backup type selection in modal */
    .backup-type-list { display: flex; flex-direction: column; gap: .5rem; margin-top: .75rem; }

    .backup-type-item {
        display: flex;
        align-items: center;
        gap: .875rem;
        padding: .875rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        color: var(--text-primary);
    }

    .backup-type-item:hover {
        border-color: var(--brand-primary);
        background: rgba(59,130,246,.05);
    }

    .backup-type-item .type-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .backup-type-item .type-icon.full   { background: rgba(59,130,246,.12); color: #3b82f6; }
    .backup-type-item .type-icon.db     { background: rgba(139,92,246,.12); color: #8b5cf6; }
    .backup-type-item .type-icon.files  { background: rgba(16,185,129,.12); color: #10b981; }

    .backup-type-item .type-info { flex: 1; min-width: 0; }
    .backup-type-item .type-name { font-size: .875rem; font-weight: 600; }
    .backup-type-item .type-desc { font-size: .75rem; color: var(--text-tertiary); margin-top: 1px; }
    .backup-type-item i.arrow { font-size: 12px; color: var(--text-tertiary); }

    .btn-warning { background: rgba(245,158,11,.12); color: #f59e0b; border: 1px solid rgba(245,158,11,.25); }
    .btn-warning:hover { background: #f59e0b; color: white; border-color: #f59e0b; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .form-grid  { grid-template-columns: 1fr; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $canCreate  = $user->can('backups.create');
    $canDelete  = $user->can('backups.delete');
    $canRestore = $user->can('backups.restore');
@endphp

{{-- ─── Stats ──────────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-database"></i></div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats->total_backups ?? 0 }}</div>
            <div class="stat-label">Total sauvegardes</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats->successful_backups ?? 0 }}</div>
            <div class="stat-label">Réussies</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats->failed_backups ?? 0 }}</div>
            <div class="stat-label">Échouées</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-hdd"></i></div>
        <div class="stat-body">
            <div class="stat-value">{{ $stats->total_size ?? '0 B' }}</div>
            <div class="stat-label">Espace utilisé</div>
        </div>
    </div>
</div>

{{-- ─── Créer une sauvegarde ────────────────────────────── --}}
@if($canCreate)
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-plus-circle"></i> Nouvelle sauvegarde
        </div>
    </div>
    <div class="card-body">
        <div class="backup-actions">
            <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                @csrf
                <input type="hidden" name="type" value="full">
                <button type="submit" class="backup-type-btn primary">
                    <i class="fas fa-database"></i> Complète
                </button>
            </form>
            <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                @csrf
                <input type="hidden" name="type" value="database">
                <button type="submit" class="backup-type-btn">
                    <i class="fas fa-table"></i> Base de données
                </button>
            </form>
            <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                @csrf
                <input type="hidden" name="type" value="files">
                <button type="submit" class="backup-type-btn">
                    <i class="fas fa-folder"></i> Fichiers
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ─── Liste des sauvegardes ───────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-archive"></i> Sauvegardes disponibles
        </div>
        <span class="badge badge-info">{{ $backups->count() ?? 0 }} fichier(s)</span>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fichier</th>
                    <th>Taille</th>
                    <th>Date de création</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td>
                        <div class="file-name">
                            <i class="fas fa-file-archive"></i>
                            {{ $backup->name }}
                        </div>
                    </td>
                    <td><span class="file-size-badge">{{ $backup->size }}</span></td>
                    <td style="color: var(--text-secondary);">{{ $backup->date }}</td>
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('admin.backup.download', $backup->name) }}"
                               class="action-btn" title="Télécharger">
                                <i class="fas fa-download"></i>
                            </a>
                            @if($canRestore)
                            <button type="button" class="action-btn btn-restore restore-btn"
                                    data-filename="{{ $backup->name }}" title="Restaurer">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            @endif
                            @if($canDelete)
                            <button type="button" class="action-btn btn-del delete-btn"
                                    data-filename="{{ $backup->name }}" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-database"></i></div>
                            <div class="empty-title">Aucune sauvegarde</div>
                            <div class="empty-text">Créez votre première sauvegarde pour protéger vos données.</div>
                            @if($canCreate)
                            <button type="button" id="createFirstBackupBtn" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer une sauvegarde
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── Paramètres ──────────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-sliders-h"></i> Paramètres de sauvegarde
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.backup.settings') }}" id="settingsForm">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email de notification</label>
                    <input type="email" name="backup_email" class="form-control"
                           value="{{ $settings->backup_email ?? '' }}"
                           placeholder="email@exemple.com">
                    <span class="form-hint">Les rapports de backup sont envoyés ici</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Type par défaut</label>
                    <select name="backup_type" class="form-select">
                        <option value="full"     {{ ($settings->backup_type ?? 'full') == 'full'     ? 'selected' : '' }}>Complète</option>
                        <option value="database" {{ ($settings->backup_type ?? '') == 'database'     ? 'selected' : '' }}>Base de données uniquement</option>
                        <option value="files"    {{ ($settings->backup_type ?? '') == 'files'        ? 'selected' : '' }}>Fichiers uniquement</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fréquence</label>
                    <select name="backup_frequency" class="form-select">
                        <option value="daily"   {{ ($settings->backup_frequency ?? 'daily') == 'daily'   ? 'selected' : '' }}>Quotidienne</option>
                        <option value="weekly"  {{ ($settings->backup_frequency ?? '') == 'weekly'        ? 'selected' : '' }}>Hebdomadaire</option>
                        <option value="monthly" {{ ($settings->backup_frequency ?? '') == 'monthly'       ? 'selected' : '' }}>Mensuelle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Heure d'exécution</label>
                    <input type="time" name="backup_time" class="form-control"
                           value="{{ $settings->backup_time ?? '02:00' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Conservation (jours)</label>
                    <input type="number" name="auto_clean_days" class="form-control"
                           value="{{ $settings->auto_clean_days ?? 30 }}" min="1" max="90">
                    <span class="form-hint">Suppression automatique après ce délai</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Dernière sauvegarde</label>
                    <input type="text" class="form-control"
                           value="{{ $settings->last_backup_at ? \Carbon\Carbon::parse($settings->last_backup_at)->format('d/m/Y à H:i') : 'Jamais effectuée' }}"
                           readonly disabled>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" id="testEmailBtn" class="btn btn-secondary">
                    <i class="fas fa-paper-plane"></i> Tester l'email
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Historique ──────────────────────────────────────── --}}
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
    <div class="table-wrap">
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
                    <td style="font-size:.8125rem; color: var(--text-secondary); font-family: monospace;">
                        {{ $log->filename }}
                    </td>
                    <td>
                        <div class="log-type">
                            <i class="fas {{ $log->type === 'manual' ? 'fa-hand-pointer' : 'fa-robot' }}"></i>
                            {{ $log->type === 'manual' ? 'Manuelle' : 'Automatique' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $log->status === 'success' ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas {{ $log->status === 'success' ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $log->status === 'success' ? 'Succès' : 'Échec' }}
                        </span>
                        @if($log->error_message)
                        <div style="font-size:.75rem; color: var(--brand-error); margin-top: 3px;">
                            {{ $log->error_message }}
                        </div>
                        @endif
                    </td>
                    <td style="color: var(--text-secondary);">{{ $log->size ?? '—' }}</td>
                    <td style="color: var(--text-secondary); font-size: .8125rem;">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-history"></i></div>
                            <div class="empty-title">Aucun historique</div>
                            <div class="empty-text">L'historique des sauvegardes apparaîtra ici.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── Modal : Suppression ─────────────────────────────── --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-trash-alt"></i> Supprimer la sauvegarde</div>
            <button type="button" class="modal-close" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-secondary); font-size: .875rem;">Vous êtes sur le point de supprimer :</p>
            <span class="file-highlight" id="deleteFilename"></span>
            <div class="danger-box">
                <i class="fas fa-exclamation-circle"></i>
                <p>Cette action est <strong>irréversible</strong>. Le fichier de sauvegarde sera définitivement supprimé.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Annuler</button>
            <form id="deleteForm" method="POST" action="">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Supprimer</button>
            </form>
        </div>
    </div>
</div>

{{-- ─── Modal : Restauration ───────────────────────────── --}}
<div id="restoreModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-undo-alt"></i> Restaurer la sauvegarde</div>
            <button type="button" class="modal-close" onclick="closeModal('restoreModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-secondary); font-size: .875rem;">Vous allez restaurer :</p>
            <span class="file-highlight" id="restoreFilename"></span>
            <div class="warn-box">
                <i class="fas fa-exclamation-triangle"></i>
                <p>La restauration <strong>remplacera toutes les données actuelles</strong> par celles de cette sauvegarde. Cette opération ne peut pas être annulée.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('restoreModal')">Annuler</button>
            <form id="restoreForm" method="POST" action="">
                @csrf
                <button type="submit" class="btn btn-warning"><i class="fas fa-undo-alt"></i> Restaurer</button>
            </form>
        </div>
    </div>
</div>

{{-- ─── Modal : Première sauvegarde ───────────────────── --}}
<div id="firstBackupModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-plus-circle"></i> Créer une sauvegarde</div>
            <button type="button" class="modal-close" onclick="closeModal('firstBackupModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-secondary); font-size: .875rem;">Choisissez le type de sauvegarde :</p>
            <div class="backup-type-list">
                <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                    @csrf <input type="hidden" name="type" value="full">
                    <button type="submit" class="backup-type-item" style="width:100%; text-align:left;">
                        <div class="type-icon full"><i class="fas fa-database"></i></div>
                        <div class="type-info">
                            <div class="type-name">Complète</div>
                            <div class="type-desc">Base de données + fichiers du projet</div>
                        </div>
                        <i class="fas fa-chevron-right arrow"></i>
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                    @csrf <input type="hidden" name="type" value="database">
                    <button type="submit" class="backup-type-item" style="width:100%; text-align:left;">
                        <div class="type-icon db"><i class="fas fa-table"></i></div>
                        <div class="type-info">
                            <div class="type-name">Base de données</div>
                            <div class="type-desc">Uniquement les tables et données SQL</div>
                        </div>
                        <i class="fas fa-chevron-right arrow"></i>
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.backup.create') }}" class="backup-form">
                    @csrf <input type="hidden" name="type" value="files">
                    <button type="submit" class="backup-type-item" style="width:100%; text-align:left;">
                        <div class="type-icon files"><i class="fas fa-folder"></i></div>
                        <div class="type-info">
                            <div class="type-name">Fichiers</div>
                            <div class="type-desc">Médias, uploads et assets uniquement</div>
                        </div>
                        <i class="fas fa-chevron-right arrow"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('firstBackupModal')">Fermer</button>
        </div>
    </div>
</div>

{{-- ─── Modal : Test email ─────────────────────────────── --}}
<div id="testEmailModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-paper-plane"></i> Test d'envoi d'email</div>
            <button type="button" class="modal-close" onclick="closeModal('testEmailModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="testEmailResult"></div>
            <div class="progress-bar" id="testEmailProgress" style="display:none;">
                <div class="progress-fill"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('testEmailModal')">Fermer</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Helpers ──────────────────────────────────────────
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    window.closeModal = closeModal;

    // Close on backdrop click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // ── Suppression ──────────────────────────────────────
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const filename = this.dataset.filename;
            document.getElementById('deleteFilename').textContent = filename;
            document.getElementById('deleteForm').action = "{{ url('admin/backup') }}/" + filename;
            openModal('deleteModal');
        });
    });

    // ── Restauration ─────────────────────────────────────
    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const filename = this.dataset.filename;
            document.getElementById('restoreFilename').textContent = filename;
            document.getElementById('restoreForm').action = "{{ url('admin/backup') }}/" + filename + '/restore';
            openModal('restoreModal');
        });
    });

    // ── Première sauvegarde ──────────────────────────────
    const firstBtn = document.getElementById('createFirstBackupBtn');
    if (firstBtn) firstBtn.addEventListener('click', () => openModal('firstBackupModal'));

    // ── Test email ───────────────────────────────────────
    const testBtn = document.getElementById('testEmailBtn');
    if (testBtn) {
        testBtn.addEventListener('click', async function () {
            const result   = document.getElementById('testEmailResult');
            const progress = document.getElementById('testEmailProgress');
            openModal('testEmailModal');
            progress.style.display = 'block';
            result.innerHTML = '<p style="color:var(--text-secondary);font-size:.875rem;">Envoi en cours…</p>';

            try {
                const res  = await fetch('{{ route("admin.backup.test-email") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                result.innerHTML = data.success
                    ? '<span class="badge badge-success" style="font-size:.8125rem;padding:.5rem 1rem;"><i class="fas fa-check"></i> Email envoyé avec succès !</span>'
                    : '<span class="badge badge-danger"  style="font-size:.8125rem;padding:.5rem 1rem;"><i class="fas fa-times"></i> Erreur : ' + data.message + '</span>';
            } catch {
                result.innerHTML = '<span class="badge badge-danger" style="font-size:.8125rem;padding:.5rem 1rem;"><i class="fas fa-times"></i> Erreur lors de l\'envoi</span>';
            } finally {
                progress.style.display = 'none';
            }
        });
    }

    // ── Effacer les logs ─────────────────────────────────
    const clearBtn = document.getElementById('clearLogsBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', async function () {
            if (!confirm('Effacer tous les logs de sauvegarde ?')) return;
            try {
                const res = await fetch('{{ route("admin.backup.logs.clear") }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (res.ok) window.location.reload();
            } catch (err) {
                console.error(err);
            }
        });
    }

    // ── Désactiver boutons pendant soumission ────────────
    document.querySelectorAll('.backup-form').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> En cours…';
            }
        });
    });

    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function () {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement…'; }
        });
    }

})();
</script>
@endpush
