{{-- resources/views/admin/billing/show-payment.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Paiement ' . ($payment->payment_number ?? '#' . $payment->id) . ' - NovaTech Admin')
@section('page-title', 'Détail du paiement')

@push('styles')
<style>
    /* Modal styles */
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

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Notification toast */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        z-index: 10001;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toast-success {
        background: #10b981;
        color: white;
    }

    .toast-error {
        background: #ef4444;
        color: white;
    }

    .toast-warning {
        background: #f59e0b;
        color: white;
    }

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

    /* Payment header styles */
    .payment-header {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .payment-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-group h4 {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .info-group p {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .badge-deposit { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-partial { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-full { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all var(--transition-fast);
    }

    .btn-primary { background: var(--brand-primary); color: white; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }
    .btn-secondary { background: var(--bg-tertiary); color: var(--text-primary); border: 1px solid var(--border-light); }
    .btn-secondary:hover { background: var(--bg-hover); }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--brand-primary);
        display: inline-block;
    }

    .info-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
    }

    .info-value {
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .payment-info {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="payment-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div>
            <h2 style="margin: 0 0 0.25rem 0;">Paiement {{ $payment->payment_number ?? '#' . $payment->id }}</h2>
            <p style="color: var(--text-secondary);">Date: {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Non définie' }}</p>
        </div>
        <div>
            @if($payment->payment_type == 'deposit')
                <span class="badge badge-deposit"><i class="fas fa-percent"></i> Acompte</span>
            @elseif($payment->payment_type == 'partial')
                <span class="badge badge-partial"><i class="fas fa-clock"></i> Paiement partiel</span>
            @else
                <span class="badge badge-full"><i class="fas fa-check-circle"></i> Paiement complet</span>
            @endif
        </div>
    </div>
</div>

<div class="action-buttons">
    @can('billing.payments.resend')
    <button type="button" class="btn btn-primary" id="resendBtn">
        <i class="fas fa-envelope"></i> Renvoyer le reçu
    </button>
    @endcan
    <a href="{{ route('admin.billing.payments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="payment-info">
    <div class="info-group">
        <h4>Client</h4>
        @if($payment->client)
        <p><strong>{{ $payment->client->name }}</strong></p>
        <p>{{ $payment->client->email }}</p>
        <p>{{ $payment->client->phone ?? '' }}</p>
        @else
        <p class="text-muted">Client non trouvé</p>
        @endif
    </div>
    <div class="info-group">
        <h4>Facture associée</h4>
        @if($payment->invoice)
        <p><strong>{{ $payment->invoice->invoice_number ?? 'N/A' }}</strong></p>
        <p>Montant facture: {{ number_format($payment->invoice->total, 0, ',', ' ') }} FCFA</p>
        <p>Statut:
            @if($payment->invoice->status == 'paid')
                <span style="color: #10b981;">Payée</span>
            @elseif($payment->invoice->status == 'partially_paid')
                <span style="color: #f59e0b;">Partiellement payée</span>
            @else
                <span style="color: #ef4444;">En attente</span>
            @endif
        </p>
        @else
        <p class="text-muted">Aucune facture associée</p>
        @endif
    </div>
    <div class="info-group">
        <h4>Détails du paiement</h4>
        <p>Montant: <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></p>
        <p>Mode:
            @if($payment->payment_method == 'cash') Espèces
            @elseif($payment->payment_method == 'bank_transfer') Virement bancaire
            @elseif($payment->payment_method == 'mobile_money') Mobile Money
            @elseif($payment->payment_method == 'card') Carte bancaire
            @elseif($payment->payment_method == 'check') Chèque
            @else {{ $payment->payment_method ?? 'Non spécifié' }}
            @endif
        </p>
        @if($payment->reference)
        <p>Référence: {{ $payment->reference }}</p>
        @endif
    </div>
</div>

<div class="info-card">
    <h3 class="section-title">Informations complètes</h3>

    <div class="info-row">
        <span class="info-label">N° Paiement:</span>
        <span class="info-value">{{ $payment->payment_number ?? '#' . $payment->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Date du paiement:</span>
        <span class="info-value">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Non définie' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Type de paiement:</span>
        <span class="info-value">
            @if($payment->payment_type == 'deposit') Acompte
            @elseif($payment->payment_type == 'partial') Paiement partiel
            @else Paiement complet
            @endif
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Mode de paiement:</span>
        <span class="info-value">
            @if($payment->payment_method == 'cash') Espèces
            @elseif($payment->payment_method == 'bank_transfer') Virement bancaire
            @elseif($payment->payment_method == 'mobile_money') Mobile Money
            @elseif($payment->payment_method == 'card') Carte bancaire
            @elseif($payment->payment_method == 'check') Chèque
            @else {{ $payment->payment_method ?? 'Non spécifié' }}
            @endif
        </span>
    </div>
    @if($payment->reference)
    <div class="info-row">
        <span class="info-label">Référence:</span>
        <span class="info-value">{{ $payment->reference }}</span>
    </div>
    @endif
    @if($payment->notes)
    <div class="info-row">
        <span class="info-label">Notes:</span>
        <span class="info-value">{{ $payment->notes }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Date d'enregistrement:</span>
        <span class="info-value">{{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'Non définie' }}</span>
    </div>
    @if($payment->email_sent_at)
    <div class="info-row">
        <span class="info-label">Reçu envoyé le:</span>
        <span class="info-value">{{ $payment->email_sent_at->format('d/m/Y H:i') }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Statut:</span>
        <span class="info-value">
            @if($payment->status == 'completed')
                <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Validé</span>
            @elseif($payment->status == 'pending')
                <span style="color: #f59e0b;"><i class="fas fa-clock"></i> En attente</span>
            @elseif($payment->status == 'failed')
                <span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Échoué</span>
            @else
                <span>{{ $payment->status ?? 'Non défini' }}</span>
            @endif
        </span>
    </div>
</div>

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
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-primary">Confirmer</button>
        </div>
    </div>
</div>

<!-- Formulaire caché pour l'envoi -->
<form id="resendForm" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
    // Éléments DOM
    const modal = document.getElementById('confirmationModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendForm = document.getElementById('resendForm');

    let currentAction = null;
    let isLoading = false;

    // Fonction pour afficher une notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `toast-notification toast-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Fonction pour ouvrir le modal
    function openModal(title, message, onConfirm) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        currentAction = onConfirm;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Fonction pour fermer le modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        currentAction = null;
    }

    // Fonction pour confirmer l'action
    async function confirmAction() {
        if (currentAction && typeof currentAction === 'function' && !isLoading) {
            try {
                await currentAction();
            } catch (error) {
                console.error('Erreur lors de l\'action:', error);
                showNotification('Une erreur est survenue', 'error');
            }
        }
        closeModal();
    }

    // Écouteur du bouton de confirmation
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

    // Renvoyer le reçu - Version avec formulaire standard (plus fiable)
    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            openModal(
                'Renvoyer le reçu',
                'Êtes-vous sûr de vouloir renvoyer le reçu par email au client ?',
                () => {
                    // Soumettre le formulaire directement
                    resendForm.action = "{{ route('admin.billing.payments.resend', $payment) }}";
                    resendForm.submit();
                }
            );
        });
    }

    // Exposer les fonctions globales
    window.closeModal = closeModal;
    window.showNotification = showNotification;
</script>
@endpush
