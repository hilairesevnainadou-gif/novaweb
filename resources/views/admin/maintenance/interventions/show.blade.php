{{-- resources/views/admin/maintenance/interventions/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Intervention ' . $intervention->intervention_number . ' · NovaTech Admin')
@section('page-title', 'Détail de l\'intervention')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════
   VUE SHOW – INTERVENTION  |  Design System NovaTech
══════════════════════════════════════════════════════ */

/* ── Breadcrumb ──────────────────────────────────────── */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--text-tertiary);
    margin-bottom: 1.5rem;
}
.breadcrumb a {
    color: var(--text-tertiary);
    transition: color var(--transition-fast);
}
.breadcrumb a:hover { color: var(--brand-primary); }
.breadcrumb .sep { opacity: 0.4; font-size: 0.625rem; }

/* ── Hero Header ─────────────────────────────────────── */
.hero-header {
    position: relative;
    background: var(--bg-secondary);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-light);
    padding: 2rem;
    margin-bottom: 1.5rem;
    overflow: hidden;
}
.hero-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary), var(--brand-accent));
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}
.hero-header::after {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.07) 0%, transparent 70%);
    pointer-events: none;
}

.hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.hero-identity {}

.hero-number {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--brand-primary);
    background: rgba(59, 130, 246, 0.1);
    padding: 0.25rem 0.625rem;
    border-radius: var(--radius-full);
    margin-bottom: 0.75rem;
}

.hero-title {
    font-size: 1.625rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 0.75rem;
    line-height: 1.2;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    font-size: 0.8125rem;
    color: var(--text-secondary);
}
.hero-meta span {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}
.hero-meta i { color: var(--text-tertiary); font-size: 0.75rem; }

/* Role badge strip */
.role-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}
.role-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.6875rem;
    font-weight: 600;
}
.role-pill-admin {
    background: rgba(59, 130, 246, 0.1);
    color: var(--brand-primary);
    border: 1px solid rgba(59, 130, 246, 0.2);
}
.role-pill-tech {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Action buttons zone */
.hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.75rem;
}

