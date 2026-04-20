@extends('admin.layouts.app')

@section('title', 'Nouvel outil - NovaTech Admin')
@section('page-title', 'Nouvel outil')

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

    .col-4 {
        flex: 0 0 33.333%;
        max-width: 33.333%;
    }

    @media (max-width: 992px) {
        .col-4, .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 768px) {
        .col-4, .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

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
        min-height: 80px;
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

    .icon-selector {
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        background: var(--bg-primary);
        max-height: 200px;
        overflow-y: auto;
        padding: 0.5rem;
    }

    .icon-option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .icon-option:hover {
        background: var(--bg-hover);
    }

    .icon-option.selected {
        background: var(--brand-primary);
        color: white;
    }

    .icon-option i {
        width: 1.25rem;
        font-size: 1rem;
    }

    .color-preview {
        width: 2rem;
        height: 2rem;
        border-radius: var(--radius-md);
        border: 2px solid var(--border-light);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .color-preview:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-md);
    }

    .color-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .color-option {
        width: 2rem;
        height: 2rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        border: 2px solid transparent;
    }

    .color-option:hover {
        transform: scale(1.05);
    }

    .color-option.selected {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
</style>
@endpush

@section('content')
@can('tools.create')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-tools"></i> Créer un nouvel outil</h2>
    </div>

    <div class="card-body-custom">
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

        <form method="POST" action="{{ route('admin.tools.store') }}" enctype="multipart/form-data" id="toolForm">
            @csrf

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
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Ex: Laravel, React, Docker...">
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
                                    <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
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
                                      placeholder="Description de l'outil...">{{ old('description') }}</textarea>
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
                                   value="{{ old('website_url') }}"
                                   placeholder="https://...">
                            <div class="form-help">Lien officiel de l'outil</div>
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
                                   value="{{ old('order', 0) }}">
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
                                <i class="fas fa-icons"></i> Icône
                            </label>
                            <input type="text"
                                   id="icon"
                                   name="icon"
                                   class="form-input-camille @error('icon') is-invalid @enderror"
                                   value="{{ old('icon') }}"
                                   placeholder="Ex: fab fa-laravel">
                            <div class="form-help">Classe FontAwesome (ex: fab fa-react, fas fa-database)</div>

                            <div class="icon-selector mt-2">
                                <div class="icon-option" data-icon="fas fa-cube">
                                    <i class="fas fa-cube"></i> <span>fas fa-cube - Par défaut</span>
                                </div>
                                @foreach($icons as $iconClass => $iconName)
                                    <div class="icon-option" data-icon="{{ $iconClass }}">
                                        <i class="{{ $iconClass }}"></i> <span>{{ $iconClass }} - {{ $iconName }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @error('icon')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-palette"></i> Couleur de l'icône
                            </label>
                            <input type="text"
                                   id="icon_color"
                                   name="icon_color"
                                   class="form-input-camille @error('icon_color') is-invalid @enderror"
                                   value="{{ old('icon_color', '#6366f1') }}"
                                   placeholder="#6366f1">

                            <div class="color-options">
                                @foreach($iconColors as $colorName => $colorValue)
                                    <div class="color-option" style="background: {{ explode(' ', $colorValue)[1] ?? $colorValue }};"
                                         data-color="{{ explode(' ', $colorValue)[1] ?? $colorValue }}"
                                         title="{{ $colorName }}"></div>
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center gap-2 mt-2">
                                <div class="color-preview" id="colorPreview" style="background: {{ old('icon_color', '#6366f1') }};"></div>
                                <div class="form-help">Code hexadécimal ou nom de couleur</div>
                            </div>
                            @error('icon_color')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-image"></i> Logo (optionnel)
                            </label>
                            <input type="file"
                                   id="logo"
                                   name="logo"
                                   accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                   class="form-input-camille @error('logo') is-invalid @enderror">
                            <div id="logoPreview" class="image-preview hidden">
                                <img id="previewImg" class="preview-img" alt="Aperçu">
                            </div>
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
                                <i class="fas fa-eye"></i> Statut
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
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
                                    <strong>Prêt à publier ?</strong>
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
                                    <p><strong>Nom :</strong> <span id="summaryName">-</span></p>
                                    <p><strong>Catégorie :</strong> <span id="summaryCategory">-</span></p>
                                    <p><strong>Site web :</strong> <span id="summaryUrl">-</span></p>
                                    <p><strong>Ordre :</strong> <span id="summaryOrder">-</span></p>
                                    <p><strong>Icône :</strong> <span id="summaryIcon">-</span></p>
                                    <p><strong>Couleur :</strong> <span id="summaryColor">-</span></p>
                                    <p><strong>Statut :</strong> <span id="summaryStatus">-</span></p>
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
                        <i class="fas fa-save"></i> Créer l'outil
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@cannot('tools.create')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de créer des outils.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

    // GESTION DES ÉTAPES
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
        if (!name) { alert('Veuillez saisir le nom de l\'outil'); document.getElementById('name').focus(); return false; }
        if (!category) { alert('Veuillez sélectionner une catégorie'); document.getElementById('category').focus(); return false; }
        return true;
    }

    function validateStep2() {
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
        if (currentStep === 1 && validateStep1()) { currentStep = 2; updateSteps(); }
        else if (currentStep === 2 && validateStep2()) { currentStep = 3; updateSteps(); }
    });

    prevBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateSteps(); } });

    // SÉLECTION DES ICÔNES
    const iconOptions = document.querySelectorAll('.icon-option');
    const iconInput = document.getElementById('icon');

    iconOptions.forEach(option => {
        option.addEventListener('click', function() {
            const icon = this.dataset.icon;
            iconInput.value = icon;

            iconOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // SÉLECTION DES COULEURS
    const colorOptions = document.querySelectorAll('.color-option');
    const colorInput = document.getElementById('icon_color');
    const colorPreview = document.getElementById('colorPreview');

    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const color = this.dataset.color;
            colorInput.value = color;
            colorPreview.style.background = color;

            colorOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    if (colorInput) {
        colorInput.addEventListener('input', function() {
            colorPreview.style.background = this.value;
        });
    }

    // APERÇU LOGO
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    logoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                logoPreview.classList.add('hidden');
            }
        });
    }

    // AUTO-GÉNÉRATION DU SLUG SI NÉCESSAIRE (optionnel pour les outils)
    // Les outils n'ont pas de slug dans le modèle, mais on garde pour cohérence

    // MISE À JOUR DU RÉCAPITULATIF À CHAQUE MODIFICATION
    const formInputs = ['name', 'category', 'website_url', 'order', 'icon', 'icon_color'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateSummary);
        }
    });
    document.getElementById('is_active')?.addEventListener('change', updateSummary);

    // SOUMISSION
    document.getElementById('toolForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    });
</script>
@endpush
