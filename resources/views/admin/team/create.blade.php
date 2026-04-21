{{-- resources/views/admin/team/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', isset($member) ? 'Modifier le membre - NovaTech Admin' : 'Nouveau membre - NovaTech Admin')
@section('page-title', isset($member) ? 'Modifier le membre' : 'Ajouter un membre')

@push('styles')
<style>
    /* ============================================
       TEAM FORM — 2 étapes — design system
    ============================================ */

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

    /* ── Stepper ── */
    .stepper {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        flex: 1;
    }

    .step:not(:last-child)::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-medium);
        margin: 0 0.75rem;
        transition: background var(--transition-base);
    }
    .step.completed:not(:last-child)::after { background: var(--brand-primary); }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-full);
        border: 2px solid var(--border-medium);
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-tertiary);
        flex-shrink: 0;
        transition: all var(--transition-base);
    }
    .step.active .step-circle {
        border-color: var(--brand-primary);
        background: var(--brand-primary);
        color: #fff;
    }
    .step.completed .step-circle {
        border-color: var(--brand-primary);
        background: rgba(59, 130, 246, 0.1);
        color: var(--brand-primary);
    }

    .step-info { min-width: 0; }
    .step-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        white-space: nowrap;
    }
    .step.active .step-label    { color: var(--brand-primary); }
    .step.completed .step-label { color: var(--text-secondary); }
    .step-desc {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        margin-top: 0.1rem;
        white-space: nowrap;
    }

    /* ── Card ── */
    .card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xs);
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
        background: rgba(59, 130, 246, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-primary);
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    .card-header h2 { font-size: 0.9375rem; font-weight: 600; margin: 0; color: var(--text-primary); }

    .card-body { padding: 1.5rem; }

    /* ── Step panels ── */
    .step-panel { display: none; }
    .step-panel.active { display: block; }

    /* ── Sections ── */
    .form-section { margin-bottom: 1.75rem; }
    .form-section:last-child { margin-bottom: 0; }

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
    .form-section-title i { color: var(--brand-primary); }

    /* ── Grid ── */
    .form-grid { display: grid; gap: 1rem; }
    .form-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .form-grid-3 { grid-template-columns: repeat(3, 1fr); }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        .stepper { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
        .step::after { display: none; }
    }

    /* ── Form elements ── */
    .form-group { display: flex; flex-direction: column; gap: 0.375rem; }

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
    .form-label i { width: 0.875rem; text-align: center; }
    .required { color: var(--brand-error); }

    .form-control {
        width: 100%;
        padding: 0.5625rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-family: inherit;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        outline: none;
    }
    .form-control::placeholder { color: var(--text-tertiary); }
    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-control.is-invalid { border-color: var(--brand-error); }
    textarea.form-control { resize: vertical; min-height: 90px; }

    .form-help { font-size: 0.6875rem; color: var(--text-tertiary); line-height: 1.4; }
    .invalid-feedback { font-size: 0.75rem; color: var(--brand-error); }

    /* ── Toggle ── */
    .toggle-group { display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem 0; }
    .toggle-input {
        width: 2.25rem;
        height: 1.25rem;
        border-radius: var(--radius-full);
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: pointer;
        appearance: none;
        position: relative;
        flex-shrink: 0;
        transition: background var(--transition-fast), border-color var(--transition-fast);
    }
    .toggle-input::before {
        content: '';
        position: absolute;
        width: 0.875rem; height: 0.875rem;
        border-radius: 50%;
        background: white;
        top: 0.125rem; left: 0.125rem;
        transition: transform var(--transition-fast);
        box-shadow: var(--shadow-xs);
    }
    .toggle-input:checked { background: var(--brand-success); border-color: var(--brand-success); }
    .toggle-input:checked::before { transform: translateX(1rem); }
    .toggle-label { font-size: 0.875rem; color: var(--text-secondary); cursor: pointer; user-select: none; }

    /* ── Upload ── */
    .upload-area {
        border: 1.5px dashed var(--border-medium);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        background: var(--bg-primary);
    }
    .upload-area:hover { border-color: var(--brand-primary); background: rgba(59,130,246,0.04); }
    .upload-area > i { font-size: 1.5rem; color: var(--text-tertiary); margin-bottom: 0.375rem; display: block; }
    .upload-area p { font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem; }

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
        width: 48px; height: 48px;
        object-fit: cover;
        border-radius: var(--radius-full);
        border: 2px solid var(--border-medium);
        flex-shrink: 0;
    }
    .preview-info { flex: 1; min-width: 0; }
    .preview-name { font-size: 0.8125rem; font-weight: 500; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-remove-img {
        background: none; border: none; color: var(--text-tertiary);
        cursor: pointer; padding: 0.375rem;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast); flex-shrink: 0;
    }
    .btn-remove-img:hover { background: rgba(239,68,68,0.1); color: var(--brand-error); }

    /* ── Skills ── */
    .skills-box {
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        padding: 0.875rem;
        background: var(--bg-primary);
        display: flex; flex-direction: column; gap: 0.75rem;
    }
    .skill-input-row { display: flex; gap: 0.5rem; }
    .skill-input-row input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-size: 0.875rem; font-family: inherit;
        outline: none;
        transition: border-color var(--transition-fast);
    }
    .skill-input-row input:focus { border-color: var(--brand-primary); }
    .skill-input-row input::placeholder { color: var(--text-tertiary); }

    .btn-add-skill {
        padding: 0.5rem 0.875rem;
        background: var(--brand-primary); color: white;
        border: none; border-radius: var(--radius-md);
        cursor: pointer; font-size: 0.75rem; font-family: inherit; font-weight: 500;
        white-space: nowrap;
        transition: background var(--transition-fast);
        display: inline-flex; align-items: center; gap: 0.375rem;
    }
    .btn-add-skill:hover { background: var(--brand-primary-hover); }

    .skills-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; min-height: 1rem; }
    .skill-tag {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        padding: 0.25rem 0.625rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem; font-weight: 500; color: var(--text-primary);
        display: flex; align-items: center; gap: 0.375rem;
    }
    .btn-remove-skill {
        background: none; border: none; color: var(--text-tertiary);
        cursor: pointer; padding: 0; font-size: 0.625rem;
        display: flex; align-items: center;
        transition: color var(--transition-fast);
    }
    .btn-remove-skill:hover { color: var(--brand-error); }

    /* ── Alert ── */
    .alert {
        padding: 0.875rem 1rem;
        border-radius: var(--radius-md);
        display: flex; align-items: flex-start; gap: 0.75rem;
        margin-bottom: 1.25rem; font-size: 0.875rem;
    }
    .alert-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: var(--brand-error); }
    .alert ul { margin: 0.5rem 0 0 1rem; padding: 0; }
    .alert li { margin-bottom: 0.25rem; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5625rem 1.125rem;
        border-radius: var(--radius-md);
        font-size: 0.8125rem; font-weight: 500; font-family: inherit;
        cursor: pointer; border: none; text-decoration: none;
        transition: all var(--transition-fast); white-space: nowrap;
    }
    .btn-primary { background: var(--brand-primary); color: #fff; }
    .btn-primary:hover { background: var(--brand-primary-hover); transform: translateY(-1px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-ghost { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-light); }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

    .form-footer {
        display: flex; align-items: center; justify-content: space-between; gap: 0.625rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-light);
        margin-top: 1.75rem;
    }
    .form-footer-right { display: flex; gap: 0.625rem; }
</style>
@endpush

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('admin.team.index') }}">Équipe</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ isset($member) ? 'Modifier' : 'Nouveau membre' }}</span>
</nav>