/* ── Status Workflow Buttons ─────────────────────────── */
.status-flow {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.btn-flow {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all var(--transition-fast);
}
.btn-flow:hover { transform: translateY(-1px); filter: brightness(1.1); }
.btn-flow:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

.btn-flow-pending    { background: rgba(245,158,11,0.12);  color:#f59e0b; border-color: rgba(245,158,11,0.25); }
.btn-flow-approved   { background: rgba(59,130,246,0.12);  color:#3b82f6; border-color: rgba(59,130,246,0.25); }
.btn-flow-in_progress{ background: rgba(139,92,246,0.12);  color:#8b5cf6; border-color: rgba(139,92,246,0.25); }
.btn-flow-completed  { background: rgba(16,185,129,0.12);  color:#10b981; border-color: rgba(16,185,129,0.25); }
.btn-flow-cancelled  { background: rgba(107,114,128,0.12); color:#6b7280; border-color: rgba(107,114,128,0.25); }

/* ── KPI Stats Strip ─────────────────────────────────── */
.kpi-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.kpi-card {
    background: var(--bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
    padding: 1.25rem 1.5rem;
    position: relative;
    overflow: hidden;
    transition: border-color var(--transition-fast), transform var(--transition-fast);
}
.kpi-card:hover {
    border-color: var(--border-heavy);
    transform: translateY(-2px);
}
.kpi-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--kpi-color, var(--brand-primary)), transparent);
}
.kpi-card[data-color="blue"]   { --kpi-color: #3b82f6; }
.kpi-card[data-color="green"]  { --kpi-color: #10b981; }
.kpi-card[data-color="purple"] { --kpi-color: #8b5cf6; }
.kpi-card[data-color="amber"]  { --kpi-color: #f59e0b; }

.kpi-icon {
    width: 2rem; height: 2rem;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
    background: rgba(var(--kpi-rgb, 59,130,246), 0.1);
    color: var(--kpi-color, var(--brand-primary));
}
.kpi-card[data-color="blue"]   .kpi-icon { background: rgba(59,130,246,0.1); }
.kpi-card[data-color="green"]  .kpi-icon { background: rgba(16,185,129,0.1); color:#10b981; }
.kpi-card[data-color="purple"] .kpi-icon { background: rgba(139,92,246,0.1); color:#8b5cf6; }
.kpi-card[data-color="amber"]  .kpi-icon { background: rgba(245,158,11,0.1);  color:#f59e0b; }

.kpi-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.375rem;
}
.kpi-label {
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-tertiary);
}

/* ── Section Cards ───────────────────────────────────── */
.section-card {
    background: var(--bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
    margin-bottom: 1.25rem;
    overflow: hidden;
    transition: border-color var(--transition-fast);
}
.section-card:hover { border-color: var(--border-medium); }

.section-header {
    padding: 1rem 1.5rem;
    background: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.section-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.section-title .icon-dot {
    width: 0.5rem; height: 0.5rem;
    border-radius: 50%;
    background: var(--brand-primary);
    flex-shrink: 0;
}
.section-title i { color: var(--brand-primary); font-size: 0.875rem; }

.section-body { padding: 1.5rem; }

/* ── Info Grid ───────────────────────────────────────── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
.info-grid-3 { grid-template-columns: repeat(3, 1fr); }

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}
.info-label {
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-tertiary);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}
.info-label i { font-size: 0.625rem; }
.info-value {
    font-size: 0.875rem;
    color: var(--text-primary);
    font-weight: 500;
}

/* Text blocks (problem, solution, notes) */
.text-block {
    background: var(--bg-tertiary);
    border-left: 3px solid var(--brand-primary);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    padding: 0.875rem 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-top: 0.375rem;
}
.text-block-success { border-color: #10b981; }
.text-block-warning { border-color: #f59e0b; }
.text-block-info    { border-color: var(--brand-accent); }

/* ── Badges ──────────────────────────────────────────── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.3rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: var(--radius-full);
}
.badge-pending    { background: rgba(245,158,11,0.12);  color:#f59e0b; }
.badge-approved   { background: rgba(59,130,246,0.12);  color:#3b82f6; }
.badge-in_progress{ background: rgba(139,92,246,0.12);  color:#8b5cf6; }
.badge-completed  { background: rgba(16,185,129,0.12);  color:#10b981; }
.badge-cancelled  { background: rgba(107,114,128,0.12); color:#6b7280; }
.badge-inprogress { background: rgba(139,92,246,0.12);  color:#8b5cf6; }
.badge-low        { background: rgba(107,114,128,0.12); color:#6b7280; }
.badge-medium     { background: rgba(59,130,246,0.12);  color:#3b82f6; }
.badge-high       { background: rgba(245,158,11,0.12);  color:#f59e0b; }
.badge-urgent     { background: rgba(239,68,68,0.12);   color:#ef4444; }
.badge-critical   { background: rgba(139,92,246,0.12);  color:#8b5cf6; }

/* ── Client Card ─────────────────────────────────────── */
.client-card {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 1.25rem;
    background: var(--bg-tertiary);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
}
.client-avatar {
    width: 3rem; height: 3rem;
    border-radius: var(--radius-lg);
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.125rem;
    color: white;
    font-weight: 700;
    flex-shrink: 0;
}
.client-details { display: flex; flex-direction: column; gap: 0.25rem; }
.client-name { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
.client-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.25rem;
}
.client-meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    color: var(--text-secondary);
}
.client-meta-item i { font-size: 0.6875rem; color: var(--text-tertiary); }

/* ── Timeline ────────────────────────────────────────── */
.timeline { position: relative; }

.timeline-item {
    position: relative;
    padding-left: 2.5rem;
    padding-bottom: 1.75rem;
}
.timeline-item:last-child { padding-bottom: 0; }

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0.625rem;
    top: 1.25rem;
    bottom: 0;
    width: 1px;
    background: var(--border-medium);
}
.timeline-item:last-child::before { display: none; }

.timeline-dot {
    position: absolute;
    left: 0;
    top: 0.1875rem;
    width: 1.25rem; height: 1.25rem;
    border-radius: 50%;
    background: var(--brand-primary);
    border: 2px solid var(--bg-secondary);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.5rem;
    color: white;
    z-index: 1;
}

.timeline-time {
    font-size: 0.6875rem;
    color: var(--text-tertiary);
    font-weight: 500;
    margin-bottom: 0.25rem;
}
.timeline-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}
.timeline-sub {
    font-size: 0.8125rem;
    color: var(--text-secondary);
}
.timeline-extra {
    font-size: 0.8125rem;
    color: var(--text-tertiary);
    margin-top: 0.25rem;
    font-style: italic;
}

/* ── Data Table ──────────────────────────────────────── */
.table-wrap { overflow-x: auto; }

.data-table {
    width: 100%;
    border-collapse: collapse;
}
.data-table thead tr {
    background: var(--bg-tertiary);
    border-bottom: 1px solid var(--border-medium);
}
.data-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-tertiary);
    white-space: nowrap;
}
.data-table td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.875rem;
    color: var(--text-primary);
    vertical-align: middle;
}
.data-table tbody tr { transition: background var(--transition-fast); }
.data-table tbody tr:hover { background: var(--bg-hover); }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tfoot td {
    padding: 0.875rem 1rem;
    font-weight: 700;
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-top: 1px solid var(--border-medium);
}

/* ── Action Buttons ──────────────────────────────────── */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5625rem 1.125rem;
    background: var(--brand-primary);
    color: white;
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}
.btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.35); }

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 1px solid var(--border-medium);
    padding: 0.5rem 0.875rem;
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}
.btn-secondary:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--brand-primary); }

.btn-sm { padding: 0.3125rem 0.625rem; font-size: 0.75rem; }

.btn-danger-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.625rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--brand-error);
    background: rgba(239,68,68,0.06);
    border: 1px solid rgba(239,68,68,0.15);
    cursor: pointer;
    transition: all var(--transition-fast);
}
.btn-danger-ghost:hover { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); }

