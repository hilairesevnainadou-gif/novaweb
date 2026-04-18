{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Connexion | Nova Tech</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    background: #020617;
    color: #fff;
    overflow-x: hidden;
    overflow-y: auto;
}

/* ─── ANIMATED BACKGROUND ORBS ─── */
.bg-orbs {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.55;
    animation: drift 12s ease-in-out infinite alternate;
}

.orb-1 {
    width: min(580px, 80vw);
    height: min(580px, 80vw);
    background: radial-gradient(circle, rgba(99,102,241,0.6), transparent 70%);
    top: -180px; left: -120px;
    animation-delay: 0s;
}

.orb-2 {
    width: min(500px, 70vw);
    height: min(500px, 70vw);
    background: radial-gradient(circle, rgba(0,164,239,0.5), transparent 70%);
    bottom: -150px; left: 30%;
    animation-delay: -4s;
}

.orb-3 {
    width: min(400px, 60vw);
    height: min(400px, 60vw);
    background: radial-gradient(circle, rgba(139,92,246,0.4), transparent 70%);
    top: 40%; right: -100px;
    animation-delay: -8s;
}

@keyframes drift {
    0%   { transform: translate(0, 0) scale(1); }
    33%  { transform: translate(30px, -40px) scale(1.04); }
    66%  { transform: translate(-20px, 25px) scale(0.97); }
    100% { transform: translate(15px, -15px) scale(1.02); }
}

/* ─── GRID NOISE OVERLAY ─── */
.noise {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 1;
    background-image:
        linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
    background-size: 48px 48px;
}

/* ─── LAYOUT ─── */
.layout {
    position: relative;
    z-index: 2;
    display: flex;
    width: 100%;
    min-height: 100vh;
}

/* ─── LEFT PANEL ─── */
.left {
    flex: 1;
    padding: clamp(40px, 5vw, 72px) clamp(30px, 5vw, 80px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0;
    overflow-y: auto;
}

.logo-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: clamp(32px, 6vw, 56px);
}

.logo-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    box-shadow: 0 0 24px rgba(99,102,241,0.5);
    flex-shrink: 0;
}

.logo-name {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: #fff;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 999px;
    border: 1px solid rgba(99,102,241,0.4);
    background: rgba(99,102,241,0.12);
    font-size: 12px;
    font-weight: 500;
    color: #a5b4fc;
    margin-bottom: 24px;
    width: fit-content;
}

.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #6366f1;
    box-shadow: 0 0 8px rgba(99,102,241,0.8);
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(0.85); }
}

.headline {
    font-size: clamp(28px, 5vw, 56px);
    font-weight: 900;
    line-height: 1.08;
    letter-spacing: -1.5px;
    margin-bottom: clamp(16px, 3vw, 24px);
}

.headline .line-1 { color: #fff; display: block; }
.headline .line-2 {
    display: block;
    background: linear-gradient(90deg, #a5b4fc 0%, #38bdf8 60%, #818cf8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.subtext {
    font-size: clamp(14px, 2.5vw, 17px);
    line-height: 1.5;
    color: rgba(255,255,255,0.55);
    max-width: 480px;
    margin-bottom: clamp(28px, 5vw, 44px);
}

/* ─── FEATURES ─── */
.features {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: clamp(12px, 2vw, 16px) clamp(16px, 2vw, 20px);
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.06);
    background: rgba(255,255,255,0.03);
    transition: border-color 0.3s, background 0.3s;
    max-width: 460px;
}

.feature-item:hover {
    border-color: rgba(99,102,241,0.3);
    background: rgba(99,102,241,0.06);
}

.feature-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(99,102,241,0.15);
    border: 1px solid rgba(99,102,241,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
    color: #818cf8;
}

.feature-text .ft-title {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
    margin-bottom: 2px;
}

.feature-text .ft-desc {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
}

/* ─── STATS ROW ─── */
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: clamp(20px, 4vw, 32px);
    margin-top: clamp(32px, 5vw, 44px);
    padding-top: clamp(24px, 4vw, 36px);
    border-top: 1px solid rgba(255,255,255,0.07);
    max-width: 460px;
}

.stat-item .stat-num {
    font-size: clamp(22px, 3.5vw, 26px);
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #fff;
}

.stat-item .stat-label {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    margin-top: 2px;
}

/* ─── RIGHT PANEL ─── */
.right {
    width: 100%;
    max-width: 520px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: clamp(20px, 4vw, 40px) clamp(20px, 5vw, 36px);
    border-left: 1px solid rgba(255,255,255,0.05);
    background: rgba(255,255,255,0.015);
    backdrop-filter: blur(30px);
    overflow-y: auto;
}

