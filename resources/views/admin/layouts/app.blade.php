<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f0f12">
    <title>@yield('title', config('app.name', 'NovaTech Admin'))</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Favicon dynamique -->
    @php
        $companyInfo = \App\Models\CompanyInfo::first();
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super-admin');
        $isAdmin = $user->hasRole('admin');
        $isProjectManager = $user->hasRole('project-manager');
        $isEditor = $user->hasRole('editor');
        $isSupport = $user->hasRole('support');
        $isTechnician = $user->hasRole('technician');
        $isViewer = $user->hasRole('viewer');
    @endphp
    @if($companyInfo && $companyInfo->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $companyInfo->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $companyInfo->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    @stack('styles')

    <style>
        /* ============================================
           DESIGN SYSTEM - Enterprise Grade
        ============================================ */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* ===== DARK THEME (Default) ===== */
            --bg-primary: #0a0a0c;
            --bg-secondary: #111113;
            --bg-tertiary: #1a1a1e;
            --bg-elevated: #222226;
            --bg-hover: rgba(59, 130, 246, 0.1);
            --bg-active: rgba(59, 130, 246, 0.15);
            --bg-selected: rgba(59, 130, 246, 0.2);

            /* Text colors */
            --text-primary: #ffffff;
            --text-secondary: #c0c0c8;
            --text-tertiary: #888894;
            --text-disabled: #3f3f46;

            /* Brand colors */
            --brand-primary: #3b82f6;
            --brand-primary-hover: #2563eb;
            --brand-secondary: #8b5cf6;
            --brand-accent: #06b6d4;
            --brand-success: #10b981;
            --brand-warning: #f59e0b;
            --brand-error: #ef4444;
            --brand-info: #3b82f6;

            /* Border colors */
            --border-light: rgba(255, 255, 255, 0.06);
            --border-medium: rgba(255, 255, 255, 0.1);
            --border-heavy: rgba(255, 255, 255, 0.15);

            /* Shadows */
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.5);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.5);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.7);

            /* Spacing */
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
            --header-height: 64px;
            --container-max: 1400px;

            /* Border radius */
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-2xl: 20px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);

            /* Z-index layers */
            --z-negative: -1;
            --z-elevate: 10;
            --z-dropdown: 50;
            --z-modal: 100;
            --z-tooltip: 150;
            --z-toast: 200;
        }

        /* ===== LIGHT THEME ===== */
        [data-theme="light"] {
            --bg-primary: #f5f7fa;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f0f2f5;
            --bg-elevated: #ffffff;
            --bg-hover: rgba(59, 130, 246, 0.08);
            --bg-active: rgba(59, 130, 246, 0.12);
            --bg-selected: rgba(59, 130, 246, 0.15);

            --text-primary: #1a1a2e;
            --text-secondary: #4a4a5a;
            --text-tertiary: #8a8a9a;
            --text-disabled: #cbd5e1;

            --border-light: rgba(0, 0, 0, 0.06);
            --border-medium: rgba(0, 0, 0, 0.1);
            --border-heavy: rgba(0, 0, 0, 0.15);

            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        /* Base styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.5;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--brand-primary);
        }

        /* Selection */
        ::selection {
            background: var(--brand-primary);
            color: white;
        }

        /* Links */
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Buttons */
        button {
            font-family: inherit;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* ============================================
           LAYOUT COMPONENTS
        ============================================ */

        /* App Container */
        .app {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            z-index: var(--z-modal);
            transition: width var(--transition-base), transform var(--transition-base);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 20px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
            background: var(--bg-secondary);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.3px;
            white-space: nowrap;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-icon i {
            color: white;
            font-size: 16px;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 12px;
        }

        .nav-group {
            margin-bottom: 28px;
        }

        .nav-group-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-tertiary);
            padding: 0 12px;
            margin-bottom: 10px;
        }

        .sidebar.collapsed .nav-group-label {
            display: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            transition: all var(--transition-fast);
            margin-bottom: 2px;
            position: relative;
            white-space: nowrap;
            cursor: pointer;
            border-left: 2px solid transparent;
        }

        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-left-color: rgba(59, 130, 246, 0.3);
        }

        .nav-item.active {
            background: var(--bg-selected);
            color: var(--brand-primary);
            border-left-color: var(--brand-primary);
            font-weight: 600;
        }

        .nav-item.active i:first-child {
            color: var(--brand-primary);
        }

        /* Collapsed : la barre active reste visible sur le bord gauche de l'icône */
        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 8px;
            border-left: 2px solid transparent;
        }

        .sidebar.collapsed .nav-item:hover {
            border-left-color: rgba(59, 130, 246, 0.3);
        }

        .sidebar.collapsed .nav-item.active {
            border-left-color: var(--brand-primary);
        }

        .nav-item i:first-child {
            width: 20px;
            font-size: 15px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            padding: 2px 6px;
            border-radius: var(--radius-full);
            font-size: 10px;
            font-weight: 600;
            background: var(--brand-error);
            color: white;
            min-width: 18px;
            text-align: center;
        }

        .sidebar.collapsed .nav-item span:not(.nav-badge) {
            display: none;
        }

        .sidebar.collapsed .nav-badge {
            position: absolute;
            top: -2px;
            right: 4px;
            transform: scale(0.8);
        }

        /* ===== SIDEBAR FOOTER — PROFILE BLOCK ===== */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border-light);
            background: var(--bg-secondary);
        }

        .profile-block {
            border-radius: var(--radius-lg);
            background: var(--bg-tertiary);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .profile-main {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.35);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .user-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 600;
            color: var(--brand-primary);
            background: rgba(59,130,246,0.12);
            border-radius: var(--radius-full);
            padding: 1px 7px;
            margin-top: 3px;
        }

        .profile-actions {
            display: flex;
            border-top: 1px solid var(--border-light);
        }

        .profile-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-fast);
            cursor: pointer;
            text-decoration: none;
            background: none;
            border: none;
            font-family: inherit;
        }

        .profile-action-btn:hover {
            color: var(--brand-primary);
            background: var(--bg-hover);
        }

        .profile-action-btn i {
            font-size: 12px;
            width: 14px;
            text-align: center;
        }

        .sidebar.collapsed .profile-action-btn span {
            display: none;
        }

        .sidebar.collapsed .profile-block {
            background: transparent;
            border-color: transparent;
        }

        .sidebar.collapsed .profile-main {
            justify-content: center;
            padding: 8px;
        }

        .sidebar.collapsed .user-info,
        .sidebar.collapsed .profile-actions {
            display: none;
        }

        .sidebar.collapsed .user-avatar {
            width: 40px;
            height: 40px;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition-base);
        }

        .sidebar.collapsed+.main {
            margin-left: var(--sidebar-collapsed);
        }

        @media (max-width: 1024px) {
            .main {
                margin-left: 0 !important;
            }
        }

        /* ===== HEADER ===== */
        .header {
            position: sticky;
            top: 0;
            right: 0;
            left: 0;
            height: var(--header-height);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            gap: 16px;
            z-index: var(--z-elevate);
            flex-shrink: 0;
        }

        [data-theme="dark"] .header {
            background: rgba(17, 17, 19, 0.95);
            backdrop-filter: blur(10px);
        }

        [data-theme="light"] .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            transition: all var(--transition-fast);
        }

        .menu-toggle:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        @media (max-width: 1024px) {
            .menu-toggle {
                display: flex;
            }
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 18px;
            }
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: all var(--transition-fast);
            cursor: pointer;
        }

        .icon-btn:hover {
            background: var(--bg-hover);
            color: var(--brand-primary);
            transform: translateY(-1px);
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 16px;
            height: 16px;
            background: linear-gradient(135deg, var(--brand-error), #dc2626);
            border-radius: var(--radius-full);
            font-size: 9px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 0 0 2px var(--bg-secondary);
        }

        /* ===== DROPDOWNS ===== */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 280px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all var(--transition-fast);
            z-index: var(--z-dropdown);
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            font-weight: 600;
            color: var(--text-primary);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-secondary);
            transition: background var(--transition-fast);
            cursor: pointer;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-light);
            margin: 6px 0;
        }

        /* ===== CONTENT AREA ===== */
        .content {
            flex: 1 0 auto;
            padding: 28px 32px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px 16px;
            }
        }

        .content-container {
            max-width: var(--container-max);
            margin: 0 auto;
            width: 100%;
        }

        /* ===== ALERTS ===== */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            animation: slideInTop 0.3s ease;
        }

        @keyframes slideInTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #10b981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #ef4444;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.25);
            color: #f59e0b;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #3b82f6;
        }

        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            color: currentColor;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity var(--transition-fast);
            padding: 4px;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* ===== FOOTER ===== */
        .footer {
            flex-shrink: 0;
            border-top: 1px solid var(--border-light);
            padding: 16px 32px;
            text-align: center;
            font-size: 12px;
            color: var(--text-tertiary);
            background: var(--bg-secondary);
            margin-top: auto;
        }

        /* ===== UTILITIES ===== */
        .hidden {
            display: none !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            :root {
                --header-height: 56px;
            }

            .header {
                padding: 0 16px;
            }

            .icon-btn {
                width: 34px;
                height: 34px;
            }
        }

        /* Loading state */
        .loading {
            position: relative;
            opacity: 0.6;
            pointer-events: none;
        }

        /* Focus styles */
        *:focus-visible {
            outline: 2px solid var(--brand-primary);
            outline-offset: 2px;
        }

        /* ===== SIDEBAR TOGGLE BUTTON (desktop) ===== */
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: var(--radius-md);
            color: var(--text-tertiary);
            flex-shrink: 0;
            transition: all var(--transition-fast);
            cursor: pointer;
            background: none;
            border: none;
        }

        .sidebar-toggle-btn:hover {
            background: var(--bg-hover);
            color: var(--brand-primary);
        }

        .sidebar-toggle-btn i {
            font-size: 11px;
            transition: transform var(--transition-base);
        }

        .sidebar.collapsed .sidebar-toggle-btn i {
            transform: rotate(180deg);
        }

        @media (max-width: 1024px) {
            .sidebar-toggle-btn { display: none; }
        }

        /* header compact quand collapsed */
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 0 8px;
            gap: 0;
        }

        .sidebar.collapsed .sidebar-toggle-btn {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="app">
        <!-- ============================================
             SIDEBAR
        ============================================ -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <div class="logo-icon">
                        @if($companyInfo && $companyInfo->logo)
                            <img src="{{ asset('storage/' . $companyInfo->logo) }}" alt="Logo">
                        @else
                            <i class="fas fa-rocket"></i>
                        @endif
                    </div>
                    <span class="logo-text">{{ $companyInfo->name ?? 'NovaTech' }}</span>
                </a>
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Réduire / Agrandir le menu">
                    <i class="fas fa-chevron-left" id="sidebarToggleIcon"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <!-- Dashboard - visible pour tous les rôles -->
                @can('dashboard.view')
                <div class="nav-group">
                    <div class="nav-group-label">Principal</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                @endcan

                <!-- Content Management - visible pour super-admin, admin, editor -->
                @if($isSuperAdmin || $isAdmin || $isEditor)
                <div class="nav-group">
                    <div class="nav-group-label">Contenu</div>

                    @can('portfolio.view')
                    <a href="{{ route('admin.portfolio.index') }}"
                        class="nav-item {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Portfolio</span>
                    </a>
                    @endcan

                    @can('blog.view')
                    <a href="{{ route('admin.blog.index') }}"
                        class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                        <i class="fas fa-pen-nib"></i>
                        <span>Blog</span>
                    </a>
                    @endcan

                    @can('services.view')
                    <a href="{{ route('admin.services.index') }}"
                        class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fas fa-th-list"></i>
                        <span>Services</span>
                    </a>
                    @endcan

                    @can('testimonials.view')
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Témoignages</span>
                    </a>
                    @endcan

                    @can('team.view')
                    <a href="{{ route('admin.team.index') }}"
                        class="nav-item {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                        <i class="fas fa-id-badge"></i>
                        <span>Équipe</span>
                    </a>
                    @endcan

                    @can('tools.view')
                    <a href="{{ route('admin.tools.index') }}"
                        class="nav-item {{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">
                        <i class="fas fa-puzzle-piece"></i>
                        <span>Outils & Technologies</span>
                    </a>
                    @endcan

                    @can('faqs.view')
                    <a href="{{ route('admin.faqs.index') }}"
                        class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        <i class="fas fa-life-ring"></i>
                        <span>FAQ</span>
                    </a>
                    @endcan

                    @can('clients.view')
                    <a href="{{ route('admin.clients.index') }}"
                        class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Clients</span>
                    </a>
                    @endcan
                </div>
                @endif

                <!-- MAINTENANCE SECTION - visible pour super-admin, admin, support, technician -->
                @if($isSuperAdmin || $isAdmin || $isSupport || $isTechnician)
                <div class="nav-group">
                    <div class="nav-group-label">Maintenance</div>

                    @can('maintenance.view')
                    <a href="{{ route('admin.maintenance.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.maintenance.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                    @endcan

                    @can('devices.view')
                    <a href="{{ route('admin.maintenance.devices.index') }}"
                        class="nav-item {{ request()->routeIs('admin.maintenance.devices*') ? 'active' : '' }}">
                        <i class="fas fa-microchip"></i>
                        <span>Appareils</span>
                        @php
                            $devicesInMaintenance = \App\Models\Device::whereIn('status', ['maintenance', 'repair'])->count();
                        @endphp
                        @if($devicesInMaintenance > 0)
                        <span class="nav-badge">{{ $devicesInMaintenance }}</span>
                        @endif
                    </a>
                    @endcan

                    @can('interventions.view')
                    <a href="{{ route('admin.maintenance.interventions.index') }}"
                        class="nav-item {{ request()->routeIs('admin.maintenance.interventions*') ? 'active' : '' }}">
                        <i class="fas fa-wrench"></i>
                        <span>Interventions</span>
                        @php
                            $pendingInterventions = \App\Models\Intervention::where('status', 'pending')->count();
                        @endphp
                        @if($pendingInterventions > 0)
                        <span class="nav-badge">{{ $pendingInterventions }}</span>
                        @endif
                    </a>
                    @endcan

                    @can('maintenance.statistics')
                    <a href="{{ route('admin.maintenance.statistics') }}"
                        class="nav-item {{ request()->routeIs('admin.maintenance.statistics') ? 'active' : '' }}">
                        <i class="fas fa-chart-area"></i>
                        <span>Statistiques</span>
                    </a>
                    @endcan
                </div>
                @endif

                <!-- Communications - visible pour super-admin, admin, support, editor -->
                @if($isSuperAdmin || $isAdmin || $isSupport || $isEditor)
                <div class="nav-group">
                    <div class="nav-group-label">Communications</div>

                    @can('users.view')
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    @endcan

                    @can('contact.view')
                    <a href="{{ route('admin.contacts.index') }}"
                        class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                        @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
                        @if($unreadCount > 0)
                        <span class="nav-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    @endcan

                    @can('tickets.view')
                    <a href="{{ route('admin.tickets.index') }}"
                        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i>
                        <span>Tickets</span>
                        @php $openTickets = \App\Models\Ticket::where('status', 'open')->count(); @endphp
                        @if($openTickets > 0)
                        <span class="nav-badge">{{ $openTickets }}</span>
                        @endif
                    </a>
                    @endcan

                    @can('newsletter.view')
                    <a href="{{ route('admin.newsletter.index') }}"
                        class="nav-item {{ request()->routeIs('admin.newsletter.*') ? 'active' : ''}}">
                        <i class="fas fa-mail-bulk"></i>
                        <span>Newsletter</span>
                        @php
                        $activeSubscribers = \App\Models\Newsletter::where('is_active', true)->count();
                        @endphp
                        @if($activeSubscribers > 0)
                        <span class="nav-badge">{{ $activeSubscribers }}</span>
                        @endif
                    </a>
                    @endcan
                </div>
                @endif

                <!-- Finance & Facturation - visible pour super-admin, admin -->
                @if($isSuperAdmin || $isAdmin)
                <div class="nav-group">
                    <div class="nav-group-label">Finance</div>

                    @can('billing.invoices.view')
                    <a href="{{ route('admin.billing.invoices.index') }}"
                        class="nav-item {{ request()->routeIs('admin.billing.invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>Factures</span>
                    </a>
                    @endcan

                    @can('billing.payments.view')
                    <a href="{{ route('admin.billing.payments.index') }}"
                        class="nav-item {{ request()->routeIs('admin.billing.payments.*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Paiements</span>
                    </a>
                    @endcan
                </div>
                @endif

                <!-- Gestion de Projets - visible pour super-admin, admin, project-manager -->
                @if($isSuperAdmin || $isAdmin || $isProjectManager)
                <div class="nav-group">
                    <div class="nav-group-label">Projets</div>

                    @can('projects.view')
                    <a href="{{ route('admin.projects.index') }}"
                        class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-diagram-project"></i>
                        <span>Projets</span>
                    </a>
                    @endcan

                    @can('tasks.view')
                    <a href="{{ route('admin.tasks.global-index') }}"
                        class="nav-item {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tâches</span>
                    </a>
                    @endcan

                    @can('meetings.view')
                    <a href="{{ route('admin.meetings.global-index') }}"
                        class="nav-item {{ request()->routeIs('admin.meetings.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Réunions</span>
                    </a>
                    @endcan
                </div>
                @endif

                <!-- Administration - visible pour super-admin, admin -->
                @if($isSuperAdmin || $isAdmin)
                <div class="nav-group">
                    <div class="nav-group-label">Administration</div>

                    @can('roles.view')
                    <a href="{{ route('admin.roles.index') }}"
                        class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Rôles & Permissions</span>
                    </a>
                    @endcan

                    @can('settings.view')
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>Paramètres</span>
                    </a>
                    @endcan

                    @can('backups.view')
                    <a href="{{ route('admin.backup.index') }}"
                        class="nav-item {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Sauvegardes</span>
                    </a>
                    @endcan
                </div>
                @endif


            </nav>

            <div class="sidebar-footer">
                <div class="profile-block">
                    <!-- Avatar + nom + rôle -->
                    <div class="profile-main">
                        <div class="user-avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                            @endif
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name ?? 'Administrateur' }}</div>
                            <div class="user-role-badge">
                                @if($isSuperAdmin)
                                    <i class="fas fa-shield-alt" style="font-size:9px;"></i> Super Admin
                                @elseif($isAdmin)
                                    <i class="fas fa-user-shield" style="font-size:9px;"></i> Admin
                                @elseif($isProjectManager)
                                    <i class="fas fa-diagram-project" style="font-size:9px;"></i> Chef de Projet
                                @elseif($isEditor)
                                    <i class="fas fa-pen" style="font-size:9px;"></i> Éditeur
                                @elseif($isSupport)
                                    <i class="fas fa-headset" style="font-size:9px;"></i> Support
                                @elseif($isTechnician)
                                    <i class="fas fa-hard-hat" style="font-size:9px;"></i> Technicien
                                @elseif($isViewer)
                                    <i class="fas fa-eye" style="font-size:9px;"></i> Visualisateur
                                @else
                                    <i class="fas fa-user" style="font-size:9px;"></i> Utilisateur
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Lien profil -->
                    <div class="profile-actions">
                        <a href="{{ route('admin.profile.edit') }}" class="profile-action-btn" id="profileNavLink" title="Mon profil">
                            <i class="fas fa-user-circle"></i>
                            <span>Mon profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ============================================
             MAIN CONTENT
        ============================================ -->
        <main class="main">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" id="mobileMenuToggle" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
                </div>

                <div class="header-actions">
                    <!-- Theme Toggle -->
                    <button class="icon-btn" id="themeToggle" aria-label="Changer le thème">
                        <i id="themeIcon" class="fas fa-moon"></i>
                    </button>

                    <!-- Notifications - visible pour TOUS les rôles -->
                    <div class="dropdown">
                        <button class="icon-btn" id="notificationsBtn" aria-label="Notifications">
                            <i class="fas fa-bell"></i>
                            @php
                                $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if($unreadNotifications > 0)
                            <span class="notification-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu" id="notificationsMenu" style="min-width: 340px; max-width: 360px;">
                            {{-- Header --}}
                            <div class="dropdown-header" style="display:flex; justify-content:space-between; align-items:center; padding: 14px 16px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <strong style="font-size:14px;">Notifications</strong>
                                    @if($unreadNotifications > 0)
                                    <span style="font-size:10px; font-weight:700; background:var(--brand-error); color:#fff; border-radius:999px; padding:1px 7px;">
                                        {{ $unreadNotifications }}
                                    </span>
                                    @endif
                                </div>
                                @if($unreadNotifications > 0)
                                <button onclick="markAllAsRead()" style="font-size:11px; font-weight:600; color:var(--brand-primary); background:none; border:none; cursor:pointer; padding:3px 8px; border-radius:4px; transition:background 0.15s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='none'">
                                    <i class="fas fa-check-double" style="margin-right:4px;"></i>Tout lire
                                </button>
                                @endif
                            </div>

                            @php
                                $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->latest()
                                    ->take(7)
                                    ->get();
                                $totalNotifications = \App\Models\Notification::where('user_id', auth()->id())->count();
                            @endphp

                            {{-- Liste scrollable : max 7 items visibles (~56px par item) --}}
                            <div style="max-height: 392px; overflow-y: auto; overflow-x: hidden;">
                                @forelse($recentNotifications as $notification)
                                <a href="{{ $notification->url ?: '#' }}"
                                   class="dropdown-item notification-item"
                                   data-id="{{ $notification->id }}"
                                   style="align-items:flex-start; padding:10px 16px; gap:10px; border-bottom:1px solid var(--border-light); {{ !$notification->is_read ? 'background:var(--bg-selected);' : '' }}">

                                    {{-- Icône colorée --}}
                                    <div style="width:32px; height:32px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px;
                                        {{ $notification->type === 'contact_message' ? 'background:rgba(59,130,246,0.12); color:var(--brand-primary);' : ($notification->type === 'intervention' ? 'background:rgba(245,158,11,0.12); color:var(--brand-warning);' : 'background:rgba(139,92,246,0.12); color:var(--brand-secondary);') }}">
                                        <i class="fas {{ $notification->type === 'contact_message' ? 'fa-envelope' : ($notification->type === 'intervention' ? 'fa-tools' : 'fa-bell') }}"></i>
                                    </div>

                                    {{-- Texte --}}
                                    <div style="flex:1; min-width:0;">
                                        <div style="font-weight:600; font-size:12px; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ $notification->title }}
                                        </div>
                                        <div style="font-size:11px; color:var(--text-secondary); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ Str::limit($notification->message, 45) }}
                                        </div>
                                        <div style="font-size:10px; color:var(--text-tertiary); margin-top:3px;">
                                            <i class="fas fa-clock" style="margin-right:3px;"></i>{{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    {{-- Point non-lu --}}
                                    @if(!$notification->is_read)
                                    <div style="width:7px; height:7px; background:var(--brand-primary); border-radius:50%; flex-shrink:0; margin-top:4px;"></div>
                                    @endif
                                </a>
                                @empty
                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px 24px; gap:10px; color:var(--text-tertiary);">
                                    <i class="fas fa-bell-slash" style="font-size:28px; opacity:0.4;"></i>
                                    <span style="font-size:13px;">Aucune notification</span>
                                </div>
                                @endforelse
                            </div>

                            {{-- Footer --}}
                            @if($totalNotifications > 0)
                            <div style="border-top:1px solid var(--border-light); padding:10px 16px; display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:11px; color:var(--text-tertiary);">
                                    {{ $totalNotifications }} notification{{ $totalNotifications > 1 ? 's' : '' }} au total
                                </span>
                                <a href="{{ route('admin.notifications.index') }}"
                                   style="font-size:11px; font-weight:600; color:var(--brand-primary); display:flex; align-items:center; gap:5px;">
                                    Voir tout <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="icon-btn" id="userMenuBtn" aria-label="Menu utilisateur">
                            <div class="user-avatar" style="width: 32px; height: 32px;">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                                @endif
                            </div>
                        </button>
                        <div class="dropdown-menu" id="userMenu" style="min-width: 220px;">
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item" id="profileMenuItem">
                                <i class="fas fa-user-circle"></i>
                                <span>Mon profil</span>
                            </a>
                            <a href="#" class="dropdown-item" id="themeMenuItem">
                                <i class="fas fa-moon"></i>
                                <span>Changer de thème</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    style="width: 100%; color: var(--brand-error);">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content">
                <div class="content-container">
                    <!-- Alerts -->
                    @if(session('success'))
                    <div class="alert alert-success" id="alertSuccess">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-error" id="alertError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endif

                    @if(session('warning'))
                    <div class="alert alert-warning" id="alertWarning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ session('warning') }}</span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="alert alert-info" id="alertInfo">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ session('info') }}</span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; {{ date('Y') }} {{ $companyInfo->name ?? 'NovaTech' }}. Tous droits réservés. | Version 1.0.0</p>
            </footer>
        </main>
    </div>

    <!-- ============================================
         JAVASCRIPT
    ============================================ -->
    <script>
        (function() {
            'use strict';

            // ===== DOM Elements =====
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const notificationsBtn = document.getElementById('notificationsBtn');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');
            const profileNavLink = document.getElementById('profileNavLink');
            const profileMenuItem = document.getElementById('profileMenuItem');

            // ===== Sidebar Management =====
            let isSidebarCollapsed = false;

            try {
                const saved = localStorage.getItem('sidebar_collapsed');
                if (saved !== null) {
                    isSidebarCollapsed = saved === 'true';
                    if (isSidebarCollapsed) {
                        sidebar.classList.add('collapsed');
                    }
                }
            } catch(e) {}

            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

            function toggleSidebarCollapse() {
                isSidebarCollapsed = !isSidebarCollapsed;
                sidebar.classList.toggle('collapsed', isSidebarCollapsed);
                try { localStorage.setItem('sidebar_collapsed', isSidebarCollapsed); } catch(e) {}
            }

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', toggleSidebarCollapse);
            }

            // Fermer la sidebar mobile
            function collapseSidebar() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove('mobile-open');
                }
            }

            // Quand on clique sur le lien "Mon profil" dans la sidebar
            if (profileNavLink) {
                profileNavLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    collapseSidebar();
                    window.location.href = this.getAttribute('href');
                });
            }

            // Quand on clique sur "Mon profil" dans le menu utilisateur
            if (profileMenuItem) {
                profileMenuItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    collapseSidebar();
                    window.location.href = this.getAttribute('href');
                });
            }

            if (mobileToggle) {
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('mobile-open');
                });
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('mobile-open');
                }
            });

            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024 && sidebar.classList.contains('mobile-open')) {
                    if (!sidebar.contains(e.target) && !mobileToggle?.contains(e.target)) {
                        sidebar.classList.remove('mobile-open');
                    }
                }
            });

            // ===== Theme Management =====
            const html = document.documentElement;

            function setTheme(theme) {
                html.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                themeIcon.className = theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';

                const themeMenuItemIcon = document.querySelector('#themeMenuItem i');
                if (themeMenuItemIcon) {
                    themeMenuItemIcon.className = theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
                }
            }

            const savedTheme = localStorage.getItem('theme') || 'dark';
            setTheme(savedTheme);

            function handleThemeToggle() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', handleThemeToggle);
            }

            const themeMenuItem = document.getElementById('themeMenuItem');
            if (themeMenuItem) {
                themeMenuItem.addEventListener('click', (e) => {
                    e.preventDefault();
                    handleThemeToggle();
                    if (userMenu) userMenu.classList.remove('show');
                });
            }

            // ===== Dropdown Management =====
            function closeAllDropdowns() {
                if (notificationsMenu) notificationsMenu.classList.remove('show');
                if (userMenu) userMenu.classList.remove('show');
            }

            function toggleDropdown(menu, event) {
                if (event) event.stopPropagation();
                if (menu.classList.contains('show')) {
                    menu.classList.remove('show');
                } else {
                    closeAllDropdowns();
                    menu.classList.add('show');
                }
            }

            if (notificationsBtn && notificationsMenu) {
                notificationsBtn.addEventListener('click', (e) => toggleDropdown(notificationsMenu, e));
            }

            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener('click', (e) => toggleDropdown(userMenu, e));
            }


            document.addEventListener('click', () => closeAllDropdowns());

            if (notificationsMenu) notificationsMenu.addEventListener('click', (e) => e.stopPropagation());
            if (userMenu) userMenu.addEventListener('click', (e) => e.stopPropagation());

            // ===== Auto-close alerts =====
            function autoCloseAlerts() {
                document.querySelectorAll('.alert').forEach(alert => {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        alert.style.transition = 'all 0.3s ease';
                        setTimeout(() => alert.remove(), 300);
                    }, 5000);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', autoCloseAlerts);
            } else {
                autoCloseAlerts();
            }

            // ===== Notification Functions =====
            window.markAllAsRead = function() {
                fetch('{{ route("admin.notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    window.location.reload();
                });
            };

            // Marquer une notification comme lue au clic
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const notificationId = this.dataset.id;
                    if (notificationId) {
                        fetch('{{ url("admin/notifications") }}/' + notificationId + '/read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                    }
                });
            });

            // ===== Keyboard shortcuts =====
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    handleThemeToggle();
                }
            });

            // ===== Active link highlighting =====
            function updateActiveNavItem() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-item').forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && href !== '#' && currentPath.includes(href)) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
            updateActiveNavItem();

            console.log('%cNovaTech Admin Panel v1.0', 'color: #3b82f6; font-size: 14px; font-weight: bold;');
        })();
    </script>

    @stack('scripts')
</body>

</html>
