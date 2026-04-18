{{-- resources/views/admin/users/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvel utilisateur - NovaTech Admin')
@section('page-title', 'Nouvel utilisateur')

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

    @media (max-width: 992px) {
        .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
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
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        cursor: pointer;
    }

    .form-check-input:checked {
        background: var(--brand-primary);
        border-color: var(--brand-primary);
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

    .roles-container, .permissions-container {
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        background: var(--bg-primary);
        padding: 1rem;
    }

    .roles-grid, .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
        margin-bottom: 0.5rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .role-item, .permission-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: var(--radius-md);
        background: var(--bg-tertiary);
        transition: all 0.2s;
    }

    .role-item:hover, .permission-item:hover {
        background: var(--bg-hover);
    }

    .role-item input, .permission-item input {
        width: 1rem;
        height: 1rem;
        cursor: pointer;
    }

    .role-item label, .permission-item label {
        flex: 1;
        cursor: pointer;
        font-size: 0.875rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-item label i, .permission-item label i {
        width: 1.25rem;
        color: var(--brand-primary);
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

    .mb-0 {
        margin-bottom: 0;
    }

    .mt-2 {
        margin-top: 0.5rem;
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

    /* Scrollbar personnalisée */
    .roles-grid::-webkit-scrollbar,
    .permissions-grid::-webkit-scrollbar {
        width: 6px;
    }

    .roles-grid::-webkit-scrollbar-track,
    .permissions-grid::-webkit-scrollbar-track {
        background: var(--bg-tertiary);
        border-radius: 3px;
    }

    .roles-grid::-webkit-scrollbar-thumb,
    .permissions-grid::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 3px;
    }

    .roles-grid::-webkit-scrollbar-thumb:hover,
    .permissions-grid::-webkit-scrollbar-thumb:hover {
        background: var(--brand-primary);
    }

    .info-badge {
        background: rgba(59, 130, 246, 0.1);
        border-left: 3px solid var(--brand-primary);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
    }

    .info-badge i {
        color: var(--brand-primary);
        margin-right: 0.5rem;
    }

    .info-badge small {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="admin-card" data-aos="fade-up">
    <div class="card-header-custom">
        <h2><i class="fas fa-user-plus"></i> Créer un nouvel utilisateur</h2>
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

        <div class="info-badge">
            <i class="fas fa-info-circle"></i>
            <small>Un mot de passe sera généré automatiquement et envoyé par email à l'utilisateur. Ce dernier pourra le modifier lors de sa première connexion.</small>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
            @csrf

            <div class="steps-wrapper">
                <div class="steps-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <div class="step-title">Informations générales</div>
                            <div class="step-desc">Identité et email</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <div class="step-title">Rôles & Permissions</div>
                            <div class="step-desc">Attribution des accès</div>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-info">
                            <div class="step-title">Validation</div>
                            <div class="step-desc">Confirmation et création</div>
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
                                <i class="fas fa-user"></i> Nom complet <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-input-camille @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Ex: Jean Dupont"
                                   maxlength="255">
                            <div class="form-help">Nom et prénom de l'utilisateur</div>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-envelope"></i> Adresse email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-input-camille @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="Ex: jean.dupont@exemple.com">
                            <div class="form-help">L'email servira d'identifiant pour la connexion. Un email avec les identifiants sera envoyé à cette adresse.</div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 2 - Rôles & Permissions -->
            <div id="step2" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-tags"></i> Rôles
                            </label>
                            <div class="roles-container">
                                <div class="roles-grid" id="rolesGrid">
                                    @foreach($roles as $role)
                                    <div class="role-item">
                                        <input type="checkbox"
                                               name="roles[]"
                                               value="{{ $role->name }}"
                                               id="role_{{ $role->id }}"
                                               class="role-checkbox"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                        <label for="role_{{ $role->id }}">
                                            <i class="fas fa-{{ $role->name === 'admin' ? 'crown' : ($role->name === 'manager' ? 'user-tie' : 'user-shield') }}"></i>
                                            {{ ucfirst($role->name) }}
                                            <small class="text-muted" style="font-size: 0.7rem;">({{ $role->permissions->count() }} permissions)</small>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-help">Les rôles déterminent les permissions globales de l'utilisateur</div>
                            @error('roles')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(isset($permissions) && $permissions->count() > 0)
                    <div class="col-12">
                        <div class="form-group-camille">
                            <label class="form-label-camille">
                                <i class="fas fa-key"></i> Permissions directes
                            </label>
                            <div class="permissions-container">
                                <div class="permissions-grid" id="permissionsGrid">
                                    @foreach($permissions->groupBy(function($item) {
                                        return explode('.', $item->name)[0];
                                    }) as $group => $groupPermissions)
                                        <div style="margin-bottom: 1rem;">
                                            <div class="form-label-camille" style="font-size: 0.7rem; margin-bottom: 0.5rem;">
                                                <i class="fas fa-folder"></i> {{ ucfirst($group) }}
                                            </div>
                                            @foreach($groupPermissions as $permission)
                                            <div class="permission-item">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       id="perm_{{ $permission->id }}"
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                <label for="perm_{{ $permission->id }}">
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ ucfirst(str_replace($group . '.', '', $permission->name)) }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-help">Permissions supplémentaires qui s'ajoutent à celles des rôles</div>
                            @error('permissions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ÉTAPE 3 - Validation -->
            <div id="step3" class="step-content">
                <div class="row">
                    <div class="col-12">
                        <div class="alert-camille alert-camille-success">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Prêt à créer l'utilisateur ?</strong>
                                <div class="small mt-1">Un email avec les identifiants de connexion sera envoyé automatiquement.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="alert-camille alert-camille-info">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Récapitulatif</strong>
                                <div class="small mt-2">
                                    <div><strong>Nom :</strong> <span id="summaryName">-</span></div>
                                    <div><strong>Email :</strong> <span id="summaryEmail">-</span></div>
                                    <div><strong>Rôles :</strong> <span id="summaryRoles">Aucun</span></div>
                                    <div><strong>Permissions :</strong> <span id="summaryPermissions">Aucune</span></div>
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
                    <a href="{{ route('admin.users.index') }}" class="btn-camille-outline me-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" id="nextBtn" class="btn-camille-primary">
                        Suivant <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn-camille-primary" style="display: none;">
                        <i class="fas fa-save"></i> Créer l'utilisateur
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
            updateSummary();
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }

        stepItems.forEach(function(step, index) {
            step.classList.remove('active', 'completed');
            if (index + 1 === currentStep) step.classList.add('active');
            else if (index + 1 < currentStep) step.classList.add('completed');
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep1() {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!name) {
            alert('Veuillez saisir le nom complet');
            document.getElementById('name').focus();
            return false;
        }
        if (!email) {
            alert('Veuillez saisir l\'adresse email');
            document.getElementById('email').focus();
            return false;
        }
        if (!email.match(/^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/)) {
            alert('Veuillez saisir une adresse email valide');
            document.getElementById('email').focus();
            return false;
        }
        return true;
    }

    function validateStep2() {
        return true;
    }

    function updateSummary() {
        const name = document.getElementById('name').value.trim() || '-';
        const email = document.getElementById('email').value.trim() || '-';

        document.getElementById('summaryName').textContent = name;
        document.getElementById('summaryEmail').textContent = email;

        // Récupérer les rôles sélectionnés
        const selectedRoles = [];
        document.querySelectorAll('.role-checkbox:checked').forEach(function(cb) {
            selectedRoles.push(cb.value);
        });
        document.getElementById('summaryRoles').textContent = selectedRoles.length > 0 ? selectedRoles.join(', ') : 'Aucun';

        // Récupérer les permissions sélectionnées
        const selectedPermissions = [];
        document.querySelectorAll('input[name="permissions[]"]:checked').forEach(function(cb) {
            const label = cb.nextElementSibling;
            selectedPermissions.push(label.textContent.trim());
        });
        document.getElementById('summaryPermissions').textContent = selectedPermissions.length > 0 ? selectedPermissions.slice(0, 3).join(', ') + (selectedPermissions.length > 3 ? '...' : '') : 'Aucune';
    }

    nextBtn.addEventListener('click', function() {
        if (currentStep === 1 && validateStep1()) {
            currentStep = 2;
            updateSteps();
        }
        else if (currentStep === 2 && validateStep2()) {
            currentStep = 3;
            updateSteps();
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

    // ==================== APERÇU EN DIRECT ====================
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const previewName = document.getElementById('previewName');
    const previewEmail = document.getElementById('previewEmail');

    if (nameInput) {
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Nom complet';
        });
    }

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            previewEmail.textContent = this.value || 'email@exemple.com';
        });
    }

    // ==================== VALIDATION FORMULAIRE ====================
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const submitBtnElement = document.getElementById('submitBtn');
        submitBtnElement.disabled = true;
        submitBtnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    });
</script>
@endpush
