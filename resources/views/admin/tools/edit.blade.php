{{-- resources/views/admin/tools/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier l\'outil - NovaTech Admin')
@section('page-title', 'Modifier l\'outil')

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

    .image-preview {
        margin-top: 0.75rem;
    }

    .image-preview.hidden {
        display: none;
    }

    .preview-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
    }

    .current-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
    }

    .current-logo img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: var(--radius-md);
    }

    .current-logo-info {
        flex: 1;
    }

    .current-logo-info .label {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        text-transform: uppercase;
    }

    .current-logo-info .filename {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .remove-logo {
        color: var(--brand-error);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
    }

    .remove-logo:hover {
        background: rgba(239, 68, 68, 0.1);
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

    .align-items-center {
        align-items: center;
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

    .delete-tool-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .delete-tool-card {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: var(--radius-md);
        padding: 1rem;
    }

    .btn-danger-outline {
        background: transparent;
        border: 1px solid var(--brand-error);
        color: var(--brand-error);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger-outline:hover {
        background: var(--brand-error);
        color: white;
    }

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
        z-index: 1000;
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
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 450px;
        transform: scale(0.95);
        transition: transform 0.3s ease;
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
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1rem;
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
    }

    .modal-body .warning-text {
        color: var(--brand-warning);
        font-size: 0.875rem;
        margin-top: 0.75rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        border: none;
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-danger {
        background: var(--brand-error);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-tertiary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
@can('tools.edit')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-edit"></i> Modifier l'outil : {{ $tool->name }}</h2>
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

        <form method="POST" action="{{ route('admin.tools.update', $tool) }}" enctype="multipart/form-data" id="toolForm">
            @csrf
            @method('PUT')

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations générales</div>
                            <div class="step-desc">Nom, catégorie, description</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Icône & apparence</div>
                            <div class="step-desc">Icône, couleur, logo</div>
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
                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-heading"></i> Nom de l'outil <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-input-camille @error('name') is-invalid @enderror"
                                   value="{{ old('name', $tool->name) }}"
                                   required
                                   placeholder="Ex: Laravel, React, Docker...">
                            <div class="form-help">Nom exact de l'outil ou technologie</div>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-tag"></i> Catégorie <span class="text-danger">*</span>
                            </label>
                            <select id="category"
                                    name="category"
                                    class="form-input-camille @error('category') is-invalid @enderror"
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" {{ old('category', $tool->category) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">La catégorie détermine le regroupement</div>
                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-input-camille @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Description de l'outil...">{{ old('description', $tool->description) }}</textarea>
                            <div class="form-help">Description concise de l'outil (optionnel)</div>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-link"></i> Site web
                            </label>
                            <input type="url"
                                   id="website_url"
                                   name="website_url"
                                   class="form-input-camille @error('website_url') is-invalid @enderror"
                                   value="{{ old('website_url', $tool->website_url) }}"
                                   placeholder="https://...">
                            <div class="form-help">Lien officiel de l'outil (optionnel)</div>
                            @error('website_url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('order', $tool->order ?? 0) }}">
                            <div class="form-help">Plus le chiffre est petit, plus l'outil apparaît en haut</div>
                            @error('order')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Icône & apparence -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-icons"></i> Icône <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" id="icon" name="icon" value="{{ old('icon', $tool->icon ?? 'fas fa-cube') }}">
                            <div class="icon-grid" id="iconGrid">
                                <div class="icon-option {{ !$tool->icon || $tool->icon == 'fas fa-cube' ? 'selected' : '' }}" data-icon="fas fa-cube">
                                    <i class="fas fa-cube"></i>
                                    <span>fas fa-cube</span>
                                </div>
                                @foreach($icons as $iconClass => $iconName)
                                    <div class="icon-option {{ old('icon', $tool->icon) == $iconClass ? 'selected' : '' }}" data-icon="{{ $iconClass }}">
                                        <i class="{{ $iconClass }}"></i>
                                        <span>{{ $iconName }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-help">Cliquez sur une icône pour la sélectionner - {{ count($icons) + 1 }} icônes disponibles</div>
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
                            <input type="hidden" id="icon_color" name="icon_color" value="{{ old('icon_color', $tool->icon_color ?? '#6366f1') }}">
                            <div class="color-options" id="colorOptions">
                                @foreach($iconColors as $colorKey => $colorLabel)
                                    @php
                                        $colorMap = [
                                            'indigo' => '#6366f1',
                                            'cyan' => '#06b6d4',
                                            'emerald' => '#10b981',
                                            'rose' => '#f43f5e',
                                            'amber' => '#f59e0b',
                                            'violet' => '#8b5cf6',
                                            'blue' => '#3b82f6',
                                            'red' => '#ef4444',
                                            'green' => '#22c55e',
                                            'orange' => '#f97316'
                                        ];
                                        $colorValue = $colorMap[$colorKey] ?? '#6366f1';
                                    @endphp
                                    <div class="color-option {{ old('icon_color', $tool->icon_color) == $colorValue ? 'selected' : '' }}" data-color="{{ $colorValue }}">
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
                                    <i class="{{ old('icon', $tool->icon ?? 'fas fa-cube') }}"></i>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-title" id="previewTitle">{{ old('name', $tool->name) }}</div>
                                    <div class="preview-text" id="previewDescription">{{ old('description', $tool->description) ?: 'Description de l\'outil...' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-image"></i> Logo
                            </label>
                            @if($tool->logo)
                                <div class="current-logo" id="currentLogoContainer">
                                    @php
                                        $logoPath = $tool->logo;
                                        if (!str_starts_with($logoPath, 'tools/') && !str_starts_with($logoPath, 'storage/')) {
                                            $logoPath = 'tools/' . $logoPath;
                                        }
                                        $logoPath = str_replace('storage/', '', $logoPath);
                                    @endphp
                                    <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $tool->name }}">
                                    <div class="current-logo-info">
                                        <div class="label">Logo actuel</div>
                                        <div class="filename">{{ basename($tool->logo) }}</div>
                                    </div>
                                    <button type="button" class="remove-logo" id="removeLogoBtn" title="Supprimer le logo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div id="newLogoContainer" style="display: none;">
                                    <input type="file"
                                           id="logo"
                                           name="logo"
                                           accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                           class="form-input-camille">
                                    <div id="logoPreview" class="image-preview hidden">
                                        <img id="previewImg" class="preview-img" alt="Aperçu">
                                    </div>
                                </div>
                            @else
                                <input type="file"
                                       id="logo"
                                       name="logo"
                                       accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                       class="form-input-camille @error('logo') is-invalid @enderror">
                                <div id="logoPreview" class="image-preview hidden">
                                    <img id="previewImg" class="preview-img" alt="Aperçu">
                                </div>
                            @endif
                            <input type="hidden" name="remove_logo" id="removeLogoHidden" value="0">
                            <div class="form-help">Format: PNG, JPG, JPEG, SVG. Max: 2MB. Si un logo est fourni, il remplace l'icône.</div>
                            @error('logo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                       {{ old('is_active', $tool->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Actif (visible sur le site)
                                </label>
                            </div>
                            <div class="form-help">Décoché = outil masqué sur le site</div>
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

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-file-alt"></i> Récapitulatif de l'outil
                            </label>
                            <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 1rem;">
                                <div id="summaryContent">
                                    <p><strong>Nom :</strong> <span id="summaryName">{{ $tool->name }}</span></p>
                                    <p><strong>Catégorie :</strong> <span id="summaryCategory">{{ $categories[$tool->category] ?? $tool->category }}</span></p>
                                    <p><strong>Site web :</strong> <span id="summaryUrl">{{ $tool->website_url ?? '-' }}</span></p>
                                    <p><strong>Ordre :</strong> <span id="summaryOrder">{{ $tool->order ?? 0 }}</span></p>
                                    <p><strong>Icône :</strong> <span id="summaryIcon">{{ $tool->icon ?? 'Aucune' }}</span></p>
                                    <p><strong>Couleur :</strong> <span id="summaryColor">{{ $tool->icon_color ?? '#6366f1' }}</span></p>
                                    <p><strong>Statut :</strong> <span id="summaryStatus">{{ $tool->is_active ? 'Actif' : 'Inactif' }}</span></p>
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
                    <a href="{{ route('admin.tools.index') }}" class="btn-camille-outline me-2">
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

        @can('tools.delete')
        <div class="delete-tool-section">
            <div class="delete-tool-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-danger">Zone dangereuse</strong>
                        <p class="small mb-0 text-secondary">La suppression de cet outil est irréversible.</p>
                    </div>
                    <button type="button" class="btn-danger-outline" id="deleteToolBtn">
                        <i class="fas fa-trash"></i> Supprimer l'outil
                    </button>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>

<!-- Modal de confirmation suppression -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Confirmation de suppression</h3>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer l'outil <strong>"{{ $tool->name }}"</strong> ?</p>
            <p class="warning-text">Action irréversible. Le logo sera également supprimé.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
            <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endcan

@cannot('tools.edit')
<div class="empty-state">
    <i class="fas fa-lock"></i>
    <p>Vous n'avez pas la permission de modifier des outils.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

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
            updateSummary();
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
        const name = document.getElementById('name').value.trim();
        const category = document.getElementById('category').value;
        if (!name) {
            alert('Veuillez saisir le nom de l\'outil');
            document.getElementById('name').focus();
            return false;
        }
        if (!category) {
            alert('Veuillez sélectionner une catégorie');
            document.getElementById('category').focus();
            return false;
        }
        return true;
    }

    function validateStep2() {
        const icon = document.getElementById('icon').value;
        const iconColor = document.getElementById('icon_color').value;
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

    function updateSummary() {
        const name = document.getElementById('name').value.trim() || '-';
        const category = document.getElementById('category').value;
        const categoryText = category ? document.getElementById('category').options[document.getElementById('category').selectedIndex]?.text || category : '-';
        const websiteUrl = document.getElementById('website_url').value.trim() || '-';
        const order = document.getElementById('order').value || '0';
        const icon = document.getElementById('icon').value.trim() || 'Aucune';
        const iconColor = document.getElementById('icon_color').value.trim() || '#6366f1';
        const isActive = document.getElementById('is_active').checked;

        document.getElementById('summaryName').textContent = name;
        document.getElementById('summaryCategory').textContent = categoryText;
        document.getElementById('summaryUrl').textContent = websiteUrl;
        document.getElementById('summaryOrder').textContent = order;
        document.getElementById('summaryIcon').textContent = icon;
        document.getElementById('summaryColor').textContent = iconColor;
        document.getElementById('summaryStatus').textContent = isActive ? 'Actif' : 'Inactif';
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
                previewIcon.className = iconValue;
            }

            const selectedColor = document.querySelector('.color-option.selected');
            if (selectedColor && previewIconContainer) {
                const colorValue = selectedColor.dataset.color;
                previewIconContainer.style.background = `${colorValue}20`;
                previewIconContainer.style.color = colorValue;
            }
        });
    });

    // ==================== SÉLECTION DES COULEURS ====================
    const colorInput = document.getElementById('icon_color');
    const colorOptionsList = document.querySelectorAll('.color-option');

    colorOptionsList.forEach(option => {
        option.addEventListener('click', function() {
            const colorValue = this.dataset.color;

            colorOptionsList.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            colorInput.value = colorValue;

            if (previewIconContainer) {
                previewIconContainer.style.background = `${colorValue}20`;
                previewIconContainer.style.color = colorValue;
            }
        });
    });

    // Initialisation des couleurs
    const initialColor = document.querySelector('.color-option.selected');
    if (initialColor && previewIconContainer) {
        const colorValue = initialColor.dataset.color;
        previewIconContainer.style.background = `${colorValue}20`;
        previewIconContainer.style.color = colorValue;
    }

    // ==================== APERÇU EN DIRECT ====================
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const previewTitle = document.getElementById('previewTitle');
    const previewDescription = document.getElementById('previewDescription');

    if (nameInput) {
        nameInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Nom de l\'outil';
            updateSummary();
        });
    }

    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Description de l\'outil...';
            updateSummary();
        });
    }

    // ==================== GESTION DU LOGO ====================
    const removeLogoBtn = document.getElementById('removeLogoBtn');
    const currentLogoContainer = document.getElementById('currentLogoContainer');
    const newLogoContainer = document.getElementById('newLogoContainer');
    const removeLogoHidden = document.getElementById('removeLogoHidden');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');

    if (removeLogoBtn) {
        removeLogoBtn.addEventListener('click', function() {
            if (confirm('Voulez-vous supprimer le logo actuel ?')) {
                if (currentLogoContainer) currentLogoContainer.style.display = 'none';
                if (newLogoContainer) newLogoContainer.style.display = 'block';
                if (removeLogoHidden) removeLogoHidden.value = '1';
            }
        });
    }

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (previewImg) previewImg.src = event.target.result;
                    if (logoPreview) logoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                if (logoPreview) logoPreview.classList.add('hidden');
            }
        });
    }

    // ==================== MISE À JOUR RÉCAPITULATIF ====================
    const formInputs = ['name', 'category', 'website_url', 'order'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateSummary);
        }
    });
    document.getElementById('is_active')?.addEventListener('change', updateSummary);

    // ==================== MODAL DE SUPPRESSION ====================
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.add('active');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) modal.classList.remove('active');
    }

    const deleteToolBtn = document.getElementById('deleteToolBtn');
    if (deleteToolBtn) {
        deleteToolBtn.addEventListener('click', openDeleteModal);
    }

    window.closeDeleteModal = closeDeleteModal;

    // ==================== SOUMISSION ====================
    document.getElementById('toolForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour en cours...';
    });
</script>
@endpush