/* ─── CARD ─── */
.card {
    width: 100%;
    max-width: 400px;
    animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}

.card-header {
    text-align: center;
    margin-bottom: clamp(28px, 5vw, 36px);
}

.card-header h2 {
    font-size: clamp(24px, 4vw, 26px);
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #fff;
    margin-bottom: 8px;
}

.card-header p {
    font-size: 14px;
    color: rgba(255,255,255,0.45);
}

/* ─── ERROR ALERT ─── */
.alert-error {
    margin-bottom: 20px;
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(248, 113, 113, 0.1);
    border: 1px solid rgba(248, 113, 113, 0.3);
    animation: shakeX 0.5s ease;
}

.alert-error div {
    font-size: 13px;
    color: #fca5a5;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-error div::before {
    content: '\f071';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 12px;
    color: #f87171;
}

.alert-success {
    margin-bottom: 20px;
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.alert-success div {
    font-size: 13px;
    color: #86efac;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-success div::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 12px;
    color: #22c55e;
}

@keyframes shakeX {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
}

/* ─── LABEL ─── */
.field-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: rgba(255,255,255,0.6);
    margin-bottom: 8px;
}

/* ─── INPUT GROUP ─── */
.input-group {
    position: relative;
    margin-bottom: 16px;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.3);
    font-size: 14px;
    pointer-events: none;
    transition: color 0.2s;
    z-index: 1;
}

.input {
    width: 100%;
    height: 50px;
    padding: 0 48px 0 44px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
}

.input::placeholder { color: rgba(255,255,255,0.25); }

.input:hover {
    border-color: rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.07);
}

.input:focus {
    border-color: #6366f1;
    background: rgba(99,102,241,0.08);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
}

.input:focus + .input-icon,
.input-group:focus-within .input-icon {
    color: #818cf8;
}

/* ─── TOGGLE PASSWORD ─── */
.toggle-pw {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: rgba(255,255,255,0.3);
    font-size: 14px;
    padding: 4px;
    display: flex;
    align-items: center;
    transition: color 0.2s;
    z-index: 1;
}

.toggle-pw:hover { color: rgba(255,255,255,0.7); }

/* ─── FORGOT ─── */
.field-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 8px;
}

.field-meta a {
    font-size: 12px;
    color: rgba(99,102,241,0.8);
    text-decoration: none;
    transition: color 0.2s;
}

.field-meta a:hover { color: #a5b4fc; }

/* ─── REMEMBER ME ─── */
.remember-row {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 24px;
}

.remember-row input[type="checkbox"] {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    accent-color: #6366f1;
    cursor: pointer;
    flex-shrink: 0;
}

.remember-row label {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    cursor: pointer;
    user-select: none;
}

/* ─── SUBMIT BUTTON ─── */
.btn-submit {
    position: relative;
    width: 100%;
    height: 50px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.1px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
    margin-bottom: 20px;
}

.btn-submit::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
    opacity: 0;
    transition: opacity 0.2s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 40px rgba(99,102,241,0.45);
}

.btn-submit:hover::before { opacity: 1; }

.btn-submit:active {
    transform: translateY(0);
    box-shadow: 0 6px 20px rgba(99,102,241,0.3);
}

.btn-submit .btn-icon {
    margin-left: 8px;
    font-size: 13px;
    transition: transform 0.2s;
}

.btn-submit:hover .btn-icon { transform: translateX(3px); }

