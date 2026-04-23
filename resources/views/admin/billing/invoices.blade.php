{{-- resources/views/admin/billing/invoices.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Factures - Admin')
@section('page-title', 'Gestion des factures')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; } }

    .stat-card { background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; border: 1px solid var(--border-light); transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; }
    .stat-icon { width: 2rem; height: 2rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; }
    .stat-icon.indigo { background: rgba(59,130,246,.1); } .stat-icon.indigo i { color: #3b82f6; }
    .stat-icon.green  { background: rgba(16,185,129,.1); } .stat-icon.green i  { color: #10b981; }
    .stat-icon.yellow { background: rgba(245,158,11,.1); } .stat-icon.yellow i { color: #f59e0b; }
    .stat-icon.red    { background: rgba(239,68,68,.1);  } .stat-icon.red i    { color: #ef4444; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; }
    .stat-label { font-size: 0.7rem; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.5px; }

    .filters-container { background: var(--bg-secondary); border-radius: 0.75rem; padding: 1rem; border: 1px solid var(--border-light); margin-bottom: 1.5rem; }
    .filter-input, .filter-select { width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-light); background: var(--bg-primary); color: var(--text-primary); font-size: 0.875rem; transition: all var(--transition-fast); outline: none; }
    .filter-input:focus, .filter-select:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
    .btn-reset { width: 100%; padding: 0.625rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-light); background: var(--bg-primary); color: var(--text-primary); font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all var(--transition-fast); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .btn-reset:hover { background: var(--bg-hover); border-color: var(--brand-primary); }
    .grid { display: grid; } .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); } .gap-3 { gap: 0.75rem; }
    @media (min-width: 640px)  { .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .lg\:grid-cols-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); } }

    .page-header { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }
    @media (min-width: 640px) { .page-header { flex-direction: row; justify-content: space-between; align-items: center; } }
    .page-title-section h1 { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.25rem 0; }
    .page-title-section p  { color: var(--text-secondary); margin: 0; font-size: 0.875rem; }
    .page-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

    .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--brand-primary); color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: all var(--transition-fast); border: none; cursor: pointer; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .btn-secondary-outline { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: transparent; color: var(--text-secondary); border: 1px solid var(--border-light); border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: all var(--transition-fast); cursor: pointer; }
    .btn-secondary-outline:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--brand-primary); }

    .table-container { background: var(--bg-secondary); border-radius: 0.75rem; border: 1px solid var(--border-light); overflow-x: auto; }
    .invoices-table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    .invoices-table thead { background: var(--bg-tertiary); }
    .invoices-table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-tertiary); border-bottom: 1px solid var(--border-light); }
    .invoices-table td { padding: 0.875rem 1rem; border-bottom: 1px solid var(--border-light); color: var(--text-primary); font-size: 0.875rem; }
    .invoices-table tbody tr:hover { background: var(--bg-hover); }
    .table-row { animation: fadeInUp 0.3s ease forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; font-size: 0.72rem; font-weight: 500; border-radius: 9999px; white-space: nowrap; }
    .badge-draft   { background: rgba(107,114,128,.1); color: #6b7280; }
    .badge-sent    { background: rgba(59,130,246,.1);  color: #3b82f6; }
    .badge-paid    { background: rgba(16,185,129,.1);  color: #10b981; }
    .badge-partial { background: rgba(245,158,11,.1);  color: #f59e0b; }
    .badge-overdue { background: rgba(239,68,68,.1);   color: #ef4444; }

    .actions-cell { text-align: right; white-space: nowrap; }
    .action-btn { background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0.5rem; border-radius: 0.375rem; transition: all var(--transition-fast); font-size: 0.95rem; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 2rem; height: 2rem; }
    .action-btn:hover { color: var(--brand-primary); background: var(--bg-hover); }
    .action-btn.action-danger:hover { color: #ef4444; background: rgba(239,68,68,.08); }
    .action-btn.action-success:hover { color: #10b981; background: rgba(16,185,129,.08); }
    .action-btn.action-warning:hover { color: #f59e0b; background: rgba(245,158,11,.08); }

    .empty-state { text-align: center; padding: 3rem; color: var(--text-tertiary); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
    .pagination-wrapper { margin-top: 1.5rem; display: flex; justify-content: center; }
    .amount-remaining { color: #f59e0b; font-size: 0.8rem; }
    .amount-remaining.overdue { color: #ef4444; }

    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal { background: var(--bg-elevated); border-radius: 0.75rem; border: 1px solid var(--border-medium); width: 90%; max-width: 450px; transform: scale(0.95); transition: transform 0.3s ease; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); }
    .modal-overlay.active .modal { transform: scale(1); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; }
    .modal-header h3 { font-size: 1.125rem; font-weight: 600; margin: 0; color: var(--text-primary); }
    .modal-close { background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0.25rem; font-size: 1.125rem; transition: color 0.2s; }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body { padding: 1.5rem; }
    .modal-body p { margin: 0 0 0.5rem 0; line-height: 1.6; color: var(--text-secondary); }
    .modal-body .warning-text { color: #f59e0b; font-size: 0.875rem; margin-top: 0.75rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 0.75rem; }
    .btn { padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all var(--transition-fast); border: none; }
    .btn-secondary { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-light); }
    .btn-secondary:hover { background: var(--bg-hover); color: var(--text-primary); }
    .btn-confirm-primary { background: var(--brand-primary); color: white; }
    .btn-confirm-primary:hover { background: var(--brand-primary-hover); }
    .btn-confirm-danger { background: #ef4444; color: white; }
    .btn-confirm-danger:hover { background: #dc2626; }
    .btn-confirm-warning { background: #f59e0b; color: white; }
    .btn-confirm-warning:hover { background: #d97706; }
</style>
@endpush

@section('content')
@can('billing.invoices.view')

<div class="page-header">
    <div class="page-title-section">
        <h1>Factures</h1>
        <p>Gérez toutes les factures de vos clients</p>
    </div>
    <div class="page-actions">
        @can('billing.payments.view')
        <a href="{{ route('admin.billing.payments.index') }}" class="btn-secondary-outline">
            <i class="fas fa-credit-card"></i>
            Paiements
        </a>
        @endcan

        @can('billing.invoices.create')
        <a href="{{ route('admin.billing.invoices.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle facture
        </a>
        @endcan
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon indigo"><i class="fas fa-file-invoice"></i></div></div>
        <div class="stat-value">{{ number_format($totalInvoiced ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Total facturé</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div></div>
        <div class="stat-value">{{ number_format($totalPaid ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Total encaissé</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon yellow"><i class="fas fa-clock"></i></div></div>
        <div class="stat-value">{{ number_format($totalOutstanding ?? 0, 0, ',', ' ') }} FCFA</div>
        <div class="stat-label">Reste à payer</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div></div>
        <div class="stat-value">{{ $overdueCount ?? 0 }}</div>
        <div class="stat-label">En retard</div>
    </div>
</div>

<div class="filters-container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div><input type="text" id="search" placeholder="N° facture, client..." class="filter-input" autocomplete="off"></div>
        <div>
            <select id="status" class="filter-select">
                <option value="">Tous statuts</option>
                <option value="draft">Brouillon</option>
                <option value="sent">Envoyée</option>
                <option value="partially_paid">Partiellement payée</option>
                <option value="paid">Payée</option>
                <option value="overdue">En retard</option>
            </select>
        </div>
        <div>
            <select id="client_id" class="filter-select">
                <option value="">Tous clients</option>
                @foreach($clients ?? [] as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div><button onclick="resetFilters()" class="btn-reset"><i class="fas fa-undo-alt"></i> Réinitialiser</button></div>
        @can('billing.invoices.edit')
        <div><button onclick="updateOverdue()" class="btn-reset"><i class="fas fa-sync-alt"></i> MAJ Retards</button></div>
        @endcan
    </div>
</div>

<div class="table-container">
    <table class="invoices-table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Client</th>
                <th>Émission</th>
                <th>Échéance</th>
                <th>Total</th>
                <th>Payé</th>
                <th>Reste</th>
                <th>Statut</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody id="invoicesTableBody">
            @forelse($invoices as $index => $invoice)
            @php
                $issueDate = $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date) : null;
                $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date) : null;
                $isOverdue = $dueDate && $dueDate->isPast() && !in_array($invoice->status, ['paid']);
            @endphp
            <tr class="table-row"
                data-id="{{ $invoice->id }}"
                data-number="{{ strtolower($invoice->invoice_number) }}"
                data-client="{{ strtolower($invoice->client->name ?? '') }}"
                data-status="{{ $invoice->status }}"
                data-client-id="{{ $invoice->client_id }}"
                style="animation-delay: {{ $index * 0.03 }}s;">
                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                <td>{{ $invoice->client->name ?? '—' }}</td>
                <td>{{ $issueDate ? $issueDate->format('d/m/Y') : '—' }}</td>
                <td>
                    {{ $dueDate ? $dueDate->format('d/m/Y') : '—' }}
                    @if($isOverdue) <br><span style="color:#ef4444;font-size:0.7rem;">(Retard)</span> @endif
                </td>
                <td>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</td>
                <td><span class="amount-remaining {{ $isOverdue ? 'overdue' : '' }}">{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</span></td>
                <td>
                    @if($invoice->status == 'draft')
                        <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Brouillon</span>
                    @elseif($invoice->status == 'sent')
                        <span class="badge badge-sent"><i class="fas fa-paper-plane"></i> Envoyée</span>
                    @elseif($invoice->status == 'partially_paid')
                        <span class="badge badge-partial"><i class="fas fa-clock"></i> Partiel</span>
                    @elseif($invoice->status == 'paid')
                        <span class="badge badge-paid"><i class="fas fa-check-circle"></i> Payée</span>
                    @elseif($invoice->status == 'overdue')
                        <span class="badge badge-overdue"><i class="fas fa-exclamation-triangle"></i> En retard</span>
                    @endif
                </td>
                <td class="actions-cell">
                    <div style="display:flex;justify-content:center;gap:0.25rem;flex-wrap:wrap;">
                        {{-- Voir --}}
                        @can('billing.invoices.view')
                        <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="action-btn" title="Voir"><i class="fas fa-eye"></i></a>
                        @endcan

                        {{-- PDF --}}
                        @can('billing.invoices.view')
                        <a href="{{ route('admin.billing.invoices.pdf', $invoice) }}" class="action-btn" title="PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        @endcan

                        {{-- Envoyer --}}
                        @can('billing.invoices.send')
                        @if(!in_array($invoice->status, ['paid']))
                        <button type="button" class="action-btn send-btn" data-id="{{ $invoice->id }}" data-number="{{ $invoice->invoice_number }}" title="Envoyer"><i class="fas fa-paper-plane"></i></button>
                        @endif
                        @endcan

                        {{-- Rappel --}}
                        @can('billing.invoices.send')
                        @if(in_array($invoice->status, ['sent', 'partially_paid', 'overdue']) && ($invoice->client->email ?? false))
                        <button type="button" class="action-btn action-warning reminder-btn" data-id="{{ $invoice->id }}" data-number="{{ $invoice->invoice_number }}" title="Rappel"><i class="fas fa-bell"></i></button>
                        @endif
                        @endcan

                        {{-- Modifier --}}
                        @can('billing.invoices.edit')
                        @if(!in_array($invoice->status, ['paid']))
                        <a href="{{ route('admin.billing.invoices.edit', $invoice) }}" class="action-btn" title="Modifier"><i class="fas fa-edit"></i></a>
                        @endif
                        @endcan

                        {{-- Supprimer --}}
                        @can('billing.invoices.delete')
                        @if($invoice->status === 'draft')
                        <button type="button" class="action-btn action-danger delete-btn" data-id="{{ $invoice->id }}" data-number="{{ $invoice->invoice_number }}" title="Supprimer"><i class="fas fa-trash"></i></button>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="9" class="empty-state">
                    <i class="fas fa-file-invoice"></i>
                    <p>Aucune facture trouvée</p>
                    @can('billing.invoices.create')
                    <a href="{{ route('admin.billing.invoices.create') }}" class="btn-primary" style="margin-top:1rem;display:inline-flex;"><i class="fas fa-plus"></i> Créer une facture</a>
                    @endcan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($invoices->hasPages())
<div class="pagination-wrapper">{{ $invoices->links() }}</div>
@endif

<div id="confirmationModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Confirmation</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
            <p id="modalWarning" class="warning-text"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button id="modalConfirmBtn" class="btn btn-confirm-primary">Confirmer</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>
<form id="reminderForm" method="POST" style="display:none;">@csrf</form>

@endcan

@cannot('billing.invoices.view')
<div class="empty-state" style="padding:3rem;text-align:center;">
    <i class="fas fa-lock" style="font-size:3rem;margin-bottom:1rem;opacity:0.5;"></i>
    <p>Vous n'avez pas la permission de voir cette page.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script>
const searchInput = document.getElementById('search');
const statusSelect = document.getElementById('status');
const clientSelect = document.getElementById('client_id');
const modal = document.getElementById('confirmationModal');
const modalTitle = document.getElementById('modalTitle');
const modalMessage = document.getElementById('modalMessage');
const modalWarning = document.getElementById('modalWarning');
const modalConfirmBtn = document.getElementById('modalConfirmBtn');
let currentAction = null;

function openModal(title, message, warning, confirmText, confirmClass, onConfirm) {
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modalWarning.textContent = warning || '';
    modalConfirmBtn.textContent = confirmText;
    modalConfirmBtn.className = 'btn ' + confirmClass;
    currentAction = onConfirm;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    currentAction = null;
}

modalConfirmBtn.onclick = () => { if (currentAction) currentAction(); closeModal(); };
modal.onclick = e => { if (e.target === modal) closeModal(); };
document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('active')) closeModal(); });

function showNotification(message, type = 'success') {
    const el = document.createElement('div');
    el.style.cssText = `position:fixed;bottom:20px;right:20px;padding:12px 20px;background:${type==='success'?'#10b981':'#ef4444'};color:white;border-radius:8px;font-size:14px;z-index:10001;box-shadow:0 4px 12px rgba(0,0,0,.15);animation:slideIn .3s ease;`;
    el.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(el);
    setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .3s'; setTimeout(()=>el.remove(),300); }, 3000);
}

const style = document.createElement('style');
style.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
document.head.appendChild(style);

// ── ENVOYER ──
document.querySelectorAll('.send-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const number = this.dataset.number;
        openModal('Envoyer la facture', `Envoyer la facture "${number}" par email au client ?`, 'Le client recevra la facture en PDF en pièce jointe.', 'Envoyer', 'btn-confirm-primary', () => {
            const confirmBtn = modalConfirmBtn;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            fetch(`{{ url('admin/billing/invoices') }}/${id}/send`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Facture envoyée avec succès');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification(data.message || 'Erreur lors de l\'envoi', 'error');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = 'Envoyer';
                }
            })
            .catch(() => {
                showNotification('Erreur technique', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Envoyer';
            });
        });
    });
});

// ── RAPPEL ──
document.querySelectorAll('.reminder-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const number = this.dataset.number;
        openModal('Envoyer un rappel', `Envoyer un rappel de paiement pour la facture "${number}" ?`, 'Le client recevra un email de rappel.', 'Envoyer le rappel', 'btn-confirm-warning', () => {
            const form = document.getElementById('reminderForm');
            form.action = `{{ url('admin/billing/invoices') }}/${id}/reminder`;
            form.submit();
        });
    });
});

// ── SUPPRIMER ──
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const number = this.dataset.number;
        openModal('Supprimer la facture', `Êtes-vous sûr de vouloir supprimer la facture "${number}" ?`, 'Cette action est irréversible.', 'Supprimer', 'btn-confirm-danger', () => {
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('admin/billing/invoices') }}/${id}`;
            form.submit();
        });
    });
});

// ── MAJ RETARDS ──
function updateOverdue() {
    openModal('Mettre à jour les retards', 'Passer toutes les factures échues en statut "En retard" ?', '', 'Mettre à jour', 'btn-confirm-warning', () => {
        fetch('{{ route('admin.billing.update-overdue') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { showNotification(`${data.count} facture(s) mise(s) à jour`); setTimeout(()=>location.reload(),1500); }
            else { showNotification('Erreur lors de la mise à jour', 'error'); }
        })
        .catch(() => showNotification('Erreur technique', 'error'));
    });
}
window.updateOverdue = updateOverdue;

// ── FILTRES ──
function filterTable() {
    const s = searchInput?.value.toLowerCase() || '';
    const st = statusSelect?.value || '';
    const cl = clientSelect?.value || '';
    document.querySelectorAll('.invoices-table tbody tr:not(#emptyStateRow):not(#noResultsRow)').forEach(row => {
        let show = true;
        if (s && !row.dataset.number?.includes(s) && !row.dataset.client?.includes(s)) show = false;
        if (show && st && row.dataset.status !== st) show = false;
        if (show && cl && row.dataset.clientId !== cl) show = false;
        row.style.display = show ? '' : 'none';
    });
    const allRows = document.querySelectorAll('.invoices-table tbody tr:not(#emptyStateRow):not(#noResultsRow)');
    const hiddenRows = [...allRows].filter(r => r.style.display === 'none');
    const noResultRow = document.getElementById('noResultsRow');
    if (allRows.length > 0 && allRows.length === hiddenRows.length) {
        if (!noResultRow) {
            const tbody = document.getElementById('invoicesTableBody');
            const tr = document.createElement('tr');
            tr.id = 'noResultsRow';
            tr.innerHTML = `<td colspan="9" class="empty-state"><i class="fas fa-search"></i><p>Aucun résultat ne correspond à vos critères</p></td>`;
            tbody.appendChild(tr);
        }
    } else {
        if (noResultRow) noResultRow.remove();
    }
}

function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (statusSelect) statusSelect.value = '';
    if (clientSelect) clientSelect.value = '';
    filterTable();
}

let timer;
if (searchInput) searchInput.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(filterTable, 300); });
if (statusSelect) statusSelect.addEventListener('change', filterTable);
if (clientSelect) clientSelect.addEventListener('change', filterTable);

window.resetFilters = resetFilters;
window.closeModal = closeModal;
</script>
@endpush
