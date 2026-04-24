{{-- resources/views/admin/projects/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($project) ? 'Modifier le projet - NovaTech Admin' : 'Nouveau projet - NovaTech Admin')
@section('page-title', isset($project) ? 'Modifier le projet' : 'Nouveau projet')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; transition:color 0.2s; }
    .breadcrumb a:hover { color:var(--brand-primary); }

    /* Stepper */
    .stepper { display:flex; align-items:center; margin-bottom:2rem; }
    .step { display:flex; align-items:center; flex:1; }
    .step-number { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.875rem; font-weight:600; flex-shrink:0; transition:all 0.3s; border:2px solid var(--border-medium); background:var(--bg-tertiary); color:var(--text-tertiary); }
    .step.active .step-number { background:var(--brand-primary); border-color:var(--brand-primary); color:white; }
    .step.done .step-number { background:#10b981; border-color:#10b981; color:white; }
    .step-label { margin-left:0.625rem; font-size:0.8125rem; font-weight:500; color:var(--text-tertiary); transition:color 0.3s; }
    .step.active .step-label { color:var(--brand-primary); }
    .step.done .step-label { color:#10b981; }
    .step-connector { flex:1; height:2px; background:var(--border-light); margin:0 0.5rem; transition:background 0.3s; }
    .step.done + .step-connector, .step.done + * .step-connector { background:#10b981; }

    /* Cards */
    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; overflow:hidden; margin-bottom:1.25rem; }
    .card-header { padding:1rem 1.5rem; background:var(--bg-tertiary); border-bottom:1px solid var(--border-light); display:flex; align-items:center; gap:0.625rem; }
    .card-header-icon { width:32px; height:32px; border-radius:0.5rem; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:var(--brand-primary); font-size:0.875rem; }
    .card-header h2 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    .form-section { margin-bottom:1.75rem; }
    .form-section-title { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-tertiary); padding-bottom:0.625rem; border-bottom:1px solid var(--border-light); margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; }
    .form-section-title i { color:var(--brand-primary); }
    .form-grid { display:grid; gap:1rem; }
    .form-grid-2 { grid-template-columns:repeat(2, 1fr); }
    .form-grid-3 { grid-template-columns:repeat(3, 1fr); }
    .col-span-2 { grid-column:span 2; }
    @media (max-width:768px) { .form-grid-2, .form-grid-3 { grid-template-columns:1fr; } .col-span-2 { grid-column:span 1; } }
    .form-group { display:flex; flex-direction:column; gap:0.375rem; }
    .form-label { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-tertiary); display:flex; align-items:center; gap:0.375rem; }
    .required { color:var(--brand-error); }
    .form-control { width:100%; padding:0.5625rem 0.875rem; border-radius:0.5rem; border:1px solid var(--border-medium); background:var(--bg-primary); color:var(--text-primary); font-size:0.875rem; font-family:inherit; transition:border-color 0.2s, box-shadow 0.2s; outline:none; }
    .form-control::placeholder { color:var(--text-tertiary); }
    .form-control:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    .form-control.is-invalid { border-color:var(--brand-error); }
    textarea.form-control { resize:vertical; min-height:100px; }
    .form-help { font-size:0.6875rem; color:var(--text-tertiary); margin-top:0.25rem; }
    .invalid-feedback { font-size:0.75rem; color:var(--brand-error); margin-top:0.25rem; }

    .color-picker-row { display:flex; gap:0.5rem; flex-wrap:wrap; }
    .color-option { width:28px; height:28px; border-radius:50%; cursor:pointer; transition:all 0.2s; border:3px solid transparent; }
    .color-option:hover, .color-option.selected { border-color:white; transform:scale(1.2); box-shadow:0 0 0 2px var(--brand-primary); }

    .tech-tags { display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.5rem; }
    .tech-tag { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.625rem; background:rgba(59,130,246,0.1); color:var(--brand-primary); border-radius:9999px; font-size:0.75rem; }
    .tech-tag button { background:none; border:none; cursor:pointer; color:inherit; padding:0; font-size:0.75rem; line-height:1; }

    .form-footer { display:flex; align-items:center; justify-content:space-between; padding-top:1.25rem; border-top:1px solid var(--border-light); margin-top:1.25rem; gap:1rem; flex-wrap:wrap; }
    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s; border:none; cursor:pointer; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s; cursor:pointer; }
    .btn-secondary:hover { background:var(--bg-hover); color:var(--text-primary); }
    .btn-next { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.5rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; }
    .btn-next:hover { background:var(--brand-primary-hover); }

    .member-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:0.625rem; }
    .member-item { display:flex; align-items:center; gap:0.625rem; padding:0.625rem; border:1px solid var(--border-light); border-radius:0.5rem; background:var(--bg-primary); cursor:pointer; transition:all 0.2s; }
    .member-item:hover { border-color:var(--brand-primary); background:var(--bg-hover); }
    .member-item.selected { border-color:var(--brand-primary); background:rgba(59,130,246,0.05); }
    .member-item input[type="checkbox"] { accent-color:var(--brand-primary); }
    .member-avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.75rem; font-weight:600; flex-shrink:0; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($project) ? 'Modifier' : 'Nouveau projet' }}</span>
