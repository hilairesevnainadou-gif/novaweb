@extends('admin.layouts.app')

@section('title', 'Nouveau projet - NovaTech Admin')
@section('page-title', 'Nouveau projet')

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

    .col-4 {
        flex: 0 0 33.333%;
        max-width: 33.333%;
    }

    @media (max-width: 992px) {
        .col-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 768px) {
        .col-4 {
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

    .tags-input-container {
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        background: var(--bg-primary);
        padding: 0.375rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        min-height: 42px;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.625rem;
        background: var(--brand-primary);
        color: white;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
    }

    .tag-remove {
        cursor: pointer;
        font-size: 0.625rem;
        opacity: 0.8;
        transition: opacity var(--transition-fast);
    }

    .tag-remove:hover {
        opacity: 1;
    }

    .tags-input {
        flex: 1;
        min-width: 120px;
        border: none;
        background: transparent;
        color: var(--text-primary);
        padding: 0.375rem 0.5rem;
        outline: none;
        font-size: 0.875rem;
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

    .gallery-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .gallery-item {
        position: relative;
        display: inline-block;
    }

    .gallery-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
    }

    .remove-gallery-item {
        position: absolute;
        top: -0.375rem;
        right: -0.375rem;
        width: 1.25rem;
        height: 1.25rem;
        background: var(--brand-error);
        color: white;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.625rem;
        cursor: pointer;
        opacity: 0;
        transition: opacity var(--transition-fast);
    }

    .gallery-item:hover .remove-gallery-item {
        opacity: 1;
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

    .ck-editor__editable {
        min-height: 250px;
        background: var(--bg-primary);
        border-radius: 0 0 var(--radius-md) var(--radius-md);
    }

    .ck-content {
        font-size: 0.875rem;
        line-height: 1.6;
        color: var(--text-primary);
    }

    .ck-editor__top {
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .ck.ck-editor__main>.ck-editor__editable {
        background: var(--bg-primary);
    }

    .ck.ck-toolbar {
        background: var(--bg-tertiary);
        border-color: var(--border-medium);
    }
</style>
@endpush

@section('content')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-plus-circle"></i> Creer un nouveau projet</h2>
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

        <form method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data" id="portfolioForm">
            @csrf

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations generales</div>
                            <div class="step-desc">Titre, categorie, client</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Contenu & medias</div>
                            <div class="step-desc">Description, images</div>
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

            <!-- ETAPE 1 -->
            <div id="step1" class="step-content active">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-heading"></i> Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-input-camille @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="Titre du projet">
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-link"></i> Slug
                            </label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-input-camille @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}"
                                   placeholder="auto-genere">
                            <div class="form-help">Laissez vide pour generation automatique</div>
                            @error('slug')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-tag"></i> Categorie <span class="text-danger">*</span>
                            </label>
                            <select id="category"
                                    name="category"
                                    class="form-input-camille @error('category') is-invalid @enderror"
                                    required>
                                <option value="">Selectionner</option>
                                <option value="site-vitrine" {{ old('category') == 'site-vitrine' ? 'selected' : '' }}>Site Vitrine</option>
                                <option value="e-commerce" {{ old('category') == 'e-commerce' ? 'selected' : '' }}>E-commerce</option>
                                <option value="application-web" {{ old('category') == 'application-web' ? 'selected' : '' }}>Application Web</option>
                                <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="optimisation" {{ old('category') == 'optimisation' ? 'selected' : '' }}>Optimisation SEO</option>
                                <option value="autre" {{ old('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('category')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-user"></i> Client
                            </label>
                            <input type="text"
                                   id="client"
                                   name="client"
                                   class="form-input-camille @error('client') is-invalid @enderror"
                                   value="{{ old('client') }}"
                                   placeholder="Nom du client">
                            @error('client')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-globe"></i> URL
                            </label>
                            <input type="url"
                                   id="url"
                                   name="url"
                                   class="form-input-camille @error('url') is-invalid @enderror"
                                   value="{{ old('url') }}"
                                   placeholder="https://">
                            @error('url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-calendar-alt"></i> Date
                            </label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   class="form-input-camille @error('date') is-invalid @enderror"
                                   value="{{ old('date', date('Y-m-d')) }}">
                            @error('date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-briefcase"></i> Type
                            </label>
                            <select id="project_type"
                                    name="project_type"
                                    class="form-input-camille @error('project_type') is-invalid @enderror">
                                <option value="external" {{ old('project_type', 'external') == 'external' ? 'selected' : '' }}>Externe (client)</option>
                                <option value="internal" {{ old('project_type') == 'internal' ? 'selected' : '' }}>Interne (NovaTech)</option>
                            </select>
                            @error('project_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-sort-numeric-down"></i> Ordre
                            </label>
                            <input type="number"
                                   id="order"
                                   name="order"
                                   class="form-input-camille @error('order') is-invalid @enderror"
                                   value="{{ old('order', 0) }}">
                            <div class="form-help">Plus petit = plus haut</div>
                            @error('order')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Technologies -->
                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-code"></i> Technologies
                            </label>
                            <div class="tags-input-container" id="tagsContainer">
                                <div id="tagsList"></div>
                                <input type="text"
                                       id="technologyInput"
                                       class="tags-input"
                                       placeholder="Ajouter une technologie...">
                            </div>
                            <input type="hidden" name="technologies" id="technologiesHidden" value="">
                            <div class="form-help">Appuyez sur Entree pour ajouter une technologie</div>
                            @error('technologies')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Durée du projet - NOUVEAU CHAMP -->
                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-hourglass-half"></i> Duree du projet
                            </label>
                            <input type="text"
                                   id="duration"
                                   name="duration"
                                   class="form-input-camille @error('duration') is-invalid @enderror"
                                   value="{{ old('duration', '2-3 semaines') }}"
                                   placeholder="Ex: 2-3 semaines">
                            <div class="form-help">Duree estimee du projet</div>
                            @error('duration')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Taille de l'équipe - NOUVEAU CHAMP -->
                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-users"></i> Taille de l'equipe
                            </label>
                            <input type="text"
                                   id="team_size"
                                   name="team_size"
                                   class="form-input-camille @error('team_size') is-invalid @enderror"
                                   value="{{ old('team_size', '2-3 personnes') }}"
                                   placeholder="Ex: 2-3 personnes">
                            <div class="form-help">Nombre de personnes sur le projet</div>
                            @error('team_size')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ETAPE 2 -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-align-left"></i> Description courte <span class="text-danger">*</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-input-camille @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Breve description du projet...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-check-circle"></i> Travaux realises
                            </label>
                            <textarea id="work_done"
                                      name="work_done"
                                      class="form-input-camille @error('work_done') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Travaux effectues...">{{ old('work_done') }}</textarea>
                            @error('work_done')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-image"></i> Image principale
                            </label>
                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   class="form-input-camille @error('image') is-invalid @enderror">
                            <div id="imagePreview" class="image-preview hidden">
                                <img id="previewImg" class="preview-img" alt="Apercu">
                            </div>
                            <div class="form-help">Format: JPG, PNG, GIF. Max: 2MB</div>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-images"></i> Galerie
                            </label>
                            <input type="file"
                                   id="images"
                                   name="images[]"
                                   multiple
                                   accept="image/*"
                                   class="form-input-camille @error('images.*') is-invalid @enderror">
                            <div id="galleryPreview" class="gallery-preview"></div>
                            <div class="form-help">Plusieurs images. Max: 2MB par image</div>
                            @error('images.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-file-alt"></i> Contenu detaille
                            </label>
                            <textarea id="content"
                                      name="content"
                                      class="@error('content') is-invalid @enderror"
                                      rows="12"
                                      placeholder="Redigez le contenu detaille...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ETAPE 3 -->
            <div id="step3" class="step-content">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-star"></i> Mise en avant
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_featured"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Projet a la une
                                </label>
                            </div>
                            <div class="form-help">Apparait en premier sur l'accueil</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-eye"></i> Publication
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Publier immediatement
                                </label>
                            </div>
                            <div class="form-help">Decoche = sauvegarder en brouillon</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-info-circle"></i> Recapitulatif
                            </label>
                            <div class="alert-camille alert-camille-info" style="margin-top: 0.25rem;">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Pret a publier ?</strong>
                                    <div class="small mt-1">Verifiez que toutes les informations sont correctes avant validation.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="prevBtn" class="btn-camille-outline" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Precedent
                </button>
                <div>
                    <a href="{{ route('admin.portfolio.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Creer le projet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

    // CKEDITOR
    ClassicEditor.create(document.querySelector('#content'), {
        toolbar: {
            items: ['heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|', 'alignment', '|',
                    'link', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraphe' },
                { model: 'heading1', view: 'h1', title: 'Titre 1' },
                { model: 'heading2', view: 'h2', title: 'Titre 2' },
                { model: 'heading3', view: 'h3', title: 'Titre 3' }
            ]
        },
        language: 'fr',
        placeholder: 'Redigez le contenu detaille...'
    }).catch(error => console.error(error));

    // GESTION DES ETAPES
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
        const title = document.getElementById('title').value.trim();
        const category = document.getElementById('category').value;
        if (!title) { alert('Veuillez saisir le titre'); document.getElementById('title').focus(); return false; }
        if (!category) { alert('Veuillez selectionner une categorie'); document.getElementById('category').focus(); return false; }
        return true;
    }

    function validateStep2() {
        const description = document.getElementById('description').value.trim();
        if (!description) { alert('Veuillez saisir une description'); document.getElementById('description').focus(); return false; }
        return true;
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep === 1 && validateStep1()) { currentStep = 2; updateSteps(); }
        else if (currentStep === 2 && validateStep2()) { currentStep = 3; updateSteps(); }
    });

    prevBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateSteps(); } });

    // GESTION DES TECHNOLOGIES
    let technologies = [];
    const tagsList = document.getElementById('tagsList');
    const technologyInput = document.getElementById('technologyInput');
    const technologiesHidden = document.getElementById('technologiesHidden');

    const oldTechnologies = @json(old('technologies', []));
    if (Array.isArray(oldTechnologies) && oldTechnologies.length > 0) {
        technologies = [...oldTechnologies];
    } else if (typeof oldTechnologies === 'string' && oldTechnologies && oldTechnologies !== '[]') {
        try {
            const parsed = JSON.parse(oldTechnologies);
            if (Array.isArray(parsed)) technologies = parsed;
        } catch(e) {
            technologies = oldTechnologies.split(',').map(t => t.trim()).filter(t => t);
        }
    }

    function renderTags() {
        tagsList.innerHTML = '';
        technologies.forEach((tech, index) => {
            const tag = document.createElement('span');
            tag.className = 'tag';
            tag.innerHTML = tech + ' <span class="tag-remove" data-index="' + index + '">&times;</span>';
            tagsList.appendChild(tag);
        });
        technologiesHidden.value = JSON.stringify(technologies);
    }

    function addTag(tech) {
        tech = tech.trim();
        if (tech && !technologies.includes(tech)) {
            technologies.push(tech);
            renderTags();
        }
        technologyInput.value = '';
    }

    function removeTag(index) {
        technologies.splice(index, 1);
        renderTags();
    }

    if (technologyInput) {
        technologyInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag(this.value);
            }
        });

        technologyInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                addTag(this.value);
            }
        });
    }

    document.getElementById('tagsContainer')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('tag-remove')) {
            const index = parseInt(e.target.dataset.index);
            removeTag(index);
        }
    });

    renderTags();

    // AUTO-SLUG
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-');
            }
        });
    }

    // APERÇU IMAGE PRINCIPALE
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    }

    // APERÇU GALERIE
    const galleryInput = document.getElementById('images');
    const galleryPreview = document.getElementById('galleryPreview');

    if (galleryInput) {
        galleryInput.addEventListener('change', function(e) {
            galleryPreview.innerHTML = '';
            const files = Array.from(e.target.files);

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = '<img src="' + event.target.result + '"><span class="remove-gallery-item" data-index="' + index + '">&times;</span>';
                        galleryPreview.appendChild(div);

                        div.querySelector('.remove-gallery-item').onclick = function() {
                            div.remove();
                            const dt = new DataTransfer();
                            const remaining = Array.from(galleryInput.files).filter((f, i) => i !== index);
                            remaining.forEach(f => dt.items.add(f));
                            galleryInput.files = dt.files;
                        };
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // SOUMISSION
    document.getElementById('portfolioForm').addEventListener('submit', function(e) {
        technologiesHidden.value = JSON.stringify(technologies);
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creation en cours...';
    });
</script>
@endpush