/* ── Access Denied ───────────────────────────────────── */
.access-denied {
    text-align: center;
    padding: 3rem 1.5rem;
}
.access-denied-icon {
    width: 4rem; height: 4rem;
    border-radius: var(--radius-xl);
    background: var(--bg-tertiary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    color: var(--text-tertiary);
    margin: 0 auto 1.25rem;
}
.access-denied h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.5rem; }
.access-denied p { font-size: 0.875rem; color: var(--text-secondary); max-width: 360px; margin: 0 auto 1.5rem; line-height: 1.6; }

/* ── Empty State ─────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 2.5rem 1.5rem;
    color: var(--text-tertiary);
}
.empty-state-icon {
    width: 3rem; height: 3rem;
    border-radius: var(--radius-lg);
    background: var(--bg-tertiary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.125rem;
    margin: 0 auto 0.75rem;
}
.empty-state p { font-size: 0.875rem; margin: 0; }

/* ── Modals ──────────────────────────────────────────── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: var(--z-modal);
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-slow);
}
.modal-overlay.active { opacity: 1; visibility: visible; }

.modal {
    background: var(--bg-elevated);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-medium);
    width: 92%;
    max-width: 500px;
    transform: translateY(12px) scale(0.98);
    transition: transform var(--transition-slow);
    box-shadow: var(--shadow-2xl);
}
.modal-overlay.active .modal { transform: translateY(0) scale(1); }

.modal-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-header h3 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-header h3 i { color: var(--brand-primary); }

.modal-close {
    width: 2rem; height: 2rem;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    background: none;
    border: none;
    color: var(--text-tertiary);
    cursor: pointer;
    transition: all var(--transition-fast);
    font-size: 1rem;
}
.modal-close:hover { background: var(--bg-hover); color: var(--text-primary); }

.modal-body   { padding: 1.5rem; }
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    background: var(--bg-tertiary);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
}

/* ── Forms ───────────────────────────────────────────── */
.form-group   { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; }
.form-group:last-child { margin-bottom: 0; }
.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
}
.required { color: var(--brand-error); margin-left: 0.125rem; }

