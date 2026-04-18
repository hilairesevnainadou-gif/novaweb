{{-- resources/views/admin/blog/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Modifier l\'article - NovaTech Admin')
@section('page-title', 'Modifier l\'article')

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
        .col-4 {
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
        min-height: 100px;
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

    .alert-camille-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
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

    .btn-camille-danger {
        padding: 0.625rem 1.5rem;
        border-radius: var(--radius-md);
        background: var(--brand-error);
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

    .btn-camille-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .image-preview {
        margin-top: 0.75rem;
        position: relative;
        display: inline-block;
    }

    .image-preview.hidden {
        display: none;
    }

    .preview-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
    }

    .current-image {
        margin-top: 0.75rem;
        padding: 0.75rem;
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .current-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: var(--radius-md);
    }

    .current-image-info {
        flex: 1;
    }

    .current-image-info p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--text-secondary);
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

    .justify-content-end {
        justify-content: flex-end;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .me-3 {
        margin-right: 0.75rem;
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
        min-height: 300px;
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

    .slug-preview {
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        padding: 0.5rem 0.75rem;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
        word-break: break-all;
    }

    .slug-preview i {
        margin-right: 0.5rem;
        color: var(--brand-primary);
    }

    .char-counter {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        text-align: right;
        margin-top: 0.25rem;
    }

    .char-counter.warning {
        color: var(--brand-warning);
    }

    .char-counter.danger {
        color: var(--brand-error);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .status-badge.published {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge.draft {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
</style>
@endpush

@section('content')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-edit"></i> Modifier l'article</h2>
    </div>

    <div class="card-body-custom">
        @if(session('success'))
            <div class="alert-camille alert-camille-success mb-4">
                <i class="fas fa-check-circle fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

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

        <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" id="blogForm">
            @csrf
            @method('PUT')

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations générales</div>
                            <div class="step-desc">Titre, catégorie, résumé</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Contenu & médias</div>
                            <div class="step-desc">Article, image principale</div>
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
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-heading"></i> Titre de l'article <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-input-camille @error('title') is-invalid @enderror"
                                   value="{{ old('title', $blog->title) }}"
                                   required
                                   placeholder="Titre de l'article"
                                   maxlength="255">
                            <div class="char-counter" id="titleCounter">0/255</div>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-link"></i> Slug (URL personnalisée)
                            </label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-input-camille @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $blog->slug) }}"
                                   placeholder="auto-généré">
                            <div class="slug-preview" id="slugPreview">
                                <i class="fas fa-globe"></i>
                                <span id="slugPreviewUrl">{{ url('/blog') }}/</span><span id="slugValue">{{ $blog->slug }}</span>
                            </div>
                            <div class="form-help">Laissez vide pour une génération automatique à partir du titre</div>
                            @error('slug')
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
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $blog->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                                <i class="fas fa-quote-right"></i> Résumé / Extrait
                            </label>
                            <textarea id="excerpt"
                                      name="excerpt"
                                      class="form-input-camille @error('excerpt') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Court résumé de l'article (apparaît dans les listes et le SEO)...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                            <div class="char-counter" id="excerptCounter">0/500</div>
                            <div class="form-help">Laissez vide pour utiliser le début du contenu</div>
                            @error('excerpt')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-calendar-alt"></i> Date de publication
                            </label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   class="form-input-camille @error('published_at') is-invalid @enderror"
                                   value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                            <div class="form-help">Laissez vide pour utiliser la date actuelle lors de la publication</div>
                            @error('published_at')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-clock"></i> Temps de lecture (estimation)
                            </label>
                            <input type="text"
                                   id="reading_time"
                                   name="reading_time"
                                   class="form-input-camille"
                                   placeholder="Ex: 5 min"
                                   value="{{ old('reading_time', $blog->reading_time ?? '3 min') }}">
                            <div class="form-help">Temps de lecture estimé</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Contenu & médias -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-file-alt"></i> Contenu de l'article <span class="text-danger">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      class="@error('content') is-invalid @enderror"
                                      rows="15"
                                      placeholder="Rédigez votre article ici...">{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-image"></i> Image à la une
                            </label>

                            @if($blog->image)
                                <div class="current-image" id="currentImageContainer">
                                    @php
                                        $imagePath = $blog->image;
                                        if (!str_starts_with($imagePath, 'blog/') && !str_starts_with($imagePath, 'storage/')) {
                                            $imagePath = 'blog/' . $imagePath;
                                        }
                                        $imagePath = str_replace('storage/', '', $imagePath);
                                    @endphp
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $blog->title }}">
                                    <div class="current-image-info">
                                        <p><strong>Image actuelle</strong></p>
                                        <p class="small">Format: JPG, PNG, WEBP</p>
                                        <button type="button" id="removeCurrentImage" class="btn-camille-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                            <i class="fas fa-trash"></i> Supprimer cette image
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                            @endif

                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   class="form-input-camille @error('image') is-invalid @enderror"
                                   style="margin-top: 0.75rem;">
                            <div id="imagePreview" class="image-preview hidden">
                                <img id="previewImg" class="preview-img" alt="Aperçu">
                            </div>
                            <div class="form-help">Format: JPG, PNG, WEBP. Max: 2MB. Recommandé: 1200x630px</div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-tags"></i> Mots-clés (SEO)
                            </label>
                            <input type="text"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   class="form-input-camille"
                                   placeholder="laravel, php, web development"
                                   value="{{ old('meta_keywords', $blog->meta_keywords ?? '') }}">
                            <div class="form-help">Séparez par des virgules</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-meta"></i> Description SEO (Meta description)
                            </label>
                            <textarea id="meta_description"
                                      name="meta_description"
                                      class="form-input-camille"
                                      rows="2"
                                      maxlength="160"
                                      placeholder="Description pour les moteurs de recherche (155-160 caractères)">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                            <div class="char-counter" id="metaDescCounter">0/160</div>
                            <div class="form-help">Optimisez pour le SEO - 155-160 caractères recommandés</div>
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
                                <i class="fas fa-eye"></i> Statut de publication
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_published"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier / Maintenir publié
                                </label>
                            </div>
                            <div class="form-help">
                                @if($blog->is_published)
                                    <span class="status-badge published mt-2" style="display: inline-flex;">
                                        <i class="fas fa-check-circle"></i> Actuellement publié
                                    </span>
                                @else
                                    <span class="status-badge draft mt-2" style="display: inline-flex;">
                                        <i class="fas fa-pen"></i> Actuellement en brouillon
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-star"></i> Article en vedette
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_featured"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured', $blog->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Mettre en avant sur la page d'accueil
                                </label>
                            </div>
                            <div class="form-help">Apparaît en priorité dans la section "À la une"</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-info-circle"></i> Récapitulatif
                            </label>
                            <div class="alert-camille alert-camille-info" style="margin-top: 0.25rem;">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Prêt à mettre à jour ?</strong>
                                    <div class="small mt-1">Vérifiez que toutes les informations sont correctes avant validation.</div>
                                    <div class="small mt-2">
                                        <i class="fas fa-arrow-right"></i> L'article est accessible à l'URL :
                                        <strong id="finalSlugPreview">{{ url('/blog') }}/{{ $blog->slug }}</strong>
                                    </div>
                                    @if($blog->is_published && $blog->published_at)
                                        <div class="small mt-1">
                                            <i class="fas fa-calendar"></i> Publié le : {{ $blog->published_at->format('d/m/Y à H:i') }}
                                        </div>
                                    @endif
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
                    <a href="{{ route('admin.blog.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Mettre à jour
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

    // ==================== CKEDITOR ====================
    let editorInstance = null;

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
        placeholder: 'Rédigez votre article ici...'
    })
    .then(editor => {
        editorInstance = editor;
    })
    .catch(error => {
        console.error('Erreur CKEditor:', error);
    });

    // ==================== GESTION DES ÉTAPES ====================
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

        if (!title) {
            alert('Veuillez saisir le titre de l\'article');
            document.getElementById('title').focus();
            return false;
        }
        if (!category) {
            alert('Veuillez sélectionner une catégorie');
            document.getElementById('category').focus();
            return false;
        }
        return true;
    }

    function validateStep2() {
        let content = '';
        if (editorInstance) {
            content = editorInstance.getData().trim();
        } else {
            content = document.getElementById('content').value.trim();
        }

        if (!content || content === '<p><br></p>' || content === '<p></p>') {
            alert('Veuillez rédiger le contenu de l\'article');
            return false;
        }
        return true;
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep === 1 && validateStep1()) {
            currentStep = 2;
            updateSteps();
        }
        else if (currentStep === 2 && validateStep2()) {
            currentStep = 3;
            updateSteps();
            updateFinalSlugPreview();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

    // ==================== GÉNÉRATION AUTO DU SLUG ====================
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const slugValue = document.getElementById('slugValue');
    const finalSlugPreview = document.getElementById('finalSlugPreview');

    function generateSlug(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    function updateSlugPreview() {
        let slug = slugInput.value.trim();
        if (!slug) {
            const title = titleInput.value.trim();
            slug = title ? generateSlug(title) : '{{ $blog->slug }}';
        }

        if (slugValue) slugValue.textContent = slug;
        updateFinalSlugPreview();
    }

    function updateFinalSlugPreview() {
        let slug = slugInput.value.trim();
        if (!slug) {
            const title = titleInput.value.trim();
            slug = title ? generateSlug(title) : '{{ $blog->slug }}';
        }
        if (!slug) slug = '{{ $blog->slug }}';

        if (finalSlugPreview) {
            finalSlugPreview.textContent = `{{ url('/blog') }}/${slug}`;
        }
    }

    if (titleInput) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                slugInput.value = generateSlug(this.value);
                updateSlugPreview();
            }
        });

        titleInput.addEventListener('input', function() {
            updateFinalSlugPreview();
        });
    }

    if (slugInput) {
        slugInput.addEventListener('input', updateSlugPreview);
    }

    // Initialiser l'aperçu
    updateSlugPreview();

    // ==================== COMPTEURS DE CARACTÈRES ====================
    function initCharCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);

        if (input && counter) {
            function updateCounter() {
                const length = input.value.length;
                counter.textContent = `${length}/${maxLength}`;
                counter.classList.remove('warning', 'danger');
                if (length > maxLength * 0.9) counter.classList.add('warning');
                if (length >= maxLength) counter.classList.add('danger');
            }

            input.addEventListener('input', updateCounter);
            updateCounter();
        }
    }

    initCharCounter('title', 'titleCounter', 255);
    initCharCounter('excerpt', 'excerptCounter', 500);
    initCharCounter('meta_description', 'metaDescCounter', 160);

    // ==================== GESTION DE L'IMAGE ====================
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeCurrentImageBtn = document.getElementById('removeCurrentImage');
    const removeImageFlag = document.getElementById('removeImageFlag');
    const currentImageContainer = document.getElementById('currentImageContainer');

    // Aperçu de la nouvelle image
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    imagePreview.classList.remove('hidden');
                    // Cacher l'image actuelle si une nouvelle est sélectionnée
                    if (currentImageContainer) {
                        currentImageContainer.style.opacity = '0.5';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                if (currentImageContainer) {
                    currentImageContainer.style.opacity = '1';
                }
            }
        });
    }

    // Supprimer l'image actuelle
    if (removeCurrentImageBtn) {
        removeCurrentImageBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                removeImageFlag.value = '1';
                if (currentImageContainer) {
                    currentImageContainer.style.display = 'none';
                }
                // Afficher un message visuel
                const infoDiv = document.createElement('div');
                infoDiv.className = 'alert-camille alert-camille-info mt-2';
                infoDiv.style.padding = '0.5rem';
                infoDiv.innerHTML = '<i class="fas fa-info-circle"></i> L\'image sera supprimée lors de l\'enregistrement.';
                imageInput.parentNode.appendChild(infoDiv);
                setTimeout(() => infoDiv.remove(), 5000);
            }
        });
    }

    // ==================== VALIDATION FORMULAIRE ====================
    document.getElementById('blogForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour en cours...';
    });

    // ==================== DATE PAR DÉFAUT ====================
    const publishedAtInput = document.getElementById('published_at');
    if (publishedAtInput && !publishedAtInput.value && {{ $blog->is_published ? 'true' : 'false' }}) {
        // Si déjà publié mais pas de date, ne rien faire
    }
</script>
@endpush
