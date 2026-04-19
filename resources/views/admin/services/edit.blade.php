{{-- resources/views/admin/services/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier le service - NovaTech Admin')
@section('page-title', 'Modifier le service')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .admin-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .card-header-custom h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header-custom h2 i {
        color: var(--brand-primary);
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .steps-wrapper {
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--bg-tertiary);
        border-radius: var(--radius-lg);
    }

    .steps-indicator {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .step-item {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: var(--bg-primary);
        border-radius: var(--radius-md);
        color: var(--text-tertiary);
        transition: all var(--transition-fast);
    }

    .step-item.active {
        background: var(--brand-primary);
        color: white;
    }

    .step-item.completed {
        background: var(--brand-success);
        color: white;
    }

    .step-number {
        width: 2rem;
        height: 2rem;
        border-radius: var(--radius-full);
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .step-info {
        flex: 1;
    }

    .step-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.125rem;
    }

    .step-desc {
        font-size: 0.625rem;
        opacity: 0.8;
    }

    .step-connector {
        width: 2rem;
        height: 2px;
        background: var(--border-medium);
        flex-shrink: 0;
    }

    .step-item.completed + .step-connector {
        background: var(--brand-success);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -0.75rem;
    }

    .row > [class*="col-"] {
        padding: 0.75rem;
    }

    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    @media (max-width: 992px) {
        .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .steps-indicator {
            flex-direction: column;
        }

        .step-connector {
            width: 2px;
            height: 1rem;
        }
    }

    .form-group-camille {
        margin-bottom: 1rem;
    }

    .form-label-camille {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label-camille i {
        margin-right: 0.5rem;
        width: 1rem;
    }

    .form-input-camille {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        outline: none;
    }

    .form-input-camille:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .form-input-camille.is-invalid {
        border-color: var(--brand-error);
    }

    textarea.form-input-camille {
        resize: vertical;
        min-height: 100px;
    }

    .text-danger {
        color: var(--brand-error);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .alert-camille {
        padding: 1rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-camille-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: var(--brand-error);
    }

    .alert-camille-info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        color: var(--brand-primary);
    }

    .alert-camille-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        border-radius: 1rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        appearance: none;
        position: relative;
        transition: all var(--transition-fast);
    }

    .form-check-input:checked {
        background: var(--brand-success);
        border-color: var(--brand-success);
    }

    .form-check-input:checked::before {
        transform: translateX(1.25rem);
    }

    .form-check-input::before {
        content: '';
        position: absolute;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: white;
        top: 0.125rem;
        left: 0.125rem;
        transition: transform var(--transition-fast);
    }

    .form-check-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        cursor: pointer;
    }

    .btn-camille-primary {
        padding: 0.625rem 1.5rem;
        border-radius: var(--radius-md);
        background: var(--brand-primary);
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-camille-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
    }

    .btn-camille-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-camille-outline {
        padding: 0.625rem 1.5rem;
        border-radius: var(--radius-md);
        background: transparent;
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: 1px solid var(--border-medium);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-camille-outline:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .features-container {
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        background: var(--bg-primary);
        padding: 0.5rem;
    }

    .features-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--bg-tertiary);
        padding: 0.5rem;
        border-radius: var(--radius-md);
    }

    .feature-item input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
    }

    .feature-item .remove-feature {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }

    .feature-item .remove-feature:hover {
        color: var(--brand-error);
    }

    .add-feature-btn {
        width: 100%;
        padding: 0.5rem;
        background: var(--bg-tertiary);
        border: 1px dashed var(--border-medium);
        border-radius: var(--radius-md);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .add-feature-btn:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .form-help {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.25rem;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .step-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Styles pour l'aperçu icône et couleur */
    .icon-color-preview {
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 0.75rem;
    }

    .preview-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--bg-primary);
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px solid var(--border-light);
    }

    .preview-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(99, 102, 241, 0.1);
        transition: all 0.3s ease;
    }

    .preview-icon i {
        font-size: 2rem;
        transition: all 0.3s ease;
    }

    .preview-info {
        flex: 1;
    }

    .preview-info .preview-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .preview-info .preview-text {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    /* Sélecteur d'icônes */
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-top: 0.5rem;
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        background: var(--bg-primary);
    }

    @media (min-width: 768px) and (max-width: 1199px) {
        .icon-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .icon-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .icon-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 0.5rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        background: var(--bg-tertiary);
    }

    .icon-option:hover {
        background: var(--bg-hover);
        border-color: var(--border-light);
        transform: translateY(-2px);
    }

    .icon-option.selected {
        background: rgba(59, 130, 246, 0.15);
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 1px var(--brand-primary);
    }

    .icon-option i {
        font-size: 1.5rem;
        color: var(--text-primary);
    }

    .icon-option span {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-align: center;
    }

    /* Sélecteur de couleurs */
    .color-options {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .color-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-md);
        transition: all 0.2s;
        min-width: 70px;
    }

    .color-option:hover {
        background: var(--bg-tertiary);
        transform: translateY(-2px);
    }

    .color-option.selected {
        background: rgba(59, 130, 246, 0.1);
    }

    .color-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid transparent;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .color-option.selected .color-circle {
        border-color: var(--text-primary);
        transform: scale(1.05);
        box-shadow: 0 0 0 2px white, 0 0 0 4px var(--text-primary);
    }

    .color-option span {
        font-size: 0.7rem;
        color: var(--text-secondary);
    }

    .ck-editor__editable {
        min-height: 250px;
        background: var(--bg-primary);
        border-radius: 0 0 var(--radius-md) var(--radius-md);
    }

    .ck-content {
        font-size: 0.875rem;
        line-height: 1.6;
        color: var(--text-primary);
    }

    .ck-editor__top {
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .ck.ck-editor__main>.ck-editor__editable {
        background: var(--bg-primary);
    }

    .ck.ck-toolbar {
        background: var(--bg-tertiary);
        border-color: var(--border-medium);
    }

    .icon-grid::-webkit-scrollbar {
        width: 6px;
    }

    .icon-grid::-webkit-scrollbar-track {
        background: var(--bg-tertiary);
        border-radius: 3px;
    }

    .icon-grid::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 3px;
    }

    .icon-grid::-webkit-scrollbar-thumb:hover {
        background: var(--brand-primary);
    }