.form-control {
    width: 100%;
    padding: 0.5625rem 0.875rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-medium);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.875rem;
    font-family: inherit;
    outline: none;
    transition: all var(--transition-fast);
}
.form-control:focus {
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
textarea.form-control { resize: vertical; min-height: 80px; }

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 1024px) {
    .kpi-strip { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .kpi-strip { grid-template-columns: repeat(2, 1fr); }
    .info-grid { grid-template-columns: 1fr; }
    .info-grid-3 { grid-template-columns: 1fr; }
    .hero-top { flex-direction: column; }
    .hero-actions { align-items: flex-start; width: 100%; }
    .client-card { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════════════════════
     PERMISSIONS & RÔLES
     ════════════════════════════════════════════════════════════ --}}
@php
    $user      = auth()->user();
    $isSuperAdmin = $user->hasRole('super-admin');
    $isAdmin      = $user->hasRole('admin');
    $isSupport    = $user->hasRole('support');
    $isTechnician = $user->hasRole('technician');

    $isAdminRole          = $isSuperAdmin || $isAdmin || $isSupport;
    $isAssignedTechnician = $isTechnician && ($intervention->technician_id == $user->id);
    $canTechnicianModify  = $isTechnician && $isAssignedTechnician;

    $canApprove    = $isAdminRole && $intervention->status === 'pending';
    $canStart      = ($isAdminRole || $canTechnicianModify) && $intervention->status === 'approved';
    $canComplete   = ($isAdminRole || $canTechnicianModify) && $intervention->status === 'in_progress';
    $canCancel     = $isAdminRole && !in_array($intervention->status, ['completed', 'cancelled']);
    $canPutPending = $isAdminRole && $intervention->status === 'approved';

    $canAddExpense          = $isAdminRole;
    $canEdit                = $isAdminRole;
    $canSeeCosts            = $isAdminRole;
    $canSeeTechnicianInfo   = $isAdminRole || $canTechnicianModify;
    $canSeeEvolutionHistory = $isAdminRole || $canTechnicianModify;
    $showClientDetails      = $isAdminRole || $canTechnicianModify;
    $showActionButtons      = $isAdminRole || $canTechnicianModify;
@endphp

{{-- ── Fil d'Ariane ─────────────────────────────────────────── --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.maintenance.interventions.index') }}">
        <i class="fas fa-tools"></i> Interventions
    </a>
    <i class="fas fa-chevron-right sep"></i>
    <span>{{ $intervention->intervention_number }}</span>
</nav>

{{-- ════════════════════════════════════════════════════════════
     HERO HEADER
     ════════════════════════════════════════════════════════════ --}}
<div class="hero-header">
    <div class="hero-top">

        {{-- Identité de l'intervention --}}
        <div class="hero-identity">
            <div class="hero-number">
                <i class="fas fa-hashtag"></i>
                {{ $intervention->intervention_number }}
            </div>

            <h1 class="hero-title">
                {{ $intervention->title }}
                <span class="badge badge-{{ str_replace('_', '', $intervention->status) }}">
                    <i class="fas
                        @switch($intervention->status)
                            @case('pending')     fa-clock         @break
                            @case('approved')    fa-check-circle  @break
                            @case('in_progress') fa-spinner fa-pulse @break
                            @case('completed')   fa-check-double  @break
                            @case('cancelled')   fa-times-circle  @break
                        @endswitch
                    "></i>
                    {{ $intervention->status_label }}
                </span>
            </h1>

            <div class="hero-meta">
                <span>
                    <i class="fas fa-calendar-plus"></i>
                    Créée le {{ $intervention->created_at->format('d/m/Y à H:i') }}
                </span>
                @if($canSeeTechnicianInfo && $intervention->technician)
                <span>
                    <i class="fas fa-user-cog"></i>
                    {{ $intervention->technician->name }}
                </span>
                @endif
                <span>
                    <i class="fas fa-building"></i>
                    {{ $intervention->client->name ?? 'N/A' }}
                </span>
            </div>

            <div class="role-strip">
                @if($isAdminRole)
                    <span class="role-pill role-pill-admin">
                        <i class="fas fa-shield-alt"></i> Accès administrateur complet
                    </span>
                @endif
                @if($canTechnicianModify && !$isAdminRole)
                    <span class="role-pill role-pill-tech">
                        <i class="fas fa-check-circle"></i> Technicien assigné
                    </span>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="hero-actions">
            @if($canEdit)
            <a href="{{ route('admin.maintenance.interventions.edit', $intervention) }}"
               class="btn-secondary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endif

            @if($showActionButtons)
            <div class="status-flow">
                @if($canPutPending)
                <button class="btn-flow btn-flow-pending" onclick="openStatusModal('pending')">
                    <i class="fas fa-clock"></i> En attente
                </button>
                @endif
                @if($canApprove)
                <button class="btn-flow btn-flow-approved" onclick="openStatusModal('approved')">
                    <i class="fas fa-check-circle"></i> Approuver
                </button>
                @endif
                @if($canStart)
                <button class="btn-flow btn-flow-in_progress" onclick="openStatusModal('in_progress')">
                    <i class="fas fa-play-circle"></i> Démarrer
                </button>
                @endif
                @if($canComplete)
                <button class="btn-flow btn-flow-completed" onclick="openStatusModal('completed')">
                    <i class="fas fa-check-double"></i> Terminer
                </button>
                @endif
                @if($canCancel)
                <button class="btn-flow btn-flow-cancelled" onclick="openStatusModal('cancelled')">
                    <i class="fas fa-times-circle"></i> Annuler
                </button>
                @endif
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     KPI STRIP (admin uniquement)
     ════════════════════════════════════════════════════════════ --}}