</div>

{{-- Stepper --}}
<div class="stepper">
    <div class="step active" id="step-indicator-1">
        <div class="step-number">1</div>
        <div class="step-label">Informations</div>
    </div>
    <div class="step-connector"></div>
    <div class="step" id="step-indicator-2">
        <div class="step-number">2</div>
        <div class="step-label">Dates & Budget</div>
    </div>
    <div class="step-connector"></div>
    <div class="step" id="step-indicator-3">
        <div class="step-number">3</div>
        <div class="step-label">Équipe & Validation</div>
    </div>
</div>

<form method="POST" action="{{ isset($project) ? route('admin.projects.update', $project) : route('admin.projects.store') }}" id="projectForm">
    @csrf
    @isset($project)@method('PUT')@endisset

    {{-- Étape 1 : Informations générales --}}
    <div id="step-1">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                <h2>Informations générales</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-folder"></i> Projet</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-tag"></i> Nom du projet <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $project->name ?? '') }}" placeholder="Ex: Refonte site web client ABC" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Type <span class="required">*</span></label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                @foreach(\App\Models\Project::TYPES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('type', $project->type ?? 'web') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-flag"></i> Priorité <span class="required">*</span></label>
                            <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                @foreach(\App\Models\Project::PRIORITIES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('priority', $project->priority ?? 'medium') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-circle-dot"></i> Statut <span class="required">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                @foreach(\App\Models\Project::STATUSES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('status', $project->status ?? 'planning') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Client</label>
                            <select name="client_id" class="form-control">
                                <option value="">— Aucun client —</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id ?? null) == $client->id)>
                                        {{ $client->company_name ?? $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Décrivez le projet...">{{ old('description', $project->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-palette"></i> Couleur du projet</div>
                    <input type="hidden" name="color" id="colorInput" value="{{ old('color', $project->color ?? '#3b82f6') }}">
                    <div class="color-picker-row">
                        @foreach(['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#f97316','#84cc16','#6366f1'] as $color)
                            <div class="color-option {{ old('color', $project->color ?? '#3b82f6') === $color ? 'selected' : '' }}" style="background:{{ $color }};" data-color="{{ $color }}" onclick="selectColor(this)"></div>
                        @endforeach
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-code"></i> Technologies</div>
                    <div style="display:flex; gap:0.5rem;">
                        <input type="text" id="techInput" class="form-control" placeholder="Ex: Laravel, Vue.js..." style="flex:1;">
                        <button type="button" class="btn-secondary" onclick="addTech()"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="tech-tags" id="techTags"></div>
                    <div id="techHiddenInputs"></div>
                    @if(isset($project) && $project->technologies)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const techs = @json($project->technologies ?? []);
                                techs.forEach(t => addTechValue(t));
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.projects.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="button" class="btn-next" onclick="goToStep(2)">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    {{-- Étape 2 : Dates & Budget --}}
    <div id="step-2" style="display:none;">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-calendar-alt"></i></div>
                <h2>Dates & Budget</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-calendar"></i> Planification</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-play"></i> Date de début</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($project) ? $project->start_date?->format('Y-m-d') : '') }}">
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-flag-checkered"></i> Date de fin prévue</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($project) ? $project->end_date?->format('Y-m-d') : '') }}">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-euro-sign"></i> Budget</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-wallet"></i> Budget total (FCFA)</label>
                            <input type="number" name="budget" class="form-control" value="{{ old('budget', $project->budget ?? '') }}" min="0" step="1000" placeholder="0">
                        </div>
                        @isset($project)
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-chart-pie"></i> Avancement (%)</label>
                            <input type="number" name="progress" class="form-control" value="{{ old('progress', $project->progress ?? 0) }}" min="0" max="100">
                        </div>
                        @endisset
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-file-invoice"></i> Facturable</label>
                            <div style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0;">
                                <input type="hidden" name="is_billable" value="0">
                                <input type="checkbox" name="is_billable" id="is_billable" value="1" {{ old('is_billable', $project->is_billable ?? true) ? 'checked' : '' }} style="accent-color:var(--brand-primary); width:18px; height:18px;">
                                <label for="is_billable" style="font-size:0.875rem; color:var(--text-secondary); cursor:pointer;">Ce projet est facturable</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-sticky-note"></i> Notes internes</div>
                    <div class="form-group">
                        <textarea name="notes" class="form-control" rows="4" placeholder="Notes et informations complémentaires...">{{ old('notes', $project->notes ?? '') }}</textarea>
                        <div class="form-help">Visible uniquement par l'équipe interne.</div>
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

    {{-- Étape 3 : Équipe & Validation --}}
    <div id="step-3" style="display:none;">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-users"></i></div>
                <h2>Équipe & Validation</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user-tie"></i> Chef de projet</div>
                    <div class="form-group">
                        <select name="manager_id" class="form-control">
                            <option value="">— Aucun chef de projet —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('manager_id', $project->manager_id ?? null) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-users"></i> Membres de l'équipe</div>
                    <div class="member-grid">
                        @foreach($users as $user)
                            @php $isSelected = isset($project) && $project->members->contains($user->id); @endphp
                            <label class="member-item {{ $isSelected ? 'selected' : '' }}" for="member_{{ $user->id }}">
                                <input type="checkbox" name="members[]" value="{{ $user->id }}" id="member_{{ $user->id }}"
                                    {{ old('members') ? (in_array($user->id, old('members', [])) ? 'checked' : '') : ($isSelected ? 'checked' : '') }}>
                                <div class="member-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <span style="font-size:0.8125rem; color:var(--text-primary);">{{ $user->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Récapitulatif --}}
                <div class="form-section" id="summarySection">
                    <div class="form-section-title"><i class="fas fa-clipboard-check"></i> Récapitulatif</div>
                    <div id="summaryContent" style="display:grid; gap:0.75rem; font-size:0.875rem; color:var(--text-secondary);">
                        <div style="display:flex; gap:0.5rem;"><span style="color:var(--text-tertiary); min-width:120px;">Nom :</span><strong id="sum_name" style="color:var(--text-primary);"></strong></div>
                        <div style="display:flex; gap:0.5rem;"><span style="color:var(--text-tertiary); min-width:120px;">Type :</span><strong id="sum_type" style="color:var(--text-primary);"></strong></div>
                        <div style="display:flex; gap:0.5rem;"><span style="color:var(--text-tertiary); min-width:120px;">Statut :</span><strong id="sum_status" style="color:var(--text-primary);"></strong></div>
                        <div style="display:flex; gap:0.5rem;"><span style="color:var(--text-tertiary); min-width:120px;">Priorité :</span><strong id="sum_priority" style="color:var(--text-primary);"></strong></div>
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
                {{ isset($project) ? 'Mettre à jour' : 'Créer le projet' }}
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const colors = [];
    let techs = @json(old('technologies', isset($project) ? ($project->technologies ?? []) : []));

    // Rendu initial des techs
    document.addEventListener('DOMContentLoaded', () => {
        techs.forEach(t => renderTechTag(t));
        updateMemberSelection();
    });

    function goToStep(step) {
        if (step > currentStep && !validateStep(currentStep)) return;
        document.getElementById(`step-${currentStep}`).style.display = 'none';
        document.getElementById(`step-${step}`).style.display = 'block';

        // Indicateurs
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
            const name = document.querySelector('[name="name"]').value.trim();
            if (!name) { alert('Le nom du projet est requis.'); return false; }
        }
        return true;
    }

    function selectColor(el) {
        document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('colorInput').value = el.dataset.color;
    }

    function addTech() {
        const input = document.getElementById('techInput');
        const val = input.value.trim();
        if (val && !techs.includes(val)) {
            techs.push(val);
            renderTechTag(val);
            input.value = '';
        }
    }

    document.getElementById('techInput')?.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addTech(); } });

    function renderTechTag(val) {
        const container = document.getElementById('techTags');
        const hidden = document.getElementById('techHiddenInputs');
        const tag = document.createElement('span');
        tag.className = 'tech-tag';
        tag.dataset.val = val;
        tag.innerHTML = `${val} <button type="button" onclick="removeTech(this, '${val}')"><i class="fas fa-times"></i></button>`;
        container.appendChild(tag);
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'technologies[]';
        input.value = val;
        input.dataset.val = val;
        hidden.appendChild(input);
    }

    function removeTech(btn, val) {
        btn.parentElement.remove();
        document.querySelector(`#techHiddenInputs [data-val="${val}"]`)?.remove();
        techs = techs.filter(t => t !== val);
    }

    function updateMemberSelection() {
        document.querySelectorAll('.member-item input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', function() {
                this.closest('.member-item').classList.toggle('selected', this.checked);
            });
        });
    }

    function updateSummary() {
        document.getElementById('sum_name').textContent = document.querySelector('[name="name"]').value || '-';
        document.getElementById('sum_type').textContent = document.querySelector('[name="type"] option:checked')?.text || '-';
        document.getElementById('sum_status').textContent = document.querySelector('[name="status"] option:checked')?.text || '-';
        document.getElementById('sum_priority').textContent = document.querySelector('[name="priority"] option:checked')?.text || '-';
    }

    @if($errors->any())
        // Si erreurs serveur, afficher l'étape appropriée
        const firstError = '{{ $errors->keys()[0] ?? '' }}';
        if (['start_date','end_date','budget','progress','notes'].includes(firstError)) goToStep(2);
    @endif
</script>
@endpush
