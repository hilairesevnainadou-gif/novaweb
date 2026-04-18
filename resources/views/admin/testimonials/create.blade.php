{{-- resources/views/admin/testimonials/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouveau témoignage - NovaTech Admin')
@section('page-title', 'Nouveau témoignage')

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

    @media (max-width: 768px) {
        .col-6 {
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
        min-height: 120px;
        line-height: 1.6;
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
        color: var(--brand-success);
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

    .image-preview {
        margin-top: 0.75rem;
    }

    .image-preview.hidden {
        display: none;
    }

    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid var(--brand-primary);
        background: var(--bg-tertiary);
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

    .me-2 {
        margin-right: 0.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .small {
        font-size: 0.75rem;
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

    /* Rating stars */
    .rating-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .star-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-option:hover {
        border-color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .star-option.selected {
        border-color: var(--brand-primary);
        background: rgba(59, 130, 246, 0.1);
    }

    .star-option input {
        display: none;
    }

    .star-display {
        display: flex;
        gap: 0.125rem;
        color: #f59e0b;
    }

    .star-display i {
        font-size: 0.875rem;
    }

    .star-display i.far {
        color: var(--border-medium);
    }

    .rating-value {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }

    /* Current avatar */
    .current-avatar {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .current-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .recap-box {
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 1rem;
    }

    .recap-item {
        display: flex;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .recap-item:last-child {
        border-bottom: none;
    }

    .recap-label {
        width: 120px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-tertiary);
        text-transform: uppercase;
    }

    .recap-value {
        flex: 1;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .recap-value i {
        color: #f59e0b;
        margin-right: 0.125rem;
    }
</style>
@endpush

@section('content')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-star"></i> Ajouter un témoignage client</h2>
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

        <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" id="testimonialForm">
            @csrf

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations client</div>
                            <div class="step-desc">Nom, poste, entreprise</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Témoignage & avis</div>
                            <div class="step-desc">Message, note, photo</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-info">
                            <div class="step-title">Publication</div>
                            <div class="step-desc">Validation finale</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 1 - Informations client -->
            <div id="step1" class="step-content active">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-user"></i> Nom complet <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-input-camille @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Ex: Jean Dupont"
                                   maxlength="255">
                            <div class="char-counter" id="nameCounter">0/255</div>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-briefcase"></i> Poste / Fonction
                            </label>
                            <input type="text"
                                   id="position"
                                   name="position"
                                   class="form-input-camille @error('position') is-invalid @enderror"
                                   value="{{ old('position') }}"
                                   placeholder="Ex: Directrice Commerciale">
                            @error('position')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-building"></i> Entreprise / Organisation
                            </label>
                            <input type="text"
                                   id="company"
                                   name="company"
                                   class="form-input-camille @error('company') is-invalid @enderror"
                                   value="{{ old('company') }}"
                                   placeholder="Ex: NovaTech Bénin">
                            @error('company')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Témoignage & avis -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-quote-right"></i> Témoignage <span class="text-danger">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      class="form-input-camille @error('content') is-invalid @enderror"
                                      rows="6"
                                      maxlength="2000"
                                      placeholder="Décrivez l'expérience du client avec vos services...
&#10;Exemple : &quot;Je suis ravie de collaborer avec NovaTech. Leur équipe est à l'écoute, professionnelle et les résultats sont exceptionnels. Mon site web a dépassé toutes mes attentes !&quot;"
                                      required>{{ old('content') }}</textarea>
                            <div class="char-counter" id="contentCounter">0/2000</div>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-help">
                                <i class="fas fa-lightbulb"></i>
                                Un témoignage authentique et détaillé inspire plus confiance. Minimum 10 caractères recommandé.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-star"></i> Note (étoiles)
                            </label>
                            <div class="rating-container" id="ratingContainer">
                                @for($i = 5; $i >= 1; $i--)
                                <label class="star-option" data-rating="{{ $i }}">
                                    <input type="radio"
                                           name="rating"
                                           value="{{ $i }}"
                                           {{ old('rating') == $i ? 'checked' : '' }}>
                                    <div class="star-display">
                                        @for($j = 1; $j <= 5; $j++)
                                            <i class="fas fa-star{{ $j <= $i ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-value">{{ $i }}/5</span>
                                </label>
                                @endfor
                                <label class="star-option" data-rating="0">
                                    <input type="radio"
                                           name="rating"
                                           value="0"
                                           {{ old('rating') == 0 ? 'checked' : '' }}>
                                    <span class="rating-value">Non noté</span>
                                </label>
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-help">La note s'affichera sous forme d'étoiles sur le site</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-camera"></i> Photo / Avatar
                            </label>
                            <input type="file"
                                   id="avatar"
                                   name="avatar"
                                   accept="image/*"
                                   class="form-input-camille @error('avatar') is-invalid @enderror">
                            <div id="avatarPreview" class="image-preview hidden">
                                <img id="previewImg" class="preview-img" alt="Aperçu">
                                <button type="button" id="removeImage" class="btn-camille-outline" style="margin-top: 0.5rem; padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                            <div class="form-help">
                                <i class="fas fa-info-circle"></i>
                                Formats: JPG, PNG, WEBP. Max: 2MB. Recommandé: 200x200px (carré)
                            </div>
                            @error('avatar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                <i class="fas fa-eye"></i> Visibilité
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Activer immédiatement
                                </label>
                            </div>
                            <div class="form-help">Décoché = enregistrer comme brouillon (non visible sur le site)</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="recap-box">
                            <strong><i class="fas fa-clipboard-list"></i> Récapitulatif du témoignage</strong>
                            <div class="recap-item">
                                <div class="recap-label">Client :</div>
                                <div class="recap-value" id="recapName">-</div>
                            </div>
                            <div class="recap-item">
                                <div class="recap-label">Poste :</div>
                                <div class="recap-value" id="recapPosition">-</div>
                            </div>
                            <div class="recap-item">
                                <div class="recap-label">Entreprise :</div>
                                <div class="recap-value" id="recapCompany">-</div>
                            </div>
                            <div class="recap-item">
                                <div class="recap-label">Note :</div>
                                <div class="recap-value" id="recapRating">-</div>
                            </div>
                            <div class="recap-item">
                                <div class="recap-label">Témoignage :</div>
                                <div class="recap-value" id="recapContent">-</div>
                            </div>
                        </div>

                        <div class="alert-camille alert-camille-info mt-3">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Prêt à publier ?</strong>
                                <div class="small mt-1">Vérifiez que toutes les informations sont correctes avant validation.</div>
                                <div class="small mt-2">
                                    <i class="fas fa-arrow-right"></i> Ce témoignage sera visible sur la page d'accueil
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
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Publier le témoignage
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

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
            updateRecap();
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
        const name = document.getElementById('name').value.trim();
        if (!name) {
            alert('Veuillez saisir le nom complet du client');
            document.getElementById('name').focus();
            return false;
        }
        return true;
    }

    function validateStep2() {
        const content = document.getElementById('content').value.trim();
        if (!content || content.length < 10) {
            alert('Veuillez saisir un témoignage (minimum 10 caractères)');
            document.getElementById('content').focus();
            return false;
        }
        return true;
    }

    function updateRecap() {
        const name = document.getElementById('name')?.value.trim() || '-';
        const position = document.getElementById('position')?.value.trim() || '-';
        const company = document.getElementById('company')?.value.trim() || '-';
        const content = document.getElementById('content')?.value.trim() || '-';

        // Récupérer la note sélectionnée
        let rating = '-';
        const selectedRating = document.querySelector('input[name="rating"]:checked');
        if (selectedRating && selectedRating.value > 0) {
            const stars = parseInt(selectedRating.value);
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += `<i class="fas fa-star${i <= stars ? '' : '-o'}"></i>`;
            }
            rating = `${starsHtml} ${stars}/5`;
        } else if (selectedRating && selectedRating.value === '0') {
            rating = 'Non noté';
        }

        document.getElementById('recapName').textContent = name;
        document.getElementById('recapPosition').textContent = position;
        document.getElementById('recapCompany').textContent = company;
        document.getElementById('recapRating').innerHTML = rating;

        // Troncature du contenu pour l'aperçu
        const shortContent = content.length > 150 ? content.substring(0, 150) + '...' : content;
        document.getElementById('recapContent').textContent = shortContent;
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep === 1 && validateStep1()) {
            currentStep = 2;
            updateSteps();
        }
        else if (currentStep === 2 && validateStep2()) {
            currentStep = 3;
            updateSteps();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

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

    initCharCounter('name', 'nameCounter', 255);
    initCharCounter('content', 'contentCounter', 2000);

    // ==================== GESTION DES ÉTOILES ====================
    const starOptions = document.querySelectorAll('.star-option');

    starOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }

            // Mettre à jour les classes selected
            starOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });

        // Si déjà sélectionné
        const radio = option.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            option.classList.add('selected');
        }
    });

    // ==================== APERÇU AVATAR ====================
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Vérifier la taille (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Maximum 2MB.');
                    this.value = '';
                    return;
                }

                // Vérifier le type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format non supporté. Utilisez JPG, PNG, GIF ou WEBP.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    avatarPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                avatarPreview.classList.add('hidden');
                previewImg.src = '';
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            avatarInput.value = '';
            avatarPreview.classList.add('hidden');
            previewImg.src = '';
        });
    }

    // ==================== VALIDATION FORMULAIRE ====================
    document.getElementById('testimonialForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication en cours...';
    });

    // Mettre à jour le récapitulatif quand les champs changent
    document.getElementById('name')?.addEventListener('input', updateRecap);
    document.getElementById('position')?.addEventListener('input', updateRecap);
    document.getElementById('company')?.addEventListener('input', updateRecap);
    document.getElementById('content')?.addEventListener('input', updateRecap);
    document.querySelectorAll('input[name="rating"]').forEach(radio => {
        radio.addEventListener('change', updateRecap);
    });
</script>
@endpush