</style>
@endpush

@section('content')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-edit"></i> Modifier le service</h2>
    </div>

    <div class="card-body-custom">
        @if(session('success'))
            <div class="alert-camille alert-camille-success mb-4">
                <i class="fas fa-check-circle fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-camille alert-camille-error mb-4">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                <div>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.update', $service) }}" id="serviceForm">
            @csrf
            @method('PUT')

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations générales</div>
                            <div class="step-desc">Titre, description, icône</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Contenu détaillé</div>
                            <div class="step-desc">Description complète, fonctionnalités</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-info">
                            <div class="step-title">Publication</div>
                            <div class="step-desc">Options, validation</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 1 - Informations générales -->
            <div id="step1" class="step-content active">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-heading"></i> Titre du service <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-input-camille @error('title') is-invalid @enderror"
                                   value="{{ old('title', $service->title) }}"
                                   required
                                   placeholder="Ex: Développement Web"
                                   maxlength="255">
                            <div class="form-help">Titre court et percutant (max 255 caractères)</div>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-align-left"></i> Description courte <span class="text-danger">*</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-input-camille @error('description') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Brève description du service (apparaît dans les listes)...">{{ old('description', $service->description) }}</textarea>
                            <div class="form-help">Description concise (max 500 caractères)</div>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-icons"></i> Icône <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" id="icon" name="icon" value="{{ old('icon', $service->icon) }}">
                            <div class="icon-grid" id="iconGrid">
                                @foreach($icons as $iconKey => $iconLabel)
                                <div class="icon-option {{ old('icon', $service->icon) == $iconKey ? 'selected' : '' }}" data-icon="{{ $iconKey }}">
                                    <i class="fas fa-{{ $iconKey }}"></i>
                                    <span>{{ $iconLabel }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-help">Cliquez sur une icône pour la sélectionner - {{ count($icons) }} icônes disponibles</div>
                            @error('icon')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-palette"></i> Couleur de l'icône <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" id="icon_color" name="icon_color" value="{{ old('icon_color', $service->icon_color) }}">
                            <div class="color-options" id="colorOptions">
                                @foreach($iconColors as $colorKey => $colorLabel)
                                @php
                                    $colorMap = [
                                        'indigo' => '#6366f1',
                                        'cyan' => '#06b6d4',
                                        'emerald' => '#10b981',
                                        'rose' => '#f43f5e',
                                        'amber' => '#f59e0b',
                                        'violet' => '#8b5cf6'
                                    ];
                                    $colorValue = $colorMap[$colorKey] ?? '#6366f1';
                                @endphp
                                <div class="color-option {{ old('icon_color', $service->icon_color) == $colorKey ? 'selected' : '' }}" data-color="{{ $colorKey }}">
                                    <div class="color-circle" style="background: {{ $colorValue }};"></div>
                                    <span>{{ $colorLabel }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-help">Cliquez sur une couleur pour la sélectionner</div>
                            @error('icon_color')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Aperçu en direct -->
                    <div class="col-12">
                        <div class="icon-color-preview">
                            <label class="form-label-camille">
                                <i class="fas fa-eye"></i> Aperçu
                            </label>
                            <div class="preview-card" id="livePreview">
                                <div class="preview-icon" id="previewIcon">
                                    <i class="fas fa-{{ old('icon', $service->icon) }}"></i>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-title" id="previewTitle">{{ old('title', $service->title) }}</div>
                                    <div class="preview-text" id="previewDescription">{{ old('description', $service->description) ?: 'Description courte du service...' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-sort-numeric-down"></i> Ordre d'affichage
                            </label>
                            <input type="number"
                                   id="order"
                                   name="order"
                                   class="form-input-camille @error('order') is-invalid @enderror"
                                   value="{{ old('order', $service->order) }}">
                            <div class="form-help">Plus le chiffre est petit, plus le service apparaît en haut</div>
                            @error('order')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Contenu détaillé -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-file-alt"></i> Description complète
                            </label>
                            <textarea id="full_description"
                                      name="full_description"
                                      class="@error('full_description') is-invalid @enderror"
                                      rows="12"
                                      placeholder="Description détaillée du service...">{{ old('full_description', $service->full_description) }}</textarea>
                            @error('full_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-list-check"></i> Fonctionnalités / Points clés
                            </label>
                            <div class="features-container">
                                <div class="features-list" id="featuresList">
                                    @php
                                        $features = old('features', $service->features ?? []);
                                        if(is_string($features)) {
                                            $features = json_decode($features, true) ?: [];
                                        }
                                    @endphp
                                    @if(!empty($features) && is_array($features))
                                        @foreach($features as $feature)
                                            @if(!empty($feature))
                                            <div class="feature-item">
                                                <i class="fas fa-grip-vertical text-muted"></i>
                                                <input type="text" name="features[]" value="{{ $feature }}" placeholder="Ex: Optimisation SEO">
                                                <button type="button" class="remove-feature">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="add-feature-btn" id="addFeatureBtn">
                                    <i class="fas fa-plus"></i> Ajouter une fonctionnalité
                                </button>
                            </div>
                            <div class="form-help">Listez les points forts ou fonctionnalités de ce service</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 3 - Publication -->
            <div id="step3" class="step-content">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-eye"></i> Visibilité
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Activer le service
                                </label>
                            </div>
                            <div class="form-help">Décoché = service masqué sur le site</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-info-circle"></i> Récapitulatif
                            </label>
                            <div class="alert-camille alert-camille-info" style="margin-top: 0.25rem;">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Prêt à mettre à jour ?</strong>
                                    <div class="small mt-1">Vérifiez que toutes les informations sont correctes avant validation.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="prevBtn" class="btn-camille-outline" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Précédent
                </button>
                <div>
                    <a href="{{ route('admin.services.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

    // ==================== CKEDITOR ====================
    let editorInstance = null;

    ClassicEditor.create(document.querySelector('#full_description'), {
        toolbar: {
            items: ['heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|', 'alignment', '|',
                    'link', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraphe' },
                { model: 'heading2', view: 'h2', title: 'Titre 2' },
                { model: 'heading3', view: 'h3', title: 'Titre 3' }
            ]
        },
        language: 'fr',
        placeholder: 'Rédigez la description détaillée du service...'
    })
    .then(editor => {
        editorInstance = editor;
    })
    .catch(error => {
        console.error('Erreur CKEditor:', error);
    });

    // ==================== GESTION DES ÉTAPES ====================
    let currentStep = 1;
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const stepItems = document.querySelectorAll('.step-item');

    function updateSteps() {
        step1.classList.toggle('active', currentStep === 1);
        step2.classList.toggle('active', currentStep === 2);
        step3.classList.toggle('active', currentStep === 3);

        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';

        if (currentStep === 3) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }

        stepItems.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 === currentStep) step.classList.add('active');
            else if (index + 1 < currentStep) step.classList.add('completed');
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep1() {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const icon = document.getElementById('icon').value;
        const iconColor = document.getElementById('icon_color').value;

        if (!title) {
            alert('Veuillez saisir le titre du service');
            document.getElementById('title').focus();
            return false;
        }
        if (!description) {
            alert('Veuillez saisir une description courte');
            document.getElementById('description').focus();
            return false;
        }
        if (!icon) {
            alert('Veuillez sélectionner une icône');
            return false;
        }
        if (!iconColor) {
            alert('Veuillez sélectionner une couleur pour l\'icône');
            return false;
        }
        return true;
    }

    function validateStep2() {
        return true;
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep === 1 && validateStep1()) {
            currentStep = 2;
            updateSteps();
        }
        else if (currentStep === 2 && validateStep2()) {
            currentStep = 3;
            updateSteps();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

    // ==================== SÉLECTION DES ICÔNES ====================
    const iconInput = document.getElementById('icon');
    const iconOptions = document.querySelectorAll('.icon-option');
    const previewIcon = document.querySelector('#previewIcon i');
    const previewIconContainer = document.getElementById('previewIcon');

    iconOptions.forEach(option => {
        option.addEventListener('click', function() {
            const iconValue = this.dataset.icon;

            iconOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            iconInput.value = iconValue;

            if (previewIcon) {
                previewIcon.className = `fas fa-${iconValue}`;
            }

            const selectedColor = document.querySelector('.color-option.selected');
            if (selectedColor && previewIconContainer) {
                const colorValue = selectedColor.dataset.color;
                const colorMap = {
                    'indigo': '#6366f1',
                    'cyan': '#06b6d4',
                    'emerald': '#10b981',
                    'rose': '#f43f5e',
                    'amber': '#f59e0b',
                    'violet': '#8b5cf6'
                };
                const bgColor = colorMap[colorValue] || '#6366f1';
                previewIconContainer.style.background = `${bgColor}20`;
                previewIconContainer.style.color = bgColor;
            }
        });
    });

    // ==================== SÉLECTION DES COULEURS ====================
    const colorInput = document.getElementById('icon_color');
    const colorOptionsList = document.querySelectorAll('.color-option');

    const colorMapPreview = {
        'indigo': '#6366f1',
        'cyan': '#06b6d4',
        'emerald': '#10b981',
        'rose': '#f43f5e',
        'amber': '#f59e0b',
        'violet': '#8b5cf6'
    };

    colorOptionsList.forEach(option => {
        option.addEventListener('click', function() {
            const colorValue = this.dataset.color;

            colorOptionsList.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            colorInput.value = colorValue;

            const bgColor = colorMapPreview[colorValue] || '#6366f1';
            if (previewIconContainer) {
                previewIconContainer.style.background = `${bgColor}20`;
                previewIconContainer.style.color = bgColor;
            }
        });
    });

    // Initialisation des couleurs
    const initialColor = document.querySelector('.color-option.selected');
    if (initialColor && previewIconContainer) {
        const colorValue = initialColor.dataset.color;
        const bgColor = colorMapPreview[colorValue] || '#6366f1';
        previewIconContainer.style.background = `${bgColor}20`;
        previewIconContainer.style.color = bgColor;
    }

    // ==================== APERÇU EN DIRECT ====================
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const previewTitle = document.getElementById('previewTitle');
    const previewDescription = document.getElementById('previewDescription');

    if (titleInput) {
        titleInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Titre du service';
        });
    }

    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Description courte du service...';
        });
    }

    // ==================== GESTION DES FONCTIONNALITÉS ====================
    const featuresList = document.getElementById('featuresList');
    const addFeatureBtn = document.getElementById('addFeatureBtn');

    function addFeature(value = '') {
        const featureDiv = document.createElement('div');
        featureDiv.className = 'feature-item';
        featureDiv.innerHTML = `
            <i class="fas fa-grip-vertical text-muted"></i>
            <input type="text" name="features[]" value="${value.replace(/"/g, '&quot;')}" placeholder="Ex: Optimisation SEO">
            <button type="button" class="remove-feature">
                <i class="fas fa-times"></i>
            </button>
        `;

        featureDiv.querySelector('.remove-feature').addEventListener('click', function() {
            featureDiv.remove();
        });

        featuresList.appendChild(featureDiv);
    }

    if (addFeatureBtn) {
        addFeatureBtn.addEventListener('click', () => addFeature(''));
    }

    document.querySelectorAll('.feature-item .remove-feature').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.closest('.feature-item').remove();
        });
    });

    // ==================== VALIDATION FORMULAIRE ====================
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour en cours...';
    });
</script>
@endpush
