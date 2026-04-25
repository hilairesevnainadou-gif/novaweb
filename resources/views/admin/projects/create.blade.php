{{-- resources/views/admin/projects/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouveau projet - Assistant - NovaTech Admin')
@section('page-title', 'Nouveau projet')

@push('styles')
<style>
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

    .type-badge, .priority-badge {
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

    .type-badge:hover, .priority-badge:hover {
        transform: translateY(-2px);
    }

    .type-badge input, .priority-badge input {
        margin: 0;
        accent-color: var(--brand-primary);
    }

    .type-badge:has(input:checked), .priority-badge:has(input:checked) {
        border-width: 2px;
        background: rgba(59,130,246,0.1);
    }

    /* Searchable Select */
    .searchable-select {
        position: relative;
    }

    .ss-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.5625rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        user-select: none;
    }

    .ss-trigger:hover,
    .ss-trigger.open {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .ss-display {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-secondary);
    }

    .ss-display.has-value {
        color: var(--text-primary);
    }

    .ss-arrow {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        transition: transform var(--transition-fast);
        flex-shrink: 0;
        margin-left: 0.5rem;
    }

    .ss-trigger.open .ss-arrow {
        transform: rotate(180deg);
    }

    .ss-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 100;
        background: var(--bg-primary);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        overflow: hidden;
    }

    .ss-dropdown.open {
        display: block;
        animation: fadeIn 0.15s ease;
    }

    .ss-search-wrap {
        position: relative;
        padding: 0.5rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .ss-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-tertiary);
        font-size: 0.75rem;
    }

    .ss-search {
        width: 100%;
        padding: 0.4375rem 0.75rem 0.4375rem 2rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.8125rem;
        outline: none;
        transition: border-color var(--transition-fast);
    }

    .ss-search:focus {
        border-color: var(--brand-primary);
    }

    .ss-options {
        max-height: 200px;
        overflow-y: auto;
        padding: 0.25rem 0;
    }

    .ss-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        cursor: pointer;
        transition: background var(--transition-fast);
    }

    .ss-option:hover {
        background: var(--bg-hover);
    }

    .ss-option.selected {
        background: rgba(59,130,246,0.08);
        color: var(--brand-primary);
        font-weight: 500;
    }

    .ss-option.hidden {
        display: none;
    }

    .ss-option-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--brand-primary);
        color: white;
        font-size: 0.6875rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .ss-no-results {
        padding: 0.75rem 0.875rem;
        font-size: 0.8125rem;
        color: var(--text-tertiary);
        text-align: center;
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $totalSteps = 3;
    $steps = [
        1 => ['label' => 'Informations générales', 'icon' => 'fa-info-circle'],
        2 => ['label' => 'Dates & Budget', 'icon' => 'fa-calendar-alt'],
        3 => ['label' => 'Validation', 'icon' => 'fa-check-circle'],
    ];
@endphp

<nav class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <span>Nouveau projet</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas fa-magic"></i>
        </div>
        <h2>Assistant de création de projet</h2>
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

        <form method="POST" action="{{ route('admin.projects.store') }}" id="projectForm">
            @csrf

            <!-- STEP 1: Informations générales -->
            <div class="step-content active" data-step="1">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Identité du projet
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-heading"></i> Nom du projet <span class="required">*</span>
                                </label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Ex: Application Mobile NovaTech">
                                <span class="form-help">Nom descriptif du projet</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description détaillée du projet...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-tag"></i> Type de projet <span class="required">*</span>
                    </div>
                    <div class="form-grid form-grid-2">
                        @foreach($types as $value => $label)
                        <label class="type-badge">
                            <input type="radio" name="type" value="{{ $value }}" {{ old('type') == $value ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-users"></i> Équipe
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Client
                            </label>
                            <div class="searchable-select" id="client-select-wrapper">
                                <input type="hidden" name="client_id" id="client_id_input" value="{{ old('client_id') }}">
                                <div class="ss-trigger" id="client-trigger">
                                    <span class="ss-display">
                                        @php
                                            $selectedClient = old('client_id') ? $clients->find(old('client_id')) : null;
                                        @endphp
                                        {{ $selectedClient ? $selectedClient->name.($selectedClient->company_name ? ' ('.$selectedClient->company_name.')' : '') : '-- Sélectionner un client --' }}
                                    </span>
                                    <i class="fas fa-chevron-down ss-arrow"></i>
                                </div>
                                <div class="ss-dropdown" id="client-dropdown">
                                    <div class="ss-search-wrap">
                                        <i class="fas fa-search ss-search-icon"></i>
                                        <input type="text" class="ss-search" placeholder="Rechercher un client..." autocomplete="off">
                                    </div>
                                    <div class="ss-options">
                                        <div class="ss-option" data-value="">-- Aucun client --</div>
                                        @foreach($clients as $client)
                                            <div class="ss-option" data-value="{{ $client->id }}">
                                                {{ $client->name }}{{ $client->company_name ? ' ('.$client->company_name.')' : '' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tie"></i> Chef de projet <span class="required">*</span>
                            </label>
                            <div class="searchable-select" id="pm-select-wrapper">
                                <input type="hidden" name="project_manager_id" id="pm_id_input" value="{{ old('project_manager_id') }}" required>
                                <div class="ss-trigger" id="pm-trigger">
                                    <span class="ss-display">
                                        @php
                                            $selectedPm = old('project_manager_id') ? $projectManagers->find(old('project_manager_id')) : null;
                                        @endphp
                                        {{ $selectedPm ? $selectedPm->name : '-- Sélectionner un chef de projet --' }}
                                    </span>
                                    <i class="fas fa-chevron-down ss-arrow"></i>
                                </div>
                                <div class="ss-dropdown" id="pm-dropdown">
                                    <div class="ss-search-wrap">
                                        <i class="fas fa-search ss-search-icon"></i>
                                        <input type="text" class="ss-search" placeholder="Rechercher un chef de projet..." autocomplete="off">
                                    </div>
                                    <div class="ss-options">
                                        <div class="ss-option" data-value="">-- Sélectionner --</div>
                                        @foreach($projectManagers as $pm)
                                            <div class="ss-option" data-value="{{ $pm->id }}">
                                                <span class="ss-option-avatar">{{ strtoupper(substr($pm->name, 0, 1)) }}</span>
                                                {{ $pm->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if($projectManagers->isEmpty())
                                <span class="form-help" style="color:var(--brand-error)"><i class="fas fa-exclamation-triangle"></i> Aucun chef de projet disponible</span>
                            @else
                                <span class="form-help">{{ $projectManagers->count() }} chef(s) de projet disponible(s)</span>
                            @endif
                        </div>
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-code"></i> Technologies
                                </label>
                                <input type="text" name="technologies" class="form-control" value="{{ old('technologies') }}" placeholder="PHP, Laravel, MySQL, React, ...">
                                <span class="form-help">Séparez les technologies par des virgules</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-flag"></i> Priorité <span class="required">*</span>
                    </div>
                    <div class="form-grid form-grid-2">
                        @foreach($priorities as $value => $label)
                        <label class="priority-badge">
                            <input type="radio" name="priority" value="{{ $value }}" {{ old('priority') == $value ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- STEP 2: Dates & Budget -->
            <div class="step-content" data-step="2">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Calendrier du projet
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-day"></i> Date de début
                            </label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Date de fin estimée
                            </label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                            <span class="form-help">Date de livraison estimée</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-chart-line"></i> Budget & Ressources
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Budget estimé (FCFA)
                            </label>
                            <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget', 0) }}" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i> Heures estimées
                            </label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control" value="{{ old('estimated_hours', 0) }}" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-link"></i> Liens utiles
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-github"></i> URL du Repository
                            </label>
                            <input type="url" name="repository_url" class="form-control" value="{{ old('repository_url') }}" placeholder="https://github.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-globe"></i> URL de production
                            </label>
                            <input type="url" name="production_url" class="form-control" value="{{ old('production_url') }}" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flask"></i> URL de staging
                            </label>
                            <input type="url" name="staging_url" class="form-control" value="{{ old('staging_url') }}" placeholder="https://staging...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Validation -->
            <div class="step-content" data-step="3">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-check-circle"></i> Vérification finale
                    </div>
                    <div id="summaryContent">
                        <div class="alert" style="background: var(--bg-tertiary); margin-bottom: 1rem;">
                            <i class="fas fa-info-circle"></i>
                            <div>Veuillez vérifier les informations avant de créer le projet.</div>
                        </div>
                        <div id="summaryDetails"></div>
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
                    <i class="fas fa-check-circle"></i> Créer le projet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Searchable Select Component
(function() {
    function initSearchableSelect(wrapperId, inputId) {
        const wrapper = document.getElementById(wrapperId);
        if (!wrapper) return;

        const hiddenInput = document.getElementById(inputId);
        const trigger = wrapper.querySelector('.ss-trigger');
        const display = wrapper.querySelector('.ss-display');
        const dropdown = wrapper.querySelector('.ss-dropdown');
        const searchInput = wrapper.querySelector('.ss-search');
        const options = wrapper.querySelectorAll('.ss-option');

        // Mark pre-selected value
        const preselected = hiddenInput?.value;
        if (preselected) {
            options.forEach(opt => {
                if (opt.dataset.value === preselected) {
                    opt.classList.add('selected');
                    display.textContent = opt.textContent.trim();
                    display.classList.add('has-value');
                }
            });
        }

        function openDropdown() {
            trigger.classList.add('open');
            dropdown.classList.add('open');
            searchInput?.focus();
        }

        function closeDropdown() {
            trigger.classList.remove('open');
            dropdown.classList.remove('open');
            if (searchInput) {
                searchInput.value = '';
                filterOptions('');
            }
        }

        function filterOptions(query) {
            const q = query.toLowerCase().trim();
            let visibleCount = 0;
            options.forEach(opt => {
                const text = opt.textContent.toLowerCase();
                const match = !q || text.includes(q);
                opt.classList.toggle('hidden', !match);
                if (match) visibleCount++;
            });

            let noResults = wrapper.querySelector('.ss-no-results');
            if (visibleCount === 0) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'ss-no-results';
                    noResults.textContent = 'Aucun résultat';
                    wrapper.querySelector('.ss-options').appendChild(noResults);
                }
                noResults.style.display = 'block';
            } else if (noResults) {
                noResults.style.display = 'none';
            }
        }

        trigger.addEventListener('click', () => {
            dropdown.classList.contains('open') ? closeDropdown() : openDropdown();
        });

        searchInput?.addEventListener('input', e => filterOptions(e.target.value));

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');

                const val = opt.dataset.value;
                const label = opt.textContent.trim();

                hiddenInput.value = val;
                display.textContent = val ? label : opt.textContent.trim();
                display.classList.toggle('has-value', !!val);

                closeDropdown();
            });
        });

        document.addEventListener('click', e => {
            if (!wrapper.contains(e.target)) closeDropdown();
        });
    }

    initSearchableSelect('client-select-wrapper', 'client_id_input');
    initSearchableSelect('pm-select-wrapper', 'pm_id_input');
})();

(function() {
    let currentStep = 1;
    const totalSteps = 3;

    const stepContents = document.querySelectorAll('.step-content');
    const stepElements = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function updateSummary() {
        const summary = document.getElementById('summaryDetails');
        if (!summary) return;

        const name = document.querySelector('input[name="name"]')?.value || 'Non renseigné';
        const description = document.querySelector('textarea[name="description"]')?.value || 'Non renseigné';
        const type = document.querySelector('input[name="type"]:checked')?.parentElement?.innerText || 'Non sélectionné';
        const client = document.querySelector('#client-select-wrapper .ss-display')?.textContent?.trim() || 'Non sélectionné';
        const pm = document.querySelector('#pm-select-wrapper .ss-display')?.textContent?.trim() || 'Non sélectionné';
        const priority = document.querySelector('input[name="priority"]:checked')?.parentElement?.innerText || 'Non sélectionnée';
        const startDate = document.querySelector('input[name="start_date"]')?.value || 'Non renseignée';
        const endDate = document.querySelector('input[name="end_date"]')?.value || 'Non renseignée';
        const budget = document.querySelector('input[name="budget"]')?.value || '0';
        const hours = document.querySelector('input[name="estimated_hours"]')?.value || '0';
        const technologies = document.querySelector('input[name="technologies"]')?.value || 'Non renseignées';

        summary.innerHTML = `
            <div class="form-grid form-grid-2">
                <div><strong>Nom du projet :</strong> ${escapeHtml(name)}</div>
                <div><strong>Type :</strong> ${escapeHtml(type)}</div>
                <div><strong>Client :</strong> ${escapeHtml(client)}</div>
                <div><strong>Chef de projet :</strong> ${escapeHtml(pm)}</div>
                <div><strong>Priorité :</strong> ${escapeHtml(priority)}</div>
                <div><strong>Budget :</strong> ${escapeHtml(budget)} FCFA</div>
                <div><strong>Heures estimées :</strong> ${escapeHtml(hours)}h</div>
                <div><strong>Date de début :</strong> ${escapeHtml(startDate)}</div>
                <div><strong>Date de fin :</strong> ${escapeHtml(endDate)}</div>
                <div class="col-span-2"><strong>Technologies :</strong> ${escapeHtml(technologies)}</div>
                <div class="col-span-2"><strong>Description :</strong><br>${escapeHtml(description)}</div>
            </div>
        `;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function updateStepper() {
        stepContents.forEach(content => {
            const step = parseInt(content.dataset.step);
            content.classList.toggle('active', step === currentStep);
            if (step === 3) {
                updateSummary();
            }
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
                const name = document.querySelector('input[name="name"]')?.value.trim();
                const type = document.querySelector('input[name="type"]:checked');
                const priority = document.querySelector('input[name="priority"]:checked');
                const pm = document.getElementById('pm_id_input')?.value;

                if (!name) {
                    alert('Veuillez saisir un nom pour le projet');
                    return false;
                }
                if (!type) {
                    alert('Veuillez sélectionner un type de projet');
                    return false;
                }
                if (!priority) {
                    alert('Veuillez sélectionner une priorité');
                    return false;
                }
                if (!pm) {
                    alert('Veuillez sélectionner un chef de projet');
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

    // Mettre à jour le résumé quand les champs changent
    const formInputs = document.querySelectorAll('#projectForm input, #projectForm select, #projectForm textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            if (currentStep === 3) updateSummary();
        });
    });

    updateStepper();

    // Soumission du formulaire
    const form = document.getElementById('projectForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
        });
    }
})();
</script>
@endpush
