{{-- resources/views/admin/profile/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Mon profil - NovaTech Admin')
@section('page-title', 'Mon profil')

@push('styles')
<style>
    /* Layout principal */
    .profile-layout {
        display: flex;
        gap: 1.5rem;
        min-height: calc(100vh - 200px);
    }

    /* Sidebar de navigation */
    .profile-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: sticky;
        top: 90px;
        height: fit-content;
    }

    .profile-sidebar-header {
        padding: 1.25rem 1.25rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        text-align: center;
    }

    .profile-avatar-large {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
        cursor: pointer;
    }

    .profile-avatar-large:hover .avatar-overlay {
        opacity: 1;
    }

    .avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 9999px;
        object-fit: cover;
        border: 3px solid var(--brand-primary);
    }

    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 9999px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        color: white;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 9999px;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .avatar-overlay i {
        color: white;
        font-size: 1.5rem;
    }

    .profile-user-info h3 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .profile-user-info p {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin: 0.25rem 0 0 0;
    }

    .profile-nav {
        padding: 0.75rem;
    }

    .profile-nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 0.25rem;
    }

    .profile-nav-item:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .profile-nav-item.active {
        background: var(--bg-selected);
        color: var(--brand-primary);
    }

    .profile-nav-item i {
        width: 1.25rem;
        font-size: 1rem;
    }

    .profile-nav-item span {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Contenu principal */
    .profile-content {
        flex: 1;
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .profile-section {
        display: none;
    }

    .profile-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .section-header h2 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header h2 i {
        color: var(--brand-primary);
    }

    .section-header p {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin: 0.25rem 0 0 0;
    }

    .section-body {
        padding: 1.5rem;
    }

    /* Formulaires */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label i {
        margin-right: 0.5rem;
        width: 1rem;
        color: var(--brand-primary);
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .form-help {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Boutons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid var(--border-medium);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    /* Alertes */
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideInTop 0.3s ease;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    /* Password strength */
    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: var(--bg-tertiary);
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
    }

    .password-strength-bar.weak { background: #ef4444; width: 25%; }
    .password-strength-bar.medium { background: #f59e0b; width: 50%; }
    .password-strength-bar.strong { background: #10b981; width: 75%; }
    .password-strength-bar.very-strong { background: #10b981; width: 100%; }

    .password-strength-text {
        font-size: 0.7rem;
        margin-top: 0.25rem;
        color: var(--text-tertiary);
    }

    @keyframes slideInTop {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-elevated);
        border-radius: 0.75rem;
        border: 1px solid var(--border-medium);
        width: 90%;
        max-width: 450px;
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }

    /* Toast */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        border-left: 4px solid #10b981;
    }

    .toast-notification.error {
        border-left: 4px solid #ef4444;
    }

    .toast-notification i {
        font-size: 1.25rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .profile-layout {
            flex-direction: column;
        }
        .profile-sidebar {
            width: 100%;
            position: static;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .section-body {
            padding: 1rem;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions button,
        .form-actions a {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-layout">
    <!-- Sidebar navigation -->
    <aside class="profile-sidebar">
        <div class="profile-sidebar-header">
            <div class="profile-avatar-large" onclick="document.getElementById('avatarInput').click()">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="avatar-large">
                @else
                    <div class="avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="avatar-overlay">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <form id="avatarForm" method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" name="avatar" id="avatarInput" accept="image/*">
            </form>
            <div class="profile-user-info">
                <h3>{{ Auth::user()->name }}</h3>
                <p>{{ Auth::user()->email }}</p>
                <p><i class="fas fa-calendar-alt"></i> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <nav class="profile-nav">
            <div class="profile-nav-item active" data-section="info">
                <i class="fas fa-user"></i>
                <span>Informations personnelles</span>
            </div>
            <div class="profile-nav-item" data-section="password">
                <i class="fas fa-lock"></i>
                <span>Sécurité & Mot de passe</span>
            </div>
        </nav>
    </aside>

    <!-- Contenu principal -->
    <main class="profile-content">
        <!-- Section Informations -->
        <div id="section-info" class="profile-section active">
            <div class="section-header">
                <h2><i class="fas fa-user"></i> Informations personnelles</h2>
                <p>Modifiez vos informations de profil</p>
            </div>
            <div class="section-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('status') === 'verification-link-sent')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>Un nouveau lien de vérification a été envoyé à votre adresse email.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-user"></i> Nom complet</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-envelope"></i> Adresse email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                            @enderror

                            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                                <div class="alert alert-warning" style="margin-top: 0.75rem;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>
                                        Votre adresse email n'est pas vérifiée.
                                        <form method="POST" action="{{ route('admin.profile.resend-verification') }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #f59e0b; text-decoration: underline; cursor: pointer; padding: 0;">
                                                Cliquez ici pour renvoyer le lien de vérification.
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Mot de passe -->
        <div id="section-password" class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-lock"></i> Sécurité & Mot de passe</h2>
                <p>Modifiez votre mot de passe</p>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-key"></i> Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password')
                                <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-lock"></i> Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" class="form-input" required>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="password-strength-text" id="passwordStrengthText"></div>
                            @error('password')
                                <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-check-circle"></i> Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Modal avatar -->
<div id="avatarModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3>Changer la photo de profil</h3>
            <button class="modal-close" onclick="closeAvatarModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>Voulez-vous mettre à jour votre photo de profil ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeAvatarModal()">Annuler</button>
            <button class="btn btn-primary" onclick="submitAvatar()">Confirmer</button>
        </div>
    </div>
</div>

<div id="toast" class="toast-notification">
    <i id="toastIcon" class="fas"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script>
    // Navigation entre sections
    const navItems = document.querySelectorAll('.profile-nav-item');
    const sections = document.querySelectorAll('.profile-section');

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            const sectionId = item.dataset.section;

            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');

            sections.forEach(section => section.classList.remove('active'));
            document.getElementById(`section-${sectionId}`).classList.add('active');
        });
    });

    // Force du mot de passe
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;

        if (strength <= 2) return { level: 'weak', text: 'Faible' };
        if (strength === 3) return { level: 'medium', text: 'Moyen' };
        if (strength === 4) return { level: 'strong', text: 'Fort' };
        return { level: 'very-strong', text: 'Très fort' };
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const result = checkPasswordStrength(password);
            strengthBar.className = 'password-strength-bar ' + result.level;
            strengthText.textContent = 'Force du mot de passe : ' + result.text;
            strengthText.style.color = result.level === 'weak' ? '#ef4444' : (result.level === 'medium' ? '#f59e0b' : '#10b981');
        });
    }

    // Upload avatar
    let selectedFile = null;

    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            selectedFile = this.files[0];
            document.getElementById('avatarModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });

    function submitAvatar() {
        if (!selectedFile) return;

        const formData = new FormData();
        formData.append('avatar', selectedFile);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("admin.profile.avatar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors du téléchargement');
                closeAvatarModal();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
            closeAvatarModal();
        });
    }

    function closeAvatarModal() {
        document.getElementById('avatarModal').classList.remove('active');
        document.body.style.overflow = '';
        selectedFile = null;
        document.getElementById('avatarInput').value = '';
    }

    // Toast notification
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');

    function showToast(message, type) {
        type = type || 'success';
        toastIcon.className = 'fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle');
        toastMessage.textContent = message;
        toast.className = 'toast-notification ' + type + ' show';
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    window.closeAvatarModal = closeAvatarModal;
    window.submitAvatar = submitAvatar;
</script>
@endpush
