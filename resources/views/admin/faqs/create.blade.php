@extends('admin.layouts.app')

@section('title', 'Nouvelle FAQ - NovaTech Admin')
@section('page-title', 'Nouvelle FAQ')

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
        .col-4, .col-6 {
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
        min-height: 120px;
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

    /* Rich text editor styles */
    .ck-editor__editable {
        min-height: 200px;
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

    .category-icon {
        margin-right: 0.5rem;
    }

    .recap-box {
        background: var(--bg-tertiary);
        border-radius: var(--radius-md);
        padding: 1rem;
    }

    .recap-item {
        display: flex;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-light);
    }

    .recap-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .recap-label {
        width: 100px;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .recap-value {
        flex: 1;
        color: var(--text-primary);
    }

    .badge-preview {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .badge-preview.general { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-preview.services { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-preview.tarifs { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-preview.technique { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .badge-preview.support { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
</style>
@endpush

@section('content')
@can('faqs.create')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-question-circle"></i> Créer une nouvelle FAQ</h2>
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

        <form method="POST" action="{{ route('admin.faqs.store') }}" id="faqForm">
            @csrf

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Question & catégorie</div>
                            <div class="step-desc">La question et son classement</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Réponse</div>
                            <div class="step-desc">Le contenu détaillé</div>
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

            <!-- ÉTAPE 1 - Question & catégorie -->
            <div id="step1" class="step-content active">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-question"></i> Question <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="question"
                                   name="question"
                                   class="form-input-camille @error('question') is-invalid @enderror"
                                   value="{{ old('question') }}"
                                   required
                                   placeholder="Ex: Quels sont vos délais de livraison ?">
                            <div class="form-help">La question qui sera affichée aux utilisateurs</div>
                            @error('question')
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
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
                                        <i class="fas
                                            @if($value == 'general') fa-globe
                                            @elseif($value == 'services') fa-cogs
                                            @elseif($value == 'tarifs') fa-tag
                                            @elseif($value == 'technique') fa-microchip
                                            @elseif($value == 'support') fa-headset
                                            @endif
                                        "></i>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">La catégorie détermine où la FAQ apparaîtra</div>
                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-sort-numeric-down"></i> Ordre d'affichage
                            </label>
                            <input type="number"
                                   id="order"
                                   name="order"
                                   class="form-input-camille @error('order') is-invalid @enderror"
                                   value="{{ old('order', 0) }}">
                            <div class="form-help">Plus le chiffre est petit, plus la FAQ apparaît en haut</div>
                            @error('order')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-eye"></i> Aperçu catégorie
                            </label>
                            <div id="categoryPreview" class="recap-box">
                                <span id="categoryBadge" class="badge-preview general">
                                    <i class="fas fa-globe"></i> Général
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Réponse -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-reply"></i> Réponse <span class="text-danger">*</span>
                            </label>
                            <textarea id="answer"
                                      name="answer"
                                      class="@error('answer') is-invalid @enderror"
                                      rows="10"
                                      placeholder="Rédigez la réponse détaillée...">{{ old('answer') }}</textarea>
                            <div class="form-help">La réponse qui sera affichée lorsque l'utilisateur clique sur la question</div>
                            @error('answer')
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
                                <i class="fas fa-eye"></i> Statut
                            </label>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (visible sur le site)
                                </label>
                            </div>
                            <div class="form-help">Décoché = FAQ masquée sur le site</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-info-circle"></i> Récapitulatif
                            </label>
                            <div class="alert-camille alert-camille-info" style="margin-top: 0.25rem;">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Prêt à publier ?</strong>
                                    <div class="small mt-1">Vérifiez que toutes les informations sont correctes avant validation.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-file-alt"></i> Récapitulatif de la FAQ
                            </label>
                            <div class="recap-box">
                                <div class="recap-item">
                                    <div class="recap-label">Question :</div>
                                    <div class="recap-value" id="summaryQuestion">-</div>
                                </div>
                                <div class="recap-item">
                                    <div class="recap-label">Catégorie :</div>
                                    <div class="recap-value" id="summaryCategory">-</div>
                                </div>
                                <div class="recap-item">
                                    <div class="recap-label">Ordre :</div>
                                    <div class="recap-value" id="summaryOrder">-</div>
                                </div>
                                <div class="recap-item">
                                    <div class="recap-label">Statut :</div>
                                    <div class="recap-value" id="summaryStatus">-</div>
                                </div>
                                <div class="recap-item">
                                    <div class="recap-label">Aperçu réponse :</div>
                                    <div class="recap-value" id="summaryAnswer">-</div>
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
                    <a href="{{ route('admin.faqs.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Créer la FAQ
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@cannot('faqs.create')
<div class="empty-state" style="padding: 3rem; text-align: center;">
    <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
    <p>Vous n'avez pas la permission de créer des FAQ.</p>
</div>
@endcannot
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    AOS.init({ duration: 500, once: true, offset: 10 });

    // CKEDITOR pour la réponse
    let answerEditor = null;

    ClassicEditor.create(document.querySelector('#answer'), {
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
        placeholder: 'Rédigez la réponse détaillée...'
    }).then(editor => {
        answerEditor = editor;
        editor.model.document.on('change:data', () => {
            updateSummary();
        });
    }).catch(error => console.error(error));

    // GESTION DES ÉTAPES
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
            updateSummary();
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
        const question = document.getElementById('question').value.trim();
        const category = document.getElementById('category').value;
        if (!question) { alert('Veuillez saisir la question'); document.getElementById('question').focus(); return false; }
        if (!category) { alert('Veuillez sélectionner une catégorie'); document.getElementById('category').focus(); return false; }
        return true;
    }

    function validateStep2() {
        let answer = '';
        if (answerEditor) {
            answer = answerEditor.getData().trim();
        } else {
            answer = document.getElementById('answer').value.trim();
        }
        if (!answer) { alert('Veuillez saisir la réponse'); return false; }
        return true;
    }

    function updateSummary() {
        const question = document.getElementById('question').value.trim() || '-';
        const category = document.getElementById('category').value;
        const order = document.getElementById('order').value || '0';
        const isActive = document.getElementById('is_active').checked;

        let answer = '';
        if (answerEditor) {
            answer = answerEditor.getData().trim();
        } else {
            answer = document.getElementById('answer').value.trim();
        }

        const categoryLabels = {
            'general': 'Général',
            'services': 'Services',
            'tarifs': 'Tarifs',
            'technique': 'Technique',
            'support': 'Support'
        };

        const categoryText = category ? categoryLabels[category] || category : '-';
        const answerPreview = answer ? (answer.replace(/<[^>]*>/g, '').substring(0, 100) + (answer.length > 100 ? '...' : '')) : '-';

        document.getElementById('summaryQuestion').textContent = question;
        document.getElementById('summaryCategory').textContent = categoryText;
        document.getElementById('summaryOrder').textContent = order;
        document.getElementById('summaryStatus').textContent = isActive ? 'Active' : 'Inactive';
        document.getElementById('summaryAnswer').innerHTML = answerPreview;
    }

    // Aperçu catégorie
    const categorySelect = document.getElementById('category');
    const categoryBadge = document.getElementById('categoryBadge');

    function updateCategoryPreview() {
        const category = categorySelect.value;
        const categoryLabels = {
            'general': { label: 'Général', icon: 'fa-globe', class: 'general' },
            'services': { label: 'Services', icon: 'fa-cogs', class: 'services' },
            'tarifs': { label: 'Tarifs', icon: 'fa-tag', class: 'tarifs' },
            'technique': { label: 'Technique', icon: 'fa-microchip', class: 'technique' },
            'support': { label: 'Support', icon: 'fa-headset', class: 'support' }
        };

        const selected = categoryLabels[category] || { label: 'Non définie', icon: 'fa-question', class: 'general' };

        categoryBadge.className = `badge-preview ${selected.class}`;
        categoryBadge.innerHTML = `<i class="fas ${selected.icon}"></i> ${selected.label}`;
    }

    categorySelect.addEventListener('change', updateCategoryPreview);
    updateCategoryPreview();

    nextBtn.addEventListener('click', () => {
        if (currentStep === 1 && validateStep1()) { currentStep = 2; updateSteps(); }
        else if (currentStep === 2 && validateStep2()) { currentStep = 3; updateSteps(); }
    });

    prevBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateSteps(); } });

    // Mise à jour du récapitulatif
    const inputs = ['question', 'category', 'order'];
    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) input.addEventListener('input', updateSummary);
    });
    document.getElementById('is_active')?.addEventListener('change', updateSummary);

    // SOUMISSION
    document.getElementById('faqForm').addEventListener('submit', function(e) {
        if (answerEditor) {
            document.getElementById('answer').value = answerEditor.getData();
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    });
</script>
@endpush
