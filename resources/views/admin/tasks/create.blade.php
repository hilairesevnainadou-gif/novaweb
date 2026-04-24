{{-- resources/views/admin/tasks/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($task) ? 'Modifier la tâche - NovaTech Admin' : 'Nouvelle tâche - NovaTech Admin')
@section('page-title', isset($task) ? 'Modifier la tâche' : 'Nouvelle tâche')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; transition:color 0.2s; }
    .breadcrumb a:hover { color:var(--brand-primary); }

    .stepper { display:flex; align-items:center; margin-bottom:2rem; }
    .step { display:flex; align-items:center; flex:1; }
    .step-number { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.875rem; font-weight:600; flex-shrink:0; transition:all 0.3s; border:2px solid var(--border-medium); background:var(--bg-tertiary); color:var(--text-tertiary); }
    .step.active .step-number { background:var(--brand-primary); border-color:var(--brand-primary); color:white; }
    .step.done .step-number { background:#10b981; border-color:#10b981; color:white; }
    .step-label { margin-left:0.625rem; font-size:0.8125rem; font-weight:500; color:var(--text-tertiary); }
    .step.active .step-label { color:var(--brand-primary); }
    .step.done .step-label { color:#10b981; }
    .step-connector { flex:1; height:2px; background:var(--border-light); margin:0 0.5rem; }

    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; overflow:hidden; margin-bottom:1.25rem; }
    .card-header { padding:1rem 1.5rem; background:var(--bg-tertiary); border-bottom:1px solid var(--border-light); display:flex; align-items:center; gap:0.625rem; }
    .card-header-icon { width:32px; height:32px; border-radius:0.5rem; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:var(--brand-primary); font-size:0.875rem; }
    .card-header h2 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    .form-section { margin-bottom:1.75rem; }
    .form-section-title { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-tertiary); padding-bottom:0.625rem; border-bottom:1px solid var(--border-light); margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; }
    .form-section-title i { color:var(--brand-primary); }
    .form-grid { display:grid; gap:1rem; }
    .form-grid-2 { grid-template-columns:repeat(2,1fr); }
    .form-grid-3 { grid-template-columns:repeat(3,1fr); }
    .col-span-2 { grid-column:span 2; }
    @media (max-width:768px) { .form-grid-2, .form-grid-3 { grid-template-columns:1fr; } .col-span-2 { grid-column:span 1; } }
    .form-group { display:flex; flex-direction:column; gap:0.375rem; }
    .form-label { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-tertiary); display:flex; align-items:center; gap:0.375rem; }
    .required { color:var(--brand-error); }
    .form-control { width:100%; padding:0.5625rem 0.875rem; border-radius:0.5rem; border:1px solid var(--border-medium); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; font-family:inherit; outline:none; transition:border-color 0.2s, box-shadow 0.2s; }
    .form-control::placeholder { color:var(--text-tertiary); }
    .form-control:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    .form-control.is-invalid { border-color:var(--brand-error); }
    textarea.form-control { resize:vertical; min-height:100px; }
    .invalid-feedback { font-size:0.75rem; color:var(--brand-error); margin-top:0.25rem; }
    .form-help { font-size:0.6875rem; color:var(--text-tertiary); margin-top:0.25rem; }

    .form-footer { display:flex; align-items:center; justify-content:space-between; padding-top:1.25rem; border-top:1px solid var(--border-light); margin-top:1.25rem; gap:1rem; }
    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.875rem; font-weight:500; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-secondary:hover { background:var(--bg-hover); color:var(--text-primary); }
    .btn-next { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.5rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; }
    .btn-next:hover { background:var(--brand-primary-hover); }

    /* Priority visual selectors */
    .priority-selector { display:flex; gap:0.625rem; flex-wrap:wrap; }
    .priority-option { flex:1; min-width:90px; padding:0.625rem; border:2px solid var(--border-light); border-radius:0.5rem; text-align:center; cursor:pointer; transition:all 0.2s; }
    .priority-option:hover { border-color:var(--brand-primary); }
    .priority-option.selected { border-color:currentColor; }
    .priority-option input { display:none; }
    .priority-option.low { color:#9ca3af; }
    .priority-option.low.selected { background:rgba(107,114,128,0.1); border-color:#9ca3af; }
    .priority-option.medium { color:#3b82f6; }
    .priority-option.medium.selected { background:rgba(59,130,246,0.1); border-color:#3b82f6; }
    .priority-option.high { color:#f59e0b; }
    .priority-option.high.selected { background:rgba(245,158,11,0.1); border-color:#f59e0b; }
    .priority-option.urgent { color:#ef4444; }
    .priority-option.urgent.selected { background:rgba(239,68,68,0.1); border-color:#ef4444; }
    .priority-icon { font-size:1.25rem; margin-bottom:0.25rem; }
    .priority-label { font-size:0.75rem; font-weight:600; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($task) ? 'Modifier la tâche' : 'Nouvelle tâche' }}</span>
</div>

<div class="stepper">
    <div class="step active" id="step-indicator-1">
        <div class="step-number">1</div>
        <div class="step-label">Description</div>
    </div>
    <div class="step-connector"></div>
    <div class="step" id="step-indicator-2">
        <div class="step-number">2</div>
        <div class="step-label">Assignation</div>
    </div>
    <div class="step-connector"></div>
    <div class="step" id="step-indicator-3">
        <div class="step-number">3</div>
        <div class="step-label">Validation</div>
    </div>
</div>

<form method="POST" action="{{ isset($task) ? route('admin.projects.tasks.update', [$project, $task]) : route('admin.projects.tasks.store', $project) }}" id="taskForm">
    @csrf
    @isset($task)@method('PUT')@endisset

    {{-- Étape 1 --}}
    <div id="step-1">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-align-left"></i></div>
                <h2>Description de la tâche</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-tag"></i> Informations de base</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-heading"></i> Titre <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $task->title ?? '') }}" placeholder="Description courte de la tâche" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-circle-dot"></i> Statut <span class="required">*</span></label>
                            <select name="status" class="form-control" required>
                                @foreach(\App\Models\Task::STATUSES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('status', $task->status ?? 'todo') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-folder"></i> Catégorie</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $task->category ?? '') }}" placeholder="Ex: Frontend, Backend, Design...">
                        </div>
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description détaillée</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Décrivez précisément ce qu'il faut faire...">{{ old('description', $task->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-flag"></i> Priorité</div>
                    <input type="hidden" name="priority" id="priorityInput" value="{{ old('priority', $task->priority ?? 'medium') }}">
                    <div class="priority-selector">
                        @foreach(['low' => ['Faible','fa-arrow-down'], 'medium' => ['Moyen','fa-equals'], 'high' => ['Élevé','fa-arrow-up'], 'urgent' => ['Urgent','fa-circle-exclamation']] as $val => [$label, $icon])
                        <div class="priority-option {{ $val }} {{ old('priority', $task->priority ?? 'medium') === $val ? 'selected' : '' }}"
                             onclick="selectPriority('{{ $val }}', this)">
                            <div class="priority-icon"><i class="fas {{ $icon }}"></i></div>
                            <div class="priority-label">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-sitemap"></i> Tâche parente (optionnel)</div>
                    <div class="form-group">
                        <select name="parent_id" class="form-control">
                            <option value="">— Tâche indépendante —</option>
                            @foreach($tasks as $parentTask)
                                <option value="{{ $parentTask->id }}" @selected(old('parent_id', $task->parent_id ?? null) == $parentTask->id)>
                                    {{ $parentTask->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-help">Optionnel — permet de créer une sous-tâche.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="button" class="btn-next" onclick="goToStep(2)">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    {{-- Étape 2 --}}
    <div id="step-2" style="display:none;">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-user-check"></i></div>
                <h2>Assignation & Planning</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user"></i> Assignation</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Assignée à</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">— Non assignée —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to ?? null) == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar"></i> Date d'échéance</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i> Estimation (heures)</label>
                            <input type="number" name="estimated_hours" class="form-control" value="{{ old('estimated_hours', $task->estimated_hours ?? '') }}" min="0" step="0.5" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-secondary" onclick="goToStep(1)">
                <i class="fas fa-arrow-left"></i> Précédent
            </button>
            <button type="button" class="btn-next" onclick="goToStep(3)">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    {{-- Étape 3 --}}
    <div id="step-3" style="display:none;">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-clipboard-check"></i></div>
                <h2>Récapitulatif & Validation</h2>
            </div>
            <div class="card-body">
                <div style="background:var(--bg-tertiary); border-radius:0.625rem; padding:1.25rem; display:grid; gap:0.875rem;">
                    <div style="display:flex; gap:0.75rem;">
                        <span style="color:var(--text-tertiary); min-width:130px; font-size:0.8125rem;">Titre :</span>
                        <strong id="sum_title" style="color:var(--text-primary); font-size:0.875rem;"></strong>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <span style="color:var(--text-tertiary); min-width:130px; font-size:0.8125rem;">Statut :</span>
                        <strong id="sum_status" style="color:var(--text-primary); font-size:0.875rem;"></strong>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <span style="color:var(--text-tertiary); min-width:130px; font-size:0.8125rem;">Priorité :</span>
                        <strong id="sum_priority" style="color:var(--text-primary); font-size:0.875rem;"></strong>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <span style="color:var(--text-tertiary); min-width:130px; font-size:0.8125rem;">Assignée à :</span>
                        <strong id="sum_assigned" style="color:var(--text-primary); font-size:0.875rem;"></strong>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <span style="color:var(--text-tertiary); min-width:130px; font-size:0.8125rem;">Échéance :</span>
                        <strong id="sum_due" style="color:var(--text-primary); font-size:0.875rem;"></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-secondary" onclick="goToStep(2)">
                <i class="fas fa-arrow-left"></i> Précédent
            </button>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                {{ isset($task) ? 'Mettre à jour' : 'Créer la tâche' }}
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let currentStep = 1;

    function goToStep(step) {
        if (step > currentStep && !validateStep(currentStep)) return;
        document.getElementById(`step-${currentStep}`).style.display = 'none';
        document.getElementById(`step-${step}`).style.display = 'block';

        for (let i = 1; i <= 3; i++) {
            const ind = document.getElementById(`step-indicator-${i}`);
            ind.classList.remove('active', 'done');
            if (i < step) { ind.classList.add('done'); ind.querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>'; }
            else if (i === step) ind.classList.add('active');
        }

        currentStep = step;
        if (step === 3) updateSummary();
    }

    function validateStep(step) {
        if (step === 1) {
            const title = document.querySelector('[name="title"]').value.trim();
            if (!title) { alert('Le titre de la tâche est requis.'); return false; }
        }
        return true;
    }

    function selectPriority(val, el) {
        document.querySelectorAll('.priority-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('priorityInput').value = val;
    }

    function updateSummary() {
        document.getElementById('sum_title').textContent = document.querySelector('[name="title"]').value || '-';
        document.getElementById('sum_status').textContent = document.querySelector('[name="status"] option:checked')?.text || '-';
        document.getElementById('sum_priority').textContent = document.getElementById('priorityInput').value;
        document.getElementById('sum_assigned').textContent = document.querySelector('[name="assigned_to"] option:checked')?.text || 'Non assignée';
        document.getElementById('sum_due').textContent = document.querySelector('[name="due_date"]').value || 'Non définie';
    }
</script>
@endpush
