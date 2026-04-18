@extends('admin.layouts.app')

@section('title', 'Mon profil - NovaTech Admin')
@section('page-title', 'Mon profil')

@push('styles')
<style>
    .profile-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .profile-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }
    .profile-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 9999px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        color: white;
    }
    .profile-info h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }
    .profile-info p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0.25rem 0 0 0;
    }
    .profile-body {
        padding: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
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
    .form-input {
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
    .form-input:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .form-input:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .form-help {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.375rem;
    }
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
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
    }
    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }
    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: #f59e0b;
    }
    .alert-warning a {
        color: #f59e0b;
        text-decoration: underline;
        cursor: pointer;
    }
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: #10b981;
    }
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
    .toast-notification i {
        font-size: 1.25rem;
    }
    @media (max-width: 640px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-large">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="profile-info">
                <h2>{{ Auth::user()->name }}</h2>
                <p><i class="fas fa-envelope"></i> {{ Auth::user()->email }}</p>
                <p><i class="fas fa-calendar-alt"></i> Membre depuis {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="profile-body">
            @if (session('status') === 'verification-link-sent')
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>Un nouveau lien de vérification a été envoyé à votre adresse email.</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Nom complet
                    </label>
                    <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i> Adresse email
                    </label>
                    <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="form-help" style="color: #ef4444;">{{ $message }}</div>
                    @enderror

                    @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                        <div class="alert-warning" style="margin-top: 0.75rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>
                                Votre adresse email n'est pas vérifiée.
                                <a href="#" onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();" style="text-decoration: underline; cursor: pointer;">
                                    Cliquez ici pour renvoyer le lien de vérification.
                                </a>
                            </span>
                        </div>
                        <form id="resend-verification-form" method="POST" action="{{ route('verification.send') }}" style="display: none;">
                            @csrf
                        </form>
                    @endif
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
</div>
@endsection
