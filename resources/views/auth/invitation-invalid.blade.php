{{-- resources/views/auth/invitation-invalid.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Lien invalide | {{ $company->name ?? 'Nova Tech' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            align-items: center;
            justify-content: center;
            background: #020617;
            color: #fff;
            overflow-x: hidden;
        }

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
        }

        .orb-2 {
            width: min(500px, 70vw);
            height: min(500px, 70vw);
            background: radial-gradient(circle, rgba(0,164,239,0.5), transparent 70%);
            bottom: -150px; right: -100px;
            animation-delay: -5s;
        }

        .orb-3 {
            width: min(400px, 60vw);
            height: min(400px, 60vw);
            background: radial-gradient(circle, rgba(139,92,246,0.4), transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
        }

        @keyframes drift {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -40px) scale(1.05); }
        }

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background-image: linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .card {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 32px;
            padding: clamp(32px, 6vw, 48px);
            max-width: 480px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .icon-wrapper i {
            font-size: 36px;
            color: #f87171;
        }

        h1 {
            font-size: clamp(24px, 5vw, 28px);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .message {
            font-size: 15px;
            line-height: 1.5;
            color: rgba(255,255,255,0.55);
            margin-bottom: 28px;
        }

        .reason-box {
            background: rgba(99,102,241,0.08);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 32px;
            font-size: 13px;
            color: rgba(255,255,255,0.5);
        }

        .reason-box i {
            color: #818cf8;
            margin-right: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            border-radius: 14px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 40px rgba(99,102,241,0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn i {
            font-size: 13px;
            transition: transform 0.2s;
        }

        .btn:hover i {
            transform: translateX(-3px);
        }

        .help-text {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 12px;
            color: rgba(255,255,255,0.3);
        }

        .help-text a {
            color: #818cf8;
            text-decoration: none;
        }

        .help-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 20px; }
            .icon-wrapper { width: 64px; height: 64px; }
            .icon-wrapper i { font-size: 28px; }
            .btn { padding: 12px 24px; font-size: 13px; }
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

<div class="card">
    <div class="icon-wrapper">
        <i class="fas fa-link-slash"></i>
    </div>

    <h1>Lien invalide</h1>

    <div class="message">
        Ce lien d'invitation n'est plus valide ou a expiré.
    </div>

    @if(isset($reason) && $reason)
        <div class="reason-box">
            <i class="fas fa-info-circle"></i> {{ $reason }}
        </div>
    @endif

    <a href="{{ route('login') }}" class="btn">
        <i class="fas fa-arrow-left"></i> Retour à la connexion
    </a>

    <div class="help-text">
        <i class="fas fa-envelope"></i> Une question ?
        <a href="mailto:{{ $company->support_email ?? 'support@novatech.com' }}">
            Contactez le support
        </a>
    </div>
</div>

</body>
</html>
