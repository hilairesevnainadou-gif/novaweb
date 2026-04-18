<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="{{ $company->description ?? 'Nova Tech - Agence Web Professionnelle' }}">
    <meta name="author" content="Nova Tech">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <title>@yield('title', $company->name ?? 'Nova Tech - Agence Web')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">

    <style>
        /* ========== VARIABLES MODERNES ========== */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --accent: #06b6d4;
            --bg-white: #ffffff;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-gray: #475569;
            --text-light: #64748b;
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ========== HEADER MODERNE ========== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            z-index: 1000;
            transition: var(--transition);
        }

        .header.scrolled {
            height: 70px;
            box-shadow: var(--shadow-md);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .header-logo {
            display: flex;
            align-items: center;
            z-index: 1002;
        }

        .header-logo img {
            height: 45px;
            width: auto;
            transition: var(--transition);
        }

        .header.scrolled .header-logo img {
            height: 40px;
        }

        /* Navigation Desktop */
        .header-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            color: var(--text-dark);
            font-size: 0.95rem;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 50px;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.08);
        }

        .nav-link.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.12);
        }

        .nav-cta {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white !important;
            padding: 12px 28px;
            margin-left: 10px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* ========== MENU MOBILE - HAMBURGER ========== */
        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 48px;
            height: 48px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 1002;
            position: relative;
            padding: 0;
        }

        .menu-toggle span {
            display: block;
            width: 26px;
            height: 2.5px;
            background: var(--text-dark);
            margin: 4px 0;
            transition: var(--transition);
            border-radius: 2px;
            transform-origin: center;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
        }

        /* ========== MENU MOBILE - DISPOSITION CORRIGÉE ========== */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
            z-index: 1001;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding-top: 100px;
            overflow-y: auto;
        }

        .mobile-menu.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu-logo {
            height: 40px;
            filter: brightness(0) invert(1);
        }

        .mobile-menu-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 400px;
            padding: 20px 24px;
        }

        .mobile-menu-nav a {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 16px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            width: 100%;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu.active .mobile-menu-nav a {
            opacity: 1;
            transform: translateY(0);
        }

        .mobile-menu-nav a:nth-child(1) { transition-delay: 0.1s; }
        .mobile-menu-nav a:nth-child(2) { transition-delay: 0.15s; }
        .mobile-menu-nav a:nth-child(3) { transition-delay: 0.2s; }
        .mobile-menu-nav a:nth-child(4) { transition-delay: 0.25s; }
        .mobile-menu-nav a:nth-child(5) { transition-delay: 0.3s; }
        .mobile-menu-nav a:nth-child(6) { transition-delay: 0.35s; }

        .mobile-menu-nav a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.02);
        }

        .mobile-menu-nav .nav-cta-mobile {
            background: white;
            color: #1e1b4b !important;
            margin-top: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: none;
        }

        .mobile-menu-nav .nav-cta-mobile:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.02);
        }

        /* Overlay */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ========== RESPONSIVE HEADER ========== */
        @media (max-width: 992px) {
            .header-nav {
                display: none;
            }

            .menu-toggle {
                display: flex;
            }

            .header {
                height: 70px;
            }

            .header-logo img {
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .header-container {
                padding: 0 16px;
            }

            .mobile-menu {
                padding-top: 90px;
            }

            .mobile-menu-header {
                height: 70px;
            }

            .mobile-menu-nav {
                padding: 16px 20px;
                gap: 6px;
            }

            .mobile-menu-nav a {
                font-size: 1.1rem;
                padding: 14px 24px;
                border-radius: 12px;
            }
        }

        /* ========== HERO SECTION ========== */
        .hero {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 50%, #faf5ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 24px;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            color: var(--text-dark);
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.125rem);
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 40px;
            max-width: 540px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border-light);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 20px 40px rgba(99, 102, 241, 0.15));
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* ========== RESPONSIVE HERO ========== */
        @media (max-width: 992px) {
            .hero {
                padding: 100px 0 60px;
                text-align: center;
            }

            .hero-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero-subtitle {
                margin: 0 auto 40px;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-visual {
                order: -1;
            }

            .hero-image {
                max-width: 400px;
            }
        }

        @media (max-width: 576px) {
            .hero {
                padding: 90px 0 40px;
            }

            .hero-container {
                padding: 0 16px;
            }

            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .hero-image {
                max-width: 100%;
            }
        }

        /* ========== SECTIONS ========== */
        section {
            padding: 100px 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
        }

        .section-tag {
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .section-title span {
            color: var(--primary);
        }

        .section-text {
            font-size: 1.125rem;
            color: var(--text-gray);
            line-height: 1.8;
        }

        @media (max-width: 768px) {
            section {
                padding: 60px 0;
            }

            .container {
                padding: 0 16px;
            }

            .section-header {
                margin-bottom: 40px;
            }
        }

        /* ========== PRELOADER ========== */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--border-light);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ========== WHATSAPP FLOAT ========== */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3);
            transition: var(--transition);
            z-index: 999;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.4);
        }

        @media (max-width: 576px) {
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 24px;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- PRELOADER -->
    <div class="preloader" id="preloader">
        <div class="preloader-spinner"></div>
    </div>

    <!-- HEADER -->
    <header class="header" id="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="header-logo">
                @if ($company && $company->logo)
                    <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}">
                @else
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Nova Tech">
                @endif
            </a>

            <!-- Navigation Desktop -->
            <nav class="header-nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                <a href="#about" class="nav-link">À propos</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="{{ route('portfolio.index') }}" class="nav-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}">Portfolio</a>
                <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
                <a href="#contact" class="nav-link nav-cta">Contact</a>
            </nav>

            <!-- Bouton Menu Mobile -->
            <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Menu Mobile Full Screen - CORRIGÉ -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            @if ($company && $company->logo)
                <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" class="mobile-menu-logo">
            @else
                <img src="{{ asset('assets/images/logo.png') }}" alt="Nova Tech" class="mobile-menu-logo">
            @endif
        </div>
        <nav class="mobile-menu-nav">
            <a href="{{ route('home') }}">Accueil</a>
            <a href="#about">À propos</a>
            <a href="#services">Services</a>
            <a href="{{ route('portfolio.index') }}">Portfolio</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="#contact" class="nav-cta-mobile">Contact</a>
        </nav>
    </div>

    <!-- Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- WHATSAPP -->
    @if ($company && $company->whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <i class="fa fa-whatsapp"></i>
    </a>
    @endif

    <!-- SCRIPTS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preloader
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('hidden');
                setTimeout(() => preloader.remove(), 500);
            }, 800);

            // Header scroll effect
            const header = document.getElementById('header');
            let lastScroll = 0;

            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;

                if (currentScroll > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                lastScroll = currentScroll;
            });

            // Mobile Menu
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.getElementById('menuOverlay');
            const mobileLinks = mobileMenu.querySelectorAll('a');

            function openMenu() {
                menuToggle.classList.add('active');
                mobileMenu.classList.add('active');
                menuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                menuToggle.setAttribute('aria-expanded', 'true');
            }

            function closeMenu() {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                menuToggle.setAttribute('aria-expanded', 'false');
            }

            menuToggle.addEventListener('click', () => {
                if (mobileMenu.classList.contains('active')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            menuOverlay.addEventListener('click', closeMenu);

            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    closeMenu();
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    closeMenu();
                }
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            const headerOffset = 80;
                            const elementPosition = target.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
