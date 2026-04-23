{{-- resources/views/admin/billing/edit-invoice.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier la facture - NovaTech Admin')
@section('page-title', 'Modifier la facture')

@push('styles')
<style>
    .form-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 0;
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
        width: 1rem;
        color: var(--brand-primary);
    }

    .form-label .optional {
        font-weight: 400;
        color: var(--text-tertiary);
        font-size: 0.7rem;
        margin-left: 0.25rem;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input[readonly], .form-input:disabled {
        background: var(--bg-tertiary);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .form-select:disabled {
        background: var(--bg-tertiary);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .form-help {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.375rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
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
        border: none;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid var(--border-light);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .error-border {
        border-color: #ef4444 !important;
    }

    .info-box {
        background: rgba(59, 130, 246, 0.05);
        border: 1px solid rgba(59, 130, 246, 0.1);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-box i {
        font-size: 1.25rem;
        color: #3b82f6;
    }

    .info-box p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .warning-box {
        background: rgba(245, 158, 11, 0.05);
        border: 1px solid rgba(245, 158, 11, 0.1);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .warning-box i {
        font-size: 1.25rem;
        color: #f59e0b;
    }

    .warning-box p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .current-values {
        background: var(--bg-primary);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-light);
    }

    .current-values h4 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0 0 0.75rem 0;
        color: var(--text-primary);
    }

    .current-values .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .current-values .value-item {
        font-size: 0.875rem;
    }

    .current-values .value-label {
        color: var(--text-tertiary);
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    .current-values .value-number {
        font-weight: 700;
        color: var(--brand-primary);
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
        .current-values .values-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.billing.invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $isLocked = in_array($invoice->status, ['paid', 'partially_paid']);
        @endphp

        @if($isLocked)
        <div class="warning-box">
            <i class="fas fa-lock"></i>
            <p>
                Cette facture ne peut pas être modifiée car elle est
                @if($invoice->status === 'paid') déjà payée.
                @elseif($invoice->status === 'partially_paid') partiellement payée.
                @endif
            </p>
        </div>
        @else
        <div class="info-box">
            <i class="fas fa-edit"></i>
            <p>Modifiez les informations de la facture <strong>{{ $invoice->invoice_number }}</strong>. Les champs marqués d'un <span class="optional">*</span> sont obligatoires.</p>
        </div>
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-hashtag"></i> Numéro de facture</label>
                <input type="text" class="form-input" value="{{ $invoice->invoice_number }}" readonly disabled>
                <div class="form-help"><i class="fas fa-info-circle"></i> Le numéro de facture est généré automatiquement</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Client <span class="optional">*</span></label>
                <select name="client_id" class="form-select @error('client_id') error-border @enderror" {{ $isLocked ? 'disabled' : '' }} required>
                    <option value="">Sélectionnez un client</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} - {{ $client->email }}
                    </option>
                    @endforeach
                </select>
                @if($isLocked)
                <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                @endif
                @error('client_id')
                    <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-tags"></i> Service (optionnel)</label>
                <select name="service_id" class="form-select" {{ $isLocked ? 'disabled' : '' }}>
                    <option value="">Sélectionnez un service (optionnel)</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id', $invoice->service_id) == $service->id ? 'selected' : '' }}>
                        {{ $service->title }}
                    </option>
                    @endforeach
                </select>
                <div class="form-help"><i class="fas fa-info-circle"></i> Si aucun service n'est sélectionné, vous pourrez saisir un nom personnalisé</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-heading"></i> Nom du service</label>
                <input type="text" name="service_name" class="form-input" value="{{ old('service_name', $invoice->service_name) }}" {{ $isLocked ? 'readonly' : '' }} placeholder="Ex: Création site vitrine">
                <div class="form-help"><i class="fas fa-info-circle"></i> Utilisé si aucun service n'est sélectionné</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-money-bill"></i> Montant <span class="optional">*</span></label>
                <input type="number" name="total" class="form-input @error('total') error-border @enderror" step="1" min="0" value="{{ old('total', $invoice->total) }}" placeholder="0" {{ $isLocked ? 'readonly' : '' }} required>
                <div class="form-help"><i class="fas fa-info-circle"></i> Montant total en FCFA</div>
                @error('total')
                    <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-calendar-alt"></i> Date d'émission</label>
                <input type="date" name="issue_date" class="form-input" value="{{ old('issue_date', $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : '') }}" {{ $isLocked ? 'readonly' : '' }}>
                <div class="form-help"><i class="fas fa-info-circle"></i> Date d'émission de la facture</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-calendar-alt"></i> Date d'échéance</label>
                <input type="date" name="due_date" class="form-input" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" {{ $isLocked ? 'readonly' : '' }}>
                <div class="form-help"><i class="fas fa-info-circle"></i> Date limite de paiement</div>
            </div>

            <div class="form-group-full">
                <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-textarea" rows="3" placeholder="Description détaillée de la prestation..." {{ $isLocked ? 'readonly' : '' }}>{{ old('description', $invoice->description) }}</textarea>
                <div class="form-help"><i class="fas fa-info-circle"></i> Description qui apparaîtra sur la facture</div>
            </div>
        </div>

        @if($invoice->status !== 'draft' && $invoice->status !== 'paid')
        <div class="current-values">
            <h4>Informations actuelles de la facture</h4>
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-label">Montant total</div>
                    <div class="value-number">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="value-item">
                    <div class="value-label">Déjà payé</div>
                    <div class="value-number">{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="value-item">
                    <div class="value-label">Reste à payer</div>
                    <div class="value-number">{{ number_format($invoice->remaining_amount, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            @if(!$isLocked)
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Mise à jour automatique du nom du service si un service est sélectionné
    const serviceSelect = document.querySelector('select[name="service_id"]');
    const serviceNameInput = document.querySelector('input[name="service_name"]');

    if (serviceSelect && serviceNameInput && !serviceSelect.disabled) {
        // Récupérer les noms des services depuis les options
        const serviceNames = {};
        serviceSelect.querySelectorAll('option').forEach(option => {
            if (option.value) {
                serviceNames[option.value] = option.text.split(' - ')[0];
            }
        });

        serviceSelect.addEventListener('change', function() {
            if (this.value && serviceNames[this.value]) {
                serviceNameInput.value = serviceNames[this.value];
                serviceNameInput.readOnly = true;
                serviceNameInput.style.background = 'var(--bg-tertiary)';
            } else {
                serviceNameInput.value = '';
                serviceNameInput.readOnly = false;
                serviceNameInput.style.background = '';
            }
        });

        // Si déjà un service sélectionné
        if (serviceSelect.value && serviceNames[serviceSelect.value]) {
            serviceNameInput.value = serviceNames[serviceSelect.value];
            serviceNameInput.readOnly = true;
            serviceNameInput.style.background = 'var(--bg-tertiary)';
        }
    }
</script>
@endpush