@if($canSeeCosts)
<div class="kpi-strip">

    <div class="kpi-card" data-color="blue">
        <div class="kpi-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="kpi-value">{{ number_format($intervention->estimated_cost, 0, ',', ' ') }}</div>
        <div class="kpi-label">Coût estimé (FCFA)</div>
    </div>

    <div class="kpi-card" data-color="green">
        <div class="kpi-icon"><i class="fas fa-coins"></i></div>
        <div class="kpi-value">{{ number_format($intervention->actual_cost, 0, ',', ' ') }}</div>
        <div class="kpi-label">Coût réel (FCFA)</div>
    </div>

    <div class="kpi-card" data-color="purple">
        <div class="kpi-icon"><i class="fas fa-stopwatch"></i></div>
        <div class="kpi-value">
            @if($intervention->duration_minutes)
                {{ floor($intervention->duration_minutes / 60) }}h {{ $intervention->duration_minutes % 60 }}min
            @else
                —
            @endif
        </div>
        <div class="kpi-label">Durée d'intervention</div>
    </div>

    <div class="kpi-card" data-color="amber">
        <div class="kpi-icon"><i class="fas fa-star"></i></div>
        <div class="kpi-value">
            @if($intervention->client_rated)
                {{ $intervention->client_rating }}<small style="font-size:0.875rem;font-weight:400;">/5</small>
            @else
                —
            @endif
        </div>
        <div class="kpi-label">Satisfaction client</div>
    </div>

