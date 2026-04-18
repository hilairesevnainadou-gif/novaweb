@extends('admin.layouts.app')

@section('title', 'Gestion des rôles — NovaTech Admin')
@section('page-title', 'Rôles & Permissions')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   PAGE RÔLES — styles autonomes, pas de dépendance
════════════════════════════════════════════════════ */

/* ── Variables locales ── */
:root {
    --rp-primary:    #6366f1;
    --rp-primary-d:  #4f46e5;
    --rp-radius:     14px;
    --rp-transition: all 0.22s ease;
}

/* ── Page header ── */
.rp-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.rp-page-title   { font-size: 20px; font-weight: 800; color: var(--text-primary); letter-spacing: -.02em; }
.rp-page-sub     { font-size: 13px; color: var(--text-tertiary); margin-top: 4px; }
.rp-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-family: inherit;
    transition: var(--rp-transition);
    text-decoration: none;
}
.rp-btn-primary {
    background: var(--brand-primary);
    color: #fff;
}
.rp-btn-primary:hover {
    background: var(--brand-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(99,102,241,.4);
    color: #fff;
}
.rp-btn-ghost {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-medium);
    color: var(--text-secondary);
}
.rp-btn-ghost:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}
.rp-btn-danger {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.25);
    color: #f87171;
}
.rp-btn-danger:hover {
    background: rgba(239,68,68,.22);
    color: #fca5a5;
}
.rp-btn-sm { padding: 7px 14px; font-size: 12px; border-radius: 8px; }

/* ── Stats KPI ── */
.rp-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 28px;
}
.rp-kpi {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--rp-radius);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.rp-kpi-icon {
    width: 42px; height: 42px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
    background: rgba(99,102,241,.15);
    color: #818cf8;
}
.rp-kpi-val   { font-size: 24px; font-weight: 800; color: var(--text-primary); line-height: 1; }
.rp-kpi-label { font-size: 12px; color: var(--text-tertiary); margin-top: 4px; }

/* ── Layout split ── */
.rp-split {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 20px;
    align-items: start;
}

/* ── Panel ── */
.rp-panel {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--rp-radius);
    overflow: hidden;
}
.rp-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-tertiary);
}
.rp-panel-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.rp-panel-title i { color: var(--brand-primary); }

/* ── Tableau des rôles ── */
.rp-table { width: 100%; border-collapse: collapse; }
.rp-table th {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-tertiary);
    padding: 12px 22px;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-tertiary);
}
.rp-table td {
    padding: 14px 22px;
    border-bottom: 1px solid var(--border-light);
    font-size: 13px;
    color: var(--text-secondary);
    vertical-align: middle;
}
.rp-table tr:last-child td { border-bottom: none; }
.rp-table tr:hover td { background: var(--bg-hover); }

/* Badge rôle */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border-medium);
}

/* Compteur de permissions */
.perm-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: var(--text-secondary);
}
.perm-count .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--brand-primary); }

/* Users count */
.users-count {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
}

/* Actions */
.rp-actions { display: flex; gap: 8px; align-items: center; }

/* ── FORMULAIRE de création/édition ── */
.rp-form-panel { position: sticky; top: 90px; }

.rp-form { padding: 22px; }

.rp-field { margin-bottom: 18px; }
.rp-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 7px;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.rp-input {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg-primary);
    border: 1px solid var(--border-medium);
    border-radius: 10px;
    color: var(--text-primary);
    font-family: inherit;
    font-size: 13px;
    outline: none;
    transition: var(--rp-transition);
}
.rp-input:focus {
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px rgba(99,102,241,.18);
}
.rp-input::placeholder { color: var(--text-tertiary); }
.rp-input.error { border-color: var(--brand-error); }
.rp-input-error { font-size: 11px; color: var(--brand-error); margin-top: 5px; display: none; }
.rp-input.error + .rp-input-error { display: block; }

textarea.rp-input { resize: vertical; min-height: 72px; line-height: 1.55; }

