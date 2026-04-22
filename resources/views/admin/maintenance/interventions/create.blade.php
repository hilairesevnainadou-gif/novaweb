{{-- resources/views/admin/maintenance/interventions/create-step.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle intervention - Assistant - NovaTech Admin')
@section('page-title', 'Nouvelle intervention')

@push('styles')
<style>
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

    .step.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
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

    .card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 1.5rem;
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

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

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

    .form-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .col-span-2 {
        grid-column: span 2;
    }

    .col-span-full {
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .col-span-2 {
            grid-column: span 1;
        }
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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-help {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
    }

    /* Styles pour la recherche multiple */
    .search-multiple-container {
        position: relative;
        width: 100%;
    }

    .search-multiple-input-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        min-height: 42px;
        cursor: text;
    }

    .search-multiple-input-wrapper:focus-within {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .selected-devices-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .device-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.5rem;
        background: var(--bg-selected);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        color: var(--brand-primary);
    }

    .device-tag i {
        cursor: pointer;
        font-size: 0.7rem;
        color: var(--text-tertiary);
        transition: color 0.2s;
    }

    .device-tag i:hover {
        color: var(--brand-error);
    }

    .search-multiple-input {
        flex: 1;
        min-width: 150px;
        border: none;
        background: transparent;
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        padding: 0.25rem 0;
    }

    .search-multiple-input::placeholder {
        color: var(--text-tertiary);
    }

    .search-multiple-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 250px;
        overflow-y: auto;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        z-index: 100;
        display: none;
        margin-top: 4px;
        box-shadow: var(--shadow-lg);
    }

    .search-multiple-dropdown.show {
        display: block;
    }

    .search-option {
        padding: 0.625rem 0.875rem;
        cursor: pointer;
        transition: background var(--transition-fast);
        border-bottom: 1px solid var(--border-light);
    }

    .search-option:last-child {
        border-bottom: none;
    }

    .search-option:hover {
        background: var(--bg-hover);
    }

    .search-option.selected {
        background: var(--bg-selected);
    }

    .option-name {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .option-details {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    .no-results {
        padding: 0.625rem 0.875rem;
        text-align: center;
        color: var(--text-tertiary);
    }

    .priority-group, .level-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .priority-badge, .level-badge {
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

    .priority-badge:hover, .level-badge:hover {
        transform: translateY(-2px);
    }

    .priority-low { color: #6b7280; }
    .priority-medium { color: #3b82f6; }
    .priority-high { color: #f59e0b; }
    .priority-urgent { color: #ef4444; }
    .priority-critical { color: #8b5cf6; }

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

    /* Flatpickr calendar styles */
    .flatpickr-calendar {
        background: var(--bg-elevated) !important;
        border: 1px solid var(--border-medium) !important;
        border-radius: var(--radius-lg) !important;
        box-shadow: var(--shadow-lg) !important;
    }
    .flatpickr-day {
        color: var(--text-primary) !important;
    }
    .flatpickr-day.selected {
        background: var(--brand-primary) !important;
        border-color: var(--brand-primary) !important;
    }
    .flatpickr-day.today {
        border-color: var(--brand-primary) !important;
    }
    .flatpickr-day:hover {
        background: var(--bg-hover) !important;
    }
    .flatpickr-months .flatpickr-month {
        color: var(--text-primary) !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        color: var(--text-primary) !important;
    }
    .flatpickr-weekday {
        color: var(--text-tertiary) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

@php
    $user = auth()->user();
    $isTechnician = $user->hasRole('technician');
    $isAdmin = $user->hasRole('admin') || $user->hasRole('super-admin') || $user->hasRole('support');

    // Déterminer le nombre d'étapes
    $totalSteps = $isTechnician ? 3 : 4;
    $steps = [
        1 => ['label' => 'Appareils', 'icon' => 'fa-microchip'],
        2 => ['label' => 'Problème', 'icon' => 'fa-bug'],
        3 => ['label' => 'Priorité & Niveau', 'icon' => 'fa-flag'],
    ];
    if (!$isTechnician) {
        $steps[4] = ['label' => 'Planning & Coût', 'icon' => 'fa-calendar-alt'];
    }
    // Pas d'étape 5 de confirmation
@endphp

<nav class="breadcrumb">
    <a href="{{ route('admin.maintenance.interventions.index') }}">Interventions</a>
    <i class="fas fa-chevron-right"></i>
    <span>Nouvelle intervention</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas fa-magic"></i>
        </div>
        <h2>Assistant de création d'intervention</h2>
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

        <!-- Stepper -->
        <div class="stepper">
            @foreach($steps as $stepNum => $step)
            <div class="step" data-step="{{ $stepNum }}">
                <span class="step-number">{{ $stepNum }}</span>
                <span class="step-label"><i class="fas {{ $step['icon'] }}"></i> {{ $step['label'] }}</span>
            </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.maintenance.interventions.store') }}" id="interventionForm">
            @csrf

            <!-- STEP 1: Appareils avec recherche multiple -->
            <div class="step-content active" data-step="1">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-microchip"></i> Appareil(s) concerné(s)
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-search"></i> Rechercher des appareils <span class="required">*</span>
                        </label>
                        <div class="search-multiple-container">
                            <div class="search-multiple-input-wrapper" id="searchWrapper">
                                <div class="selected-devices-tags" id="selectedDevicesTags"></div>
                                <input type="text" id="deviceSearchInput" class="search-multiple-input" placeholder="Tapez pour rechercher un appareil..." autocomplete="off">
                            </div>
                            <div id="deviceDropdown" class="search-multiple-dropdown"></div>
                        </div>
                        <input type="hidden" name="device_ids" id="device_ids" value="{{ old('device_ids') }}">
                        @error('device_ids')
                            <span class="form-help" style="color: var(--brand-error);">{{ $message }}</span>
                        @enderror
                        <span class="form-help">Vous pouvez sélectionner plusieurs appareils pour cette intervention</span>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Problème -->
            <div class="step-content" data-step="2">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-bug"></i> Description du problème
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="col-span-1">
                            <div class="form-group">
                                <label class="form-label" for="title">
                                    <i class="fas fa-heading"></i> Titre de l'intervention <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Ex: Panne écran, Révision générale...">
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="form-group">
                                <label class="form-label" for="problem_type">
                                    <i class="fas fa-tag"></i> Type de problème
                                </label>
                                <select name="problem_type" id="problem_type" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="hardware" {{ old('problem_type') == 'hardware' ? 'selected' : '' }}>Matériel</option>
                                    <option value="software" {{ old('problem_type') == 'software' ? 'selected' : '' }}>Logiciel</option>
                                    <option value="network" {{ old('problem_type') == 'network' ? 'selected' : '' }}>Réseau</option>
                                    <option value="electrical" {{ old('problem_type') == 'electrical' ? 'selected' : '' }}>Électrique</option>
                                    <option value="mechanical" {{ old('problem_type') == 'mechanical' ? 'selected' : '' }}>Mécanique</option>
                                    <option value="other" {{ old('problem_type') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label" for="problem_description">
                                    <i class="fas fa-align-left"></i> Description détaillée
                                </label>
                                <textarea id="problem_description" name="problem_description" class="form-control" rows="4" placeholder="Décrivez précisément le problème rencontré...">{{ old('problem_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Priorité & Niveau -->
            <div class="step-content" data-step="3">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-flag"></i> Priorité <span class="required">*</span>
                    </div>
                    <div class="priority-group">
                        <label class="priority-badge priority-low">
                            <input type="radio" name="priority" value="low" {{ old('priority') == 'low' ? 'checked' : '' }}> Basse
                        </label>
                        <label class="priority-badge priority-medium">
                            <input type="radio" name="priority" value="medium" {{ old('priority') == 'medium' ? 'checked' : '' }}> Moyenne
                        </label>
                        <label class="priority-badge priority-high">
                            <input type="radio" name="priority" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}> Haute
                        </label>
                        <label class="priority-badge priority-urgent">
                            <input type="radio" name="priority" value="urgent" {{ old('priority') == 'urgent' ? 'checked' : '' }}> Urgente
                        </label>
                        <label class="priority-badge priority-critical">
                            <input type="radio" name="priority" value="critical" {{ old('priority') == 'critical' ? 'checked' : '' }}> Critique
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-level-up-alt"></i> Niveau d'évolution <span class="required">*</span>
                    </div>
                    <div class="level-group">
                        <label class="level-badge">
                            <input type="radio" name="evolution_level" value="1" {{ old('evolution_level') == 1 ? 'checked' : '' }}> Niveau 1 - Diagnostic
                        </label>
                        <label class="level-badge">
                            <input type="radio" name="evolution_level" value="2" {{ old('evolution_level') == 2 ? 'checked' : '' }}> Niveau 2 - Intervention simple
                        </label>
                        <label class="level-badge">
                            <input type="radio" name="evolution_level" value="3" {{ old('evolution_level') == 3 ? 'checked' : '' }}> Niveau 3 - Intervention complexe
                        </label>
                        <label class="level-badge">
                            <input type="radio" name="evolution_level" value="4" {{ old('evolution_level') == 4 ? 'checked' : '' }}> Niveau 4 - Intervention majeure
                        </label>
                        <label class="level-badge">
                            <input type="radio" name="evolution_level" value="5" {{ old('evolution_level') == 5 ? 'checked' : '' }}> Niveau 5 - Intervention critique
                        </label>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Planning & Coût (UNIQUEMENT pour admin/support) -->
            @if(!$isTechnician)
            <div class="step-content" data-step="4">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Planning de l'intervention
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="scheduled_date">
                            <i class="fas fa-calendar-check"></i> Date et heure planifiées
                        </label>
                        <input type="text" id="scheduled_date" name="scheduled_date" class="form-control" placeholder="Sélectionnez une date et heure" value="{{ old('scheduled_date') }}">
                        <span class="form-help">Cliquez sur le champ pour ouvrir le calendrier</span>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-money-bill-wave"></i> Coût estimé
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="estimated_cost">
                            <i class="fas fa-calculator"></i> Montant estimé (FCFA)
                        </label>
                        <input type="number" id="estimated_cost" name="estimated_cost" class="form-control" value="{{ old('estimated_cost', 0) }}" step="1" min="0">
                        <span class="form-help">Montant estimé hors pièces détachées</span>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note"></i> Notes internes
                    </div>
                    <div class="form-group">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires, précautions, consignes particulières...">{{ old('notes') }}</textarea>
                        <span class="form-help">Ces notes sont internes et ne seront pas visibles par le client</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation buttons -->
            <div class="step-buttons">
                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Précédent
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn">
                    Suivant <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                    <i class="fas fa-check-circle"></i> Créer l'intervention
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
(function() {
    @php
        $user = auth()->user();
        $isTechnician = $user->hasRole('technician');
        $totalSteps = $isTechnician ? 3 : 4;
    @endphp

    let currentStep = 1;
    const totalSteps = {{ $totalSteps }};
    const isTechnician = @json($isTechnician);

    const stepContents = document.querySelectorAll('.step-content');
    const stepElements = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // ===== SÉLECTION MULTIPLE D'APPAREILS =====
    const deviceSearchInput = document.getElementById('deviceSearchInput');
    const deviceDropdown = document.getElementById('deviceDropdown');
    const selectedDevicesTags = document.getElementById('selectedDevicesTags');
    const deviceIdsField = document.getElementById('device_ids');

    let selectedDevices = [];
    let allDevices = [];

    // Charger les appareils depuis PHP
    @php
        $devicesList = [];
        foreach($devices as $device) {
            $devicesList[] = [
                'id' => $device->id,
                'name' => $device->name,
                'brand' => $device->brand,
                'model' => $device->model,
                'reference' => $device->reference,
                'client_name' => $device->client->name ?? 'Sans client'
            ];
        }
    @endphp
    allDevices = @json($devicesList);

    function renderDropdown(filter = '') {
        if (!deviceDropdown) return;

        const filteredDevices = allDevices.filter(device =>
            !selectedDevices.some(d => d.id === device.id) &&
            (filter === '' ||
             device.name.toLowerCase().includes(filter.toLowerCase()) ||
             device.reference.toLowerCase().includes(filter.toLowerCase()) ||
             (device.brand && device.brand.toLowerCase().includes(filter.toLowerCase())) ||
             (device.model && device.model.toLowerCase().includes(filter.toLowerCase())))
        );

        if (filteredDevices.length === 0) {
            deviceDropdown.innerHTML = '<div class="no-results">Aucun appareil trouvé</div>';
            deviceDropdown.classList.add('show');
        } else {
            deviceDropdown.innerHTML = filteredDevices.map(device => `
                <div class="search-option" data-id="${device.id}" data-name="${escapeHtml(device.name)}" data-brand="${escapeHtml(device.brand || '')}" data-model="${escapeHtml(device.model || '')}" data-reference="${escapeHtml(device.reference || '')}" data-client="${escapeHtml(device.client_name)}">
                    <div class="option-name">${escapeHtml(device.name)}</div>
                    <div class="option-details">${escapeHtml(device.brand || '')} ${escapeHtml(device.model || '')} | Réf: ${escapeHtml(device.reference || '-')} | Client: ${escapeHtml(device.client_name)}</div>
                </div>
            `).join('');
            deviceDropdown.classList.add('show');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function addDevice(deviceData) {
        if (!selectedDevices.some(d => d.id === deviceData.id)) {
            selectedDevices.push(deviceData);
            updateSelectedDevicesDisplay();
            updateDeviceIdsField();
        }
        deviceSearchInput.value = '';
        renderDropdown('');
    }

    function removeDevice(deviceId) {
        selectedDevices = selectedDevices.filter(d => d.id !== deviceId);
        updateSelectedDevicesDisplay();
        updateDeviceIdsField();
        if (deviceSearchInput.value) {
            renderDropdown(deviceSearchInput.value);
        }
    }

    function updateSelectedDevicesDisplay() {
        if (!selectedDevicesTags) return;

        if (selectedDevices.length === 0) {
            selectedDevicesTags.innerHTML = '';
            return;
        }

        selectedDevicesTags.innerHTML = selectedDevices.map(device => `
            <span class="device-tag">
                ${escapeHtml(device.name)}
                <i class="fas fa-times" onclick="removeDevice(${device.id})"></i>
            </span>
        `).join('');
    }

    function updateDeviceIdsField() {
        if (deviceIdsField) {
            deviceIdsField.value = selectedDevices.map(d => d.id).join(',');
        }
    }

    window.removeDevice = removeDevice;

    if (deviceSearchInput && deviceDropdown) {
        deviceSearchInput.addEventListener('focus', function() {
            renderDropdown('');
        });

        deviceSearchInput.addEventListener('input', function() {
            renderDropdown(this.value);
        });

        deviceDropdown.addEventListener('click', function(e) {
            const option = e.target.closest('.search-option');
            if (option) {
                const deviceData = {
                    id: parseInt(option.dataset.id),
                    name: option.dataset.name,
                    brand: option.dataset.brand,
                    model: option.dataset.model,
                    reference: option.dataset.reference,
                    client_name: option.dataset.client
                };
                addDevice(deviceData);
            }
        });

        document.addEventListener('click', function(e) {
            if (!deviceSearchInput.contains(e.target) && !deviceDropdown.contains(e.target)) {
                deviceDropdown.classList.remove('show');
            }
        });
    }

    const oldDeviceIds = "{{ old('device_ids') }}";
    if (oldDeviceIds && allDevices.length) {
        const ids = oldDeviceIds.split(',').map(id => parseInt(id));
        ids.forEach(id => {
            const device = allDevices.find(d => d.id === id);
            if (device && !selectedDevices.some(d => d.id === id)) {
                selectedDevices.push(device);
            }
        });
        updateSelectedDevicesDisplay();
        updateDeviceIdsField();
    }

    // ===== CALENDRIER FLATPICKR (uniquement pour admin) =====
    if (!isTechnician) {
        const dateInput = document.getElementById('scheduled_date');
        if (dateInput) {
            flatpickr(dateInput, {
                locale: 'fr',
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                time_24hr: true,
                minuteIncrement: 15,
                placeholder: "Sélectionnez une date et heure",
                allowInput: false
            });
        }
    }

    // ===== STEPPER NAVIGATION =====
    function updateStepper() {
        stepContents.forEach(content => {
            const step = parseInt(content.dataset.step);
            content.classList.toggle('active', step === currentStep);
        });

        stepElements.forEach((step, index) => {
            const stepNum = index + 1;
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
                if (selectedDevices.length === 0) {
                    alert('Veuillez sélectionner au moins un appareil');
                    return false;
                }
                return true;
            case 2:
                const title = document.getElementById('title')?.value.trim();
                if (!title) {
                    alert('Veuillez saisir un titre pour l\'intervention');
                    return false;
                }
                return true;
            case 3:
                const priority = document.querySelector('input[name="priority"]:checked');
                const level = document.querySelector('input[name="evolution_level"]:checked');
                if (!priority) {
                    alert('Veuillez sélectionner une priorité');
                    return false;
                }
                if (!level) {
                    alert('Veuillez sélectionner un niveau d\'évolution');
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

    if (prevBtn) prevBtn.addEventListener('click', prevStep);
    if (nextBtn) nextBtn.addEventListener('click', nextStep);

    // Styles pour les badges
    const style = document.createElement('style');
    style.textContent = `
        .priority-badge input, .level-badge input {
            margin: 0;
            accent-color: var(--brand-primary);
        }
        .priority-badge:has(input:checked), .level-badge:has(input:checked) {
            border-width: 2px;
            background: rgba(59,130,246,0.1);
        }
        .priority-low:has(input:checked) { border-color: #6b7280; }
        .priority-medium:has(input:checked) { border-color: #3b82f6; }
        .priority-high:has(input:checked) { border-color: #f59e0b; }
        .priority-urgent:has(input:checked) { border-color: #ef4444; }
        .priority-critical:has(input:checked) { border-color: #8b5cf6; }
        .level-badge:has(input:checked) { border-color: var(--brand-primary); }
    `;
    document.head.appendChild(style);

    updateStepper();

    // Soumission du formulaire
    const form = document.getElementById('interventionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateDeviceIdsField();

            if (selectedDevices.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un appareil');
                return false;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
        });
    }
})();
</script>
@endpush
