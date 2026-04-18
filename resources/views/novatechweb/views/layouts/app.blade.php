<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="{{ $company->description ?? $company->meta_description ?? 'Nova Tech - Agence Web Professionnelle' }}">
    <meta name="author" content="{{ $company->name ?? 'Nova Tech' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    @if(isset($company) && $company)
        @if($company->meta_keywords)
            <meta name="keywords" content="{{ $company->meta_keywords }}">
        @endif
        @if($company->meta_description)
            <meta name="description" content="{{ $company->meta_description }}">
        @endif
    @endif

    <!-- Favicon -->
    @if(isset($company) && $company && $company->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $company->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $company->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <title>@yield('title', isset($company) && $company->name ? $company->name : 'Nova Tech - Agence Web')</title>

    <!-- CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">

    <!-- Styles spécifiques à la page -->
    @stack('styles')

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

        .header-logo {
            display: flex;
            align-items: center;
            z-index: 1002;
        }

        .header-logo img {
            height: 45px;
            width: auto;
            transition: var(--transition);
            object-fit: contain;
        }

        .header.scrolled .header-logo img {
            height: 40px;
        }

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
            width: auto;
            object-fit: contain;
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

        main {
            min-height: calc(100vh - 400px);
        }

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

        /* ========== FOOTER MODERNE ========== */
        .footer {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: white;
            position: relative;
        }

        .footer-main {
            padding: 80px 0 60px;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1.5fr;
            gap: 60px;
        }

        .footer-brand-col {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .footer-logo {
            display: inline-block;
            background: white;
            padding: 15px 25px;
            border-radius: 16px;
            width: fit-content;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .footer-logo img {
            height: 45px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            display: block;
        }

        .footer-desc {
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.75);
            max-width: 350px;
        }

        .footer-social {
            display: flex;
            gap: 12px;
        }

        .social-link {
            width: 44px;
            height: 44px;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .footer-links-col,
        .footer-contact-col,
        .footer-newsletter-col {
            display: flex;
            flex-direction: column;
        }

        .footer-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 24px;
            color: white;
            position: relative;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: default;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        .footer-toggle-icon {
            display: none;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
            transition: transform 0.3s ease;
        }

        .footer-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .footer-list li a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            position: relative;
            left: 0;
        }

        .footer-list li a i {
            font-size: 0.75rem;
            color: var(--primary);
            transition: transform 0.3s ease;
        }

        .footer-list li a:hover {
            color: white;
            left: 5px;
        }

        .footer-list li a:hover i {
            transform: translateX(3px);
        }

        .contact-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: rgba(255, 255, 255, 0.75);
        }

        .contact-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-light);
            font-size: 0.9rem;
            margin-top: 2px;
        }

        .contact-icon i {
            display: block;
        }

        .contact-text,
        .contact-list li a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            line-height: 1.6;
            transition: color 0.3s ease;
            word-break: break-word;
            flex: 1;
            padding-top: 8px;
        }

        .contact-list li a:hover {
            color: white;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .newsletter-form {
            margin-bottom: 16px;
        }

        .newsletter-group {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .newsletter-group:focus-within {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .newsletter-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 16px;
            color: white;
            font-size: 0.95rem;
            font-family: inherit;
            min-width: 0;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-input:focus {
            outline: none;
        }

        .newsletter-btn {
            background: var(--primary);
            border: none;
            color: white;
            padding: 0 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .newsletter-btn:hover {
            background: var(--primary-light);
        }

        .newsletter-btn i {
            font-size: 1rem;
        }

        .newsletter-note {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 24px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .copyright {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
        }

        .footer-legal {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .footer-legal a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: white;
        }

        .divider {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Newsletter Modal Styles */
        .newsletter-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .newsletter-modal.active {
            visibility: visible;
            opacity: 1;
        }

        .newsletter-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .newsletter-modal-container {
            position: relative;
            background: white;
            border-radius: 24px;
            max-width: 450px;
            width: 90%;
            padding: 40px;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .newsletter-modal.active .newsletter-modal-container {
            transform: scale(1);
        }

        .newsletter-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #f1f5f9;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            color: #64748b;
            transition: all 0.2s;
        }

        .newsletter-modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .newsletter-modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .newsletter-modal-icon i {
            font-size: 40px;
            color: white;
        }

        .newsletter-modal-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .newsletter-modal-icon.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .newsletter-modal-container h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .newsletter-modal-container p {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .newsletter-modal-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .newsletter-modal-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        @media (max-width: 1200px) {
            .footer-grid {
                grid-template-columns: 1.5fr 1fr 1.5fr;
                gap: 40px;
            }

            .footer-newsletter-col {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 992px) {
            .footer-main {
                padding: 60px 0 40px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }

            .footer-brand-col {
                grid-column: 1 / -1;
            }

            .footer-newsletter-col {
                grid-column: span 1;
            }

            .footer-desc {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .footer-container {
                padding: 0 16px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 0;
                text-align: left;
            }

            .footer-brand-col {
                text-align: center;
                margin-bottom: 30px;
            }

            .footer-brand {
                align-items: center;
            }

            .footer-logo {
                margin: 0 auto;
            }

            .footer-social {
                justify-content: center;
            }

            .footer-links-col .footer-title,
            .footer-contact-col .footer-title {
                cursor: pointer;
                margin-bottom: 0;
                padding: 20px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .footer-links-col .footer-title::after,
            .footer-contact-col .footer-title::after {
                display: none;
            }

            .footer-toggle-icon {
                display: block;
            }

            .footer-title.active .footer-toggle-icon {
                transform: rotate(180deg);
            }

            .footer-list {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease, padding 0.4s ease;
                gap: 0;
            }

            .footer-list.active {
                max-height: 500px;
                padding-top: 20px;
                padding-bottom: 20px;
                gap: 14px;
            }

            .footer-list li {
                opacity: 0;
                transform: translateY(-10px);
                transition: all 0.3s ease;
            }

            .footer-list.active li {
                opacity: 1;
                transform: translateY(0);
            }

            .footer-list.active li:nth-child(1) { transition-delay: 0.05s; }
            .footer-list.active li:nth-child(2) { transition-delay: 0.1s; }
            .footer-list.active li:nth-child(3) { transition-delay: 0.15s; }
            .footer-list.active li:nth-child(4) { transition-delay: 0.2s; }
            .footer-list.active li:nth-child(5) { transition-delay: 0.25s; }
            .footer-list.active li:nth-child(6) { transition-delay: 0.3s; }

            .contact-list li {
                justify-content: flex-start;
                flex-direction: row;
                gap: 12px;
            }

            .contact-icon {
                margin-top: 0;
            }

            .contact-text,
            .contact-list li a {
                padding-top: 8px;
                text-align: left;
            }

            .footer-newsletter-col {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .footer-newsletter-col .footer-title {
                margin-bottom: 20px;
            }

            .footer-newsletter-col .footer-title::after {
                display: block;
                left: 0;
                transform: none;
            }

            .newsletter-group {
                max-width: 100%;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-legal {
                flex-wrap: wrap;
                justify-content: center;
            }

            .newsletter-modal-container {
                padding: 30px 25px;
            }

            .newsletter-modal-icon {
                width: 60px;
                height: 60px;
            }

            .newsletter-modal-icon i {
                font-size: 30px;
            }

            .newsletter-modal-container h3 {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .footer-main {
                padding: 40px 0 30px;
            }

            .footer-logo {
                padding: 12px 20px;
            }

            .footer-logo img {
                height: 38px;
            }

            .footer-title {
                font-size: 1.05rem;
            }

            .social-link {
                width: 42px;
                height: 42px;
            }

            .contact-icon {
                width: 36px;
                height: 36px;
                font-size: 0.85rem;
            }

            .copyright,
            .footer-legal a {
                font-size: 0.8rem;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-grid > div {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .footer-grid > div:nth-child(1) { animation-delay: 0.1s; }
        .footer-grid > div:nth-child(2) { animation-delay: 0.2s; }
        .footer-grid > div:nth-child(3) { animation-delay: 0.3s; }
        .footer-grid > div:nth-child(4) { animation-delay: 0.4s; }
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
                @if(isset($company) && $company && $company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name ?? 'Logo' }}">
                @else
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Nova Tech">
                @endif
            </a>

            <nav class="header-nav">
                <a href="{{ route('home') }}" class="nav-link" id="homeLink">Accueil</a>
                <a href="#about" class="nav-link" id="aboutLink">À propos</a>
                <a href="#services" class="nav-link" id="servicesLink">Services</a>
                <a href="{{ route('portfolio.index') }}" class="nav-link" id="portfolioLink">Portfolio</a>
                <a href="{{ route('blog.index') }}" class="nav-link" id="blogLink">Blog</a>
                <a href="#contact" class="nav-link nav-cta" id="contactLink">Contact</a>
            </nav>

            <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Menu Mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            @if(isset($company) && $company && $company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name ?? 'Nova Tech' }}" class="mobile-menu-logo">
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

    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- WHATSAPP -->
    @if(isset($company) && $company && $company->whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <i class="fa fa-whatsapp"></i>
    </a>
    @endif

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-container">
                <div class="footer-grid">
                    <div class="footer-brand-col">
                        <div class="footer-brand">
                            <a href="{{ route('home') }}" class="footer-logo">
                                @if(isset($company) && $company && $company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name ?? 'Nova Tech' }}" loading="lazy">
                                @else
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Nova Tech" loading="lazy">
                                @endif
                            </a>
                            <p class="footer-desc">
                                {{ $company->description ?? $company->mission ?? 'Agence web professionnelle dédiée à la création de solutions digitales innovantes et sur mesure pour faire rayonner votre présence en ligne.' }}
                            </p>
                        </div>
                        <div class="footer-social">
                            @if(isset($company) && $company && $company->facebook)
                                <a href="{{ $company->facebook }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
                                    <i class="fa fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(isset($company) && $company && $company->instagram)
                                <a href="{{ $company->instagram }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            @endif
                            @if(isset($company) && $company && $company->linkedin)
                                <a href="{{ $company->linkedin }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            @endif
                            @if(isset($company) && $company && $company->twitter)
                                <a href="{{ $company->twitter }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            @endif
                            @if(isset($company) && $company && $company->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="WhatsApp">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="footer-links-col">
                        <h4 class="footer-title" data-toggle="footer-links">
                            Liens rapides
                            <i class="fa fa-chevron-down footer-toggle-icon"></i>
                        </h4>
                        <ul class="footer-list" id="footer-links">
                            <li><a href="{{ route('home') }}"><i class="fa fa-chevron-right"></i> Accueil</a></li>
                            <li><a href="#about"><i class="fa fa-chevron-right"></i> À propos</a></li>
                            <li><a href="#services"><i class="fa fa-chevron-right"></i> Services</a></li>
                            <li><a href="{{ route('portfolio.index') }}"><i class="fa fa-chevron-right"></i> Portfolio</a></li>
                            <li><a href="{{ route('blog.index') }}"><i class="fa fa-chevron-right"></i> Blog</a></li>
                            <li><a href="#contact"><i class="fa fa-chevron-right"></i> Contact</a></li>
                        </ul>
                    </div>

                    <div class="footer-contact-col">
                        <h4 class="footer-title" data-toggle="footer-contact">
                            Contact
                            <i class="fa fa-chevron-down footer-toggle-icon"></i>
                        </h4>
                        <ul class="footer-list contact-list" id="footer-contact">
                            @if(isset($company) && $company && $company->address)
                                <li>
                                    <span class="contact-icon"><i class="fa fa-map-marker"></i></span>
                                    <span class="contact-text">{{ $company->address }}</span>
                                </li>
                            @endif
                            @if(isset($company) && $company && $company->email)
                                <li>
                                    <span class="contact-icon"><i class="fa fa-envelope"></i></span>
                                    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                </li>
                            @endif
                            @if(isset($company) && $company && $company->phone)
                                <li>
                                    <span class="contact-icon"><i class="fa fa-phone"></i></span>
                                    <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                </li>
                            @endif
                            @if(isset($company) && $company && $company->whatsapp)
                                <li>
                                    <span class="contact-icon"><i class="fa fa-whatsapp"></i></span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="footer-newsletter-col">
                        <h4 class="footer-title">Newsletter</h4>
                        <p class="footer-text">Recevez nos actualités et offres spéciales directement dans votre boîte mail.</p>
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="newsletter-group">
                                <input type="email" name="email" class="newsletter-input" placeholder="Votre email" required aria-label="Votre email">
                                <button type="submit" class="newsletter-btn" aria-label="S'inscrire">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        <p class="newsletter-note">En vous inscrivant, vous acceptez notre politique de confidentialité.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-container">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; {{ date('Y') }} {{ (isset($company) && $company && $company->name) ? $company->name : 'Nova Tech' }}. Tous droits réservés.
                    </p>
                    <div class="footer-legal">
                        <a href="{{ route('mentions.legales') }}">Mentions légales</a>
                        <span class="divider">|</span>
                        <a href="{{ route('politique.confidentialite') }}">Politique de confidentialité</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- NEWSLETTER MODAL -->
    <div class="newsletter-modal" id="newsletterModal">
        <div class="newsletter-modal-overlay"></div>
        <div class="newsletter-modal-container">
            <button class="newsletter-modal-close" id="newsletterModalClose">
                <i class="fa fa-times"></i>
            </button>
            <div class="newsletter-modal-icon" id="modalIcon">
                <i class="fa fa-envelope-open-text"></i>
            </div>
            <h3 id="modalTitle">Inscription à la newsletter</h3>
            <p id="modalMessage">Merci pour votre inscription ! Vous recevrez bientôt nos actualités.</p>
            <button class="newsletter-modal-btn" id="newsletterModalBtn">Fermer</button>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Scripts spécifiques à la page -->
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preloader
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('hidden');
                setTimeout(() => preloader.remove(), 500);
            }, 800);

            // Header scroll
            const header = document.getElementById('header');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Mobile Menu
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.getElementById('menuOverlay');

            function toggleMenu() {
                const isOpen = mobileMenu.classList.contains('active');
                if (isOpen) {
                    menuToggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    menuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    menuToggle.classList.add('active');
                    mobileMenu.classList.add('active');
                    menuOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleMenu);
            if (menuOverlay) menuOverlay.addEventListener('click', toggleMenu);

            if (mobileMenu) {
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', toggleMenu);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
                    toggleMenu();
                }
            });

            // Smooth scroll pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#' && href !== '#about' && href !== '#services' && href !== '#contact') {
                        const targetId = href.split('#')[1];
                        const target = document.getElementById(targetId);
                        if (target) {
                            e.preventDefault();
                            const headerOffset = 80;
                            const elementPosition = target.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                            window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                        }
                    }
                });
            });

            // Gestion de l'activation du menu au scroll
            function updateActiveMenuOnScroll() {
                const sections = [
                    { id: 'about', linkId: 'aboutLink' },
                    { id: 'services', linkId: 'servicesLink' },
                    { id: 'contact', linkId: 'contactLink' }
                ];

                let currentSection = '';
                const scrollPosition = window.scrollY + 120;

                for (const section of sections) {
                    const element = document.getElementById(section.id);
                    if (element) {
                        const offsetTop = element.offsetTop;
                        const offsetBottom = offsetTop + element.offsetHeight;

                        if (scrollPosition >= offsetTop && scrollPosition < offsetBottom) {
                            currentSection = section.id;
                            break;
                        }
                    }
                }

                const navLinks = document.querySelectorAll('.header-nav .nav-link');
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });

                if (currentSection) {
                    const activeLink = document.getElementById(currentSection + 'Link');
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                }

                if (window.scrollY < 100) {
                    const homeLink = document.getElementById('homeLink');
                    if (homeLink) homeLink.classList.add('active');
                }

                const currentPath = window.location.pathname;
                const portfolioLink = document.getElementById('portfolioLink');
                const blogLink = document.getElementById('blogLink');

                if (portfolioLink) {
                    if (currentPath.includes('/portfolio')) {
                        portfolioLink.classList.add('active');
                    } else {
                        portfolioLink.classList.remove('active');
                    }
                }

                if (blogLink) {
                    if (currentPath.includes('/blog')) {
                        blogLink.classList.add('active');
                    } else {
                        blogLink.classList.remove('active');
                    }
                }
            }

            window.addEventListener('scroll', updateActiveMenuOnScroll);
            window.addEventListener('load', updateActiveMenuOnScroll);

            if (mobileMenu) {
                const mobileLinks = mobileMenu.querySelectorAll('.mobile-menu-nav a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        const href = this.getAttribute('href');
                        if (href && href.includes('#')) {
                            const targetId = href.split('#')[1];
                            const target = document.getElementById(targetId);
                            if (target) {
                                e.preventDefault();
                                const headerOffset = 80;
                                const elementPosition = target.getBoundingClientRect().top;
                                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                                toggleMenu();
                            }
                        }
                    });
                });
            }

            // Footer accordéons mobile
            const footerToggles = document.querySelectorAll('.footer-title[data-toggle]');

            footerToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    if (window.innerWidth > 768) return;

                    const targetId = this.getAttribute('data-toggle');
                    const targetList = document.getElementById(targetId);

                    this.classList.toggle('active');
                    if (targetList) {
                        targetList.classList.toggle('active');
                    }
                });
            });

            // ========== NEWSLETTER MODAL & SUBSCRIPTION ==========
            const newsletterModal = document.getElementById('newsletterModal');
            const newsletterModalClose = document.getElementById('newsletterModalClose');
            const newsletterModalBtn = document.getElementById('newsletterModalBtn');
            const modalIcon = document.getElementById('modalIcon');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');

            function showModal(type, title, message) {
                if (!modalIcon || !modalTitle || !modalMessage) return;

                modalIcon.className = 'newsletter-modal-icon';

                if (type === 'success') {
                    modalIcon.classList.add('success');
                    modalIcon.innerHTML = '<i class="fa fa-check-circle"></i>';
                } else if (type === 'error') {
                    modalIcon.classList.add('error');
                    modalIcon.innerHTML = '<i class="fa fa-exclamation-circle"></i>';
                } else {
                    modalIcon.innerHTML = '<i class="fa fa-envelope-open-text"></i>';
                }

                modalTitle.textContent = title;
                modalMessage.textContent = message;
                if (newsletterModal) newsletterModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                if (newsletterModal) newsletterModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (newsletterModalClose) newsletterModalClose.addEventListener('click', closeModal);
            if (newsletterModalBtn) newsletterModalBtn.addEventListener('click', closeModal);
            document.querySelector('.newsletter-modal-overlay')?.addEventListener('click', closeModal);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && newsletterModal && newsletterModal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Newsletter form submission
            document.querySelectorAll('.newsletter-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const emailInput = this.querySelector('input[type="email"]');
                    const email = emailInput ? emailInput.value.trim() : '';
                    const submitBtn = this.querySelector('button[type="submit"]');

                    if (!email || !email.includes('@') || !email.includes('.')) {
                        showModal('error', 'Email invalide', 'Veuillez entrer une adresse email valide.');
                        return;
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                    }

                    try {
                        const response = await fetch('{{ route("newsletter.subscribe") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ email: email })
                        });

                        const data = await response.json();

                        if (data.success) {
                            showModal('success', 'Inscription réussie !', data.message);
                            if (emailInput) emailInput.value = '';
                        } else {
                            showModal('error', 'Oups !', data.message);
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        showModal('error', 'Erreur', 'Une erreur est survenue. Veuillez réessayer plus tard.');
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fa fa-paper-plane"></i>';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