{{-- ── Stepper ── --}}
<div class="stepper">
    <div class="step active" data-step="1">
        <div class="step-circle">1</div>
        <div class="step-info">
            <div class="step-label">Étape 1</div>
            <div class="step-desc">Infos & photo</div>
        </div>
    </div>
    <div class="step" data-step="2">
        <div class="step-circle">2</div>
        <div class="step-info">
            <div class="step-label">Étape 2</div>
            <div class="step-desc">Compétences & réseaux</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas {{ isset($member) ? 'fa-edit' : 'fa-user-plus' }}"></i>
        </div>
        <h2 id="cardTitle">{{ isset($member) ? 'Modifier le membre' : 'Ajouter un membre' }}</h2>
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

        <form method="POST"
              action="{{ isset($member) ? route('admin.team.update', $member) : route('admin.team.store') }}"
              enctype="multipart/form-data"
              id="teamForm">
            @csrf
            @isset($member) @method('PUT') @endisset

            {{-- ══════════ ÉTAPE 1 : Infos & photo ══════════ --}}
            <div class="step-panel active" id="panel-1">

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user"></i> Informations principales
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                <i class="fas fa-user"></i> Nom complet <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $member->name ?? '') }}"
                                   required placeholder="Nom et prénom">
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="position">
                                <i class="fas fa-briefcase"></i> Poste <span class="required">*</span>
                            </label>
                            <input type="text" id="position" name="position"
                                   class="form-control @error('position') is-invalid @enderror"
                                   value="{{ old('position', $member->position ?? '') }}"
                                   required placeholder="Développeur, Designer…">
                            @error('position')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $member->email ?? '') }}"
                                   placeholder="contact@exemple.com">
                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">
                                <i class="fas fa-phone"></i> Téléphone
                            </label>
                            <input type="text" id="phone" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $member->phone ?? '') }}"
                                   placeholder="+229 XX XX XX XX">
                            @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-image"></i> Photo de profil
                    </div>
                    <div class="form-group">
                        <div class="upload-area" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Cliquez pour télécharger la photo</p>
                            <span class="form-help">PNG, JPG — max 2 MB · recommandé 400 × 400 px</span>
                            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                        </div>
                        <div id="photoPreviewContainer">
                            @if(isset($member) && $member->photo)
                            <div class="image-preview" id="photoPreview">
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="Photo">
                                <div class="preview-info">
                                    <div class="preview-name">Photo actuelle</div>
                                    <span class="form-help">{{ basename($member->photo) }}</span>
                                </div>
                                <button type="button" class="btn-remove-img" data-preview="photoPreview">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        @error('photo')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-align-left"></i> Présentation
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="bio">
                                <i class="fas fa-align-left"></i> Biographie
                            </label>
                            <textarea id="bio" name="bio" rows="4"
                                      class="form-control @error('bio') is-invalid @enderror"
                                      placeholder="Parcours, expériences, passions…">{{ old('bio', $member->bio ?? '') }}</textarea>
                            @error('bio')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="quote">
                                <i class="fas fa-quote-right"></i> Citation
                            </label>
                            <textarea id="quote" name="quote" rows="2"
                                      class="form-control @error('quote') is-invalid @enderror"
                                      placeholder="Une citation inspirante…">{{ old('quote', $member->quote ?? '') }}</textarea>
                            @error('quote')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sliders-h"></i> Paramètres d'affichage
                    </div>
                    <div class="form-grid form-grid-3">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-toggle-on"></i> Statut</label>
                            <div class="toggle-group">
                                <input type="checkbox" class="toggle-input" id="is_active"
                                       name="is_active" value="1"
                                       {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
                                <label class="toggle-label" for="is_active">Membre actif</label>
                            </div>
                            <span class="form-help">Les membres inactifs n'apparaissent pas sur le site</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-star"></i> À la une</label>
                            <div class="toggle-group">
                                <input type="checkbox" class="toggle-input" id="is_featured"
                                       name="is_featured" value="1"
                                       {{ old('is_featured', $member->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="toggle-label" for="is_featured">Mettre en avant</label>
                            </div>
                            <span class="form-help">Apparaît en premier sur la page Équipe</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="order">
                                <i class="fas fa-sort-numeric-down"></i> Ordre d'affichage
                            </label>
                            <input type="number" id="order" name="order" min="0"
                                   class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', $member->order ?? 0) }}"
                                   placeholder="0">
                            <span class="form-help">Plus petit = affiché en premier</span>
                            @error('order')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" class="btn btn-primary" onclick="goToStep(2)">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ══════════ ÉTAPE 2 : Compétences & réseaux ══════════ --}}
            <div class="step-panel" id="panel-2">

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-code"></i> Compétences
                    </div>
                    <div class="form-group">
                        <div class="skills-box">
                            <div class="skill-input-row">
                                <input type="text" id="skillInput"
                                       placeholder="Ex : Laravel, React, UI/UX Design…">
                                <button type="button" class="btn-add-skill" onclick="addSkill()">
                                    <i class="fas fa-plus"></i> Ajouter
                                </button>
                            </div>
                            <div id="skillsList" class="skills-tags"></div>
                        </div>
                        {{--
                            Les compétences sont envoyées via des inputs skills[]
                            → Laravel reçoit un tableau : $request->input('skills') = ['Laravel', 'React', ...]
                            → Règle de validation correcte : 'skills' => 'nullable|array', 'skills.*' => 'string'
                        --}}
                        <div id="skillsInputs"></div>
                        <span class="form-help">Appuyez sur Entrée ou cliquez Ajouter pour valider</span>
                        @error('skills')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        @error('skills.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-share-alt"></i> Réseaux sociaux
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="linkedin">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </label>
                            <input type="url" id="linkedin" name="linkedin"
                                   class="form-control @error('linkedin') is-invalid @enderror"
                                   value="{{ old('linkedin', $member->linkedin ?? '') }}"
                                   placeholder="https://linkedin.com/in/…">
                            @error('linkedin')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="github">
                                <i class="fab fa-github"></i> GitHub
                            </label>
                            <input type="url" id="github" name="github"
                                   class="form-control @error('github') is-invalid @enderror"
                                   value="{{ old('github', $member->github ?? '') }}"
                                   placeholder="https://github.com/…">
                            @error('github')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="twitter">
                                <i class="fab fa-twitter"></i> Twitter / X
                            </label>
                            <input type="url" id="twitter" name="twitter"
                                   class="form-control @error('twitter') is-invalid @enderror"
                                   value="{{ old('twitter', $member->twitter ?? '') }}"
                                   placeholder="https://twitter.com/…">
                            @error('twitter')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="facebook">
                                <i class="fab fa-facebook"></i> Facebook
                            </label>
                            <input type="url" id="facebook" name="facebook"
                                   class="form-control @error('facebook') is-invalid @enderror"
                                   value="{{ old('facebook', $member->facebook ?? '') }}"
                                   placeholder="https://facebook.com/…">
                            @error('facebook')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-ghost" onclick="goToStep(1)">
                        <i class="fas fa-arrow-left"></i> Précédent
                    </button>
                    <div class="form-footer-right">
                        <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            {{ isset($member) ? 'Mettre à jour' : 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ══════════════════════════════════════
    //  STEPPER
    // ══════════════════════════════════════
    let currentStep = 1;

    // Après une soumission avec erreurs : détecter si l'erreur
    // concerne l'étape 2 pour y revenir automatiquement
    @if($errors->any())
    (function() {
        const step2Keys = ['skills', 'linkedin', 'github', 'twitter', 'facebook'];
        const errorKeys = @json($errors->keys());
        const hasStep2Error = errorKeys.some(k => step2Keys.includes(k) || k.startsWith('skills.'));
        if (hasStep2Error) currentStep = 2;
    })();
    @endif

    window.goToStep = function (step) {
        // Validation côté client avant de passer à l'étape 2
        if (step === 2 && currentStep === 1) {
            const nameEl     = document.getElementById('name');
            const positionEl = document.getElementById('position');
            let valid = true;

            [nameEl, positionEl].forEach(el => {
                el.classList.remove('is-invalid');
                if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    valid = false;
                }
            });

            if (!valid) { nameEl.focus(); return; }
        }

        currentStep = step;

        // Panels
        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + step).classList.add('active');

        // Stepper visuels
        document.querySelectorAll('.step').forEach(s => {
            const n = parseInt(s.dataset.step);
            s.classList.remove('active', 'completed');
            if (n === step)    s.classList.add('active');
            else if (n < step) s.classList.add('completed');

            // Icône check dans le cercle de l'étape complétée
            const circle = s.querySelector('.step-circle');
            if (n < step) {
                circle.innerHTML = '<i class="fas fa-check" style="font-size:0.7rem;"></i>';
            } else if (n === step) {
                circle.textContent = n;
            } else {
                circle.textContent = n;
            }
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // Initialisation
    goToStep(currentStep);

    // ══════════════════════════════════════
    //  COMPÉTENCES — envoyées via skills[]
    //  Règle Laravel : 'skills' => 'nullable|array'
    //                  'skills.*' => 'string|max:100'
    // ══════════════════════════════════════
    let skills = [];

    // Recharger les compétences existantes (édition ou old input)
    @if(isset($member) && !empty($member->skills))
        skills = @json($member->skills);
    @elseif(old('skills'))
        skills = @json(old('skills', []));
    @endif

    const skillsList   = document.getElementById('skillsList');
    const skillsInputs = document.getElementById('skillsInputs');
    const skillInput   = document.getElementById('skillInput');

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function renderSkills() {
        // ── Tags visuels
        skillsList.innerHTML = '';
        skills.forEach((skill, i) => {
            const tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.innerHTML = escHtml(skill) +
                '<button type="button" class="btn-remove-skill" title="Supprimer">' +
                '<i class="fas fa-times"></i></button>';
            tag.querySelector('.btn-remove-skill').addEventListener('click', () => {
                skills.splice(i, 1);
                renderSkills();
            });
            skillsList.appendChild(tag);
        });

        // ── Inputs cachés skills[] → tableau Laravel valide
        skillsInputs.innerHTML = '';
        skills.forEach(skill => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'skills[]';
            inp.value = skill;
            skillsInputs.appendChild(inp);
        });
    }

    window.addSkill = function () {
        const val = skillInput.value.trim();
        if (val && !skills.includes(val)) {
            skills.push(val);
            renderSkills();
            skillInput.value = '';
            skillInput.focus();
        }
    };

    skillInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); window.addSkill(); }
    });

    renderSkills();

    // ══════════════════════════════════════
    //  APERÇU PHOTO
    // ══════════════════════════════════════
    const photoInput            = document.getElementById('photoInput');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');

    photoInput?.addEventListener('change', function () {
        if (!this.files?.[0]) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('photoPreview')?.remove();
            const div = document.createElement('div');
            div.id = 'photoPreview';
            div.className = 'image-preview';
            div.innerHTML =
                '<img src="' + ev.target.result + '" alt="Aperçu">' +
                '<div class="preview-info">' +
                  '<div class="preview-name">Nouvelle photo</div>' +
                  '<span class="form-help">' + (photoInput.files[0].size / 1024).toFixed(1) + ' KB</span>' +
                '</div>' +
                '<button type="button" class="btn-remove-img" data-preview="photoPreview">' +
                  '<i class="fas fa-trash-alt"></i>' +
                '</button>';
            photoPreviewContainer.appendChild(div);
            bindRemoveImg();
        };
        reader.readAsDataURL(this.files[0]);
    });

    function bindRemoveImg() {
        document.querySelectorAll('.btn-remove-img').forEach(btn => {
            btn.onclick = function (ev) {
                ev.stopPropagation();
                document.getElementById(this.dataset.preview)?.remove();
                if (photoInput) photoInput.value = '';
            };
        });
    }
    bindRemoveImg();

    // ══════════════════════════════════════
    //  SUBMIT
    // ══════════════════════════════════════
    document.getElementById('teamForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ isset($member) ? "Mise à jour…" : "Enregistrement…" }}';
        }
    });

})();
</script>
@endpush
