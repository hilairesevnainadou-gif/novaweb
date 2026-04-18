{{-- resources/views/admin/tickets/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->id . ' - NovaTech Admin')
@section('page-title', 'Détail du ticket #' . $ticket->id)

@push('styles')
<style>
    .ticket-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .ticket-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .ticket-header {
        padding: 1.25rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .ticket-header h2 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .ticket-header h2 i {
        color: var(--brand-primary);
    }
    .ticket-body {
        padding: 1.5rem;
    }
    .info-panel {
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-light);
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        width: 120px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }
    .info-value {
        flex: 1;
        font-size: 0.875rem;
        color: var(--text-primary);
        word-break: break-word;
    }
    .info-value i {
        margin-right: 0.5rem;
        color: var(--brand-primary);
        width: 1.25rem;
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
    .badge-status-open {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    .badge-status-in_progress {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .badge-status-closed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-priority-low {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-priority-medium {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    .badge-priority-high {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .badge-priority-urgent {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .messages-section {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .messages-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .messages-header h3 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .messages-header h3 i {
        color: var(--brand-primary);
    }
    .messages-list {
        padding: 1.5rem;
        max-height: 500px;
        overflow-y: auto;
    }
    .message-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .message-item:last-child {
        margin-bottom: 0;
    }
    .message-avatar {
        flex-shrink: 0;
    }
    .message-avatar .avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .message-avatar .avatar.admin {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    .message-avatar .avatar.user {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
    }
    .message-content {
        flex: 1;
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
        padding: 1rem;
    }
    .message-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .message-author {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
    }
    .message-author i {
        margin-right: 0.25rem;
    }
    .message-date {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }
    .message-text {
        font-size: 0.875rem;
        line-height: 1.5;
        color: var(--text-secondary);
        white-space: pre-wrap;
        word-break: break-word;
    }
    .reply-form {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }
    .reply-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }
    .reply-header h3 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .reply-body {
        padding: 1.5rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-label i {
        margin-right: 0.5rem;
    }
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        resize: vertical;
        min-height: 120px;
        outline: none;
    }
    .form-textarea:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .form-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        cursor: pointer;
    }
    .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .form-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1rem;
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
    }
    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: transparent;
        color: var(--text-secondary);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid var(--border-medium);
        cursor: pointer;
    }
    .btn-outline:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }
    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #ef4444;
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    .btn-warning {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #f59e0b;
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-1px);
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        margin-bottom: 1rem;
        transition: color 0.2s;
    }
    .back-link:hover {
        color: var(--brand-primary);
    }
    .status-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
        }
        .info-label {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        .message-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .ticket-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .status-selector {
            width: 100%;
        }
        .status-selector .form-select {
            flex: 1;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="ticket-container">
    <a href="{{ route('admin.tickets.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>

    <!-- Ticket principal -->
    <div class="ticket-card">
        <div class="ticket-header">
            <h2>
                <i class="fas fa-ticket-alt"></i>
                Ticket #{{ $ticket->id }} - {{ $ticket->subject }}
            </h2>
            <div class="status-selector">
                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select" style="width: auto;">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>📥 Ouvert</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🔄 En cours</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>✅ Fermé</option>
                    </select>
                    <select name="priority" class="form-select" style="width: auto;">
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>🟢 Basse</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>🔵 Moyenne</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>🟠 Haute</option>
                        <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>🔴 Urgente</option>
                    </select>
                    <button type="submit" class="btn-primary" style="padding: 0.625rem 1rem;">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <div class="ticket-body">
            <div class="info-panel">
                <div class="info-row">
                    <div class="info-label">Client</div>
                    <div class="info-value">
                        <i class="fas fa-user"></i> {{ $ticket->user->name ?? 'Utilisateur' }}
                        <span style="font-size: 0.7rem; color: var(--text-tertiary); margin-left: 0.5rem;">({{ $ticket->user->email ?? '' }})</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Priorité</div>
                    <div class="info-value">
                        @php
                            $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'];
                            $priorityIcons = ['low' => 'arrow-down', 'medium' => 'minus', 'high' => 'arrow-up', 'urgent' => 'exclamation-triangle'];
                        @endphp
                        <span class="badge badge-priority-{{ $ticket->priority }}">
                            <i class="fas fa-{{ $priorityIcons[$ticket->priority] ?? 'flag' }}"></i>
                            {{ $priorityLabels[$ticket->priority] ?? ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Statut</div>
                    <div class="info-value">
                        @php
                            $statusLabels = ['open' => 'Ouvert', 'in_progress' => 'En cours', 'closed' => 'Fermé'];
                            $statusIcons = ['open' => 'inbox', 'in_progress' => 'spinner', 'closed' => 'check-circle'];
                        @endphp
                        <span class="badge badge-status-{{ $ticket->status }}">
                            <i class="fas fa-{{ $statusIcons[$ticket->status] ?? 'circle' }}"></i>
                            {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date de création</div>
                    <div class="info-value">
                        <i class="fas fa-calendar-alt"></i> {{ $ticket->created_at->format('d/m/Y à H:i') }}
                        <span style="font-size: 0.7rem; color: var(--text-tertiary); margin-left: 0.5rem;">({{ $ticket->created_at->diffForHumans() }})</span>
                    </div>
                </div>
                @if($ticket->closed_at)
                <div class="info-row">
                    <div class="info-label">Date de fermeture</div>
                    <div class="info-value">
                        <i class="fas fa-calendar-check"></i> {{ $ticket->closed_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="messages-section">
        <div class="messages-header">
            <h3>
                <i class="fas fa-comments"></i>
                Conversation ({{ $messages->count() }} messages)
            </h3>
        </div>
        <div class="messages-list" id="messagesList">
            @forelse($messages as $message)
            <div class="message-item">
                <div class="message-avatar">
                    @if($message->is_admin)
                        <div class="avatar admin">
                            <i class="fas fa-headset"></i>
                        </div>
                    @else
                        <div class="avatar user">
                            {{ strtoupper(substr($message->user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <div class="message-author">
                            @if($message->is_admin)
                                <i class="fas fa-headset" style="color: var(--brand-primary);"></i> Support NovaTech
                            @else
                                <i class="fas fa-user" style="color: var(--brand-primary);"></i> {{ $message->user->name ?? 'Client' }}
                            @endif
                        </div>
                        <div class="message-date">
                            <i class="far fa-clock"></i> {{ $message->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                    <div class="message-text">
                        {{ nl2br(e($message->message)) }}
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-tertiary);">
                <i class="fas fa-comment-slash" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                Aucun message dans ce ticket
            </div>
            @endforelse
        </div>
    </div>

    <!-- Formulaire de réponse -->
    @if($ticket->status !== 'closed')
    <div class="reply-form">
        <div class="reply-header">
            <h3>
                <i class="fas fa-reply"></i>
                Répondre au ticket
            </h3>
        </div>
        <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" id="replyForm">
            @csrf
            <div class="reply-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-comment-dots"></i> Votre message
                    </label>
                    <textarea name="message" class="form-textarea" placeholder="Saisissez votre réponse..." required></textarea>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.tickets.index') }}" class="btn-outline">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-primary" id="submitReply">
                        <i class="fas fa-paper-plane"></i> Envoyer la réponse
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="reply-form">
        <div class="reply-header">
            <h3>
                <i class="fas fa-lock"></i>
                Ticket fermé
            </h3>
        </div>
        <div class="reply-body" style="text-align: center; padding: 2rem;">
            <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981; margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-secondary);">Ce ticket est fermé. Vous ne pouvez plus ajouter de messages.</p>
            <button type="button" onclick="reopenTicket({{ $ticket->id }})" class="btn-warning" style="margin-top: 1rem;">
                <i class="fas fa-folder-open"></i> Rouvrir le ticket
            </button>
        </div>
    </div>
    @endif
</div>

<div id="toast" class="toast-notification">
    <i id="toastIcon" class="fas"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script>
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    const csrfToken = '{{ csrf_token() }}';

    function showToast(message, type) {
        type = type || 'success';
        toastIcon.className = 'fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle');
        toastMessage.textContent = message;
        toast.className = 'toast-notification ' + type + ' show';
        setTimeout(function() { toast.classList.remove('show'); }, 3000);
    }

    function reopenTicket(id) {
        fetch('/admin/tickets/' + id + '/close', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success !== undefined) {
                showToast('Ticket rouvert avec succès', 'success');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showToast('Une erreur est survenue', 'error');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Auto-scroll vers le dernier message
    document.addEventListener('DOMContentLoaded', function() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
    });

    // Confirmation avant envoi
    document.getElementById('replyForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitReply');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    });

    window.reopenTicket = reopenTicket;
</script>
@endpush
