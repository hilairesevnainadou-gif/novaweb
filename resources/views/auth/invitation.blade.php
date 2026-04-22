{{-- resources/views/auth/invitation.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>
        @if($type === 'reset')
            Réinitialisation | {{ $company->name ?? 'Nova Tech' }}
        @else
            Activation | {{ $company->name ?? 'Nova Tech' }}
        @endif
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    @if(isset($company) && $company && $company->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $company->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $company->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

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

        .user-info {
            background: rgba(99,102,241,0.08);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: center;
        }

        .user-email {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            word-break: break-all;
        }

        .user-email i {
            color: #818cf8;
            margin-right: 8px;
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

        /* ─── PASSWORD STRENGTH ─── */
        .strength-meter {
            margin-top: 8px;
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .strength-bar {
            width: 0%;
            height: 100%;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .strength-text {
            font-size: 11px;
            margin-top: 6px;
            color: rgba(255,255,255,0.4);
            text-align: right;
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

        .card-footer a {
            font-size: 13px;
            color: rgba(99,102,241,0.8);
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
            .layout { flex-direction: column; }
            .left { display: none; }
            .right {
                max-width: 100%;
                width: 100%;
                min-height: 100vh;
                border-left: none;
                background: rgba(255,255,255,0.02);
                backdrop-filter: blur(20px);
                padding: 40px 24px;
            }
            .card { max-width: 420px; margin: 0 auto; }
            .orb-1, .orb-2, .orb-3 { opacity: 0.35; }
        }

        @media (max-width: 480px) {
            .right { padding: 24px 20px; }
            .card-header { margin-bottom: 28px; }
            .input {
                height: 46px;
                font-size: 16px;
                padding: 0 44px 0 40px;
            }
            .btn-submit { height: 46px; font-size: 14px; }
        }

        @media (max-width: 380px) {
            .trust-row {
                flex-direction: column;
                gap: 12px;
            }
            .trust-badge { white-space: normal; }
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
    <div class="left">
        <div class="badge">
            <span class="badge-dot"></span>
            Sécurité & Confidentialité
        </div>

        <h1 class="headline">
            <span class="line-1">Créez votre</span>
            <span class="line-2">accès sécurisé</span>
        </h1>

        <p class="subtext">
            {{ $company->name ?? 'Nova Tech' }} protège vos données avec des standards de sécurité de niveau bancaire.
        </p>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Chiffrement SSL 256 bits</div>
                    <div class="ft-desc">Protection des données en transit</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Hachage sécurisé</div>
                    <div class="ft-desc">Mots de passe protégés par bcrypt</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-clock"></i></div>
                <div class="feature-text">
                    <div class="ft-title">Lien à usage unique</div>
                    <div class="ft-desc">Expiration automatique après utilisation</div>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">256-bit</div>
                <div class="stat-label">Chiffrement SSL</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Surveillance</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">RGPD</div>
                <div class="stat-label">Conforme</div>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="card">
            <div class="card-header">
                <h2>
                    @if($type === 'reset')
                        Nouveau mot de passe
                    @else
                        Activez votre compte
                    @endif
                </h2>
                <p>
                    @if($type === 'reset')
                        Choisissez un mot de passe sécurisé
                    @else
                        Définissez votre mot de passe pour commencer
                    @endif
                </p>
            </div>

            <div class="user-info">
                <div class="user-email">
                    <i class="fas fa-user"></i>
                    <strong>{{ $invitation->user->name ?? $invitation->user->email }}</strong>
                </div>
                <div class="user-email" style="margin-top: 8px; font-size: 12px;">
                    <i class="fas fa-envelope"></i>
                    {{ $invitation->user->email }}
                </div>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.accept', $token) }}" id="passwordForm">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="input-group" style="margin-bottom: 8px;">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="input"
                        placeholder="Nouveau mot de passe"
                        autocomplete="new-password"
                        required
                        minlength="8"
                    >
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Afficher le mot de passe">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="strength-meter" id="strengthMeter">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>

                <div class="input-group" style="margin-top: 16px; margin-bottom: 24px;">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="input"
                        placeholder="Confirmer le mot de passe"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-pw" id="toggleConfirmPw" aria-label="Afficher la confirmation">
                        <i class="fas fa-eye" id="confirmEyeIcon"></i>
                    </button>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    @if($type === 'reset')
                        Réinitialiser mon mot de passe
                    @else
                        Activer mon compte
                    @endif
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>
            </form>

            <div class="card-footer">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i> Retour à la connexion
                </a>
            </div>

            <div class="trust-row">
                <div class="trust-badge"><i class="fas fa-lock"></i> SSL sécurisé</div>
                <div class="trust-badge"><i class="fas fa-shield-halved"></i> RGPD conforme</div>
                <div class="trust-badge"><i class="fas fa-hourglass-half"></i> Lien temporaire</div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Toggle password visibility
    const togglePw = document.getElementById('togglePw');
    const toggleConfirm = document.getElementById('toggleConfirmPw');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const eyeIcon = document.getElementById('eyeIcon');
    const confirmEyeIcon = document.getElementById('confirmEyeIcon');

    if (togglePw && passwordInput && eyeIcon) {
        togglePw.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }

    if (toggleConfirm && confirmInput && confirmEyeIcon) {
        toggleConfirm.addEventListener('click', function() {
            const isHidden = confirmInput.type === 'password';
            confirmInput.type = isHidden ? 'text' : 'password';
            confirmEyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }

    // Password strength meter
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    function checkStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        return Math.min(strength, 4);
    }

    function updateStrengthMeter() {
        const password = passwordInput.value;
        const strength = checkStrength(password);

        let width = 0;
        let color = '#ef4444';
        let text = '';

        if (password.length === 0) {
            width = 0;
            text = '';
        } else {
            switch(strength) {
                case 0:
                case 1:
                    width = 25;
                    color = '#ef4444';
                    text = 'Très faible';
                    break;
                case 2:
                    width = 50;
                    color = '#f59e0b';
                    text = 'Faible';
                    break;
                case 3:
                    width = 75;
                    color = '#eab308';
                    text = 'Moyen';
                    break;
                case 4:
                    width = 100;
                    color = '#22c55e';
                    text = 'Fort';
                    break;
            }
        }

        strengthBar.style.width = width + '%';
        strengthBar.style.background = color;
        strengthText.textContent = text;
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', updateStrengthMeter);
    }

    // Form validation on submit
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (password !== confirm) {
                e.preventDefault();
                showError('Les mots de passe ne correspondent pas');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                showError('Le mot de passe doit contenir au moins 8 caractères');
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
        });
    }

    function showError(message) {
        const existingAlert = document.querySelector('.alert-error:not(.session-alert)');
        if (existingAlert) existingAlert.remove();

        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert-error';
        alertDiv.innerHTML = '<div>' + message + '</div>';

        const card = document.querySelector('.card');
        const formElement = document.querySelector('form');
        if (card && formElement) {
            card.insertBefore(alertDiv, formElement);
        }

        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }
})();
</script>
</body>
</html>
