{{-- resources/views/admin/maintenance/devices/create-step.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($device) ? 'Modifier l\'appareil - NovaTech Admin' : 'Nouvel appareil - NovaTech Admin')
@section('page-title', isset($device) ? 'Modifier l\'appareil' : 'Nouvel appareil')

@push('styles')
<style>
    /* Breadcrumb */
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

    /* Stepper */
    .stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        background: var(--bg-tertiary);
        padding: 1rem;
        border-radius: var(--radius-lg);
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .step {
        flex: 1;
        text-align: center;
        padding: 0.5rem;
        border-radius: var(--radius-md);
        background: var(--bg-secondary);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .step.active {
        background: var(--brand-primary);
        color: white;
    }

    .step.completed {
        background: var(--brand-success);
        color: white;
    }

    .step-number {
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 28px;
        border-radius: 50%;
        background: var(--bg-tertiary);
        color: var(--text-primary);
        margin-right: 0.5rem;
    }

    .step.active .step-number {
        background: white;
        color: var(--brand-primary);
    }

    .step.completed .step-number {
        background: white;
        color: var(--brand-success);
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Card */
    .card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
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

    /* Step content */
    .step-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form */
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

    .form-section-title i {
        color: var(--brand-primary);
    }

    .form-grid {
        display: grid;
        gap: 1rem;
    }
    .form-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .form-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .col-span-2 { grid-column: span 2; }
    .col-span-full { grid-column: 1 / -1; }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        .col-span-2 { grid-column: span 1; }
        .stepper {
            flex-direction: column;
        }
        .step {
            width: 100%;
            text-align: left;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

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

    .form-label i {
        width: 0.875rem;
        text-align: center;
    }

    .required {
        color: var(--brand-error);
    }

    .form-control {
        width: 100%;
        padding: 0.5625rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        outline: none;
    }

    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .form-help {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
    }

    /* Upload area */
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
    }

    .btn-remove {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast);
    }

    .btn-remove:hover {
        background: rgba(239,68,68,0.1);
        color: var(--brand-error);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5625rem 1.125rem;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all var(--transition-fast);
    }

    .btn-primary {
        background: var(--brand-primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .btn-success {
        background: var(--brand-success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .step-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    /* Alert */
    .alert {
        padding: 0.875rem 1rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .alert-error {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.2);
        color: var(--brand-error);
    }

    .alert ul {
        margin: 0.5rem 0 0 1rem;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        border: 1px solid var(--border-light);
        background: var(--bg-tertiary);
    }

    .status-badge:hover {
        transform: translateY(-2px);
    }

    .status-badge input {
        margin: 0;
        accent-color: var(--brand-primary);
    }

    .status-badge:has(input:checked) {
        border-width: 2px;
    }

    .status-operational:has(input:checked) { border-color: #10b981; }
    .status-maintenance:has(input:checked) { border-color: #f59e0b; }
    .status-repair:has(input:checked) { border-color: #ef4444; }
    .status-out_of_service:has(input:checked) { border-color: #6b7280; }

    .flex-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    /* Category badges */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        border: 1px solid var(--border-light);
        background: var(--bg-tertiary);
    }

    .category-badge:hover {
        transform: translateY(-2px);
        border-color: var(--brand-primary);
    }

    .category-badge input {
        margin: 0;
        accent-color: var(--brand-primary);
    }

    .category-badge:has(input:checked) {
        border-color: var(--brand-primary);
        background: rgba(59,130,246,0.1);
        border-width: 2px;
    }
</style>
@endpush

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('admin.maintenance.devices.index') }}">Appareils</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($device) ? 'Modifier' : 'Nouvel appareil' }}</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas {{ isset($device) ? 'fa-edit' : 'fa-microchip' }}"></i>
        </div>
        <h2>{{ isset($device) ? 'Modifier l\'appareil' : 'Nouvel appareil' }}</h2>
    </div>

    <div class="card-body">

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

        <!-- Stepper - 4 étapes -->
        <div class="stepper">
            <div class="step" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label">Infos générales</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label">Commercial</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label">Garantie & Statut</span>
            </div>
            <div class="step" data-step="4">
                <span class="step-number">4</span>
                <span class="step-label">Image</span>
            </div>
        </div>

        <form method="POST"
              action="{{ isset($device) ? route('admin.maintenance.devices.update', $device) : route('admin.maintenance.devices.store') }}"
              enctype="multipart/form-data"
              id="deviceForm">
            @csrf
            @isset($device) @method('PUT') @endisset

            <!-- STEP 1: Informations générales -->
            <div class="step-content active" data-step="1">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Identité de l'appareil
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label" for="name">
                                    <i class="fas fa-microchip"></i> Nom de l'appareil <span class="required">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $device->name ?? '') }}" required placeholder="Ex: Ordinateur principal, Serveur, Imprimante bureau...">
                                @error('name') <span class="form-help" style="color: var(--brand-error);">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="brand">
                                <i class="fas fa-trademark"></i> Marque
                            </label>
                            <input type="text" id="brand" name="brand" class="form-control"
                                   value="{{ old('brand', $device->brand ?? '') }}" placeholder="Ex: Dell, HP, Canon...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="model">
                                <i class="fas fa-cube"></i> Modèle
                            </label>
                            <input type="text" id="model" name="model" class="form-control"
                                   value="{{ old('model', $device->model ?? '') }}" placeholder="Ex: XPS 15, LaserJet Pro...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="serial_number">
                                <i class="fas fa-barcode"></i> Numéro de série
                            </label>
                            <input type="text" id="serial_number" name="serial_number" class="form-control"
                                   value="{{ old('serial_number', $device->serial_number ?? '') }}" placeholder="N° de série unique">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="category">
                                <i class="fas fa-tag"></i> Catégorie <span class="required">*</span>
                            </label>
                            <div class="flex-wrap">
                                <label class="category-badge">
                                    <input type="radio" name="category" value="computer" {{ old('category', $device->category ?? '') == 'computer' ? 'checked' : '' }}> <i class="fas fa-laptop"></i> Ordinateur
                                </label>
                                <label class="category-badge">
                                    <input type="radio" name="category" value="printer" {{ old('category', $device->category ?? '') == 'printer' ? 'checked' : '' }}> <i class="fas fa-print"></i> Imprimante
                                </label>
                                <label class="category-badge">
                                    <input type="radio" name="category" value="network" {{ old('category', $device->category ?? '') == 'network' ? 'checked' : '' }}> <i class="fas fa-network-wired"></i> Réseau
                                </label>
                                <label class="category-badge">
                                    <input type="radio" name="category" value="phone" {{ old('category', $device->category ?? '') == 'phone' ? 'checked' : '' }}> <i class="fas fa-phone"></i> Téléphonie
                                </label>
                                <label class="category-badge">
                                    <input type="radio" name="category" value="other" {{ old('category', $device->category ?? '') == 'other' ? 'checked' : '' }}> <i class="fas fa-microchip"></i> Autre
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Informations commerciales -->
            <div class="step-content" data-step="2">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-building"></i> Informations commerciales
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="client_id">
                                <i class="fas fa-users"></i> Client associé
                            </label>
                            <select id="client_id" name="client_id" class="form-control">
                                <option value="">-- Sélectionner un client --</option>
                                @foreach($clients ?? [] as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $device->client_id ?? '') == $client->id ? 'selected' : '' }}>
                                        {{ $client->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="form-help">Le client propriétaire de l'appareil</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="location">
                                <i class="fas fa-map-marker-alt"></i> Emplacement
                            </label>
                            <input type="text" id="location" name="location" class="form-control"
                                   value="{{ old('location', $device->location ?? '') }}" placeholder="Ex: Bureau 204, Salle serveur, Atelier...">
                            <span class="form-help">Localisation physique de l'appareil</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Garantie et statut -->
            <div class="step-content" data-step="3">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Garantie
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="purchase_date">
                                <i class="fas fa-shopping-cart"></i> Date d'achat
                            </label>
                            <input type="date" id="purchase_date" name="purchase_date" class="form-control"
                                   value="{{ old('purchase_date', isset($device) && $device->purchase_date ? $device->purchase_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="warranty_end_date">
                                <i class="fas fa-shield-alt"></i> Fin de garantie
                            </label>
                            <input type="date" id="warranty_end_date" name="warranty_end_date" class="form-control"
                                   value="{{ old('warranty_end_date', isset($device) && $device->warranty_end_date ? $device->warranty_end_date->format('Y-m-d') : '') }}">
                            <span class="form-help">Date d'expiration de la garantie</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-chart-line"></i> Statut de l'appareil
                    </div>
                    <div class="flex-wrap">
                        <label class="status-badge status-operational">
                            <input type="radio" name="status" value="operational" {{ old('status', $device->status ?? 'operational') == 'operational' ? 'checked' : '' }}>
                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Opérationnel
                        </label>
                        <label class="status-badge status-maintenance">
                            <input type="radio" name="status" value="maintenance" {{ old('status', $device->status ?? '') == 'maintenance' ? 'checked' : '' }}>
                            <i class="fas fa-tools" style="color: #f59e0b;"></i> En maintenance
                        </label>
                        <label class="status-badge status-repair">
                            <input type="radio" name="status" value="repair" {{ old('status', $device->status ?? '') == 'repair' ? 'checked' : '' }}>
                            <i class="fas fa-wrench" style="color: #ef4444;"></i> En réparation
                        </label>
                        <label class="status-badge status-out_of_service">
                            <input type="radio" name="status" value="out_of_service" {{ old('status', $device->status ?? '') == 'out_of_service' ? 'checked' : '' }}>
                            <i class="fas fa-ban" style="color: #6b7280;"></i> Hors service
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-toggle-on"></i> Activation
                    </div>
                    <div class="form-group">
                        <label class="toggle-switch" style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($device) ? $device->is_active : true) ? 'checked' : '' }}>
                            <span>Appareil actif</span>
                        </label>
                        <span class="form-help">Les appareils inactifs n'apparaissent pas dans les listes</span>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Image -->
            <div class="step-content" data-step="4">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-image"></i> Image de l'appareil
                    </div>
                    <div class="form-group">
                        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Cliquez pour télécharger une image</p>
                            <span class="form-help">PNG, JPG — max 2 MB — recommandé 200x200px</span>
                            <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;">
                        </div>
                        <div id="imagePreviewContainer">
                            @if(isset($device) && $device->image)
                            <div class="image-preview" id="imagePreview">
                                <img src="{{ asset('storage/' . $device->image) }}" alt="Image">
                                <div class="preview-info">
                                    <div class="preview-name">Image actuelle</div>
                                </div>
                                <button type="button" class="btn-remove" data-preview="imagePreview">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation buttons -->
            <div class="step-buttons">
                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Précédent
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn">
                    Suivant <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                    <i class="fas fa-save"></i>
                    {{ isset($device) ? 'Mettre à jour' : 'Créer l\'appareil' }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    let currentStep = 1;
    const totalSteps = 4;

    const stepContents = document.querySelectorAll('.step-content');
    const stepElements = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function updateStepper() {
        stepContents.forEach(content => {
            const step = parseInt(content.dataset.step);
            content.classList.toggle('active', step === currentStep);
        });

        stepElements.forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed');

            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });

        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';

        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    function validateCurrentStep() {
        switch(currentStep) {
            case 1:
                const name = document.getElementById('name').value.trim();
                if (!name) {
                    alert('Veuillez saisir le nom de l\'appareil');
                    return false;
                }
                const category = document.querySelector('input[name="category"]:checked');
                if (!category) {
                    alert('Veuillez sélectionner une catégorie');
                    return false;
                }
                return true;
            default:
                return true;
        }
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepper();
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepper();
        }
    }

    prevBtn.addEventListener('click', prevStep);
    nextBtn.addEventListener('click', nextStep);

    // Image preview
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const existing = document.getElementById('imagePreview');
                if (existing) existing.remove();

                const div = document.createElement('div');
                div.id = 'imagePreview';
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Aperçu">
                    <div class="preview-info">
                        <div class="preview-name">Nouvelle image</div>
                        <span class="form-help">${(imageInput.files[0].size / 1024).toFixed(1)} KB</span>
                    </div>
                    <button type="button" class="btn-remove" data-preview="imagePreview">
                        <i class="fas fa-trash-alt"></i>
                    </button>`;
                previewContainer.appendChild(div);
                bindRemove();
            };
            reader.readAsDataURL(this.files[0]);
        });
    }

    function bindRemove() {
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.onclick = function(e) {
                e.stopPropagation();
                const el = document.getElementById(this.dataset.preview);
                if (el) el.remove();
                if (imageInput) imageInput.value = '';
            };
        });
    }
    bindRemove();

    updateStepper();

    // Soumission
    const form = document.getElementById('deviceForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ isset($device) ? "Mise à jour..." : "Création..." }}';
        });
    }
})();
</script>
@endpush