</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     SECTION – Détails de l'intervention
     ════════════════════════════════════════════════════════════ --}}
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-clipboard-list"></i>
            Détails de l'intervention
        </div>
        @if($canEdit)
        <a href="{{ route('admin.maintenance.interventions.edit', $intervention) }}"
           class="btn-secondary btn-sm">
            <i class="fas fa-edit"></i> Modifier
        </a>
        @endif
    </div>

    <div class="section-body">
        <div class="info-grid">

            {{-- Appareil --}}
            <div class="info-item">
                <span class="info-label"><i class="fas fa-microchip"></i> Appareil</span>
                <span class="info-value">
                    <strong>{{ $intervention->device->name ?? 'N/A' }}</strong>
                    <br>
                    <span style="font-size:0.75rem;color:var(--text-tertiary);">
                        {{ $intervention->device->brand ?? '' }} {{ $intervention->device->model ?? '' }}
                        @if($intervention->device->serial_number)
                            &nbsp;·&nbsp; S/N : {{ $intervention->device->serial_number }}
                        @endif
                    </span>
                </span>
            </div>

            {{-- Client --}}
            <div class="info-item">
                <span class="info-label"><i class="fas fa-building"></i> Client</span>
                <span class="info-value">
                    <strong>{{ $intervention->client->name ?? 'N/A' }}</strong>
                    @if($intervention->client?->phone && $canSeeTechnicianInfo)
                    <br>
                    <span style="font-size:0.75rem;color:var(--text-tertiary);">
                        <i class="fas fa-phone"></i> {{ $intervention->client->phone }}
                    </span>
                    @endif
                </span>
            </div>

            {{-- Priorité --}}
            <div class="info-item">
                <span class="info-label"><i class="fas fa-flag"></i> Priorité</span>
                <span class="info-value">
                    <span class="badge badge-{{ $intervention->priority }}">
                        <i class="fas fa-circle" style="font-size:0.5rem;"></i>
                        {{ $intervention->priority_label }}
                    </span>
                </span>
            </div>

            {{-- Niveau d'évolution --}}
            <div class="info-item">
                <span class="info-label"><i class="fas fa-layer-group"></i> Niveau d'évolution</span>
                <span class="info-value">
                    <span class="badge" style="background:rgba(139,92,246,0.12);color:#8b5cf6;">
                        <i class="fas fa-level-up-alt" style="font-size:0.625rem;"></i>
                        {{ $intervention->evolution_level_label }}
                    </span>
                </span>
            </div>

            {{-- Type de problème --}}
            @if($canSeeTechnicianInfo)
            <div class="info-item">
                <span class="info-label"><i class="fas fa-bug"></i> Type de problème</span>
                <span class="info-value">{{ $intervention->problem_type_label }}</span>
            </div>
            @endif

            {{-- Dates --}}
            <div class="info-item">
                <span class="info-label"><i class="fas fa-calendar-alt"></i> Dates</span>
                <span class="info-value" style="display:flex;flex-direction:column;gap:0.25rem;">
                    @if($intervention->scheduled_date)
                    <span style="font-size:0.8125rem;">
                        <i class="fas fa-calendar-day" style="color:var(--text-tertiary);width:1rem;"></i>
                        Planifiée : {{ $intervention->scheduled_date->format('d/m/Y H:i') }}
                    </span>
                    @endif
                    @if($intervention->start_date)
                    <span style="font-size:0.8125rem;">
                        <i class="fas fa-play" style="color:#10b981;width:1rem;"></i>
                        Début : {{ $intervention->start_date->format('d/m/Y H:i') }}
                    </span>
                    @endif
                    @if($intervention->end_date)
                    <span style="font-size:0.8125rem;">
                        <i class="fas fa-stop" style="color:#ef4444;width:1rem;"></i>
                        Fin : {{ $intervention->end_date->format('d/m/Y H:i') }}
                    </span>
                    @endif
                </span>
            </div>

        </div>

        {{-- Description du problème --}}
        @if($intervention->problem_description)
        <div class="info-item" style="margin-top:1.25rem;">
            <span class="info-label"><i class="fas fa-exclamation-triangle"></i> Description du problème</span>
            <div class="text-block text-block-warning">{{ $intervention->problem_description }}</div>
        </div>
        @endif

        {{-- Solution apportée --}}
        @if($intervention->solution)
        <div class="info-item" style="margin-top:1rem;">
            <span class="info-label"><i class="fas fa-check-circle"></i> Solution apportée</span>
            <div class="text-block text-block-success">{{ $intervention->solution }}</div>
        </div>
        @endif

        {{-- Notes internes --}}
        @if($intervention->notes && $canSeeTechnicianInfo)
        <div class="info-item" style="margin-top:1rem;">
            <span class="info-label"><i class="fas fa-sticky-note"></i> Notes internes</span>
            <div class="text-block text-block-info">{{ $intervention->notes }}</div>
        </div>
        @endif

    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     SECTION – Informations de contact client
     ════════════════════════════════════════════════════════════ --}}
