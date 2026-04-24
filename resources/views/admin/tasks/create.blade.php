{{-- resources/views/admin/tasks/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle tâche - Assistant - NovaTech Admin')
@section('page-title', 'Nouvelle tâche')

@push('styles')
<style>
    /* ========== STEPPER ========== */
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

    /* ========== CARD ========== */
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

    /* ========== STEP CONTENT ========== */
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

    /* ========== FORM SECTIONS ========== */
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

    .col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .form-grid-2 {
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

    .form-control[readonly] {
        background: var(--bg-tertiary);
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-help {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        margin-top: 0.25rem;
    }

    /* ========== BADGES ========== */
    .priority-badge, .type-badge {
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

    .priority-badge:hover, .type-badge:hover {
        transform: translateY(-2px);
    }

    .priority-badge input, .type-badge input {
        margin: 0;
        accent-color: var(--brand-primary);
    }

    .priority-badge:has(input:checked), .type-badge:has(input:checked) {
        border-width: 2px;
        background: rgba(59,130,246,0.1);
    }

    .priority-low { color: #6b7280; }
    .priority-medium { color: #3b82f6; }
    .priority-high { color: #f59e0b; }
    .priority-urgent { color: #ef4444; }

    /* ========== BUTTONS ========== */
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

    /* ========== ALERT ========== */
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

    /* ========== BREADCRUMB ========== */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
    }

    .breadcrumb a {
        color: var(--text-tertiary);
        transition: color var(--transition-fast);
    }

    .breadcrumb a:hover {
        color: var(--brand-primary);
    }

    .breadcrumb i {
        font-size: 0.6rem;
    }

    /* ========== SUMMARY ========== */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        background: var(--bg-tertiary);
        padding: 1rem;
        border-radius: var(--radius-md);
    }

    .summary-item {
        padding: 0.5rem;
    }

    .summary-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

@php
    $isEdit = isset($task);
    $hasProject = isset($project) && $project !== null;
    $title = $isEdit ? 'Modifier la tâche' : 'Nouvelle tâche';
    $totalSteps = 3;
    $steps = [
        1 => ['label' => 'Description', 'icon' => 'fa-info-circle'],
        2 => ['label' => 'Assignation', 'icon' => 'fa-user-check'],
        3 => ['label' => 'Validation', 'icon' => 'fa-check-circle'],
    ];
@endphp

<nav class="breadcrumb">
    @if($hasProject)
        <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.projects.tasks.index', $project) }}">Tâches</a>
    @else
        <a href="{{ route('admin.tasks.global-index') }}">Tâches</a>
    @endif
    <i class="fas fa-chevron-right"></i>
    <span>{{ $title }}</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas fa-magic"></i>
        </div>
        <h2>Assistant de création de tâche</h2>
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

        <!-- Formulaire - Action différente selon le contexte -->
        <form method="POST" action="{{ $isEdit ? route('admin.tasks.update', $task) : ($hasProject ? route('admin.projects.tasks.store', $project) : route('admin.tasks.store')) }}" id="taskForm">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- STEP 1: Description -->
            <div class="step-content active" data-step="1">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Informations de la tâche
                    </div>
                    <div class="form-grid form-grid-2">
                        <!-- Sélection du projet - UNIQUEMENT si pas de projet spécifique et pas en édition -->
                        @if(!$hasProject && !$isEdit)
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-project-diagram"></i> Projet <span class="required">*</span>
                                </label>
                                <select name="project_id" class="form-control" id="project_id" required>
                                    <option value="">-- Sélectionner un projet --</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                            {{ $proj->name }} ({{ $proj->project_number }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="form-help">Le projet auquel cette tâche est rattachée</span>
                            </div>
                        </div>
                        @elseif($hasProject && !$isEdit)
                        <!-- Projet spécifique - champ caché + affichage -->
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-project-diagram"></i> Projet
                                </label>
                                <input type="text" class="form-control" value="{{ $project->name }}" readonly disabled>
                                <span class="form-help">La tâche sera rattachée à ce projet</span>
                            </div>
                        </div>
                        @endif

                        <!-- Titre -->
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-heading"></i> Titre de la tâche <span class="required">*</span>
                                </label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $task->title ?? '') }}" required placeholder="Ex: Développer l'API de connexion">
                                <span class="form-help">Titre clair et descriptif de la tâche</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description détaillée de la tâche...">{{ old('description', $task->description ?? '') }}</textarea>
                                <span class="form-help">Décrivez les objectifs, livrables et critères de réussite</span>
                            </div>
                        </div>

                        <!-- Type de tâche -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Type de tâche <span class="required">*</span>
                            </label>
                            <select name="task_type" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($taskTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('task_type', $task->task_type ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tâche parente -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-level-up-alt"></i> Tâche parente
                            </label>
                            <select name="parent_id" class="form-control" id="parent_id">
                                <option value="">-- Aucune --</option>
                                @forelse($parentTasks ?? [] as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $task->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->title }}
                                    </option>
                                @empty
                                    <option value="" disabled>Aucune tâche parente disponible</option>
                                @endforelse
                            </select>
                            <span class="form-help">Si cette tâche est une sous-tâche d'une autre</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Assignation -->
            <div class="step-content" data-step="2">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user-check"></i> Assignation & Planning
                    </div>
                    <div class="form-grid form-grid-2">
                        <!-- Assigner à -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Assigner à
                            </label>
                            <select name="assigned_to" class="form-control">
                                <option value="">-- Non assigné --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="form-help">Personne responsable de la réalisation</span>
                        </div>

                        <!-- Heures estimées -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-hourglass-half"></i> Heures estimées
                            </label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control" value="{{ old('estimated_hours', $task->estimated_hours ?? 0) }}" placeholder="0">
                            <span class="form-help">Temps estimé en heures</span>
                        </div>

                        <!-- Date de début -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-day"></i> Date de début
                            </label>
                            <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', isset($task) && $task->start_date ? $task->start_date->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <!-- Date d'échéance -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Date d'échéance
                            </label>
                            <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}">
                            <span class="form-help">Date limite de réalisation</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-flag"></i> Priorité <span class="required">*</span>
                    </div>
                    <div class="form-grid form-grid-2">
                        <label class="priority-badge priority-low">
                            <input type="radio" name="priority" value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'checked' : '' }}> Basse
                        </label>
                        <label class="priority-badge priority-medium">
                            <input type="radio" name="priority" value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'checked' : '' }}> Moyenne
                        </label>
                        <label class="priority-badge priority-high">
                            <input type="radio" name="priority" value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'checked' : '' }}> Haute
                        </label>
                        <label class="priority-badge priority-urgent">
                            <input type="radio" name="priority" value="urgent" {{ old('priority', $task->priority ?? '') == 'urgent' ? 'checked' : '' }}> Urgente
                        </label>
                    </div>
                </div>

                @if($isEdit)
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-chart-line"></i> Suivi
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i> Heures réalisées
                            </label>
                            <input type="number" step="0.5" name="actual_hours" class="form-control" value="{{ old('actual_hours', $task->actual_hours ?? 0) }}" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tasks"></i> Statut <span class="required">*</span>
                            </label>
                            <select name="status" class="form-control" required>
                                @foreach($statuses ?? [] as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $task->status ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif
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
                            <div>Veuillez vérifier les informations avant de créer la tâche.</div>
                        </div>
                        <div id="summaryDetails" class="summary-grid"></div>
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
                    <i class="fas fa-check-circle"></i> {{ $isEdit ? 'Mettre à jour' : 'Créer la tâche' }}
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
    const totalSteps = 3;

    const stepContents = document.querySelectorAll('.step-content');
    const stepElements = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Variables pour savoir le contexte
    const hasProject = @json($hasProject);
    const isEdit = @json($isEdit);
    const projectNameFromController = @json($hasProject ? $project->name : '');

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getSelectedOptionText(selectId) {
        const select = document.getElementById(selectId);
        if (select && select.selectedIndex >= 0) {
            return select.options[select.selectedIndex].text;
        }
        return 'Non sélectionné';
    }

    function getSelectedRadioText(name) {
        const radio = document.querySelector(`input[name="${name}"]:checked`);
        if (radio && radio.parentElement) {
            return radio.parentElement.innerText.trim();
        }
        return 'Non sélectionnée';
    }

    function updateSummary() {
        const summary = document.getElementById('summaryDetails');
        if (!summary) return;

        // Récupération des valeurs
        let projectHtml = '';

        if (!hasProject && !isEdit) {
            // Mode création sans projet - on prend le projet sélectionné
            const projectName = getSelectedOptionText('project_id');
            projectHtml = `
                <div class="summary-item">
                    <div class="summary-label">Projet</div>
                    <div class="summary-value">${escapeHtml(projectName)}</div>
                </div>
            `;
        } else if (hasProject && !isEdit) {
            // Mode création avec projet spécifique
            projectHtml = `
                <div class="summary-item">
                    <div class="summary-label">Projet</div>
                    <div class="summary-value">${escapeHtml(projectNameFromController)}</div>
                </div>
            `;
        } else if (isEdit) {
            // Mode édition
            projectHtml = `
                <div class="summary-item">
                    <div class="summary-label">Projet</div>
                    <div class="summary-value">${escapeHtml(@json($task->project->name ?? ''))}</div>
                </div>
            `;
        }

        const title = document.querySelector('input[name="title"]')?.value || 'Non renseigné';
        const description = document.querySelector('textarea[name="description"]')?.value || 'Non renseignée';
        const taskType = getSelectedOptionText('task_type') || 'Non sélectionné';
        const parentTask = document.getElementById('parent_id') ? getSelectedOptionText('parent_id') : 'Aucune';
        const assignedTo = getSelectedOptionText('assigned_to') || 'Non assigné';
        const priority = getSelectedRadioText('priority');
        const hours = document.querySelector('input[name="estimated_hours"]')?.value || '0';
        const startDate = document.querySelector('input[name="start_date"]')?.value || 'Non renseignée';
        const dueDate = document.querySelector('input[name="due_date"]')?.value || 'Non renseignée';

        let statusHtml = '';
        if (isEdit) {
            const status = getSelectedOptionText('status') || 'Non sélectionné';
            const actualHours = document.querySelector('input[name="actual_hours"]')?.value || '0';
            statusHtml = `
                <div class="summary-item">
                    <div class="summary-label">Statut</div>
                    <div class="summary-value">${escapeHtml(status)}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Heures réalisées</div>
                    <div class="summary-value">${escapeHtml(actualHours)}h</div>
                </div>
            `;
        }

        summary.innerHTML = `
            ${projectHtml}
            <div class="summary-item">
                <div class="summary-label">Titre</div>
                <div class="summary-value">${escapeHtml(title)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Type</div>
                <div class="summary-value">${escapeHtml(taskType)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Assignée à</div>
                <div class="summary-value">${escapeHtml(assignedTo)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Priorité</div>
                <div class="summary-value">${escapeHtml(priority)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Heures estimées</div>
                <div class="summary-value">${escapeHtml(hours)}h</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date de début</div>
                <div class="summary-value">${escapeHtml(startDate)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date d'échéance</div>
                <div class="summary-value">${escapeHtml(dueDate)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Tâche parente</div>
                <div class="summary-value">${escapeHtml(parentTask)}</div>
            </div>
            ${statusHtml}
            <div class="summary-item col-span-2">
                <div class="summary-label">Description</div>
                <div class="summary-value">${escapeHtml(description).substring(0, 200)}${description.length > 200 ? '...' : ''}</div>
            </div>
        `;
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
                const title = document.querySelector('input[name="title"]')?.value.trim();
                const taskType = document.querySelector('select[name="task_type"]')?.value;

                // Validation du projet uniquement en mode création sans projet
                if (!hasProject && !isEdit) {
                    const projectId = document.querySelector('select[name="project_id"]')?.value;
                    if (!projectId) {
                        alert('Veuillez sélectionner un projet');
                        return false;
                    }
                }

                if (!title) {
                    alert('Veuillez saisir un titre pour la tâche');
                    return false;
                }
                if (!taskType) {
                    alert('Veuillez sélectionner un type de tâche');
                    return false;
                }
                return true;
            case 2:
                const priority = document.querySelector('input[name="priority"]:checked');
                if (!priority) {
                    alert('Veuillez sélectionner une priorité');
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
    const formInputs = document.querySelectorAll('#taskForm input, #taskForm select, #taskForm textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            if (currentStep === 3) updateSummary();
        });
        input.addEventListener('keyup', () => {
            if (currentStep === 3) updateSummary();
        });
    });

    updateStepper();

    // Soumission du formulaire
    const form = document.getElementById('taskForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ $isEdit ? "Mise à jour en cours..." : "Création en cours..." }}';
        });
    }
})();
</script>
@endpush
