<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a0a0c">
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
            --bg-primary: #09090b;
            --bg-secondary: #0f0f12;
            --bg-tertiary: #18181b;
            --bg-elevated: #1f1f24;
            --bg-hover: rgba(255, 255, 255, 0.04);
            --bg-active: rgba(255, 255, 255, 0.06);
            --bg-selected: rgba(59, 130, 246, 0.1);

            /* Text colors */
            --text-primary: #fafafa;
            --text-secondary: #a1a1aa;
            --text-tertiary: #71717a;
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
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #f1f3f5;
            --bg-elevated: #ffffff;
            --bg-hover: rgba(0, 0, 0, 0.04);
            --bg-active: rgba(0, 0, 0, 0.06);
            --bg-selected: rgba(59, 130, 246, 0.08);

            --text-primary: #18181b;
            --text-secondary: #52525b;
            --text-tertiary: #a1a1aa;
            --text-disabled: #d4d4d8;

            --border-light: rgba(0, 0, 0, 0.06);
            --border-medium: rgba(0, 0, 0, 0.1);
            --border-heavy: rgba(0, 0, 0, 0.15);

            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border-heavy);
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
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-tertiary);
            padding: 0 12px;
            margin-bottom: 8px;
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
        }

        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: var(--bg-selected);
            color: var(--brand-primary);
        }

        .nav-item i:first-child {
            width: 20px;
            font-size: 16px;
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

        /* Sidebar Footer - User Menu */
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border-light);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background var(--transition-fast);
        }

        .user-card:hover {
            background: var(--bg-hover);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .user-info {
            display: none;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 11px;
            color: var(--text-tertiary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            height: var(--header-height);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            gap: 16px;
            z-index: var(--z-elevate);
            backdrop-filter: blur(10px);
            background: rgba(var(--bg-secondary-rgb, 15, 15, 18), 0.8);
            flex-shrink: 0;
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
            font-weight: 600;
            letter-spacing: -0.3px;
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
        }

        .icon-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--brand-error);
            border-radius: var(--radius-full);
            border: 2px solid var(--bg-secondary);
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
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--brand-success);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--brand-error);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: var(--brand-warning);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--brand-info);
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
            </div>

            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-group">
                    <div class="nav-group-label">Principal</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Content Management -->
                <div class="nav-group">
                    <div class="nav-group-label">Contenu</div>

                    <a href="{{ route('admin.portfolio.index') }}"
                        class="nav-item {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Portfolio</span>
                    </a>

                    <a href="{{ route('admin.blog.index') }}"
                        class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Blog</span>
                    </a>

                    <a href="{{ route('admin.services.index') }}"
                        class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Services</span>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}"
                        class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Témoignages</span>
                    </a>
                </div>

                <!-- Communications -->
                <div class="nav-group">
                    <div class="nav-group-label">Communications</div>

                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>

                    <a href="{{ route('admin.contacts.index') }}"
                        class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                        @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
                        @if($unreadCount > 0)
                        <span class="nav-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.tickets.index') }}"
                        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Tickets</span>
                        @php $openTickets = \App\Models\Ticket::where('status', 'open')->count(); @endphp
                        @if($openTickets > 0)
                        <span class="nav-badge">{{ $openTickets }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.newsletter.index') }}" class="nav-item">
                        <i class="fas fa-newspaper"></i>
                        <span>Newsletter</span>
                        @php $unreadNewsletter = \App\Models\Newsletter::where('is_active', true)->count(); @endphp
                        @if($unreadNewsletter > 0)
                        <span class="nav-badge">{{ $unreadNewsletter }}</span>
                        @endif
                    </a>
                </div>

                <!-- Administration -->
                <div class="nav-group">
                    <div class="nav-group-label">Administration</div>

                    <a href="{{ route('admin.roles.index') }}"
                        class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Rôles & Permissions</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>Paramètres</span>
                    </a>

                    <a href="{{ route('admin.profile.edit') }}"
                        class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Mon profil</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card" id="sidebarUserMenu">
                    <div class="user-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        @endif
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name ?? 'Administrateur' }}</div>
                        <div class="user-email">{{ auth()->user()->email ?? 'admin@novatech.com' }}</div>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-tertiary);"></i>
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
                    <!-- Search -->
                    <button class="icon-btn" id="searchBtn" aria-label="Recherche">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Theme Toggle -->
                    <button class="icon-btn" id="themeToggle" aria-label="Changer le thème">
                        <i id="themeIcon" class="fas fa-moon"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="icon-btn" id="notificationsBtn" aria-label="Notifications">
                            <i class="fas fa-bell"></i>
                            @php $unreadContacts = \App\Models\Contact::where('is_read', false)->count(); @endphp
                            @if($unreadContacts > 0)
                            <span class="notification-dot"></span>
                            @endif
                        </button>
                        <div class="dropdown-menu" id="notificationsMenu">
                            <div class="dropdown-header">
                                <strong>Notifications</strong>
                            </div>
                            @php $recentContacts = \App\Models\Contact::latest()->take(5)->get(); @endphp
                            @forelse($recentContacts as $contact)
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="dropdown-item">
                                <i class="fas fa-envelope"></i>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 500; margin-bottom: 2px;">{{ $contact->name }}</div>
                                    <div
                                        style="font-size: 12px; color: var(--text-tertiary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($contact->message, 40) }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-tertiary); margin-top: 4px;">
                                        {{ $contact->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="dropdown-item" style="justify-content: center; gap: 8px;">
                                <i class="fas fa-bell-slash"></i>
                                <span>Aucune notification</span>
                            </div>
                            @endforelse
                            @if($recentContacts->count() > 0)
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.contacts.index') }}" class="dropdown-item"
                                style="justify-content: center;">
                                <span>Voir tous les messages</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
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
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
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
            const sidebarUserMenu = document.getElementById('sidebarUserMenu');

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

                // Mettre à jour l'élément du menu également
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

            if (sidebarUserMenu && userMenu) {
                sidebarUserMenu.addEventListener('click', (e) => toggleDropdown(userMenu, e));
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

            // ===== Keyboard shortcuts =====
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchBtn = document.getElementById('searchBtn');
                    if (searchBtn) searchBtn.click();
                }
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    handleThemeToggle();
                }
            });

            const searchBtn = document.getElementById('searchBtn');
            if (searchBtn) {
                searchBtn.addEventListener('click', () => {
                    console.log('Search clicked');
                });
            }

            // ===== Active link highlighting =====
            function updateActiveNavItem() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-item').forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && href !== '#' && currentPath.includes(href)) {
                        item.classList.add('active');
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
