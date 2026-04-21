{{-- resources/views/admin/billing/create-invoice.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle facture - NovaTech Admin')
@section('page-title', 'Créer une facture')

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
        border-color: var(--brand-error) !important;
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

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.billing.invoices.store') }}" method="POST">
        @csrf

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Créez une nouvelle facture pour un client. Les champs marqués d'un <span class="optional">*</span> sont obligatoires.</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Client <span class="optional">*</span></label>
                <select name="client_id" class="form-select @error('client_id') error-border @enderror" required>
                    <option value="">Sélectionnez un client</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId ?? '') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} - {{ $client->email }}
                    </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="form-help" style="color: var(--brand-error);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-tags"></i> Service (optionnel)</label>
                <select name="service_id" class="form-select">
                    <option value="">Sélectionnez un service (optionnel)</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->title }}
                    </option>
                    @endforeach
                </select>
                <div class="form-help"><i class="fas fa-info-circle"></i> Si aucun service n'est sélectionné, vous pourrez saisir un nom personnalisé</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-heading"></i> Nom du service</label>
                <input type="text" name="service_name" class="form-input" value="{{ old('service_name') }}" placeholder="Ex: Création site vitrine">
                <div class="form-help"><i class="fas fa-info-circle"></i> Utilisé si aucun service n'est sélectionné</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-money-bill"></i> Montant HT <span class="optional">*</span></label>
                <input type="number" name="amount" class="form-input @error('amount') error-border @enderror" step="1" min="0" value="{{ old('amount') }}" placeholder="0" required>
                <div class="form-help"><i class="fas fa-info-circle"></i> Montant hors taxes en FCFA</div>
                @error('amount')
                    <div class="form-help" style="color: var(--brand-error);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-percent"></i> Taux de TVA (%)</label>
                <input type="number" name="tax_rate" class="form-input" step="0.01" min="0" max="100" value="{{ old('tax_rate', 18) }}">
                <div class="form-help"><i class="fas fa-info-circle"></i> TVA par défaut: 18%</div>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-calendar-alt"></i> Délai d'échéance (jours)</label>
                <input type="number" name="due_days" class="form-input" min="1" max="365" value="{{ old('due_days', 30) }}">
                <div class="form-help"><i class="fas fa-info-circle"></i> Nombre de jours avant échéance (30 par défaut)</div>
            </div>

            <div class="form-group-full">
                <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-textarea" rows="3" placeholder="Description détaillée de la prestation...">{{ old('description') }}</textarea>
                <div class="form-help"><i class="fas fa-info-circle"></i> Description qui apparaîtra sur la facture</div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.billing.invoices.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Créer la facture
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Mise à jour automatique du nom du service si un service est sélectionné
    const serviceSelect = document.querySelector('select[name="service_id"]');
    const serviceNameInput = document.querySelector('input[name="service_name"]');

    if (serviceSelect && serviceNameInput) {
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
