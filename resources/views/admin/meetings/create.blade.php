{{-- resources/views/admin/meetings/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($meeting) ? 'Modifier la réunion - NovaTech Admin' : 'Planifier une réunion - NovaTech Admin')
@section('page-title', isset($meeting) ? 'Modifier la réunion' : 'Planifier une réunion')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; } .breadcrumb a:hover { color:var(--brand-primary); }

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
    textarea.form-control { resize:vertical; min-height:80px; }
    .invalid-feedback { font-size:0.75rem; color:var(--brand-error); margin-top:0.25rem; }
    .form-help { font-size:0.6875rem; color:var(--text-tertiary); margin-top:0.25rem; }

    .mode-selector { display:flex; gap:0.625rem; }
    .mode-option { flex:1; padding:0.75rem; border:2px solid var(--border-light); border-radius:0.5rem; text-align:center; cursor:pointer; transition:all 0.2s; }
    .mode-option input { display:none; }
    .mode-option.selected { border-color:var(--brand-primary); background:rgba(59,130,246,0.05); }
    .mode-option i { font-size:1.25rem; color:var(--text-tertiary); display:block; margin-bottom:0.375rem; }
    .mode-option.selected i { color:var(--brand-primary); }
    .mode-option span { font-size:0.8125rem; font-weight:500; color:var(--text-secondary); }
    .mode-option.selected span { color:var(--brand-primary); }

    .member-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:0.5rem; }
    .member-item { display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border:1px solid var(--border-light); border-radius:0.5rem; background:var(--bg-primary); cursor:pointer; transition:all 0.2s; }
    .member-item:hover { border-color:var(--brand-primary); }
    .member-item.selected { border-color:var(--brand-primary); background:rgba(59,130,246,0.05); }
    .member-item input { accent-color:var(--brand-primary); }
    .member-avatar { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.65rem; font-weight:700; flex-shrink:0; }

    .form-footer { display:flex; align-items:center; justify-content:space-between; padding-top:1.25rem; border-top:1px solid var(--border-light); margin-top:1.25rem; }
    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.875rem; font-weight:500; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .btn-secondary:hover { background:var(--bg-hover); color:var(--text-primary); }
    .btn-next { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.5rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:600; border:none; cursor:pointer; }
    .btn-next:hover { background:var(--brand-primary-hover); }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($meeting) ? 'Modifier la réunion' : 'Planifier une réunion' }}</span>
</div>

<div class="stepper">
    <div class="step active" id="step-indicator-1">
        <div class="step-number">1</div>
        <div class="step-label">Informations</div>
    </div>
    <div class="step-connector"></div>
    <div class="step" id="step-indicator-2">
        <div class="step-number">2</div>
        <div class="step-label">Participants & Validation</div>
    </div>
</div>