@if($showClientDetails)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-address-card"></i> Contact client
        </div>
    </div>
    <div class="section-body">
        <div class="client-card">
            <div class="client-avatar">
                {{ mb_strtoupper(mb_substr($intervention->client->name ?? 'C', 0, 1)) }}
            </div>
            <div class="client-details">
                <div class="client-name">{{ $intervention->client->name ?? 'N/A' }}</div>
                <div class="client-meta-row">
                    @if($intervention->client?->phone)
                    <span class="client-meta-item">
                        <i class="fas fa-phone"></i> {{ $intervention->client->phone }}
                    </span>
                    @endif
                    @if($intervention->client?->email)
                    <span class="client-meta-item">
                        <i class="fas fa-envelope"></i> {{ $intervention->client->email }}
                    </span>
                    @endif
                    @if($intervention->client?->address)
                    <span class="client-meta-item">
                        <i class="fas fa-map-marker-alt"></i> {{ $intervention->client->address }}
                    </span>
                    @endif
                    @if($intervention->client?->city)
                    <span class="client-meta-item">
                        <i class="fas fa-city"></i>
                        {{ $intervention->client->city }}{{ $intervention->client->country ? ', ' . $intervention->client->country : '' }}
                    </span>
                    @endif
                    @if($intervention->client?->contact_name)
                    <span class="client-meta-item">
                        <i class="fas fa-user-tie"></i>
                        {{ $intervention->client->contact_name }}
                        @if($intervention->client->contact_position)
                            &nbsp;·&nbsp; {{ $intervention->client->contact_position }}
                        @endif
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     SECTION – Dépenses (admin/support uniquement)
     ════════════════════════════════════════════════════════════ --}}
@if($canAddExpense)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-receipt"></i> Dépenses
        </div>
        <button class="btn-primary btn-sm" id="openExpenseModalBtn">
            <i class="fas fa-plus"></i> Ajouter une dépense
        </button>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qté</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                    <th>Référence</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($intervention->expenses as $expense)
                <tr>
                    <td><strong>{{ $expense->description }}</strong></td>
                    <td>{{ $expense->quantity }}</td>
                    <td>{{ number_format($expense->amount, 0, ',', ' ') }} FCFA</td>
                    <td>
                        <span style="font-weight:700;color:var(--brand-primary);">
                            {{ number_format($expense->total, 0, ',', ' ') }} FCFA
                        </span>
                    </td>
                    <td style="color:var(--text-secondary);">{{ $expense->reference ?? '—' }}</td>
                    <td style="text-align:center;">
                        <form action="{{ route('admin.maintenance.interventions.expenses.destroy', ['intervention' => $intervention, 'expense' => $expense]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn-danger-ghost"
                                    onclick="return confirm('Supprimer cette dépense ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-receipt"></i></div>
                            <p>Aucune dépense enregistrée</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($intervention->expenses->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;">Total dépenses</td>
                    <td colspan="2" style="color:var(--brand-primary);">
                        {{ number_format($intervention->expenses->sum('total'), 0, ',', ' ') }} FCFA
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     SECTION – Historique des évolutions
     ════════════════════════════════════════════════════════════ --}}
@if($canSeeEvolutionHistory && $intervention->evolutionHistory->count() > 0)
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-chart-line"></i> Historique des niveaux d'évolution
        </div>
    </div>
    <div class="section-body">
        <div class="timeline">
            @foreach($intervention->evolutionHistory as $evolution)
            <div class="timeline-item">
                <div class="timeline-dot"><i class="fas fa-circle" style="font-size:0.375rem;"></i></div>
                <div class="timeline-time">{{ $evolution->created_at->format('d/m/Y à H:i') }}</div>
                <div class="timeline-label">{{ $evolution->level_change_label }}</div>
                <div class="timeline-sub">
                    Par <strong>{{ $evolution->user->name }}</strong>
                    @if($canSeeCosts && $evolution->additional_cost > 0)
                    &nbsp;·&nbsp;
                    <span style="color:#f59e0b;">
                        +{{ number_format($evolution->additional_cost, 0, ',', ' ') }} FCFA
                    </span>
                    @endif
                </div>
                @if($evolution->reason)
                <div class="timeline-extra">{{ $evolution->reason }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     SECTION – Accès limité (technicien non assigné)
     ════════════════════════════════════════════════════════════ --}}
@if($isTechnician && !$isAssignedTechnician && !$isAdminRole)
<div class="section-card">
    <div class="section-body">
        <div class="access-denied">
            <div class="access-denied-icon"><i class="fas fa-lock"></i></div>
            <h3>Accès limité</h3>
            <p>Vous n'êtes pas le technicien assigné à cette intervention. Seul le technicien assigné peut consulter les détails complets.</p>
            <a href="{{ route('admin.maintenance.interventions.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     MODAL – Ajouter une dépense
     ════════════════════════════════════════════════════════════ --}}