/* ─── FOOTER LINK ─── */
.card-footer {
    text-align: center;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.card-footer p {
    font-size: 13px;
    color: rgba(255,255,255,0.35);
}

.card-footer a {
    color: #818cf8;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.card-footer a:hover { color: #a5b4fc; }

/* ─── TRUST BADGES ─── */
.trust-row {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

.trust-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: rgba(255,255,255,0.25);
    white-space: nowrap;
}

.trust-badge i { font-size: 12px; color: rgba(99,102,241,0.5); }

/* ─── RESPONSIVE BREAKPOINTS ─── */

/* Tablette paysage */
@media (max-width: 1024px) {
    .left {
        padding: 50px 40px;
    }

    .features {
        gap: 10px;
    }
}

/* Tablette portrait */
@media (max-width: 860px) {
    .layout {
        flex-direction: column;
    }

    .left {
        display: none;
    }

    .right {
        max-width: 100%;
        width: 100%;
        min-height: 100vh;
        border-left: none;
        background: rgba(255,255,255,0.02);
        backdrop-filter: blur(20px);
        padding: 40px 24px;
    }

    .card {
        max-width: 420px;
        margin: 0 auto;
    }

    /* Ajustement des orbes pour mobile */
    .orb-1, .orb-2, .orb-3 {
        opacity: 0.35;
    }
}

/* Mobile */
@media (max-width: 480px) {
    .right {
        padding: 24px 20px;
    }

    .card-header {
        margin-bottom: 28px;
    }

    .input {
        height: 46px;
        font-size: 16px; /* Empêche zoom sur iOS */
        padding: 0 44px 0 40px;
    }

    .input-icon {
        font-size: 13px;
        left: 13px;
    }

    .toggle-pw {
        right: 12px;
    }

    .btn-submit {
        height: 46px;
        font-size: 14px;
    }

    .trust-row {
        gap: 16px;
    }

    .trust-badge {
        font-size: 10px;
    }

    .field-meta {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Très petits mobiles */
@media (max-width: 380px) {
    .right {
        padding: 20px 16px;
    }

    .trust-row {
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .trust-badge {
        white-space: normal;
    }
}

/* Support du dark mode */
@media (prefers-color-scheme: dark) {
    body {
        background: #020617;
    }
}

/* Support des écrans hauts */
@media (min-height: 800px) and (max-width: 860px) {
    .right {
        padding: 60px 24px;
    }
}

/* Améliorations pour le touch */
@media (hover: none) and (pointer: coarse) {
    .btn-submit:hover {
        transform: none;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .feature-item:hover {
        border-color: rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.03);
    }

    .feature-item:active {
        border-color: rgba(99,102,241,0.3);
        background: rgba(99,102,241,0.06);
    }
}

/* Animation d'entrée différée pour les éléments */
.feature-item:nth-child(1) { animation: slideUp 0.5s ease 0.1s both; }
.feature-item:nth-child(2) { animation: slideUp 0.5s ease 0.2s both; }
.feature-item:nth-child(3) { animation: slideUp 0.5s ease 0.3s both; }
.stats-row { animation: slideUp 0.5s ease 0.4s both; }

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: rgba(99,102,241,0.4);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(99,102,241,0.6);
}
</style>
</head>

<body>

<!-- Background effects -->
<div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<div class="noise"></div>

<div class="layout">

    <!-- ─── LEFT PANEL ─── -->
    <div class="left">
        <div class="badge">
            <span class="badge-dot"></span>
            Plateforme nouvelle génération
        </div>

        <h1 class="headline">
            <span class="line-1">Accélérez votre</span>
            <span class="line-2">transformation digitale</span>
        </h1>

        <p class="subtext">
            Nova Tech conçoit des solutions digitales haute performance pour les entreprises ambitieuses qui visent l'excellence.
        </p>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-code"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Applications web sur mesure</div>
                    <div class="ft-desc">Interfaces modernes, rapides et évolutives</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-cloud"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Solutions SaaS évolutives</div>
                    <div class="ft-desc">Architecture cloud-native et microservices</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Sécurité &amp; performance</div>
                    <div class="ft-desc">Conformité RGPD, chiffrement de bout en bout</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── RIGHT PANEL (LOGIN FORM) ─── -->
    <div class="right">
        <div class="card">

            <div class="card-header">
                <h2>Bon retour 👋</h2>
                <p>Connectez-vous pour accéder à votre espace</p>
            </div>

            @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            @if(session('status'))
            <div class="alert-success">
                <div>{{ session('status') }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label class="field-label" for="email">Adresse email</label>
                <div class="input-group" style="margin-bottom:20px;">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="input"
                        placeholder="vous@exemple.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div class="field-meta">
                    <label class="field-label" for="password" style="margin-bottom:0;">Mot de passe</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    @endif
                </div>
                <div class="input-group" style="margin-bottom:8px;">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="input"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Afficher le mot de passe">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn-submit">
                    Se connecter <i class="fas fa-arrow-right btn-icon"></i>
                </button>

            </form>

            <div class="card-footer">
                <p>Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
            </div>

            <div class="trust-row">
                <div class="trust-badge"><i class="fas fa-lock"></i> SSL sécurisé</div>
                <div class="trust-badge"><i class="fas fa-shield-halved"></i> RGPD conforme</div>
                <div class="trust-badge"><i class="fas fa-circle-check"></i> 99.9% uptime</div>
            </div>

        </div>
    </div>

</div>

<script>
(function() {
    const btn = document.getElementById('togglePw');
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (!btn || !input || !icon) return;

    btn.addEventListener('click', function() {
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
        btn.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
})();
</script>

</body>
</html>
