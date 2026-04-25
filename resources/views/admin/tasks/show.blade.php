{{-- resources/views/admin/tasks/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $task->title . ' · NovaTech Admin')
@section('page-title', $task->title)

@push('styles')
<style>
/* ══════════════════════════════════════════════
   LAYOUT
══════════════════════════════════════════════ */
.show-layout {
    display: grid;
    grid-template-columns: 1fr 288px;
    gap: 1.25rem;
    align-items: start;
}

/* ── Page header ── */
.page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.4rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-medium);
    color: var(--text-secondary);
    text-decoration: none;
    transition: all var(--transition-fast);
    flex-shrink: 0;
}

.back-btn:hover {
    background: var(--bg-hover);
    color: var(--brand-primary);
    border-color: rgba(59,130,246,.3);
}

.page-header-info { flex: 1; min-width: 0; }

.page-header-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.page-header-sub {
    font-size: 0.7rem;
    color: var(--text-tertiary);
    margin-top: 0.15rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* ── Cards ── */
.card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.18);
}

.card:last-child { margin-bottom: 0; }

.card-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.85rem 1.1rem;
    border-bottom: 1px solid var(--border-light);
    background: rgba(255,255,255,.02);
}

.card-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    flex-shrink: 0;
}

.icon-blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
.icon-purple { background: rgba(139,92,246,.15);  color: #a78bfa; }
.icon-green  { background: rgba(16,185,129,.15);  color: #34d399; }
.icon-amber  { background: rgba(245,158,11,.15);  color: #fbbf24; }
.icon-teal   { background: rgba(20,184,166,.15);  color: #2dd4bf; }
.icon-rose   { background: rgba(244,63,94,.15);   color: #fb7185; }
.icon-indigo { background: rgba(99,102,241,.15);  color: #818cf8; }

.card-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-primary);
    flex: 1;
}

.card-body { padding: 1.1rem; }

/* ── Badges ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    padding: 0.22rem 0.6rem;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: .03em;
    white-space: nowrap;
}

/* Status */
.badge-todo        { background: rgba(100,116,139,.18); color: #94a3b8; border: 1px solid rgba(100,116,139,.25); }
.badge-in_progress { background: rgba(59,130,246,.15);  color: #60a5fa; border: 1px solid rgba(59,130,246,.25); }
.badge-review      { background: rgba(245,158,11,.15);  color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
.badge-approved    { background: rgba(16,185,129,.15);  color: #34d399; border: 1px solid rgba(16,185,129,.25); }
.badge-rejected    { background: rgba(239,68,68,.15);   color: #f87171; border: 1px solid rgba(239,68,68,.25);  }
.badge-completed   { background: rgba(139,92,246,.15);  color: #a78bfa; border: 1px solid rgba(139,92,246,.25); }
.badge-cancelled   { background: rgba(107,114,128,.15); color: #9ca3af; border: 1px solid rgba(107,114,128,.25);}

/* Priority */
.badge-low    { background: rgba(16,185,129,.12);  color: #34d399; border: 1px solid rgba(16,185,129,.2); }
.badge-medium { background: rgba(59,130,246,.12);  color: #60a5fa; border: 1px solid rgba(59,130,246,.2); }
.badge-high   { background: rgba(245,158,11,.12);  color: #fbbf24; border: 1px solid rgba(245,158,11,.2); }
.badge-urgent { background: rgba(239,68,68,.12);   color: #f87171; border: 1px solid rgba(239,68,68,.2);  }

/* ── Action buttons ── */
.action-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 0.85rem 1.1rem;
    border-top: 1px solid var(--border-light);
    background: rgba(255,255,255,.012);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.38rem;
    padding: 0.48rem 0.9rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all var(--transition-fast);
    white-space: nowrap;
    font-family: inherit;
}

.btn-ghost  { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-medium); }
.btn-ghost:hover { background: var(--bg-tertiary); color: var(--text-primary); }

.btn-primary { background: var(--brand-primary); color: #fff; box-shadow: 0 2px 6px rgba(59,130,246,.3); }
.btn-primary:hover { background: #2563eb; transform: translateY(-1px); }

.btn-success { background: #059669; color: #fff; box-shadow: 0 2px 6px rgba(16,185,129,.3); }
.btn-success:hover { background: #047857; transform: translateY(-1px); }

.btn-warning { background: #d97706; color: #fff; box-shadow: 0 2px 6px rgba(245,158,11,.3); }
.btn-warning:hover { background: #b45309; transform: translateY(-1px); }

.btn-danger  { background: #dc2626; color: #fff; box-shadow: 0 2px 6px rgba(239,68,68,.3); }
.btn-danger:hover { background: #b91c1c; transform: translateY(-1px); }

.btn-sm { padding: 0.32rem 0.65rem; font-size: 0.68rem; }

.btn:disabled { opacity: .55; cursor: not-allowed; transform: none !important; }

/* ── Tabs ── */
.tabs-nav {
    display: flex;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    padding: 4px;
    gap: 2px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.18);
}

.tab-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.52rem 0.75rem;
    border-radius: 9px;
    border: none;
    background: transparent;
    color: var(--text-tertiary);
    font-size: 0.73rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-fast);
    font-family: inherit;
    white-space: nowrap;
}

.tab-btn:hover { color: var(--text-secondary); background: rgba(255,255,255,.04); }

.tab-btn.active {
    background: var(--bg-elevated);
    color: var(--text-primary);
    box-shadow: 0 1px 4px rgba(0,0,0,.3);
}

.tab-btn .tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 999px;
    background: var(--bg-tertiary);
    font-size: 0.6rem;
    font-weight: 700;
}

.tab-btn.active .tab-count { background: rgba(59,130,246,.2); color: #60a5fa; }

.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* ── Description ── */
.desc-text {
    font-size: 0.82rem;
    color: var(--text-secondary);
    line-height: 1.7;
    white-space: pre-wrap;
    word-break: break-word;
}

/* ── Progress bar ── */
.progress-wrap { display: flex; align-items: center; gap: 0.6rem; }
.progress-bar  { flex: 1; height: 6px; border-radius: 999px; background: var(--bg-elevated); overflow: hidden; }
.progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--brand-primary), #818cf8); transition: width .4s ease; }

/* ── Sidebar meta rows ── */
.meta-list { display: flex; flex-direction: column; gap: 0; }

.meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.55rem 0;
    border-bottom: 1px solid rgba(255,255,255,.04);
    gap: 0.5rem;
}

.meta-row:last-child { border-bottom: none; padding-bottom: 0; }
.meta-row:first-child { padding-top: 0; }

.meta-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--text-tertiary);
    flex-shrink: 0;
}

.meta-val {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    text-align: right;
}

.meta-val.muted { color: var(--text-tertiary); font-weight: 400; }
.meta-val.danger { color: #f87171; }
.meta-val.success { color: #34d399; }

/* ── Comment ── */
.comment-wrap { display: flex; flex-direction: column; gap: 0.75rem; }

.comment-item {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-light);
    border-radius: 10px;
    overflow: hidden;
}

.comment-head {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 0.9rem;
    border-bottom: 1px solid rgba(255,255,255,.04);
    background: rgba(255,255,255,.02);
}

.comment-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--brand-primary), #818cf8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.comment-author { font-size: 0.75rem; font-weight: 700; color: var(--text-primary); flex: 1; }
.comment-date   { font-size: 0.62rem; color: var(--text-tertiary); }

.comment-body { padding: 0.75rem 0.9rem; }
.comment-text { font-size: 0.78rem; color: var(--text-secondary); line-height: 1.6; white-space: pre-wrap; word-break: break-word; }

.comment-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    border-top: 1px solid rgba(255,255,255,.04);
    background: rgba(255,255,255,.012);
}

.comment-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    background: none;
    border: none;
    color: var(--text-tertiary);
    font-size: 0.68rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0.2rem 0.4rem;
    border-radius: 5px;
    font-family: inherit;
    transition: all var(--transition-fast);
}

.comment-btn:hover { color: var(--text-secondary); background: rgba(255,255,255,.06); }
.comment-btn.danger:hover { color: #f87171; background: rgba(239,68,68,.08); }

/* Replies */
.replies-wrap {
    border-top: 1px solid rgba(255,255,255,.04);
    background: rgba(0,0,0,.15);
}

.reply-item {
    display: flex;
    gap: 0.6rem;
    padding: 0.6rem 0.9rem;
    border-bottom: 1px solid rgba(255,255,255,.03);
}

.reply-item:last-child { border-bottom: none; }

.reply-avatar {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: linear-gradient(135deg, #059669, #2dd4bf);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.55rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    margin-top: 1px;
}

.reply-content { flex: 1; min-width: 0; }
.reply-author  { font-size: 0.68rem; font-weight: 700; color: var(--text-primary); }
.reply-date    { font-size: 0.6rem; color: var(--text-tertiary); margin-left: 0.4rem; }
.reply-text    { font-size: 0.75rem; color: var(--text-secondary); line-height: 1.5; margin-top: 0.2rem; white-space: pre-wrap; word-break: break-word; }

/* Reply form */
.reply-form-wrap {
    display: none;
    padding: 0.75rem 0.9rem;
    border-top: 1px solid rgba(255,255,255,.04);
    background: rgba(0,0,0,.1);
}

.reply-form-wrap.open { display: block; }

/* ── Comment / time form inputs ── */
.fi {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1px solid var(--border-medium);
    background: var(--bg-tertiary);
    color: var(--text-primary);
    font-size: 0.8rem;
    font-family: inherit;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    outline: none;
    width: 100%;
}

.fi:hover { border-color: var(--border-heavy); }
.fi:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
.fi::placeholder { color: var(--text-disabled); font-size: 0.75rem; }
textarea.fi { resize: vertical; min-height: 72px; line-height: 1.5; }

/* ── Time entries ── */
.time-entry-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
}

.time-entry-item:last-child { border-bottom: none; padding-bottom: 0; }
.time-entry-item:first-child { padding-top: 0; }

.time-entry-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--brand-primary);
    flex-shrink: 0;
    margin-top: 5px;
}

.time-entry-body { flex: 1; min-width: 0; }
.time-entry-main { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.time-entry-hours { font-size: 0.82rem; font-weight: 700; color: var(--text-primary); }
.time-entry-date  { font-size: 0.68rem; color: var(--text-tertiary); }
.time-entry-user  { font-size: 0.68rem; color: var(--text-tertiary); margin-left: auto; }
.time-entry-desc  { font-size: 0.72rem; color: var(--text-tertiary); margin-top: 0.18rem; }

/* ── Subtask items ── */
.subtask-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0;
    border-bottom: 1px solid var(--border-light);
}

.subtask-item:last-child { border-bottom: none; padding-bottom: 0; }
.subtask-item:first-child { padding-top: 0; }

.subtask-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.subtask-info { flex: 1; min-width: 0; }
.subtask-title { font-size: 0.78rem; font-weight: 600; color: var(--text-primary); text-decoration: none; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.subtask-title:hover { color: var(--brand-primary); }
.subtask-num { font-size: 0.62rem; color: var(--text-tertiary); font-family: monospace; }

/* ── Empty state ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2.5rem 1rem;
    color: var(--text-tertiary);
    gap: 0.6rem;
}

.empty-state i { font-size: 1.8rem; opacity: .3; }
.empty-state p { font-size: 0.78rem; }

/* ── Sidebar sticky ── */
.sidebar-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.18);
    position: sticky;
    top: 80px;
}

.sidebar-card:last-child { margin-bottom: 0; }

.sidebar-header {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-tertiary);
    background: rgba(255,255,255,.02);
}

.sidebar-body { padding: 0.85rem 1rem; }

/* ── Dot colors ── */
.dot-todo        { background: #94a3b8; }
.dot-in_progress { background: #60a5fa; }
.dot-review      { background: #fbbf24; }
.dot-approved    { background: #34d399; }
.dot-rejected    { background: #f87171; }
.dot-completed   { background: #a78bfa; }
.dot-cancelled   { background: #6b7280; }

/* ── Alert / notification in-page ── */
.alert-warning {
    display: flex;
    gap: 0.6rem;
    background: rgba(245,158,11,.07);
    border: 1px solid rgba(245,158,11,.2);
    border-radius: 9px;
    padding: 0.65rem 0.85rem;
    font-size: 0.73rem;
    color: #fbbf24;
    margin-bottom: 1rem;
    align-items: flex-start;
}

.alert-danger {
    display: flex;
    gap: 0.6rem;
    background: rgba(239,68,68,.07);
    border: 1px solid rgba(239,68,68,.2);
    border-radius: 9px;
    padding: 0.65rem 0.85rem;
    font-size: 0.73rem;
    color: #f87171;
    margin-bottom: 1rem;
    align-items: flex-start;
}

/* ══════════════════════════════════════════════
   MODALS
══════════════════════════════════════════════ */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9000;
    background: rgba(0,0,0,.6);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-overlay.open { display: flex; }

.modal-box {
    background: var(--bg-secondary);
    border: 1px solid var(--border-medium);
    border-radius: 16px;
    box-shadow: 0 24px 64px rgba(0,0,0,.6);
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    animation: modalIn .18s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(.95) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
}

.modal-head-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.modal-head h3 {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    flex: 1;
}

.modal-close-btn {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-light);
    color: var(--text-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.78rem;
    transition: all var(--transition-fast);
    flex-shrink: 0;
}

.modal-close-btn:hover { background: var(--bg-elevated); color: var(--text-primary); }

.modal-body {
    padding: 1.25rem;
    overflow-y: auto;
    flex: 1;
}

.modal-body p {
    font-size: 0.82rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.modal-field { display: flex; flex-direction: column; gap: 0.32rem; }

.modal-field label {
    font-size: 0.63rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-tertiary);
}

.modal-field label .req { color: #f87171; margin-left: .2rem; }

.modal-foot {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.6rem;
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--border-light);
    background: rgba(255,255,255,.015);
}

/* Modal color variants */
.modal-icon-success { background: rgba(16,185,129,.15);  color: #34d399; }
.modal-icon-warning { background: rgba(245,158,11,.15);  color: #fbbf24; }
.modal-icon-danger  { background: rgba(239,68,68,.15);   color: #f87171; }

/* ── Flash messages ── */
.flash-msg {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-size: 0.78rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.flash-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #34d399; }
.flash-error   { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.25);  color: #f87171; }

/* ── Responsive ── */
@media (max-width: 1050px) {
    .show-layout { grid-template-columns: 1fr; }
    .sidebar-card { position: static; }
}

@media (max-width: 640px) {
    .tabs-nav { flex-wrap: wrap; }
    .tab-btn  { flex: unset; width: calc(50% - 2px); }
}
</style>
@endpush

@section('content')

@php
    $user           = auth()->user();
    $canEdit        = $user->can('tasks.edit');
    $canDelete      = $user->can('tasks.delete');
    $canApprove     = $user->can('tasks.approve');
    $isAssignee     = $task->assigned_to == $user->id;
    $isCreator      = $task->created_by == $user->id;
    $isProjectMgr   = optional($task->project)->project_manager_id == $user->id;

    $canMarkComplete  = ($isAssignee || $isProjectMgr || $canEdit)
                        && $task->status === 'in_progress';

    $canReview        = ($isProjectMgr || $canApprove) && $task->status === 'review';

    $commentsCount    = $task->comments->where('parent_id', null)->count();
    $subtasksCount    = $task->subtasks->count();
    $timeCount        = $task->timeEntries->count();

    // Progression heures
    $progress = $task->estimated_hours > 0
        ? min(100, round(($task->actual_hours / $task->estimated_hours) * 100))
        : 0;
@endphp

{{-- Flash messages --}}
@if(session('success'))
<div class="flash-msg flash-success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash-msg flash-error"><i class="fas fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- ── Page header ── --}}
<div class="page-header">
    <a href="{{ route('admin.projects.tasks.index', $task->project) }}" class="back-btn" title="Retour aux tâches">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="page-header-info">
        <div class="page-header-title">{{ $task->title }}</div>
        <div class="page-header-sub">
            <span style="font-family:monospace;font-size:.68rem">{{ $task->task_number }}</span>
            <span style="opacity:.35">·</span>
            <span class="badge badge-{{ $task->status }}">
                <i class="fas fa-circle" style="font-size:.45rem"></i>
                {{ $task->status_label }}
            </span>
            <span class="badge badge-{{ $task->priority }}">
                {{ $task->priority_label }}
            </span>
            @if($task->is_overdue)
            <span class="badge" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25)">
                <i class="fas fa-clock"></i> En retard
            </span>
            @endif
        </div>
    </div>
    {{-- Quick action buttons in header --}}
    <div style="display:flex;gap:.4rem;flex-shrink:0;flex-wrap:wrap">
        @if($canEdit)
        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-ghost" style="font-size:.72rem;padding:.4rem .75rem">
            <i class="fas fa-pen"></i> Modifier
        </a>
        @endif
        @if($canDelete)
        <button class="btn btn-danger" style="font-size:.72rem;padding:.4rem .75rem" onclick="openModal('deleteModal')">
            <i class="fas fa-trash"></i>
        </button>
        @endif
    </div>
</div>

{{-- Rejected note --}}
@if($task->status === 'rejected' && $task->review_notes)
<div class="alert-danger" style="margin-bottom:1.1rem">
    <i class="fas fa-circle-xmark" style="flex-shrink:0;margin-top:1px"></i>
    <div>
        <strong>Tâche rejetée</strong> par {{ $task->reviewer?->name ?? '—' }}
        @if($task->reviewed_at) le {{ $task->reviewed_at->format('d/m/Y à H:i') }} @endif
        <br><span style="opacity:.8">{{ $task->review_notes }}</span>
    </div>
</div>
@endif

{{-- ── Main layout ── --}}
<div class="show-layout">

    {{-- ════════ MAIN COLUMN ════════ --}}
    <div>

        {{-- Description --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon icon-purple"><i class="fas fa-align-left"></i></div>
                <span class="card-title">Description</span>
            </div>
            <div class="card-body">
                @if($task->description)
                    <p class="desc-text">{{ $task->description }}</p>
                @else
                    <p style="font-size:.78rem;color:var(--text-disabled);font-style:italic">Aucune description fournie.</p>
                @endif

                @if($task->completion_notes)
                <div style="margin-top:1rem;padding:.75rem;border-radius:9px;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.18)">
                    <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#34d399;margin-bottom:.35rem">
                        <i class="fas fa-check-circle"></i> Notes de complétion
                    </div>
                    <p style="font-size:.78rem;color:var(--text-secondary);line-height:1.6;margin:0">{{ $task->completion_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Action bar --}}
            <div class="action-bar">
                @if($canMarkComplete)
                <button class="btn btn-warning" onclick="openModal('completeModal')">
                    <i class="fas fa-paper-plane"></i> Soumettre pour revue
                </button>
                @endif

                @if($canReview)
                <button class="btn btn-success" onclick="openModal('approveModal')">
                    <i class="fas fa-circle-check"></i> Approuver
                </button>
                <button class="btn btn-danger" onclick="openModal('rejectModal')">
                    <i class="fas fa-circle-xmark"></i> Rejeter
                </button>
                @endif

                @if(!$canMarkComplete && !$canReview)
                <span style="font-size:.72rem;color:var(--text-tertiary)">
                    <i class="fas fa-info-circle"></i>
                    @if($task->status === 'todo') Démarrez la tâche pour la soumettre.
                    @elseif($task->status === 'completed') Tâche terminée et approuvée.
                    @elseif($task->status === 'cancelled') Tâche annulée.
                    @elseif($task->status === 'approved') Tâche approuvée.
                    @else Aucune action disponible pour votre rôle.
                    @endif
                </span>
                @endif
            </div>
        </div>

        {{-- ── Tabs ── --}}
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="comments">
                <i class="fas fa-comments"></i> Commentaires
                <span class="tab-count">{{ $commentsCount }}</span>
            </button>
            <button class="tab-btn" data-tab="time">
                <i class="fas fa-clock"></i> Temps
                <span class="tab-count">{{ $timeCount }}</span>
            </button>
            <button class="tab-btn" data-tab="subtasks">
                <i class="fas fa-list-check"></i> Sous-tâches
                <span class="tab-count">{{ $subtasksCount }}</span>
            </button>
        </div>

        {{-- ──── TAB : Comments ──── --}}
        <div class="tab-pane active" id="tab-comments">

            {{-- Add comment (tasks.edit required) --}}
            @if($canEdit)
            <div class="card" style="margin-bottom:.85rem">
                <div class="card-header">
                    <div class="card-icon icon-blue"><i class="fas fa-pen-to-square"></i></div>
                    <span class="card-title">Ajouter un commentaire</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.comments.store', $task) }}" method="POST"
                          style="display:flex;flex-direction:column;gap:.65rem">
                        @csrf
                        <textarea name="comment" class="fi" rows="3"
                                  placeholder="Partagez une mise à jour, une question ou une information…" required></textarea>
                        <div style="display:flex;justify-content:flex-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane"></i> Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Comments list --}}
            <div class="card">
                <div class="card-body" style="padding:0">
                    @php $rootComments = $task->comments->where('parent_id', null); @endphp
                    @forelse($rootComments as $comment)
                    <div class="comment-item" style="border:none;border-bottom:1px solid var(--border-light);border-radius:0">
                        <div class="comment-head">
                            <div class="comment-avatar">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</div>
                            <span class="comment-author">{{ $comment->user->name }}</span>
                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-body">
                            <div class="comment-text">{{ $comment->comment }}</div>
                        </div>
                        <div class="comment-actions">
                            @if($canEdit)
                            <button class="comment-btn" onclick="toggleReplyForm({{ $comment->id }})">
                                <i class="fas fa-reply"></i> Répondre
                            </button>
                            @endif
                            @if($comment->user_id == $user->id || $canDelete)
                            <form action="{{ route('admin.tasks.comments.destroy', $comment) }}" method="POST"
                                  style="margin-left:auto"
                                  onsubmit="return confirm('Supprimer ce commentaire ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="comment-btn danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                            @endif
                        </div>

                        {{-- Reply form --}}
                        @if($canEdit)
                        <div id="reply-form-{{ $comment->id }}" class="reply-form-wrap">
                            <form action="{{ route('admin.tasks.comments.reply', [$task, $comment]) }}" method="POST"
                                  style="display:flex;flex-direction:column;gap:.5rem">
                                @csrf
                                <textarea name="comment" class="fi" rows="2"
                                          placeholder="Votre réponse…" required></textarea>
                                <div style="display:flex;gap:.4rem;justify-content:flex-end">
                                    <button type="button" class="btn btn-ghost btn-sm"
                                            onclick="toggleReplyForm({{ $comment->id }})">Annuler</button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-reply"></i> Répondre
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        {{-- Replies --}}
                        @if($comment->replies->isNotEmpty())
                        <div class="replies-wrap">
                            @foreach($comment->replies as $reply)
                            <div class="reply-item">
                                <div class="reply-avatar">{{ strtoupper(substr($reply->user->name, 0, 2)) }}</div>
                                <div class="reply-content">
                                    <span class="reply-author">{{ $reply->user->name }}</span>
                                    <span class="reply-date">{{ $reply->created_at->diffForHumans() }}</span>
                                    <div class="reply-text">{{ $reply->comment }}</div>
                                </div>
                                @if($reply->user_id == $user->id || $canDelete)
                                <form action="{{ route('admin.tasks.comments.destroy', $reply) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cette réponse ?')"
                                      style="flex-shrink:0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="comment-btn danger" style="margin-top:2px">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <p>Aucun commentaire pour l'instant</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ──── TAB : Time tracking ──── --}}
        <div class="tab-pane" id="tab-time">

            {{-- Add time entry (tasks.edit required) --}}
            @if($canEdit)
            <div class="card" style="margin-bottom:.85rem">
                <div class="card-header">
                    <div class="card-icon icon-teal"><i class="fas fa-stopwatch"></i></div>
                    <span class="card-title">Saisir du temps</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.time.store', $task) }}" method="POST">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.65rem">
                            <div style="display:flex;flex-direction:column;gap:.3rem">
                                <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Date <span style="color:#f87171">*</span></label>
                                <input type="date" name="date" class="fi" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.3rem">
                                <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Heures <span style="color:#f87171">*</span></label>
                                <input type="number" name="hours" class="fi" step="0.5" min="0.5" max="24" placeholder="0.5" required>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:.3rem;margin-bottom:.65rem">
                            <label style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-tertiary)">Description</label>
                            <textarea name="description" class="fi" rows="2"
                                      placeholder="Ce qui a été fait…"></textarea>
                        </div>
                        <div style="display:flex;justify-content:flex-end">
                            <button type="submit" class="btn btn-teal btn-sm" style="background:#0d9488;color:#fff;box-shadow:0 2px 6px rgba(20,184,166,.3)">
                                <i class="fas fa-plus"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Summary --}}
            <div class="card" style="margin-bottom:.85rem">
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.6rem">
                        <span style="font-size:.75rem;color:var(--text-secondary);font-weight:600">Total enregistré</span>
                        <span style="font-size:1rem;font-weight:800;color:var(--text-primary)">{{ number_format($totalTimeSpent, 1) }}h</span>
                    </div>
                    @if($task->estimated_hours > 0)
                    <div class="progress-wrap">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $progress }}%"></div>
                        </div>
                        <span style="font-size:.68rem;font-weight:700;color:var(--text-tertiary);width:36px;text-align:right;flex-shrink:0">{{ $progress }}%</span>
                    </div>
                    <div style="font-size:.65rem;color:var(--text-tertiary);margin-top:.35rem">
                        {{ number_format($task->estimated_hours, 1) }}h estimées ·
                        {{ number_format(max(0, $task->estimated_hours - $totalTimeSpent), 1) }}h restantes
                    </div>
                    @endif
                </div>
            </div>

            {{-- Time entries list --}}
            <div class="card">
                <div class="card-body">
                    @forelse($task->timeEntries as $entry)
                    <div class="time-entry-item">
                        <div class="time-entry-dot"></div>
                        <div class="time-entry-body">
                            <div class="time-entry-main">
                                <span class="time-entry-hours">{{ number_format($entry->hours, 1) }}h</span>
                                <span class="time-entry-date">{{ $entry->date->format('d/m/Y') }}</span>
                                <span class="time-entry-user">par {{ $entry->user->name }}</span>
                            </div>
                            @if($entry->description)
                            <div class="time-entry-desc">{{ $entry->description }}</div>
                            @endif
                        </div>
                        @if($entry->user_id == $user->id || $canDelete)
                        <form action="{{ route('admin.tasks.time.destroy', $entry) }}" method="POST"
                              onsubmit="return confirm('Supprimer cette entrée ?')"
                              style="flex-shrink:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="comment-btn danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="empty-state" style="padding:1.75rem 1rem">
                        <i class="fas fa-clock"></i>
                        <p>Aucune entrée de temps</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ──── TAB : Subtasks ──── --}}
        <div class="tab-pane" id="tab-subtasks">

            @if($canEdit)
            <div style="display:flex;justify-content:flex-end;margin-bottom:.75rem">
                <a href="{{ route('admin.projects.tasks.create', $task->project) }}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvelle sous-tâche
                </a>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @forelse($task->subtasks as $subtask)
                    <div class="subtask-item">
                        <div class="subtask-status-dot dot-{{ $subtask->status }}"></div>
                        <div class="subtask-info">
                            <a href="{{ route('admin.tasks.show', $subtask) }}" class="subtask-title">
                                {{ $subtask->title }}
                            </a>
                            <div class="subtask-num">{{ $subtask->task_number }}</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0">
                            <span class="badge badge-{{ $subtask->status }}" style="font-size:.58rem">
                                {{ $subtask->status_label }}
                            </span>
                            <span class="badge badge-{{ $subtask->priority }}" style="font-size:.58rem">
                                {{ $subtask->priority_label }}
                            </span>
                        </div>
                        @if($canEdit)
                        <a href="{{ route('admin.tasks.edit', $subtask) }}"
                           class="btn btn-ghost btn-sm" style="flex-shrink:0">
                            <i class="fas fa-pen"></i>
                        </a>
                        @endif
                    </div>
                    @empty
                    <div class="empty-state" style="padding:1.75rem 1rem">
                        <i class="fas fa-list-check"></i>
                        <p>Aucune sous-tâche</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>{{-- end main --}}

    {{-- ════════ SIDEBAR ════════ --}}
    <div>

        {{-- Détails --}}
        <div class="sidebar-card">
            <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Détails</div>
            <div class="sidebar-body">
                <div class="meta-list">
                    <div class="meta-row">
                        <span class="meta-label">Statut</span>
                        <span class="badge badge-{{ $task->status }}" style="font-size:.62rem">
                            {{ $task->status_label }}
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Priorité</span>
                        <span class="badge badge-{{ $task->priority }}" style="font-size:.62rem">
                            {{ $task->priority_label }}
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Type</span>
                        <span class="meta-val">{{ $task->type_label }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Assignée à</span>
                        <span class="meta-val {{ !$task->assignee ? 'muted' : '' }}">
                            {{ $task->assignee?->name ?? 'Non assignée' }}
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Créée par</span>
                        <span class="meta-val">{{ $task->creator?->name ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Début</span>
                        <span class="meta-val {{ !$task->start_date ? 'muted' : '' }}">
                            {{ $task->start_date ? $task->start_date->format('d/m/Y') : 'Non définie' }}
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Échéance</span>
                        <span class="meta-val {{ $task->is_overdue ? 'danger' : (!$task->due_date ? 'muted' : '') }}">
                            {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Non définie' }}
                            @if($task->is_overdue) <i class="fas fa-exclamation-circle" style="font-size:.6rem"></i> @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Heures est.</span>
                        <span class="meta-val {{ !$task->estimated_hours ? 'muted' : '' }}">
                            {{ $task->estimated_hours ? number_format($task->estimated_hours, 1).'h' : '—' }}
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Heures réel.</span>
                        <span class="meta-val {{ !$task->actual_hours ? 'muted' : '' }}">
                            {{ $task->actual_hours ? number_format($task->actual_hours, 1).'h' : '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Projet --}}
        <div class="sidebar-card">
            <div class="sidebar-header"><i class="fas fa-folder-open" style="margin-right:.35rem"></i>Projet</div>
            <div class="sidebar-body">
                <div class="meta-list">
                    <div class="meta-row">
                        <span class="meta-label">Nom</span>
                        <a href="{{ route('admin.projects.show', $task->project) }}"
                           style="font-size:.73rem;font-weight:600;color:var(--brand-primary);text-decoration:none;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px">
                            {{ $task->project?->name ?? '—' }}
                        </a>
                    </div>
                    @if($task->parent)
                    <div class="meta-row">
                        <span class="meta-label">Parente</span>
                        <a href="{{ route('admin.tasks.show', $task->parent) }}"
                           style="font-size:.7rem;font-weight:600;color:var(--brand-primary);text-decoration:none;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px">
                            {{ $task->parent->title }}
                        </a>
                    </div>
                    @endif
                    <div class="meta-row">
                        <span class="meta-label">N° tâche</span>
                        <span class="meta-val" style="font-family:monospace;font-size:.68rem">{{ $task->task_number }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Créée le</span>
                        <span class="meta-val">{{ $task->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Modifiée le</span>
                        <span class="meta-val">{{ $task->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revue --}}
        @if($task->completed_at || $task->reviewed_at)
        <div class="sidebar-card">
            <div class="sidebar-header"><i class="fas fa-magnifying-glass" style="margin-right:.35rem"></i>Revue</div>
            <div class="sidebar-body">
                <div class="meta-list">
                    @if($task->completed_at)
                    <div class="meta-row">
                        <span class="meta-label">Soumis le</span>
                        <span class="meta-val">{{ $task->completed_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($task->reviewed_at)
                    <div class="meta-row">
                        <span class="meta-label">Révisé le</span>
                        <span class="meta-val">{{ $task->reviewed_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Révisé par</span>
                        <span class="meta-val">{{ $task->reviewer?->name ?? '—' }}</span>
                    </div>
                    @endif
                    @if($task->review_notes && $task->status !== 'rejected')
                    <div style="margin-top:.5rem;padding:.5rem .65rem;border-radius:7px;background:rgba(255,255,255,.04);font-size:.72rem;color:var(--text-tertiary);line-height:1.5">
                        {{ $task->review_notes }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>{{-- end sidebar --}}

</div>{{-- end layout --}}

{{-- ══════════════════════════════════════════════
     MODALS
══════════════════════════════════════════════ --}}

{{-- Modal : Soumettre pour revue --}}
@if($canMarkComplete)
<div class="modal-overlay" id="completeModal" role="dialog" aria-modal="true" aria-labelledby="completeTitle">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-icon modal-icon-warning"><i class="fas fa-paper-plane"></i></div>
            <h3 id="completeTitle">Soumettre pour revue</h3>
            <button class="modal-close-btn" onclick="closeModal('completeModal')" aria-label="Fermer"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p>La tâche sera transmise au chef de projet pour validation. Le statut passera en <strong>En revue</strong>.</p>
            <div class="modal-field">
                <label for="completion_notes">Notes de complétion</label>
                <textarea id="completion_notes" class="fi" rows="4"
                          placeholder="Décrivez le travail effectué, les tests réalisés…"></textarea>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-ghost" onclick="closeModal('completeModal')">Annuler</button>
            <button class="btn btn-warning" id="completeBtn" onclick="submitComplete()">
                <i class="fas fa-paper-plane"></i> Soumettre
            </button>
        </div>
    </div>
</div>
@endif

{{-- Modal : Approuver --}}
@if($canReview)
<div class="modal-overlay" id="approveModal" role="dialog" aria-modal="true" aria-labelledby="approveTitle">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-icon modal-icon-success"><i class="fas fa-circle-check"></i></div>
            <h3 id="approveTitle">Approuver la tâche</h3>
            <button class="modal-close-btn" onclick="closeModal('approveModal')" aria-label="Fermer"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p>Vous confirmez que cette tâche est correctement réalisée. Elle passera en statut <strong>Terminée</strong>.</p>
            <div class="modal-field">
                <label for="approve_notes">Commentaire de revue</label>
                <textarea id="approve_notes" class="fi" rows="3"
                          placeholder="Félicitations, remarques éventuelles…"></textarea>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-ghost" onclick="closeModal('approveModal')">Annuler</button>
            <button class="btn btn-success" id="approveBtn" onclick="submitApprove()">
                <i class="fas fa-circle-check"></i> Approuver
            </button>
        </div>
    </div>
</div>

{{-- Modal : Rejeter --}}
<div class="modal-overlay" id="rejectModal" role="dialog" aria-modal="true" aria-labelledby="rejectTitle">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-icon modal-icon-danger"><i class="fas fa-circle-xmark"></i></div>
            <h3 id="rejectTitle">Rejeter la tâche</h3>
            <button class="modal-close-btn" onclick="closeModal('rejectModal')" aria-label="Fermer"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p>La tâche sera renvoyée à l'assigné(e) avec vos explications. Elle passera en statut <strong>Rejetée</strong>.</p>
            <div class="modal-field">
                <label for="reject_notes">Raison du rejet <span class="req" style="color:#f87171">*</span></label>
                <textarea id="reject_notes" class="fi" rows="4" minlength="10"
                          placeholder="Expliquez clairement ce qui doit être corrigé…" required></textarea>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-ghost" onclick="closeModal('rejectModal')">Annuler</button>
            <button class="btn btn-danger" id="rejectBtn" onclick="submitReject()">
                <i class="fas fa-circle-xmark"></i> Rejeter
            </button>
        </div>
    </div>
</div>
@endif

{{-- Modal : Supprimer --}}
@if($canDelete)
<div class="modal-overlay" id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteTitle">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-icon modal-icon-danger"><i class="fas fa-trash"></i></div>
            <h3 id="deleteTitle">Supprimer la tâche</h3>
            <button class="modal-close-btn" onclick="closeModal('deleteModal')" aria-label="Fermer"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p>Vous êtes sur le point de supprimer <strong>{{ $task->title }}</strong>. Cette action est <strong>irréversible</strong> et supprimera également les commentaires et entrées de temps associés.</p>
        </div>
        <div class="modal-foot">
            <button class="btn btn-ghost" onclick="closeModal('deleteModal')">Annuler</button>
            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function () {
    /* ── Tabs ── */
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });

    /* ── Reply forms ── */
    window.toggleReplyForm = function (id) {
        const wrap = document.getElementById('reply-form-' + id);
        if (!wrap) return;
        wrap.classList.toggle('open');
        if (wrap.classList.contains('open')) {
            wrap.querySelector('textarea')?.focus();
        }
    };

    /* ── Modal helpers ── */
    window.openModal = function (id) {
        const el = document.getElementById(id);
        if (el) { el.classList.add('open'); }
    };

    window.closeModal = function (id) {
        const el = document.getElementById(id);
        if (el) { el.classList.remove('open'); }
    };

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // Close on ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(el => closeModal(el.id));
        }
    });

    /* ── AJAX : mark as complete ── */
    window.submitComplete = function () {
        const btn   = document.getElementById('completeBtn');
        const notes = document.getElementById('completion_notes')?.value ?? '';

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi…';

        fetch('{{ route('admin.tasks.complete', $task) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ completion_notes: notes }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { window.location.reload(); }
            else { alert(data.message || 'Erreur.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Soumettre'; }
        })
        .catch(() => { alert('Erreur réseau.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Soumettre'; });
    };

    /* ── AJAX : approve ── */
    window.submitApprove = function () {
        const btn   = document.getElementById('approveBtn');
        const notes = document.getElementById('approve_notes')?.value ?? '';

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approbation…';

        fetch('{{ route('admin.tasks.approve', $task) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ review_notes: notes }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { window.location.reload(); }
            else { alert(data.message || 'Erreur.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-circle-check"></i> Approuver'; }
        })
        .catch(() => { alert('Erreur réseau.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-circle-check"></i> Approuver'; });
    };

    /* ── AJAX : reject ── */
    window.submitReject = function () {
        const btn   = document.getElementById('rejectBtn');
        const notes = document.getElementById('reject_notes')?.value?.trim() ?? '';

        if (notes.length < 10) {
            document.getElementById('reject_notes').focus();
            document.getElementById('reject_notes').style.borderColor = '#f87171';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejet…';

        fetch('{{ route('admin.tasks.reject', $task) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ review_notes: notes }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { window.location.reload(); }
            else { alert(data.message || 'Erreur.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-circle-xmark"></i> Rejeter'; }
        })
        .catch(() => { alert('Erreur réseau.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-circle-xmark"></i> Rejeter'; });
    };
})();
</script>
@endpush
