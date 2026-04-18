{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Vérification email | Nova Tech</title>
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

/* ─── INFO BOX ─── */
.info-box {
    background: rgba(99,102,241,0.1);
    border: 1px solid rgba(99,102,241,0.2);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
}

.info-box i {
    color: #818cf8;
    font-size: 18px;
    margin-right: 12px;
}

.info-box p {
    font-size: 13px;
    line-height: 1.5;
    color: rgba(255,255,255,0.7);
}

/* ─── ALERT SUCCESS ─── */
.alert-success {
    margin-bottom: 20px;
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    animation: fadeIn 0.5s ease;
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ─── BUTTONS ─── */
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
    margin-bottom: 16px;
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

.btn-ghost {
    width: 100%;
    height: 46px;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px;
    background: rgba(255,255,255,0.03);
    color: rgba(255,255,255,0.7);
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-ghost:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.25);
    color: #fff;
}

.btn-ghost i {
    margin-right: 8px;
    font-size: 13px;
}

/* ─── FOOTER LINK ─── */
.card-footer {
    text-align: center;
    padding-top: 16px;
    margin-top: 16px;
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

/* ─── RESPONSIVE ─── */
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

    .orb-1, .orb-2, .orb-3 {
        opacity: 0.35;
    }
}

@media (max-width: 480px) {
    .right {
        padding: 24px 20px;
    }

    .card-header {
        margin-bottom: 28px;
    }

    .btn-submit, .btn-ghost {
        height: 46px;
        font-size: 14px;
    }

    .trust-row {
        gap: 16px;
        flex-wrap: wrap;
    }

    .trust-badge {
        font-size: 10px;
    }
}

@media (max-width: 380px) {
    .trust-row {
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
}

@media (min-height: 800px) and (max-width: 860px) {
    .right {
        padding: 60px 24px;
    }
}

/* Scrollbar */
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

<div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<div class="noise"></div>

<div class="layout">

    <!-- LEFT PANEL -->
    <div class="left">
        <div class="badge">
            <span class="badge-dot"></span>
            Vérification requise
        </div>

        <h1 class="headline">
            <span class="line-1">Confirmez votre</span>
            <span class="line-2">adresse email</span>
        </h1>

        <p class="subtext">
            Un email de vérification vous a été envoyé. Cliquez sur le lien pour activer votre compte et accéder à toutes nos fonctionnalités.
        </p>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-envelope"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Email de vérification</div>
                    <div class="ft-desc">Vérifiez vos spams si vous ne le trouvez pas</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Sécurité renforcée</div>
                    <div class="ft-desc">Protection avancée de votre compte</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-rocket"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Accès immédiat</div>
                    <div class="ft-desc">Débloquez toutes les fonctionnalités</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL (VERIFICATION FORM) -->
    <div class="right">
        <div class="card">

            <div class="card-header">
                <h2>Vérifiez votre email ✉️</h2>
                <p>Nous avons besoin de confirmer votre adresse</p>
            </div>

            <div class="info-box">
                <p>
                    <i class="fas fa-info-circle"></i>
                    {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert-success">
                    <div>
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-submit">
                    Renvoyer l'email <i class="fas fa-paper-plane btn-icon"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </button>
            </form>

            <div class="card-footer">
                <p>Email non reçu ? <a href="#" onclick="document.querySelector('form[action*=\'verification.send\'] button').click(); return false;">Renvoyer le lien</a></p>
            </div>

            <div class="trust-row">
                <div class="trust-badge"><i class="fas fa-lock"></i> SSL sécurisé</div>
                <div class="trust-badge"><i class="fas fa-shield-halved"></i> RGPD conforme</div>
                <div class="trust-badge"><i class="fas fa-circle-check"></i> 99.9% uptime</div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