/* Sélecteur de couleur - SIMPLIFIÉ */
.rp-color-grid {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.rp-color-opt {
    width: 30px; height: 30px;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--rp-transition);
    flex-shrink: 0;
}
.rp-color-opt:hover { transform: scale(1.12); }
.rp-color-opt.selected { border-color: #fff; transform: scale(1.1); }

/* ── Permissions par groupes ── */
.perm-groups { display: flex; flex-direction: column; gap: 14px; }
.perm-group { background: var(--bg-tertiary); border: 1px solid var(--border-light); border-radius: 10px; overflow: hidden; }
.perm-group-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    cursor: pointer;
    user-select: none;
    transition: background .15s;
}
.perm-group-head:hover { background: var(--bg-hover); }
.perm-group-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: .07em;
}
.perm-group-name i { color: var(--brand-primary); font-size: 13px; }
.perm-group-toggle { color: var(--text-tertiary); font-size: 12px; transition: transform .2s; }
.perm-group-toggle.open { transform: rotate(180deg); }
.perm-group-select-all {
    font-size: 11px;
    font-weight: 600;
    color: var(--brand-primary);
    cursor: pointer;
    padding: 3px 8px;
    border-radius: 5px;
    transition: background .15s;
    border: none;
    background: none;
    font-family: inherit;
}
.perm-group-select-all:hover { background: rgba(99,102,241,.15); }
.perm-group-body { padding: 10px 14px 14px; display: none; }
.perm-group-body.open { display: block; }
.perm-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.perm-item {
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    padding: 6px 8px;
    border-radius: 7px;
    transition: background .15s;
}
.perm-item:hover { background: var(--bg-hover); }
.perm-checkbox {
    width: 16px; height: 16px;
    border-radius: 4px;
    border: 1.5px solid var(--border-medium);
    background: var(--bg-primary);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: var(--rp-transition);
    cursor: pointer;
}
.perm-checkbox.checked {
    background: var(--brand-primary);
    border-color: var(--brand-primary);
}
.perm-checkbox.checked::after {
    content: '';
    width: 8px; height: 5px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px);
    display: block;
}
.perm-name {
    font-size: 12px;
    color: var(--text-secondary);
    transition: color .15s;
}
.perm-item:hover .perm-name { color: var(--text-primary); }
.perm-item.checked .perm-name { color: var(--text-primary); }

/* Compteur permissions sélectionnées */
.perm-counter {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: rgba(99,102,241,.07);
    border-radius: 9px;
    margin-bottom: 16px;
    font-size: 12px;
    color: var(--text-secondary);
}
.perm-counter strong { color: var(--brand-primary); font-size: 18px; font-weight: 800; }

/* Séparateur */
.rp-divider {
    height: 1px;
    background: var(--border-light);
    margin: 18px 0;
}

/* ── Toast ── */
.rp-toast {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    padding: 14px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    backdrop-filter: blur(10px);
    transform: translateY(80px);
    opacity: 0;
    transition: all .35s cubic-bezier(.16,1,.3,1);
    pointer-events: none;
}
.rp-toast.show { transform: translateY(0); opacity: 1; }
.rp-toast.success { background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }
.rp-toast.error   { background: rgba(239,68,68,.15);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }

/* ── Modal de confirmation suppression ── */
.rp-modal-bg {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.6);
    backdrop-filter: blur(4px);
    z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity .25s;
}
.rp-modal-bg.open { opacity: 1; pointer-events: all; }
.rp-modal {
    background: var(--bg-elevated);
    border: 1px solid var(--border-medium);
    border-radius: 18px;
    padding: 28px 28px 24px;
    width: 100%;
    max-width: 400px;
    transform: scale(.95);
    transition: transform .25s;
}
.rp-modal-bg.open .rp-modal { transform: scale(1); }
.rp-modal-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: rgba(239,68,68,.15);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
    font-size: 22px; color: #f87171;
}
.rp-modal-title { font-size: 17px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
.rp-modal-sub   { font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 24px; }
.rp-modal-actions { display: flex; gap: 10px; justify-content: flex-end; }

/* ── Responsive ── */
@media (max-width: 1100px) {
    .rp-split { grid-template-columns: 1fr; }
    .rp-form-panel { position: static; }
    .rp-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .rp-kpi-grid { grid-template-columns: 1fr 1fr; }
    .perm-list { grid-template-columns: 1fr; }
    .rp-table th:nth-child(3),
    .rp-table td:nth-child(3) { display: none; }
}
</style>
@endpush


@section('content')

{{-- Toast --}}
<div class="rp-toast success" id="rpToast">
    <i class="fas fa-check-circle"></i>
    <span id="rpToastMsg">Action effectuée avec succès.</span>
</div>

{{-- Modal suppression --}}
<div class="rp-modal-bg" id="deleteModal">
    <div class="rp-modal">
        <div class="rp-modal-icon"><i class="fas fa-trash-alt"></i></div>
        <div class="rp-modal-title">Supprimer ce rôle ?</div>
        <p class="rp-modal-sub">
            Cette action est irréversible. Les utilisateurs ayant ce rôle perdront leurs permissions associées.
        </p>
        <div class="rp-modal-actions">
            <button class="rp-btn rp-btn-ghost" onclick="closeDeleteModal()">Annuler</button>
            <form id="deleteForm" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="rp-btn rp-btn-danger">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>


{{-- ── Page header ── --}}
<div class="rp-page-header">
    <div>
        <div class="rp-page-title"><i class="fas fa-shield-alt" style="color:var(--brand-primary);margin-right:10px;"></i>Rôles & Permissions</div>
        <div class="rp-page-sub">Gérez les rôles utilisateurs et leurs niveaux d'accès.</div>
    </div>
</div>


{{-- ── KPI Cards ── --}}
<div class="rp-kpi-grid">
    <div class="rp-kpi">
        <div class="rp-kpi-icon purple"><i class="fas fa-user-shield"></i></div>
        <div>
            <div class="rp-kpi-val">{{ $roles->count() }}</div>
            <div class="rp-kpi-label">Rôles actifs</div>
        </div>
    </div>
    <div class="rp-kpi">
        <div class="rp-kpi-icon cyan"><i class="fas fa-key"></i></div>
        <div>
            <div class="rp-kpi-val">{{ $permissions->count() }}</div>
            <div class="rp-kpi-label">Permissions totales</div>
        </div>
    </div>
    <div class="rp-kpi">
        <div class="rp-kpi-icon green"><i class="fas fa-users"></i></div>
        <div>
            <div class="rp-kpi-val">{{ $roles->sum(function($r) { return $r->users_count ?? $r->users()->count(); }) }}</div>
            <div class="rp-kpi-label">Utilisateurs assignés</div>
        </div>
    </div>
    <div class="rp-kpi">
        <div class="rp-kpi-icon amber"><i class="fas fa-layer-group"></i></div>
        <div>
            <div class="rp-kpi-val">{{ $permissions->groupBy(function($p) { return explode('.', $p->name)[0]; })->count() }}</div>
            <div class="rp-kpi-label">Groupes de permissions</div>
        </div>
    </div>
</div>


{{-- ── Split : liste gauche + formulaire droite ── --}}
<div class="rp-split">

    {{-- ════ LISTE DES RÔLES ════ --}}
    <div class="rp-panel">
        <div class="rp-panel-head">
            <div class="rp-panel-title">
                <i class="fas fa-list-ul"></i> Rôles existants
            </div>
        </div>
        <table class="rp-table">
            <thead>
                <tr>
                    <th>Rôle</th>
                    <th>Permissions</th>
                    <th>Utilisateurs</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <span class="role-badge">
                            <i class="fas fa-circle" style="font-size:6px;"></i>
                            {{ $role->display_name ?? $role->name }}
                        </span>
                    </td>
                    <td>
                        <div class="perm-count">
                            <span class="dot"></span>
                            {{ $role->permissions->count() }} permissions
                        </div>
                    </td>
                    <td>
                        <span class="users-count">{{ $role->users_count ?? $role->users()->count() }}</span>
                    </td>
                    <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $role->description ?? '—' }}
                    </td>
                    <td>
                        <div class="rp-actions">
                            @can('roles.edit')
                            <button
                                class="rp-btn rp-btn-ghost rp-btn-sm"
                                onclick="loadRoleForEdit({{ $role->id }})"
                                title="Modifier">
                                <i class="fas fa-pen"></i>
                            </button>
                            @endcan
                            @can('roles.delete')
                            @if(!in_array($role->name, ['super-admin', 'admin']))
                            <button
                                class="rp-btn rp-btn-danger rp-btn-sm"
                                onclick="openDeleteModal({{ $role->id }})"
                                title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:36px;color:var(--text-tertiary);">
                        <i class="fas fa-shield-alt" style="font-size:28px;display:block;margin-bottom:10px;opacity:.3;"></i>
                        Aucun rôle trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- ════ FORMULAIRE CRÉATION / ÉDITION ════ --}}
    @canany(['roles.create', 'roles.edit'])
    <div class="rp-form-panel">
        <div class="rp-panel">
            <div class="rp-panel-head">
                <div class="rp-panel-title">
                    <i class="fas fa-plus-circle"></i>
                    <span id="formPanelTitle">Créer un rôle</span>
                </div>
                <button class="rp-btn rp-btn-ghost rp-btn-sm" id="resetFormBtn" onclick="resetForm()" style="display:none;">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>

            <form id="roleForm" method="POST" action="{{ route('admin.roles.store') }}" class="rp-form">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="role_id"  id="roleId"     value="">

                {{-- Nom technique --}}
                <div class="rp-field">
                    <label class="rp-label" for="roleName">
                        <i class="fas fa-code"></i> Nom technique (slug)
                    </label>
                    <input
                        type="text"
                        id="roleName"
                        name="name"
                        class="rp-input"
                        placeholder="ex: content-manager"
                        pattern="[a-z0-9\-]+"
                        required
                    >
                    <div class="rp-input-error">Lettres minuscules, chiffres et tirets uniquement.</div>
                </div>

                {{-- Nom affiché --}}
                <div class="rp-field">
                    <label class="rp-label" for="roleDisplayName">
                        <i class="fas fa-tag"></i> Nom affiché
                    </label>
                    <input
                        type="text"
                        id="roleDisplayName"
                        name="display_name"
                        class="rp-input"
                        placeholder="ex: Gestionnaire de contenu"
                        required
                    >
                </div>

                {{-- Description --}}
                <div class="rp-field">
                    <label class="rp-label" for="roleDescription">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea
                        id="roleDescription"
                        name="description"
                        class="rp-input"
                        placeholder="Décrivez les responsabilités de ce rôle…"
                        rows="2"
                    ></textarea>
                </div>

                {{-- Couleur --}}
                <div class="rp-field">
                    <label class="rp-label"><i class="fas fa-palette"></i> Couleur du badge</label>
                    <div class="rp-color-grid">
                        @foreach(['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'] as $color)
                        <div
                            class="rp-color-opt"
                            data-color="{{ $color }}"
                            style="background: {{ $color }};"
                            onclick="selectColor('{{ $color }}', this)"
                            title="{{ $color }}">
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" id="roleColor" value="#6366f1">
                </div>

                <div class="rp-divider"></div>

                {{-- Permissions --}}
                <div class="rp-field">
                    <label class="rp-label"><i class="fas fa-key"></i> Permissions</label>

                    <div class="perm-counter">
                        <span><i class="fas fa-check-circle" style="color:var(--brand-primary);margin-right:6px;"></i> Permissions sélectionnées</span>
                        <strong id="permCounter">0</strong>
                    </div>

                    <div class="perm-groups" id="permGroups">
                        @php
                            $grouped = $permissions->groupBy(function($p) {
                                return explode('.', $p->name)[0];
                            });
                            $groupIcons = [
                                'dashboard'    => 'fas fa-chart-pie',
                                'users'        => 'fas fa-users',
                                'portfolio'    => 'fas fa-briefcase',
                                'blog'         => 'fas fa-newspaper',
                                'services'     => 'fas fa-layer-group',
                                'testimonials' => 'fas fa-star',
                                'contact'      => 'fas fa-envelope',
                                'tickets'      => 'fas fa-ticket-alt',
                                'maintenance'  => 'fas fa-tools',
                                'backups'      => 'fas fa-database',
                                'settings'     => 'fas fa-sliders-h',
                                'profile'      => 'fas fa-user-circle',
                                'roles'        => 'fas fa-shield-alt',
                            ];
                        @endphp

                        @foreach($grouped as $group => $perms)
                        <div class="perm-group">
                            <div class="perm-group-head" onclick="toggleGroup('group-{{ $group }}', this)">
                                <div class="perm-group-name">
                                    <i class="{{ $groupIcons[$group] ?? 'fas fa-circle' }}"></i>
                                    {{ ucfirst($group) }}
                                    <span style="font-size:10px;color:var(--text-tertiary);font-weight:400;">({{ $perms->count() }})</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <button
                                        type="button"
                                        class="perm-group-select-all"
                                        onclick="event.stopPropagation(); selectAllInGroup('group-{{ $group }}')"
                                    >Tout</button>
                                    <i class="fas fa-chevron-down perm-group-toggle" id="toggle-group-{{ $group }}"></i>
                                </div>
                            </div>
                            <div class="perm-group-body" id="group-{{ $group }}">
                                <div class="perm-list">
                                    @foreach($perms as $perm)
                                    <div
                                        class="perm-item"
                                        data-perm="{{ $perm->name }}"
                                        onclick="togglePerm(this, '{{ $perm->name }}')"
                                    >
                                        <div class="perm-checkbox" id="chk-{{ str_replace('.', '-', $perm->name) }}"></div>
                                        <span class="perm-name">
                                            {{ str_replace($group.'.', '', $perm->name) }}
                                        </span>
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $perm->name }}"
                                            id="perm-{{ str_replace('.', '-', $perm->name) }}"
                                            style="display:none;"
                                        >
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="rp-divider"></div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="rp-btn rp-btn-primary" style="flex:1;">
                        <i class="fas fa-save" id="submitIcon"></i>
                        <span id="submitLabel">Créer le rôle</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    @endcanany

