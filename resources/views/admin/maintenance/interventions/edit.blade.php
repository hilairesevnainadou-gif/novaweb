{{-- resources/views/admin/maintenance/interventions/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier · ' . $intervention->intervention_number . ' · NovaTech Admin')
@section('page-title', 'Modifier l\'intervention')

@push('styles')
<style>
/* ── Layout ──────────────────────────────────────────────── */
.edit-layout {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 1rem;
    align-items: start;
}
@media (max-width: 1024px) {
    .edit-layout { grid-template-columns: 1fr; }
}

/* ── Breadcrumb ──────────────────────────────────────────── */
.breadcrumb {
    display: flex; align-items: center; gap: 0.375rem;
    font-size: 0.6875rem; font-weight: 500;
    color: var(--text-tertiary); margin-bottom: 1rem;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.breadcrumb a { color: var(--text-tertiary); text-decoration: none; transition: color var(--transition-fast); }
.breadcrumb a:hover { color: var(--brand-primary); }
.breadcrumb i { font-size: 0.45rem; opacity: 0.4; }
.breadcrumb span { color: var(--text-primary); }

/* ── Page header ─────────────────────────────────────────── */
.ph {
    display: flex; align-items: center; justify-content: space-between;
    gap: 0.75rem; flex-wrap: wrap;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: 0.875rem 1.25rem;
    margin-bottom: 1rem;
}
.ph-left { display: flex; align-items: center; gap: 0.75rem; }
.ph-icon {
    width: 36px; height: 36px; border-radius: var(--radius-md);
    background: linear-gradient(135deg, rgba(59,130,246,.12), rgba(139,92,246,.12));
    border: 1px solid rgba(59,130,246,.18);
    display: flex; align-items: center; justify-content: center;
    color: var(--brand-primary); font-size: 0.875rem; flex-shrink: 0;
}
.ph-title { font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.1rem; }
.ph-sub   { font-size: 0.6875rem; color: var(--text-tertiary); margin: 0; }
.ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

/* ── Status pill ─────────────────────────────────────────── */
.s-pill {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.25rem 0.625rem; border-radius: 9999px;
    font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
}
.s-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.s-pending    { background: rgba(245,158,11,.1);  color: #f59e0b; } .s-pending .s-dot    { background: #f59e0b; }
.s-approved   { background: rgba(59,130,246,.1);  color: #3b82f6; } .s-approved .s-dot   { background: #3b82f6; }
.s-in_progress{ background: rgba(139,92,246,.1);  color: #8b5cf6; } .s-in_progress .s-dot{ background: #8b5cf6; animation: pdot 1.6s ease infinite; }
.s-completed  { background: rgba(16,185,129,.1);  color: #10b981; } .s-completed .s-dot  { background: #10b981; }
.s-cancelled  { background: rgba(107,114,128,.1); color: #6b7280; } .s-cancelled .s-dot  { background: #6b7280; }
@keyframes pdot { 0%,100%{box-shadow:0 0 0 0 rgba(139,92,246,.4)} 50%{box-shadow:0 0 0 4px rgba(139,92,246,0)} }

/* ── Card ────────────────────────────────────────────────── */
.card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-bottom: 0.875rem;
}
.card:last-child { margin-bottom: 0; }

/* ── Card header ─────────────────────────────────────────── */
.ch {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-light);
}
.ch-icon {
    width: 22px; height: 22px; border-radius: 5px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 0.625rem;
}
.ci-blue   { background: rgba(59,130,246,.12);  color: #3b82f6; }
.ci-violet { background: rgba(139,92,246,.12);  color: #8b5cf6; }
.ci-green  { background: rgba(16,185,129,.12);  color: #10b981; }
.ci-amber  { background: rgba(245,158,11,.12);  color: #f59e0b; }
.ci-rose   { background: rgba(239,68,68,.12);   color: #ef4444; }
.ci-slate  { background: rgba(100,116,139,.12); color: #64748b; }
.ch-title {
    font-size: 0.6875rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.5px;
    color: var(--text-primary); flex: 1;
}

/* ── Card body ───────────────────────────────────────────── */
.cb         { padding: 1rem 1.25rem; }
.cb-compact { padding: 0.75rem 1.25rem; }

/* ── Form grid ───────────────────────────────────────────── */
.fg   { display: grid; gap: 0.75rem; }
.fg-2 { grid-template-columns: repeat(2, 1fr); }
.fg-3 { grid-template-columns: repeat(3, 1fr); }
.fg-4 { grid-template-columns: repeat(4, 1fr); }
@media (max-width: 900px)  { .fg-4 { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 768px)  { .fg-2,.fg-3,.fg-4 { grid-template-columns: 1fr; } }

/* ── Form elements ───────────────────────────────────────── */
.fg-group { display: flex; flex-direction: column; gap: 0.3rem; }
.fg-label {
    font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
    color: var(--text-tertiary); display: flex; align-items: center; gap: 0.3rem;
}
.fg-label i { width: 0.75rem; text-align: center; font-size: 0.5625rem; }
.req { color: #ef4444; }

.fc {
    width: 100%;
    padding: 0.5rem 0.8125rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-medium);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.8125rem;
    font-family: inherit;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    outline: none; line-height: 1.5;
}
.fc::placeholder { color: var(--text-tertiary); opacity: .6; }
.fc:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
.fc:disabled,.fc[readonly] {
    opacity: .5; background: var(--bg-tertiary);
    cursor: not-allowed; border-style: dashed;
}
textarea.fc { resize: vertical; min-height: 80px; }
select.fc   { cursor: pointer; }

/* ── Chips ───────────────────────────────────────────────── */
.chips { display: flex; flex-wrap: wrap; gap: 0.375rem; }
.chip-lbl { position: relative; }
.chip-lbl input { position: absolute; opacity: 0; pointer-events: none; }
.chip-in {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-md);
    border: 1.5px solid var(--border-medium);
    background: var(--bg-primary);
    font-size: 0.75rem; font-weight: 500;
    color: var(--text-secondary);
    cursor: pointer; transition: all var(--transition-fast); user-select: none; white-space: nowrap;
}
.chip-in:hover { border-color: var(--brand-primary); color: var(--text-primary); transform: translateY(-1px); }
.cdot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.cp-low    .cdot { background: #6b7280; }
.cp-medium .cdot { background: #3b82f6; }
.cp-high   .cdot { background: #f59e0b; }
.cp-urgent .cdot { background: #ef4444; }
.cp-critical .cdot { background: #8b5cf6; }
.cp-low.chip-lbl:has(input:checked)      .chip-in { border-color:#6b7280; background:rgba(107,114,128,.08); font-weight:600; color:var(--text-primary); }
.cp-medium.chip-lbl:has(input:checked)   .chip-in { border-color:#3b82f6; background:rgba(59,130,246,.08);  font-weight:600; color:var(--text-primary); }
.cp-high.chip-lbl:has(input:checked)     .chip-in { border-color:#f59e0b; background:rgba(245,158,11,.08);  font-weight:600; color:var(--text-primary); }
.cp-urgent.chip-lbl:has(input:checked)   .chip-in { border-color:#ef4444; background:rgba(239,68,68,.08);   font-weight:600; color:var(--text-primary); }
.cp-critical.chip-lbl:has(input:checked) .chip-in { border-color:#8b5cf6; background:rgba(139,92,246,.08);  font-weight:600; color:var(--text-primary); }
.cl-chip.chip-lbl:has(input:checked)     .chip-in { border-color:var(--brand-primary); background:rgba(59,130,246,.08); color:var(--brand-primary); font-weight:600; }

/* ── Device strip ────────────────────────────────────────── */
.device-strip {
    display: flex; align-items: center; gap: 0.875rem;
    padding: 0.75rem 1rem;
    background: var(--bg-tertiary);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    position: relative; overflow: hidden;
}
.device-strip::before {
    content:''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: linear-gradient(to bottom, var(--brand-primary), var(--brand-secondary, #8b5cf6));
}
.device-ava {
    width: 40px; height: 40px; border-radius: var(--radius-md); flex-shrink: 0;
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary, #8b5cf6));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem;
    box-shadow: 0 3px 8px rgba(59,130,246,.25);
}
.device-info h4 { font-size: 0.875rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.15rem; }
.device-info p  { font-size: 0.6875rem; color: var(--text-tertiary); margin: 0; }
.device-info p strong { color: var(--text-secondary); font-weight: 600; }

/* ── Alert ───────────────────────────────────────────────── */
.alert {
    display: flex; align-items: flex-start; gap: 0.625rem;
    padding: 0.75rem 1rem; border-radius: var(--radius-md);
    font-size: 0.8125rem; margin-bottom: 0.875rem;
}
.alert-err { background: rgba(239,68,68,.06); border: 1px solid rgba(239,68,68,.18); color: #ef4444; }
.alert ul  { margin: 0.3rem 0 0 1rem; padding: 0; }
.alert li  { margin-bottom: 0.2rem; font-size: 0.8125rem; }

/* ── Buttons ─────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.5rem 1rem; border-radius: var(--radius-md);
    font-size: 0.8125rem; font-weight: 600; font-family: inherit;
    cursor: pointer; border: none; text-decoration: none;
    transition: all var(--transition-fast); white-space: nowrap; line-height: 1;
}
.btn-primary { background: var(--brand-primary); color: #fff; box-shadow: 0 1px 3px rgba(59,130,246,.3); }
.btn-primary:hover:not(:disabled) { background: var(--brand-primary-hover,#2563eb); box-shadow: 0 4px 12px rgba(59,130,246,.35); transform: translateY(-1px); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }
.btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-medium); }
.btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }
.btn-sm { padding: 0.375rem 0.6875rem; font-size: 0.75rem; }

/* ── Form footer ─────────────────────────────────────────── */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    gap: 0.625rem; flex-wrap: wrap;
    padding: 0.875rem 1.25rem;
    background: var(--bg-tertiary); border-top: 1px solid var(--border-light);
}
.ff-r { display: flex; align-items: center; gap: 0.4375rem; }

/* ── Access denied ───────────────────────────────────────── */
.access-denied {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 4rem 2rem; color: var(--text-tertiary);
}
.ad-icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(239,68,68,.07); border: 1px solid rgba(239,68,68,.15);
    display: flex; align-items: center; justify-content: center;
    color: #ef4444; font-size: 1.375rem; margin-bottom: 1.125rem;
}
.access-denied h3 { font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; }
.access-denied p  { font-size: 0.8125rem; max-width: 340px; line-height: 1.6; margin-bottom: 1.25rem; }

/* ── Sidebar ─────────────────────────────────────────────── */
.scard { background: var(--bg-secondary); border: 1px solid var(--border-light); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 0.875rem; }
.scard:last-child { margin-bottom: 0; }
.sh {
    display: flex; align-items: center; gap: 0.375rem;
    padding: 0.5625rem 1rem;
    background: var(--bg-tertiary); border-bottom: 1px solid var(--border-light);
}
.sh span { font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-secondary); }
.sb { padding: 0.75rem 1rem; }
.irow {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 0.5rem; padding: 0.375rem 0;
    border-bottom: 1px solid var(--border-light); font-size: 0.75rem;
}
.irow:last-child { border-bottom: none; padding-bottom: 0; }
.irow:first-child { padding-top: 0; }
.ilabel { color: var(--text-tertiary); font-size: 0.6875rem; flex-shrink: 0; }
.ivalue { color: var(--text-primary); font-weight: 600; text-align: right; word-break: break-word; }

/* ── Info box ────────────────────────────────────────────── */
.info-box {
    background: rgba(59,130,246,.05);
    border-left: 3px solid var(--brand-primary);
    padding: 0.75rem;
    border-radius: var(--radius-md);
    margin-bottom: 0.75rem;
}
.info-box p {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}
.info-box i {
    color: var(--brand-primary);
    margin-right: 0.5rem;
}
</style>
@endpush

@section('content')

@php
    $user = auth()->user();

    $isSuperAdmin = $user->hasRole('super-admin');
    $isAdmin      = $user->hasRole('admin');
    $isSupport    = $user->hasRole('support');
    $isTechnician = $user->hasRole('technician');

    $isPrivileged         = $isSuperAdmin || $isAdmin || $isSupport;
    $isAssignedTechnician = $isTechnician && ($intervention->technician_id == $user->id);

    $canEdit = $isPrivileged || $isAssignedTechnician;
@endphp

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.maintenance.interventions.index') }}">Interventions</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}">{{ $intervention->intervention_number }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Modifier</span>
</nav>

{{-- Page header --}}
<div class="ph">
    <div class="ph-left">
        <div class="ph-icon"><i class="fas fa-pen-to-square"></i></div>
        <div>
            <p class="ph-title">Modifier l'intervention</p>
            <p class="ph-sub">{{ $intervention->intervention_number }} · modifié {{ $intervention->updated_at->diffForHumans() }}</p>
        </div>
    </div>
    <div class="ph-right">
        <span class="s-pill s-{{ $intervention->status }}">
            <span class="s-dot"></span>{{ $intervention->status_label }}
        </span>
        <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-eye"></i> Voir
        </a>
    </div>
</div>

@if(!$canEdit)
{{-- Accès refusé --}}
<div class="card">
    <div class="access-denied">
        <div class="ad-icon"><i class="fas fa-lock"></i></div>
        <h3>Accès non autorisé</h3>
        <p>Seuls les administrateurs, le support ou le technicien assigné à cette intervention peuvent effectuer des modifications.</p>
        <a href="{{ route('admin.maintenance.interventions.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

@else
{{-- Formulaire --}}
<form method="POST" action="{{ route('admin.maintenance.interventions.update', $intervention) }}"
      id="interventionForm" novalidate>
    @csrf
    @method('PUT')
    <input type="hidden" name="device_id" value="{{ $intervention->device_id }}">
    <input type="hidden" name="client_id" value="{{ $intervention->client_id }}">

    <div class="edit-layout">

        {{-- Colonne principale --}}
        <div>

            {{-- Erreurs de validation --}}
            @if($errors->any())
            <div class="alert alert-err">
                <i class="fas fa-triangle-exclamation" style="margin-top:2px;flex-shrink:0;"></i>
                <div>
                    <strong>Erreurs à corriger :</strong>
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            </div>
            @endif

            {{-- Appareil (contexte, toujours visible) --}}
            <div class="card">
                <div class="ch">
                    <span class="ch-icon ci-slate"><i class="fas fa-microchip"></i></span>
                    <span class="ch-title">Appareil concerné</span>
                </div>
                <div class="cb-compact">
                    <div class="device-strip">
                        <div class="device-ava"><i class="fas fa-microchip"></i></div>
                        <div class="device-info">
                            <h4>{{ $intervention->device->name ?? 'Appareil inconnu' }}</h4>
                            <p>
                                {{ trim(($intervention->device->brand ?? '') . ' ' . ($intervention->device->model ?? '')) }}
                                @if($intervention->device->reference ?? null)
                                    · Réf. <strong>{{ $intervention->device->reference }}</strong>
                                @endif
                            </p>
                            <p><strong>Client :</strong> {{ $intervention->client->name ?? 'Non associé' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION CONTENU TECHNIQUE --}}
            @if($isPrivileged)
                {{-- Pour admin/support : tous les champs sont modifiables --}}
                <div class="card">
                    <div class="ch">
                        <span class="ch-icon ci-blue"><i class="fas fa-wrench"></i></span>
                        <span class="ch-title">Contenu technique</span>
                    </div>
                    <div class="cb">
                        <div class="fg">

                            <div class="fg-group">
                                <label class="fg-label" for="title">
                                    <i class="fas fa-heading"></i> Titre <span class="req">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="fc" required
                                       value="{{ old('title', $intervention->title) }}"
                                       placeholder="Ex : Remplacement disque dur SSD">
                            </div>

                            <div class="fg fg-2">
                                <div class="fg-group">
                                    <label class="fg-label" for="problem_type">
                                        <i class="fas fa-tag"></i> Type de problème
                                    </label>
                                    <select name="problem_type" id="problem_type" class="fc">
                                        <option value="">— Sélectionner —</option>
                                        @foreach([
                                            'hardware'   => 'Matériel',
                                            'software'   => 'Logiciel',
                                            'network'    => 'Réseau',
                                            'electrical' => 'Électrique',
                                            'mechanical' => 'Mécanique',
                                            'other'      => 'Autre',
                                        ] as $v => $l)
                                        <option value="{{ $v }}" {{ old('problem_type', $intervention->problem_type) == $v ? 'selected' : '' }}>
                                            {{ $l }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fg-group">
                                    <label class="fg-label" for="description">
                                        <i class="fas fa-align-left"></i> Description générale
                                    </label>
                                    <input type="text" id="description" name="description" class="fc"
                                           value="{{ old('description', $intervention->description) }}"
                                           placeholder="Contexte rapide...">
                                </div>
                            </div>

                            <div class="fg fg-2">
                                <div class="fg-group">
                                    <label class="fg-label" for="problem_description">
                                        <i class="fas fa-circle-exclamation"></i> Description du problème
                                    </label>
                                    <textarea id="problem_description" name="problem_description" class="fc" rows="3"
                                              placeholder="Dysfonctionnement constaté...">{{ old('problem_description', $intervention->problem_description) }}</textarea>
                                </div>
                                <div class="fg-group">
                                    <label class="fg-label" for="solution">
                                        <i class="fas fa-circle-check"></i> Solution apportée
                                    </label>
                                    <textarea id="solution" name="solution" class="fc" rows="3"
                                              placeholder="Solution mise en œuvre...">{{ old('solution', $intervention->solution) }}</textarea>
                                </div>
                            </div>

                            <div class="fg-group">
                                <label class="fg-label" for="notes">
                                    <i class="fas fa-note-sticky"></i> Notes internes
                                </label>
                                <textarea name="notes" id="notes" class="fc" rows="2"
                                          placeholder="Consignes, précautions, informations complémentaires...">{{ old('notes', $intervention->notes) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                {{-- Pour technicien assigné : champs limités --}}
                <div class="card">
                    <div class="ch">
                        <span class="ch-icon ci-blue"><i class="fas fa-wrench"></i></span>
                        <span class="ch-title">Intervention technique</span>
                    </div>
                    <div class="cb">

                        <div class="fg">

                            {{-- Titre et Type de problème côte à côte --}}
                            <div class="fg fg-2">
                                <div class="fg-group">
                                    <label class="fg-label">
                                        <i class="fas fa-heading"></i> Titre
                                    </label>
                                    <input type="text" class="fc" value="{{ $intervention->title }}" readonly disabled>
                                </div>
                                <div class="fg-group">
                                    <label class="fg-label">
                                        <i class="fas fa-tag"></i> Type de problème
                                    </label>
                                    <input type="text" class="fc" value="{{ $intervention->problem_type_label ?? 'Non spécifié' }}" readonly disabled>
                                </div>
                            </div>

                            {{-- Description du problème (modifiable) --}}
                            <div class="fg-group">
                                <label class="fg-label" for="problem_description">
                                    <i class="fas fa-circle-exclamation"></i> Description du problème
                                </label>
                                <textarea id="problem_description" name="problem_description" class="fc" rows="3"
                                          placeholder="Dysfonctionnement constaté...">{{ old('problem_description', $intervention->problem_description) }}</textarea>
                            </div>

                            {{-- Solution (modifiable) --}}
                            <div class="fg-group">
                                <label class="fg-label" for="solution">
                                    <i class="fas fa-circle-check"></i> Solution apportée
                                </label>
                                <textarea id="solution" name="solution" class="fc" rows="3"
                                          placeholder="Solution mise en œuvre...">{{ old('solution', $intervention->solution) }}</textarea>
                            </div>

                            {{-- Notes internes (modifiable) --}}
                            <div class="fg-group">
                                <label class="fg-label" for="notes">
                                    <i class="fas fa-note-sticky"></i> Notes internes
                                </label>
                                <textarea name="notes" id="notes" class="fc" rows="2"
                                          placeholder="Consignes, précautions, informations complémentaires...">{{ old('notes', $intervention->notes) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            {{-- SECTIONS PRIORITÉ, PLANIFICATION, ASSIGNATION (uniquement pour privilégiés) --}}
            @if($isPrivileged)

            {{-- Priorité & Niveau --}}
            <div class="card">
                <div class="ch">
                    <span class="ch-icon ci-amber"><i class="fas fa-flag"></i></span>
                    <span class="ch-title">Priorité &amp; Niveau d'évolution</span>
                </div>
                <div class="cb">
                    <div class="fg fg-2">
                        <div class="fg-group">
                            <label class="fg-label"><i class="fas fa-flag"></i> Priorité <span class="req">*</span></label>
                            <div class="chips">
                                @foreach([
                                    'low'      => ['Basse',    'cp-low'],
                                    'medium'   => ['Moyenne',  'cp-medium'],
                                    'high'     => ['Haute',    'cp-high'],
                                    'urgent'   => ['Urgente',  'cp-urgent'],
                                    'critical' => ['Critique', 'cp-critical'],
                                ] as $v => [$l, $c])
                                <label class="chip-lbl {{ $c }}">
                                    <input type="radio" name="priority" value="{{ $v }}"
                                           {{ old('priority', $intervention->priority) == $v ? 'checked' : '' }}>
                                    <span class="chip-in"><span class="cdot"></span>{{ $l }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="fg-group">
                            <label class="fg-label"><i class="fas fa-layer-group"></i> Niveau <span class="req">*</span></label>
                            <div class="chips">
                                @foreach([
                                    1 => 'Diagnostic',
                                    2 => 'Simple',
                                    3 => 'Complexe',
                                    4 => 'Majeure',
                                    5 => 'Critique',
                                ] as $n => $d)
                                <label class="chip-lbl cl-chip">
                                    <input type="radio" name="evolution_level" value="{{ $n }}"
                                           {{ old('evolution_level', $intervention->evolution_level) == $n ? 'checked' : '' }}>
                                    <span class="chip-in"><strong>N{{ $n }}</strong>&nbsp;{{ $d }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Planification, Coûts, Assignation --}}
            <div class="card">
                <div class="ch">
                    <span class="ch-icon ci-green"><i class="fas fa-sliders"></i></span>
                    <span class="ch-title">Planification, Coûts &amp; Assignation</span>
                </div>
                <div class="cb">
                    <div class="fg">

                        <div class="fg fg-3">
                            <div class="fg-group">
                                <label class="fg-label" for="scheduled_date">
                                    <i class="fas fa-calendar-check"></i> Date planifiée
                                </label>
                                <input type="datetime-local" id="scheduled_date" name="scheduled_date" class="fc"
                                       value="{{ old('scheduled_date', $intervention->scheduled_date?->format('Y-m-d\TH:i')) }}">
                            </div>
                            <div class="fg-group">
                                <label class="fg-label" for="start_date">
                                    <i class="fas fa-play-circle"></i> Début
                                </label>
                                <input type="datetime-local" id="start_date" name="start_date" class="fc"
                                       value="{{ old('start_date', $intervention->start_date?->format('Y-m-d\TH:i')) }}">
                            </div>
                            <div class="fg-group">
                                <label class="fg-label" for="end_date">
                                    <i class="fas fa-stop-circle"></i> Fin
                                </label>
                                <input type="datetime-local" id="end_date" name="end_date" class="fc"
                                       value="{{ old('end_date', $intervention->end_date?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        <div class="fg fg-4">
                            <div class="fg-group">
                                <label class="fg-label" for="estimated_cost">
                                    <i class="fas fa-calculator"></i> Coût estimé (FCFA)
                                </label>
                                <input type="number" id="estimated_cost" name="estimated_cost" class="fc"
                                       value="{{ old('estimated_cost', $intervention->estimated_cost) }}"
                                       step="1" min="0" placeholder="0">
                            </div>
                            <div class="fg-group">
                                <label class="fg-label" for="actual_cost">
                                    <i class="fas fa-receipt"></i> Coût réel (FCFA)
                                </label>
                                <input type="number" id="actual_cost" name="actual_cost" class="fc"
                                       value="{{ old('actual_cost', $intervention->actual_cost) }}"
                                       step="1" min="0" placeholder="0">
                            </div>
                            <div class="fg-group">
                                <label class="fg-label" for="duration_minutes">
                                    <i class="fas fa-hourglass-half"></i> Durée (min)
                                </label>
                                <input type="number" id="duration_minutes" name="duration_minutes" class="fc"
                                       value="{{ old('duration_minutes', $intervention->duration_minutes) }}"
                                       min="0" placeholder="0">
                            </div>
                            <div class="fg-group">
                                <label class="fg-label" for="technician_id">
                                    <i class="fas fa-user-gear"></i> Technicien assigné
                                </label>
                                <select name="technician_id" id="technician_id" class="fc">
                                    <option value="">— Non assigné —</option>
                                    @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}"
                                        {{ old('technician_id', $intervention->technician_id) == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @endif {{-- /isPrivileged --}}

            {{-- Pied de formulaire --}}
            <div class="card">
                <div class="form-footer">
                    <a href="{{ route('admin.maintenance.interventions.index') }}" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Liste
                    </a>
                    <div class="ff-r">
                        <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="btn btn-ghost">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-floppy-disk"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- /colonne principale --}}

        {{-- Sidebar --}}
        <aside>

            <div class="scard">
                <div class="sh">
                    <i class="fas fa-circle-info" style="color:var(--brand-primary);font-size:.6875rem;"></i>
                    <span>Résumé</span>
                </div>
                <div class="sb">
                    <div class="irow">
                        <span class="ilabel">Numéro</span>
                        <span class="ivalue">{{ $intervention->intervention_number }}</span>
                    </div>
                    <div class="irow">
                        <span class="ilabel">Statut</span>
                        <span class="ivalue">
                            <span class="s-pill s-{{ $intervention->status }}" style="font-size:.5625rem;padding:.1875rem .5rem;">
                                <span class="s-dot"></span>{{ $intervention->status_label }}
                            </span>
                        </span>
                    </div>
                    <div class="irow">
                        <span class="ilabel">Créée le</span>
                        <span class="ivalue">{{ $intervention->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($intervention->technician)
                    <div class="irow">
                        <span class="ilabel">Technicien</span>
                        <span class="ivalue">{{ $intervention->technician->name }}</span>
                    </div>
                    @endif
                    @if($intervention->priority)
                    <div class="irow">
                        <span class="ilabel">Priorité</span>
                        <span class="ivalue">{{ ucfirst($intervention->priority) }}</span>
                    </div>
                    @endif
                    @if($intervention->evolution_level)
                    <div class="irow">
                        <span class="ilabel">Niveau</span>
                        <span class="ivalue">N{{ $intervention->evolution_level }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($isAssignedTechnician && !$isPrivileged)
            <div class="scard">
                <div class="sh">
                    <i class="fas fa-circle-info" style="color:#3b82f6;font-size:.6875rem;"></i>
                    <span>À noter</span>
                </div>
                <div class="sb" style="font-size:.75rem;color:var(--text-secondary);line-height:1.6;">
                    Vous êtes le technicien assigné. Vous pouvez uniquement modifier :
                    <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                        <li>Description du problème</li>
                        <li>Solution apportée</li>
                        <li>Notes internes</li>
                    </ul>
                    Pour modifier les autres champs, contactez un administrateur.
                </div>
            </div>
            @endif

        </aside>

    </div>{{-- /edit-layout --}}
</form>
@endif

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const form      = document.getElementById('interventionForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) { e.preventDefault(); form.reportValidity(); return; }
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement…';
        }
    });

    const sd = document.getElementById('start_date');
    const ed = document.getElementById('end_date');
    if (sd && ed) {
        const checkDates = () => {
            ed.setCustomValidity(
                sd.value && ed.value && ed.value < sd.value
                    ? 'La date de fin doit être postérieure à la date de début.'
                    : ''
            );
        };
        ed.addEventListener('change', checkDates);
        sd.addEventListener('change', checkDates);
    }

    let dirty = false;
    form.addEventListener('input',  () => dirty = true);
    form.addEventListener('change', () => dirty = true);
    form.addEventListener('submit', () => dirty = false);
    window.addEventListener('beforeunload', e => {
        if (dirty) { e.preventDefault(); e.returnValue = ''; }
    });

})();
</script>
@endpush