<form method="POST" action="{{ isset($meeting) ? route('admin.projects.meetings.update', [$project, $meeting]) : route('admin.projects.meetings.store', $project) }}">
    @csrf
    @isset($meeting)@method('PUT')@endisset

    {{-- Étape 1 --}}
    <div id="step-1">
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-video"></i></div>
                <h2>Informations de la réunion</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-tag"></i> Général</div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-heading"></i> Titre <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $meeting->title ?? '') }}" placeholder="Ex: Réunion de lancement du projet" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Type <span class="required">*</span></label>
                            <select name="type" class="form-control" required>
                                @foreach(\App\Models\Meeting::TYPES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('type', $meeting->type ?? 'other') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @isset($meeting)
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-circle-dot"></i> Statut</label>
                            <select name="status" class="form-control">
                                @foreach(\App\Models\Meeting::STATUSES as $val => $label)
                                    <option value="{{ $val }}" @selected(old('status', $meeting->status) === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endisset
                        <div class="form-group col-span-2">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Objectif de la réunion...">{{ old('description', $meeting->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-calendar"></i> Planning</div>
                    <div class="form-grid form-grid-3">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar-day"></i> Date & Heure <span class="required">*</span></label>
                            <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror"
                                   value="{{ old('scheduled_at', isset($meeting) ? $meeting->scheduled_at->format('Y-m-d\TH:i') : '') }}" required>
                            @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-hourglass-half"></i> Durée (minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $meeting->duration_minutes ?? '') }}" min="5" step="5" placeholder="60">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-location-dot"></i> Mode & Lieu</div>
                    <input type="hidden" name="meeting_mode" id="modeInput" value="{{ old('meeting_mode', $meeting->meeting_mode ?? 'online') }}">
                    <div class="mode-selector" style="margin-bottom:1rem;">
                        @foreach(['in_person' => ['fa-users', 'Présentiel'], 'online' => ['fa-video', 'En ligne'], 'hybrid' => ['fa-code-branch', 'Hybride']] as $val => [$icon, $label])
                        <div class="mode-option {{ old('meeting_mode', $meeting->meeting_mode ?? 'online') === $val ? 'selected' : '' }}"
                             onclick="selectMode('{{ $val }}', this)">
                            <i class="fas {{ $icon }}"></i>
                            <span>{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group" id="locationGroup" style="{{ old('meeting_mode', $meeting->meeting_mode ?? 'online') === 'online' ? 'display:none;' : '' }}">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Lieu</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $meeting->location ?? '') }}" placeholder="Ex: Salle de conférence A">
                        </div>
                        <div class="form-group" id="urlGroup" style="{{ old('meeting_mode', $meeting->meeting_mode ?? 'online') === 'in_person' ? 'display:none;' : '' }}">
                            <label class="form-label"><i class="fas fa-link"></i> Lien de la réunion</label>
                            <input type="url" name="meeting_url" class="form-control" value="{{ old('meeting_url', $meeting->meeting_url ?? '') }}" placeholder="https://meet.google.com/...">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-list"></i> Ordre du jour</div>
                    <div class="form-group">
                        <textarea name="agenda" class="form-control" rows="4" placeholder="Points à aborder lors de cette réunion...">{{ old('agenda', $meeting->agenda ?? '') }}</textarea>
                    </div>
                </div>

                @isset($meeting)
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-file-alt"></i> Compte-rendu</div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Compte-rendu / PV</label>
                        <textarea name="minutes" class="form-control" rows="4" placeholder="Résumé de ce qui a été dit...">{{ old('minutes', $meeting->minutes ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Actions à prendre</label>
                        <textarea name="action_items" class="form-control" rows="3" placeholder="Liste des actions décidées...">{{ old('action_items', $meeting->action_items ?? '') }}</textarea>
                    </div>
                </div>
                @endisset
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
                <div class="card-header-icon"><i class="fas fa-users"></i></div>
                <h2>Participants & Validation</h2>
            </div>
            <div class="card-body">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-users"></i> Participants</div>
                    <div class="member-grid">
                        @foreach($users as $user)
                        @php
                            $isSelected = isset($meeting) && $meeting->participants->contains($user->id);
                        @endphp
                        <label class="member-item {{ $isSelected ? 'selected' : '' }}" for="participant_{{ $user->id }}">
                            <input type="checkbox" name="participants[]" value="{{ $user->id }}" id="participant_{{ $user->id }}"
                                {{ old('participants') ? (in_array($user->id, old('participants', [])) ? 'checked' : '') : ($isSelected ? 'checked' : '') }}>
                            <div class="member-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <span style="font-size:0.8125rem; color:var(--text-primary);">{{ $user->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="background:var(--bg-tertiary); border-radius:0.625rem; padding:1.25rem;">
                    <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; color:var(--text-tertiary); margin-bottom:0.875rem; letter-spacing:0.5px;">Récapitulatif</div>
                    <div style="display:grid; gap:0.625rem; font-size:0.875rem;">
                        <div style="display:flex; gap:0.75rem;">
                            <span style="color:var(--text-tertiary); min-width:100px;">Titre :</span>
                            <strong id="sum_title" style="color:var(--text-primary);"></strong>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <span style="color:var(--text-tertiary); min-width:100px;">Date :</span>
                            <strong id="sum_date" style="color:var(--text-primary);"></strong>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <span style="color:var(--text-tertiary); min-width:100px;">Type :</span>
                            <strong id="sum_type" style="color:var(--text-primary);"></strong>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <span style="color:var(--text-tertiary); min-width:100px;">Mode :</span>
                            <strong id="sum_mode" style="color:var(--text-primary);"></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-secondary" onclick="goToStep(1)">
                <i class="fas fa-arrow-left"></i> Précédent
            </button>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                {{ isset($meeting) ? 'Mettre à jour' : 'Planifier la réunion' }}
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

        for (let i = 1; i <= 2; i++) {
            const ind = document.getElementById(`step-indicator-${i}`);
            ind.classList.remove('active', 'done');
            if (i < step) { ind.classList.add('done'); ind.querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>'; }
            else if (i === step) ind.classList.add('active');
        }

        currentStep = step;
        if (step === 2) updateSummary();
    }

    function validateStep(step) {
        if (step === 1) {
            const title = document.querySelector('[name="title"]').value.trim();
            const date = document.querySelector('[name="scheduled_at"]').value;
            if (!title) { alert('Le titre est requis.'); return false; }
            if (!date) { alert('La date est requise.'); return false; }
        }
        return true;
    }

    function selectMode(val, el) {
        document.querySelectorAll('.mode-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('modeInput').value = val;
        document.getElementById('locationGroup').style.display = val === 'online' ? 'none' : '';
        document.getElementById('urlGroup').style.display = val === 'in_person' ? 'none' : '';
    }

    function updateSummary() {
        document.getElementById('sum_title').textContent = document.querySelector('[name="title"]').value || '-';
        const rawDate = document.querySelector('[name="scheduled_at"]').value;
        document.getElementById('sum_date').textContent = rawDate ? new Date(rawDate).toLocaleString('fr-FR') : '-';
        document.getElementById('sum_type').textContent = document.querySelector('[name="type"] option:checked')?.text || '-';
        document.getElementById('sum_mode').textContent = document.querySelector('.mode-option.selected span')?.textContent || '-';
    }

    // Participant checkbox UX
    document.querySelectorAll('.member-item').forEach(item => {
        item.querySelector('input[type="checkbox"]').addEventListener('change', function() {
            item.classList.toggle('selected', this.checked);
        });
    });
</script>
@endpush
