{{-- resources/views/admin/clients/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($client) ? 'Modifier le client - NovaTech Admin' : 'Nouveau client - NovaTech Admin')
@section('page-title', isset($client) ? 'Modifier le client' : 'Nouveau client')

@push('styles')
<style>
    /* ============================================
       CLIENTS FORM — aligned with design system
    ============================================ */

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
    }
    .breadcrumb a { color: var(--text-tertiary); transition: color var(--transition-fast); }
    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.6rem; }

    /* ── Card ── */
    .card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
        margin-bottom: 1.25rem;
    }

    .card-header {
        padding: 1rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        background: rgba(59,130,246,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-primary);
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .card-header h2 {
        font-size: 0.9375rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* ── Section divider ── */
    .form-section {
        margin-bottom: 1.75rem;
    }

    .form-section-title {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        padding-bottom: 0.625rem;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-section-title i { color: var(--brand-primary); }

    /* ── Grid ── */
    .form-grid {
        display: grid;
        gap: 1rem;
    }
    .form-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .form-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .form-grid-4 { grid-template-columns: repeat(4, 1fr); }
    .col-span-2 { grid-column: span 2; }
    .col-span-full { grid-column: 1 / -1; }

    @media (max-width: 992px) {
        .form-grid-3, .form-grid-4 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .form-grid-2, .form-grid-3, .form-grid-4 { grid-template-columns: 1fr; }
        .col-span-2 { grid-column: span 1; }
    }

    /* ── Form elements ── */
    .form-group { display: flex; flex-direction: column; gap: 0.375rem; }

    .form-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    .form-label i { width: 0.875rem; text-align: center; }
    .required { color: var(--brand-error); }

    .form-control {
        width: 100%;
        padding: 0.5625rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-family: inherit;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        outline: none;
    }
    .form-control::placeholder { color: var(--text-tertiary); }
    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .form-control.is-invalid { border-color: var(--brand-error); }
    .form-control:disabled {
        opacity: 0.6;
        background: var(--bg-tertiary);
        cursor: not-allowed;
    }
    textarea.form-control { resize: vertical; min-height: 80px; }

    .form-help {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        line-height: 1.4;
    }

    .invalid-feedback {
        font-size: 0.75rem;
        color: var(--brand-error);
    }

    /* ── Toggle switch ── */
    .toggle-group {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.625rem 0;
    }

    .toggle-input {
        width: 2.25rem;
        height: 1.25rem;
        border-radius: var(--radius-full);
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        appearance: none;
        position: relative;
        transition: background var(--transition-fast), border-color var(--transition-fast);
        flex-shrink: 0;
    }
    .toggle-input::before {
        content: '';
        position: absolute;
        width: 0.875rem;
        height: 0.875rem;
        border-radius: 50%;
        background: white;
        top: 0.125rem;
        left: 0.125rem;
        transition: transform var(--transition-fast);
        box-shadow: var(--shadow-xs);
    }
    .toggle-input:checked {
        background: var(--brand-success);
        border-color: var(--brand-success);
    }
    .toggle-input:checked::before {
        transform: translateX(1rem);
    }

    .toggle-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        cursor: pointer;
        user-select: none;
    }

    /* ── Client type selector ── */
    .type-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.75rem;
    }

    .type-btn {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem 1rem;
        border-radius: var(--radius-md);
        background: var(--bg-tertiary);
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: all var(--transition-fast);
        text-align: left;
    }
    .type-btn:hover {
        background: var(--bg-hover);
        border-color: var(--border-medium);
    }
    .type-btn.active {
        border-color: var(--brand-primary);
        background: rgba(59,130,246,0.08);
    }

    .type-btn-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        background: var(--bg-elevated);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--text-tertiary);
        flex-shrink: 0;
        transition: all var(--transition-fast);
    }
    .type-btn.active .type-btn-icon {
        background: rgba(59,130,246,0.15);
        color: var(--brand-primary);
    }

    .type-btn-text strong {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.125rem;
    }
    .type-btn-text span {
        font-size: 0.75rem;
        color: var(--text-tertiary);
    }
    .type-btn.active .type-btn-text strong { color: var(--brand-primary); }

    /* ── Upload area ── */
    .upload-area {
        border: 1.5px dashed var(--border-medium);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        background: var(--bg-primary);
    }
    .upload-area:hover {
        border-color: var(--brand-primary);
        background: rgba(59,130,246,0.04);
    }
    .upload-area i {
        font-size: 1.75rem;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
        display: block;
    }
    .upload-area p { font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem; }

    .image-preview {
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.75rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
    }
    .image-preview img {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
        flex-shrink: 0;
    }
    .preview-info { flex: 1; min-width: 0; }
    .preview-name {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-remove {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }
    .btn-remove:hover { background: rgba(239,68,68,0.1); color: var(--brand-error); }

    /* ── Alert ── */
    .alert {
        padding: 0.875rem 1rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
    }
    .alert-error {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.2);
        color: var(--brand-error);
    }
    .alert ul { margin: 0.5rem 0 0 1rem; padding: 0; }
    .alert li { margin-bottom: 0.25rem; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5625rem 1.125rem;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all var(--transition-fast);
        white-space: nowrap;
    }
    .btn-primary { background: var(--brand-primary); color: #fff; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-ghost {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.625rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-light);
        margin-top: 0.5rem;
    }

    .company-fields { transition: opacity var(--transition-base), max-height var(--transition-base); }
    .company-fields.hidden { display: none; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.clients.index') }}">Clients</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($client) ? 'Modifier' : 'Nouveau client' }}</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas {{ isset($client) ? 'fa-edit' : 'fa-user-plus' }}"></i>
        </div>
        <h2>{{ isset($client) ? 'Modifier le client' : 'Nouveau client' }}</h2>
    </div>

    <div class="card-body">

        {{-- Erreurs de validation --}}
        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form method="POST"
              action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}"
              enctype="multipart/form-data"
              id="clientForm">
            @csrf
            @isset($client) @method('PUT') @endisset

            {{-- Type de client --}}
            <div class="type-selector">
                <div class="type-btn {{ old('client_type', $client->client_type ?? 'company') == 'company' ? 'active' : '' }}" data-type="company">
                    <div class="type-btn-icon"><i class="fas fa-building"></i></div>
                    <div class="type-btn-text">
                        <strong>Entreprise</strong>
                        <span>Professionnel / société</span>
                    </div>
                </div>
                <div class="type-btn {{ old('client_type', $client->client_type ?? 'company') == 'individual' ? 'active' : '' }}" data-type="individual">
                    <div class="type-btn-icon"><i class="fas fa-user"></i></div>
                    <div class="type-btn-text">
                        <strong>Particulier</strong>
                        <span>Client individuel</span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="client_type" id="client_type"
                   value="{{ old('client_type', $client->client_type ?? 'company') }}">

            {{-- Champs entreprise --}}
            <div id="companyFields"
                 class="form-section company-fields {{ old('client_type', $client->client_type ?? 'company') == 'company' ? '' : 'hidden' }}">
                <div class="form-section-title">
                    <i class="fas fa-building"></i> Informations entreprise
                </div>
                <div class="form-grid form-grid-2">
                    <div class="col-span-2">
                        <div class="form-group">
                            <label class="form-label" for="company_name">
                                <i class="fas fa-building"></i> Nom de l'entreprise
                                <span class="required" id="companyNameRequired">*</span>
                            </label>
                            <input type="text"
                                   id="company_name"
                                   name="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $client->company_name ?? '') }}"
                                   placeholder="Nom de l'entreprise">
                            @error('company_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tax_number">
                            <i class="fas fa-id-card"></i> IFU (N° fiscal)
                        </label>
                        <input type="text"
                               id="tax_number"
                               name="tax_number"
                               class="form-control @error('tax_number') is-invalid @enderror"
                               value="{{ old('tax_number', $client->tax_number ?? '') }}"
                               placeholder="Numéro IFU">
                        @error('tax_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="website">
                            <i class="fas fa-globe"></i> Site web
                        </label>
                        <input type="url"
                               id="website"
                               name="website"
                               class="form-control @error('website') is-invalid @enderror"
                               value="{{ old('website', $client->website ?? '') }}"
                               placeholder="https://">
                        @error('website')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Informations générales --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-user"></i> Informations générales
                </div>
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <i class="fas fa-user"></i> Nom complet <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $client->name ?? '') }}"
                               required
                               placeholder="Nom et prénom">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i> Email <span class="required">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $client->email ?? '') }}"
                               required
                               placeholder="contact@exemple.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            <i class="fas fa-phone"></i> Téléphone
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $client->phone ?? '') }}"
                               placeholder="+229 XX XX XX XX">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="gender">
                            <i class="fas fa-venus-mars"></i> Genre
                        </label>
                        <select id="gender" name="gender"
                                class="form-control @error('gender') is-invalid @enderror">
                            <option value="">Non spécifié</option>
                            <option value="M" {{ old('gender', $client->gender ?? '') == 'M' ? 'selected' : '' }}>Homme</option>
                            <option value="F" {{ old('gender', $client->gender ?? '') == 'F' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Adresse --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-map-marker-alt"></i> Adresse
                </div>
                <div class="form-grid form-grid-2">
                    <div class="col-span-2 form-group">
                        <label class="form-label" for="address">
                            <i class="fas fa-road"></i> Adresse complète
                        </label>
                        <input type="text"
                               id="address"
                               name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $client->address ?? '') }}"
                               placeholder="Rue, quartier, BP…">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="city">
                            <i class="fas fa-city"></i> Ville
                        </label>
                        <input type="text"
                               id="city"
                               name="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city', $client->city ?? '') }}"
                               placeholder="Cotonou, Porto-Novo…">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="country">
                            <i class="fas fa-globe"></i> Pays
                        </label>
                        <input type="text"
                               id="country"
                               name="country"
                               class="form-control @error('country') is-invalid @enderror"
                               value="{{ old('country', $client->country ?? 'Bénin') }}"
                               placeholder="Pays">
                        @error('country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Contact référent --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-user-tie"></i> Personne de contact
                </div>
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="contact_name">
                            <i class="fas fa-user-tie"></i> Nom du référent
                        </label>
                        <input type="text"
                               id="contact_name"
                               name="contact_name"
                               class="form-control @error('contact_name') is-invalid @enderror"
                               value="{{ old('contact_name', $client->contact_name ?? '') }}"
                               placeholder="Nom et prénom">
                        <span class="form-help">Personne à contacter pour ce client</span>
                        @error('contact_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_position">
                            <i class="fas fa-briefcase"></i> Poste / Fonction
                        </label>
                        <input type="text"
                               id="contact_position"
                               name="contact_position"
                               class="form-control @error('contact_position') is-invalid @enderror"
                               value="{{ old('contact_position', $client->contact_position ?? '') }}"
                               placeholder="Directeur, Responsable, Gérant…">
                        @error('contact_position')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Facturation & Statut --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-file-invoice"></i> Facturation &amp; Statut
                </div>
                <div class="form-grid form-grid-3">
                    <div class="form-group">
                        <label class="form-label" for="billing_cycle">
                            <i class="fas fa-chart-line"></i> Cycle de facturation
                        </label>
                        <select id="billing_cycle" name="billing_cycle"
                                class="form-control @error('billing_cycle') is-invalid @enderror">
                            <option value="monthly"   {{ old('billing_cycle', $client->billing_cycle ?? 'monthly') == 'monthly'   ? 'selected' : '' }}>Mensuel</option>
                            <option value="quarterly" {{ old('billing_cycle', $client->billing_cycle ?? '') == 'quarterly' ? 'selected' : '' }}>Trimestriel</option>
                            <option value="yearly"    {{ old('billing_cycle', $client->billing_cycle ?? '') == 'yearly'    ? 'selected' : '' }}>Annuel</option>
                            <option value="one_time"  {{ old('billing_cycle', $client->billing_cycle ?? '') == 'one_time'  ? 'selected' : '' }}>Paiement unique</option>
                        </select>
                        @error('billing_cycle')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Factures par email
                        </label>
                        <div class="toggle-group">
                            <input type="checkbox"
                                   class="toggle-input"
                                   id="invoice_by_email"
                                   name="invoice_by_email"
                                   value="1"
                                   {{ old('invoice_by_email', $client->invoice_by_email ?? true) ? 'checked' : '' }}>
                            <label class="toggle-label" for="invoice_by_email">Envoi automatique</label>
                        </div>
                        <span class="form-help">Les factures seront envoyées à l'email du client</span>
                        @error('invoice_by_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on"></i> Statut du compte
                        </label>
                        <div class="toggle-group">
                            <input type="checkbox"
                                   class="toggle-input"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $client->is_active ?? true) ? 'checked' : '' }}>
                            <label class="toggle-label" for="is_active">Client actif</label>
                        </div>
                        <span class="form-help">Les clients inactifs n'apparaissent pas sur le site public</span>
                        @error('is_active')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Logo / Avatar --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-image"></i> <span id="logoSectionTitle">Logo / Identité visuelle</span>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="logoLabelText">Logo de l'entreprise</span>
                    </label>
                    <div class="upload-area" onclick="document.getElementById('logoInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p id="uploadText">Cliquez pour télécharger le logo</p>
                        <span class="form-help">PNG, JPG — max 2 MB · recommandé 200 × 200 px</span>
                        <input type="file" id="logoInput" name="logo" accept="image/*" style="display:none;">
                    </div>
                    <div id="logoPreviewContainer">
                        @if(isset($client) && $client->logo)
                        <div class="image-preview" id="logoPreview">
                            <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo">
                            <div class="preview-info">
                                <div class="preview-name">Logo actuel</div>
                                <span class="form-help">{{ basename($client->logo) }}</span>
                            </div>
                            <button type="button" class="btn-remove" data-preview="logoPreview">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @error('logo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Footer --}}
            <div class="form-footer">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    {{ isset($client) ? 'Mettre à jour' : 'Créer le client' }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Type de client ──
    const typeBtns      = document.querySelectorAll('.type-btn');
    const clientTypeIn  = document.getElementById('client_type');
    const companyFields = document.getElementById('companyFields');
    const companyNameIn = document.getElementById('company_name');
    const companyReq    = document.getElementById('companyNameRequired');
    const logoLabelText = document.getElementById('logoLabelText');
    const uploadText    = document.getElementById('uploadText');

    function setClientType(type) {
        clientTypeIn.value = type;
        typeBtns.forEach(b => b.classList.toggle('active', b.dataset.type === type));

        const isCompany = type === 'company';
        companyFields.classList.toggle('hidden', !isCompany);
        if (companyReq)    companyReq.style.display = isCompany ? 'inline' : 'none';
        if (companyNameIn) companyNameIn.required   = isCompany;
        logoLabelText.textContent = isCompany ? 'Logo de l\'entreprise' : 'Photo / Avatar';
        uploadText.textContent    = isCompany ? 'Cliquez pour télécharger le logo' : 'Cliquez pour télécharger une photo';
    }

    typeBtns.forEach(btn => btn.addEventListener('click', () => setClientType(btn.dataset.type)));

    // ── Aperçu logo ──
    const logoInput            = document.getElementById('logoInput');
    const logoPreviewContainer = document.getElementById('logoPreviewContainer');

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const existing = document.getElementById('logoPreview');
                if (existing) existing.remove();

                const div = document.createElement('div');
                div.id        = 'logoPreview';
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Aperçu">
                    <div class="preview-info">
                        <div class="preview-name">${clientTypeIn.value === 'company' ? 'Nouveau logo' : 'Nouvelle photo'}</div>
                        <span class="form-help">${(logoInput.files[0].size / 1024).toFixed(1)} KB</span>
                    </div>
                    <button type="button" class="btn-remove" data-preview="logoPreview">
                        <i class="fas fa-trash-alt"></i>
                    </button>`;
                logoPreviewContainer.appendChild(div);
                bindRemove();
            };
            reader.readAsDataURL(this.files[0]);
        });
    }

    function bindRemove() {
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                const el = document.getElementById(this.dataset.preview);
                if (el) el.remove();
                if (logoInput) logoInput.value = '';
            };
        });
    }
    bindRemove();

    // ── Submit ──
    document.getElementById('clientForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ isset($client) ? "Mise à jour…" : "Création…" }}';
    });
})();
</script>
@endpush
