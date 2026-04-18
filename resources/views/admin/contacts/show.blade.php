{{-- resources/views/admin/contacts/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Message de ' . $contact->name . ' - NovaTech Admin')
@section('page-title', 'Détail du message')

@section('content')
<div>
    <a href="{{ route('admin.contacts.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); text-decoration: none; margin-bottom: 1rem; transition: color 0.2s;">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>

    <div style="background: var(--bg-secondary); border-radius: 0.75rem; border: 1px solid var(--border-light); overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; background: var(--bg-tertiary); border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin: 0; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-envelope" style="color: var(--brand-primary);"></i>
                Message de {{ $contact->name }}
            </h2>
            <div>
                @if($contact->is_read)
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 500; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-check-circle"></i> Lu
                    </span>
                @else
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 500; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-circle"></i> Non lu
                    </span>
                @endif
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <!-- Informations -->
            <div style="background: var(--bg-tertiary); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                <div style="display: flex; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 120px; font-size: 0.75rem; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;">Nom complet</div>
                    <div style="flex: 1; font-size: 0.875rem; color: var(--text-primary);">
                        <i class="fas fa-user" style="margin-right: 0.5rem; color: var(--brand-primary); width: 1.25rem;"></i> {{ $contact->name }}
                    </div>
                </div>
                <div style="display: flex; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 120px; font-size: 0.75rem; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;">Email</div>
                    <div style="flex: 1; font-size: 0.875rem; color: var(--text-primary);">
                        <i class="fas fa-envelope" style="margin-right: 0.5rem; color: var(--brand-primary); width: 1.25rem;"></i>
                        <a href="mailto:{{ $contact->email }}" style="color: var(--brand-primary); text-decoration: none;">{{ $contact->email }}</a>
                    </div>
                </div>
                @if($contact->phone)
                <div style="display: flex; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 120px; font-size: 0.75rem; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;">Téléphone</div>
                    <div style="flex: 1; font-size: 0.875rem; color: var(--text-primary);">
                        <i class="fas fa-phone" style="margin-right: 0.5rem; color: var(--brand-primary); width: 1.25rem;"></i>
                        <a href="tel:{{ $contact->phone }}" style="color: var(--brand-primary); text-decoration: none;">{{ $contact->phone }}</a>
                    </div>
                </div>
                @endif
                <div style="display: flex; padding: 0.75rem 0;">
                    <div style="width: 120px; font-size: 0.75rem; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;">Date d'envoi</div>
                    <div style="flex: 1; font-size: 0.875rem; color: var(--text-primary);">
                        <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: var(--brand-primary); width: 1.25rem;"></i>
                        {{ $contact->created_at->format('d/m/Y à H:i') }}
                        <span style="font-size: 0.7rem; color: var(--text-tertiary); margin-left: 0.5rem;">({{ $contact->created_at->diffForHumans() }})</span>
                    </div>
                </div>
            </div>

            <!-- Sujet -->
            <div style="background: var(--bg-primary); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid var(--border-light);">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-tag" style="color: var(--brand-primary);"></i> Sujet
                </h3>
                <p style="font-weight: 600; font-size: 1rem; color: var(--brand-primary); margin: 0;">{{ $contact->subject }}</p>
            </div>

            <!-- Message -->
            <div style="background: var(--bg-primary); border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid var(--border-light);">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-comment-dots" style="color: var(--brand-primary);"></i> Message
                </h3>
                <p style="font-size: 0.9375rem; line-height: 1.6; color: var(--text-primary); white-space: pre-wrap; word-break: break-word; margin: 0;">{{ nl2br(e($contact->message)) }}</p>
            </div>

            <!-- Boutons d'action -->
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
                @if(!$contact->is_read)
                    <button type="button" onclick="markAsRead({{ $contact->id }})" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: #10b981; color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s;">
                        <i class="fas fa-check-circle"></i> Marquer comme lu
                    </button>
                @else
                    <button type="button" onclick="markAsUnread({{ $contact->id }})" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: #f59e0b; color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s;">
                        <i class="fas fa-circle"></i> Marquer comme non lu
                    </button>
                @endif

                <a href="{{ route('admin.contacts.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: transparent; color: var(--text-secondary); border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid var(--border-medium); cursor: pointer; transition: all 0.2s;">
                    <i class="fas fa-list"></i> Retour à la liste
                </a>

                <button type="button" onclick="confirmDelete({{ $contact->id }}, '{{ addslashes($contact->name) }}')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: #ef4444; color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s;">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: var(--bg-elevated); border-radius: 0.75rem; border: 1px solid var(--border-medium); width: 90%; max-width: 450px;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0; color: var(--text-primary);">Confirmation</h3>
            <button onclick="closeModal()" style="background: none; border: none; color: var(--text-tertiary); cursor: pointer; font-size: 1.25rem;">&times;</button>
        </div>
        <div style="padding: 1.5rem;">
            <p id="modalMessage" style="margin: 0 0 0.5rem 0; line-height: 1.6; color: var(--text-secondary);"></p>
            <p id="modalWarning" style="color: #f59e0b; font-size: 0.875rem; margin-top: 0.75rem;"></p>
        </div>
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button onclick="closeModal()" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: var(--bg-tertiary); color: var(--text-secondary); border: none; cursor: pointer;">Annuler</button>
            <button id="confirmDeleteBtn" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: #ef4444; color: white; border: none; cursor: pointer;">Supprimer</button>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="toastMessage" style="position: fixed; bottom: 2rem; right: 2rem; background: var(--bg-elevated); border: 1px solid var(--border-medium); border-radius: 0.5rem; padding: 1rem 1.5rem; display: none; align-items: center; gap: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 10000;">
    <i id="toastIcon" class="fas"></i>
    <span id="toastText"></span>
</div>
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const toastDiv = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    const toastText = document.getElementById('toastText');

    let currentDeleteId = null;
    const csrfToken = '{{ csrf_token() }}';

    function showToast(message, type) {
        type = type || 'success';
        toastIcon.className = 'fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle');
        toastText.textContent = message;
        toastDiv.style.display = 'flex';

        if (type === 'success') {
            toastDiv.style.borderLeft = '4px solid #10b981';
        } else {
            toastDiv.style.borderLeft = '4px solid #ef4444';
        }

        setTimeout(function() {
            toastDiv.style.display = 'none';
        }, 3000);
    }

    function openModal(message, warning) {
        modalMessage.textContent = message;
        modalWarning.textContent = warning || '';
        deleteModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        deleteModal.style.display = 'none';
        document.body.style.overflow = '';
        currentDeleteId = null;
    }

    function confirmDelete(id, name) {
        currentDeleteId = id;
        openModal(
            'Êtes-vous sûr de vouloir supprimer le message de "' + name + '" ?',
            'Action irréversible. Le message sera définitivement supprimé.'
        );
    }

    function deleteContact() {
        if (!currentDeleteId) return;

        fetch('/admin/contacts/' + currentDeleteId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast('Message supprimé avec succès', 'success');
                setTimeout(function() {
                    window.location.href = '{{ route("admin.contacts.index") }}';
                }, 500);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
                closeModal();
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de la suppression', 'error');
            closeModal();
        });
    }

    function markAsRead(id) {
        fetch('/admin/contacts/' + id + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast(data.message || 'Message marqué comme lu', 'success');
                setTimeout(function() { location.reload(); }, 500);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    function markAsUnread(id) {
        fetch('/admin/contacts/' + id + '/unread', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast(data.message || 'Message marqué comme non lu', 'success');
                setTimeout(function() { location.reload(); }, 500);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    // Événements
    deleteModal.onclick = function(e) {
        if (e.target === deleteModal) {
            closeModal();
        }
    };

    confirmDeleteBtn.onclick = deleteContact;

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.style.display === 'flex') {
            closeModal();
        }
    });

    // Exposer les fonctions globalement
    window.markAsRead = markAsRead;
    window.markAsUnread = markAsUnread;
    window.confirmDelete = confirmDelete;
    window.closeModal = closeModal;
</script>
@endpush
