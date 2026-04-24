{{-- resources/views/admin/meetings/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nouvelle réunion - Assistant - NovaTech Admin')
@section('page-title', 'Nouvelle réunion')

@push('styles')
<style>
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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-help {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
    }

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

    .step-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

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

    /* Styles pour la recherche multiple */
    .search-multiple-container {
        position: relative;
        width: 100%;
    }

    .search-multiple-input-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        min-height: 42px;
        cursor: text;
    }

    .search-multiple-input-wrapper:focus-within {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .selected-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .user-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.5rem;
        background: var(--bg-selected);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        color: var(--brand-primary);
    }

    .user-tag i {
        cursor: pointer;
        font-size: 0.7rem;
        color: var(--text-tertiary);
        transition: color 0.2s;
    }

    .user-tag i:hover {
        color: var(--brand-error);
    }

    .search-multiple-input {
        flex: 1;
        min-width: 150px;
        border: none;
        background: transparent;
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        padding: 0.25rem 0;
    }

    .search-multiple-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 250px;
        overflow-y: auto;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-md);
        z-index: 100;
        display: none;
        margin-top: 4px;
        box-shadow: var(--shadow-lg);
    }

    .search-multiple-dropdown.show {
        display: block;
    }

    .search-option {
        padding: 0.625rem 0.875rem;
        cursor: pointer;
        transition: background var(--transition-fast);
        border-bottom: 1px solid var(--border-light);
    }

    .search-option:last-child {
        border-bottom: none;
    }

    .search-option:hover {
        background: var(--bg-hover);
    }

    .option-name {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .option-details {
        font-size: 0.7rem;
        color: var(--text-tertiary);
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

@php
    $totalSteps = 2;
    $steps = [
        1 => ['label' => 'Informations', 'icon' => 'fa-info-circle'],
        2 => ['label' => 'Participants', 'icon' => 'fa-users'],
    ];
@endphp

<nav class="breadcrumb">
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.meetings.index', $project) }}">Réunions</a>
    <i class="fas fa-chevron-right"></i>
    <span>Nouvelle réunion</span>
</nav>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="fas fa-magic"></i>
        </div>
        <h2>Planifier une nouvelle réunion</h2>
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

        <div class="stepper">
            @foreach($steps as $stepNum => $step)
            <div class="step" data-step="{{ $stepNum }}">
                <span class="step-number">{{ $stepNum }}</span>
                <span class="step-label"><i class="fas {{ $step['icon'] }}"></i> {{ $step['label'] }}</span>
            </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.projects.meetings.store', $project) }}" id="meetingForm">
            @csrf

            <!-- STEP 1: Informations -->
            <div class="step-content active" data-step="1">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Détails de la réunion
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-heading"></i> Titre de la réunion <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Ex: Revue technique hebdomadaire">
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Ordre du jour de la réunion...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-day"></i> Date et heure <span class="required">*</span>
                            </label>
                            <input type="text" id="meeting_date" name="meeting_date" class="form-control" placeholder="Sélectionnez une date et heure" value="{{ old('meeting_date') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i> Durée (minutes) <span class="required">*</span>
                            </label>
                            <select name="duration_minutes" class="form-control" required>
                                <option value="15" {{ old('duration_minutes') == 15 ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('duration_minutes') == 45 ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('duration_minutes') == 60 ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('duration_minutes') == 90 ? 'selected' : '' }}>1 heure 30</option>
                                <option value="120" {{ old('duration_minutes') == 120 ? 'selected' : '' }}>2 heures</option>
                                <option value="180" {{ old('duration_minutes') == 180 ? 'selected' : '' }}>3 heures</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Lieu
                            </label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Ex: Salle de conférence, Bureau 12">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-video"></i> Lien de réunion
                            </label>
                            <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/... ou https://zoom.us/...">
                            <span class="form-help">Pour les réunions à distance (Google Meet, Zoom, Teams)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Participants -->
            <div class="step-content" data-step="2">
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-users"></i> Participants
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-plus"></i> Ajouter des participants
                        </label>
                        <div class="search-multiple-container">
                            <div class="search-multiple-input-wrapper" id="searchWrapper">
                                <div class="selected-tags" id="selectedUsersTags"></div>
                                <input type="text" id="userSearchInput" class="search-multiple-input" placeholder="Tapez pour rechercher un participant..." autocomplete="off">
                            </div>
                            <div id="userDropdown" class="search-multiple-dropdown"></div>
                        </div>
                        <input type="hidden" name="attendees" id="attendees" value="{{ old('attendees') }}">
                        <span class="form-help">Sélectionnez les personnes à inviter à cette réunion</span>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note"></i> Notes (optionnelles)
                    </div>
                    <div class="form-group">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations supplémentaires ou ordre du jour détaillé...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="step-buttons">
                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Précédent
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn">
                    Suivant <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                    <i class="fas fa-calendar-plus"></i> Planifier la réunion
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
(function() {
    let currentStep = 1;
    const totalSteps = 2;

    const stepContents = document.querySelectorAll('.step-content');
    const stepElements = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // ===== CALENDRIER =====
    const dateInput = document.getElementById('meeting_date');
    if (dateInput) {
        flatpickr(dateInput, {
            locale: 'fr',
            enableTime: true,
            dateFormat: "Y-m-d H:i:s",
            time_24hr: true,
            minuteIncrement: 15,
            minDate: "today",
            placeholder: "Sélectionnez une date et heure",
            allowInput: false
        });
    }

    // ===== SÉLECTION MULTIPLE DES PARTICIPANTS =====
    const userSearchInput = document.getElementById('userSearchInput');
    const userDropdown = document.getElementById('userDropdown');
    const selectedUsersTags = document.getElementById('selectedUsersTags');
    const attendeesField = document.getElementById('attendees');

    let selectedUsers = [];
    let allUsers = [];

    @php
        $usersList = [];
        foreach($users as $user) {
            $usersList[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }
    @endphp
    allUsers = @json($usersList);

    function renderUserDropdown(filter = '') {
        if (!userDropdown) return;

        const filteredUsers = allUsers.filter(user =>
            !selectedUsers.some(u => u.id === user.id) &&
            (filter === '' ||
             user.name.toLowerCase().includes(filter.toLowerCase()) ||
             user.email.toLowerCase().includes(filter.toLowerCase()))
        );

        if (filteredUsers.length === 0) {
            userDropdown.innerHTML = '<div class="no-results">Aucun utilisateur trouvé</div>';
            userDropdown.classList.add('show');
        } else {
            userDropdown.innerHTML = filteredUsers.map(user => `
                <div class="search-option" data-id="${user.id}" data-name="${escapeHtml(user.name)}" data-email="${escapeHtml(user.email)}">
                    <div class="option-name">${escapeHtml(user.name)}</div>
                    <div class="option-details">${escapeHtml(user.email)}</div>
                </div>
            `).join('');
            userDropdown.classList.add('show');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function addUser(userData) {
        if (!selectedUsers.some(u => u.id === userData.id)) {
            selectedUsers.push(userData);
            updateSelectedUsersDisplay();
            updateAttendeesField();
        }
        userSearchInput.value = '';
        renderUserDropdown('');
    }

    function removeUser(userId) {
        selectedUsers = selectedUsers.filter(u => u.id !== userId);
        updateSelectedUsersDisplay();
        updateAttendeesField();
        if (userSearchInput.value) {
            renderUserDropdown(userSearchInput.value);
        }
    }

    function updateSelectedUsersDisplay() {
        if (!selectedUsersTags) return;

        if (selectedUsers.length === 0) {
            selectedUsersTags.innerHTML = '';
            return;
        }

        selectedUsersTags.innerHTML = selectedUsers.map(user => `
            <span class="user-tag">
                ${escapeHtml(user.name)}
                <i class="fas fa-times" onclick="removeUser(${user.id})"></i>
            </span>
        `).join('');
    }

    function updateAttendeesField() {
        if (attendeesField) {
            attendeesField.value = JSON.stringify(selectedUsers.map(u => u.id));
        }
    }

    window.removeUser = removeUser;

    if (userSearchInput && userDropdown) {
        userSearchInput.addEventListener('focus', function() {
            renderUserDropdown('');
        });

        userSearchInput.addEventListener('input', function() {
            renderUserDropdown(this.value);
        });

        userDropdown.addEventListener('click', function(e) {
            const option = e.target.closest('.search-option');
            if (option) {
                const userData = {
                    id: parseInt(option.dataset.id),
                    name: option.dataset.name,
                    email: option.dataset.email
                };
                addUser(userData);
            }
        });

        document.addEventListener('click', function(e) {
            if (userSearchInput && !userSearchInput.contains(e.target) && userDropdown && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }

    // Restaurer les anciennes valeurs
    const oldAttendees = "{{ old('attendees') }}";
    if (oldAttendees && allUsers.length) {
        try {
            const ids = JSON.parse(oldAttendees);
            ids.forEach(id => {
                const user = allUsers.find(u => u.id === id);
                if (user && !selectedUsers.some(u => u.id === id)) {
                    selectedUsers.push(user);
                }
            });
            updateSelectedUsersDisplay();
            updateAttendeesField();
        } catch(e) {}
    }

    function updateStepper() {
        stepContents.forEach(content => {
            const step = parseInt(content.dataset.step);
            content.classList.toggle('active', step === currentStep);
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
                const title = document.getElementById('title')?.value.trim();
                const meetingDate = document.getElementById('meeting_date')?.value;

                if (!title) {
                    alert('Veuillez saisir un titre pour la réunion');
                    return false;
                }
                if (!meetingDate) {
                    alert('Veuillez sélectionner une date et heure');
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

    updateStepper();

    const form = document.getElementById('meetingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateAttendeesField();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Planification en cours...';
        });
    }
})();
</script>
@endpush
