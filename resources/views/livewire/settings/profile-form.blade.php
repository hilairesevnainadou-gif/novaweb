<div>
    @if (session('status') === 'verification-link-sent')
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Un nouveau lien de vérification a été envoyé à votre adresse email.</span>
        </div>
    @endif

    <form wire:submit="updateProfileInformation">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-user"></i> Nom complet
            </label>
            <input type="text" wire:model="name" class="form-input" required autofocus>
            @error('name') <div class="form-help" style="color: #ef4444;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-envelope"></i> Adresse email
            </label>
            <input type="email" wire:model="email" class="form-input" required>
            @error('email') <div class="form-help" style="color: #ef4444;">{{ $message }}</div> @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="alert-warning" style="margin-top: 0.75rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>
                        Votre adresse email n'est pas vérifiée.
                        <a href="#" wire:click.prevent="resendVerificationNotification" style="text-decoration: underline; cursor: pointer;">
                            Cliquez ici pour renvoyer le lien de vérification.
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="button" onclick="window.location.href='{{ route('admin.dashboard') }}'" class="btn-danger">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <i class="fas fa-save"></i>
                <span wire:loading.remove>Enregistrer</span>
                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Enregistrement...</span>
            </button>
        </div>
    </form>

    @if (session('success'))
        <div id="toastMessage" style="position: fixed; bottom: 2rem; right: 2rem; background: var(--bg-elevated); border-left: 4px solid #10b981; border-radius: 0.5rem; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; z-index: 10000;">
            <i class="fas fa-check-circle" style="color: #10b981;"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toastMessage');
                if (toast) toast.remove();
            }, 3000);
        </script>
    @endif
</div>