@if($canAddExpense)
<div id="expenseModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-receipt"></i> Ajouter une dépense</h3>
            <button type="button" class="modal-close" id="closeExpenseModalBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="expenseForm"
              method="POST"
              action="{{ route('admin.maintenance.interventions.expenses.store', $intervention) }}"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Description <span class="required">*</span></label>
                    <input type="text" name="description" class="form-control" placeholder="Ex : Pièce de rechange, déplacement…" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Montant unitaire <span class="required">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Référence</label>
                    <input type="text" name="reference" class="form-control" placeholder="N° facture, bon de commande…">
                </div>
                <div class="form-group">
                    <label class="form-label">Justificatif</label>
                    <input type="file" name="invoice_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelExpenseModalBtn">Annuler</button>
                <button type="submit" class="btn-primary" id="confirmExpenseBtn">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     MODAL – Changement de statut
     ════════════════════════════════════════════════════════════ --}}
@if($showActionButtons)
<div id="statusModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="statusModalTitle"><i class="fas fa-exchange-alt"></i> Changer le statut</h3>
            <button type="button" class="modal-close" id="closeStatusModalBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="statusForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Notes <span style="color:var(--text-tertiary);font-weight:400;">(optionnel)</span></label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Ajoutez un commentaire sur ce changement…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelStatusModalBtn">Annuler</button>
                <button type="submit" class="btn-primary" id="confirmStatusBtn">
                    <i class="fas fa-check"></i> Confirmer
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Helpers ──────────────────────────────────────────────── */
    function openModal(modal) {
        if (!modal) return;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(modal) {
        if (!modal) return;
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    function bindClose(modal, ...triggers) {
        triggers.forEach(btn => btn?.addEventListener('click', () => closeModal(modal)));
        modal?.addEventListener('click', e => { if (e.target === modal) closeModal(modal); });
    }
    function disableSubmit(btnId, label) {
        const btn = document.getElementById(btnId);
        if (btn) { btn.disabled = true; btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${label}`; }
    }

    /* ── Modal dépense ────────────────────────────────────────── */
    @if($canAddExpense)
    const expenseModal = document.getElementById('expenseModal');
    const expenseForm  = document.getElementById('expenseForm');

    document.getElementById('openExpenseModalBtn')?.addEventListener('click', () => openModal(expenseModal));
    bindClose(expenseModal,
        document.getElementById('closeExpenseModalBtn'),
        document.getElementById('cancelExpenseModalBtn')
    );
    expenseForm?.addEventListener('submit', () => disableSubmit('confirmExpenseBtn', 'Ajout…'));
    @endif

    /* ── Modal statut ─────────────────────────────────────────── */
    @if($showActionButtons)
    const statusModal = document.getElementById('statusModal');
    const statusForm  = document.getElementById('statusForm');

    const STATUS_LABELS = {
        pending:     'Mettre en attente',
        approved:    "Approuver l'intervention",
        in_progress: "Démarrer l'intervention",
        completed:   "Terminer l'intervention",
        cancelled:   "Annuler l'intervention",
    };

    window.openStatusModal = function (status) {
        const titleEl = document.getElementById('statusModalTitle');
        if (titleEl) titleEl.innerHTML = `<i class="fas fa-exchange-alt"></i> ${STATUS_LABELS[status] ?? 'Changer le statut'}`;

        if (statusForm) {
            statusForm.action = "{{ route('admin.maintenance.interventions.status', $intervention) }}";
            statusForm.querySelector('input[name="status"]')?.remove();
            const input = Object.assign(document.createElement('input'), {
                type: 'hidden', name: 'status', value: status,
            });
            statusForm.appendChild(input);
        }
        openModal(statusModal);
    };

    bindClose(statusModal,
        document.getElementById('closeStatusModalBtn'),
        document.getElementById('cancelStatusModalBtn')
    );
    statusForm?.addEventListener('submit', () => disableSubmit('confirmStatusBtn', 'Confirmation…'));
    @endif

    /* ── Echap pour fermer ────────────────────────────────────── */
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        @if($canAddExpense)  closeModal(document.getElementById('expenseModal')); @endif
        @if($showActionButtons) closeModal(document.getElementById('statusModal')); @endif
    });

})();
</script>
@endpush