</div>{{-- /rp-split --}}

@endsection


@push('scripts')
<script>
(function () {
    'use strict';

    /* ══════════════════════════════════
       PERMISSIONS — toggle, counter
    ══════════════════════════════════ */
    window.togglePerm = function (el, permName) {
        el.classList.toggle('checked');
        var chk  = document.getElementById('chk-'  + permName.replace(/\./g, '-'));
        var inp  = document.getElementById('perm-' + permName.replace(/\./g, '-'));
        var isOn = el.classList.contains('checked');
        if (chk) { chk.classList.toggle('checked', isOn); }
        if (inp) { inp.checked = isOn; }
        updateCounter();
    };

    window.selectAllInGroup = function (groupId) {
        var group = document.getElementById(groupId);
        if (!group) return;
        var items = group.querySelectorAll('.perm-item');
        var allOn = Array.from(items).every(function (i) { return i.classList.contains('checked'); });
        items.forEach(function (item) {
            var perm = item.dataset.perm;
            var chk  = document.getElementById('chk-'  + perm.replace(/\./g, '-'));
            var inp  = document.getElementById('perm-' + perm.replace(/\./g, '-'));
            item.classList.toggle('checked', !allOn);
            if (chk) chk.classList.toggle('checked', !allOn);
            if (inp) inp.checked = !allOn;
        });
        updateCounter();
    };

    function updateCounter() {
        var count = document.querySelectorAll('.perm-item.checked').length;
        var el = document.getElementById('permCounter');
        if (el) el.textContent = count;
    }

    window.toggleGroup = function (groupId, head) {
        var body   = document.getElementById(groupId);
        var toggle = document.getElementById('toggle-' + groupId);
        if (!body) return;
        var isOpen = body.classList.toggle('open');
        if (toggle) toggle.classList.toggle('open', isOpen);
    };

    /* ══════════════════════════════════
       COULEUR DU BADGE
    ══════════════════════════════════ */
    window.selectColor = function (color, el) {
        document.querySelectorAll('.rp-color-opt').forEach(function (o) { o.classList.remove('selected'); });
        el.classList.add('selected');
        var inp = document.getElementById('roleColor');
        if (inp) inp.value = color;
    };

    /* ══════════════════════════════════
       CHARGER UN RÔLE POUR ÉDITION
    ══════════════════════════════════ */
    window.loadRoleForEdit = function (roleId) {
        fetch('/admin/roles/' + roleId + '/edit-data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('roleId').value          = data.id;
            document.getElementById('roleName').value        = data.name;
            document.getElementById('roleDisplayName').value = data.display_name || '';
            document.getElementById('roleDescription').value = data.description  || '';

            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('roleForm').action  = '/admin/roles/' + data.id;

            document.getElementById('formPanelTitle').textContent = 'Modifier le rôle';
            document.getElementById('submitLabel').textContent    = 'Mettre à jour';
            document.getElementById('submitIcon').className       = 'fas fa-sync-alt';
            document.getElementById('resetFormBtn').style.display = 'inline-flex';

            var color = data.color || '#6366f1';
            document.querySelectorAll('.rp-color-opt').forEach(function (o) {
                o.classList.toggle('selected', o.dataset.color === color);
            });
            document.getElementById('roleColor').value = color;

            document.querySelectorAll('.perm-item').forEach(function (item) {
                var perm  = item.dataset.perm;
                var isOn  = data.permissions.includes(perm);
                var chk   = document.getElementById('chk-'  + perm.replace(/\./g, '-'));
                var inp   = document.getElementById('perm-' + perm.replace(/\./g, '-'));
                item.classList.toggle('checked', isOn);
                if (chk) chk.classList.toggle('checked', isOn);
                if (inp) inp.checked = isOn;
            });

            updateCounter();

            document.querySelectorAll('.perm-group-body').forEach(function (body) {
                if (body.querySelectorAll('.perm-item.checked').length > 0) {
                    body.classList.add('open');
                    var toggle = document.getElementById('toggle-' + body.id);
                    if (toggle) toggle.classList.add('open');
                }
            });

            document.getElementById('roleForm').closest('.rp-panel').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(function () {
            showToast('Impossible de charger les données du rôle.', 'error');
        });
    };

    /* ══════════════════════════════════
       RESET FORMULAIRE
    ══════════════════════════════════ */
    window.resetForm = function () {
        var form = document.getElementById('roleForm');
        form.reset();
        form.action = '{{ route("admin.roles.store") }}';
        document.getElementById('formMethod').value     = 'POST';
        document.getElementById('roleId').value         = '';
        document.getElementById('formPanelTitle').textContent = 'Créer un rôle';
        document.getElementById('submitLabel').textContent    = 'Créer le rôle';
        document.getElementById('submitIcon').className       = 'fas fa-save';
        document.getElementById('resetFormBtn').style.display = 'none';

        document.querySelectorAll('.perm-item').forEach(function (item) {
            item.classList.remove('checked');
            var perm = item.dataset.perm;
            var chk  = document.getElementById('chk-'  + perm.replace(/\./g, '-'));
            var inp  = document.getElementById('perm-' + perm.replace(/\./g, '-'));
            if (chk) chk.classList.remove('checked');
            if (inp) inp.checked = false;
        });

        document.querySelectorAll('.rp-color-opt').forEach(function (o) { o.classList.remove('selected'); });
        var first = document.querySelector('.rp-color-opt');
        if (first) { first.classList.add('selected'); document.getElementById('roleColor').value = first.dataset.color; }

        updateCounter();
    };

    /* ══════════════════════════════════
       MODAL SUPPRESSION
    ══════════════════════════════════ */
    window.openDeleteModal = function (roleId) {
        document.getElementById('deleteForm').action = '/admin/roles/' + roleId;
        document.getElementById('deleteModal').classList.add('open');
    };
    window.closeDeleteModal = function () {
        document.getElementById('deleteModal').classList.remove('open');
    };
    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });

    /* ══════════════════════════════════
       TOAST
    ══════════════════════════════════ */
    window.showToast = function (msg, type) {
        var toast = document.getElementById('rpToast');
        var msgEl = document.getElementById('rpToastMsg');
        toast.className = 'rp-toast ' + (type || 'success');
        if (msgEl) msgEl.textContent = msg;
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 3500);
    };

    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif

    /* ══════════════════════════════════
       VALIDATION NOM TECHNIQUE
    ══════════════════════════════════ */
    var nameInput = document.getElementById('roleName');
    if (nameInput) {
        nameInput.addEventListener('input', function () {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9\-]/g, '-');
        });
        nameInput.addEventListener('blur', function () {
            if (this.value && !/^[a-z][a-z0-9\-]*$/.test(this.value)) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    }

    updateCounter();

})();
</script>
@endpush
