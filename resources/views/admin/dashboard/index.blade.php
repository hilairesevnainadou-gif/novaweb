{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard - NovaTech Admin')
@section('page-title', 'Tableau de bord')

@section('content')
<style>
    /* ============================================
       DASHBOARD SIMPLIFIÉ - DESIGN COMPACT
    ============================================ */

    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 0.875rem;
        padding: 0.875rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    [data-theme="light"] .welcome-banner {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    }

    .welcome-content h2 {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.125rem 0;
    }

    [data-theme="light"] .welcome-content h2 {
        color: #1e293b;
    }

    .welcome-content p {
        font-size: 0.6875rem;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
    }

    [data-theme="light"] .welcome-content p {
        color: #475569;
    }

    .welcome-date {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.625rem;
        color: rgba(255, 255, 255, 0.8);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-1px);
        border-color: var(--border-medium);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8125rem;
    }

    .stat-icon.primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.info { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-icon.slate { background: rgba(100, 116, 139, 0.1); color: #64748b; }

    .stat-badge {
        font-size: 0.5625rem;
        font-weight: 600;
        padding: 0.125rem 0.375rem;
        border-radius: 1rem;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stat-badge.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .stat-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 0.125rem;
    }

    .stat-label {
        font-size: 0.625rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--text-tertiary);
    }

    .stat-footer {
        margin-top: 0.5rem;
        padding-top: 0.375rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        font-size: 0.5625rem;
        color: var(--text-tertiary);
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.625rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .section-title i {
        font-size: 0.6875rem;
        color: var(--brand-primary);
    }

    .section-title h3 {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        margin: 0;
    }

    .section-link {
        font-size: 0.5625rem;
        font-weight: 500;
        color: var(--brand-primary);
        text-decoration: none;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .chart-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 0.75rem;
    }

    .chart-title {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        margin-bottom: 0.625rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    /* Actions Rapides */
    .actions-wrapper {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .actions-header {
        padding: 0.5rem 0.875rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .actions-header .section-title {
        margin-bottom: 0;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 0.5rem;
        padding: 0.625rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.625rem;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
    }

    .action-btn:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        transform: translateY(-1px);
    }

    .action-btn-icon {
        width: 1.625rem;
        height: 1.625rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6875rem;
    }

    .action-btn-icon.primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .action-btn-icon.info { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .action-btn-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .action-btn-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .action-btn-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .action-btn-icon.slate { background: rgba(100, 116, 139, 0.1); color: #64748b; }

    .action-btn-text {
        flex: 1;
    }

    .action-btn-title {
        font-size: 0.6875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.0625rem;
    }

    .action-btn-desc {
        font-size: 0.5625rem;
        color: var(--text-tertiary);
    }

    /* Cards Grid */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }

    .widget-card {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .widget-header {
        padding: 0.5rem 0.75rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .widget-title {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .widget-body {
        padding: 0.5rem;
        max-height: 250px;
        overflow-y: auto;
    }

    /* List Items */
    .list-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.5rem;
        border-bottom: 1px solid var(--border-light);
        transition: background 0.2s;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item:hover {
        background: var(--bg-hover);
    }

    .list-icon {
        width: 1.625rem;
        height: 1.625rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-tertiary);
        color: var(--brand-primary);
        font-size: 0.625rem;
        flex-shrink: 0;
    }

    .list-content {
        flex: 1;
        min-width: 0;
    }

    .list-title {
        font-size: 0.6875rem;
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.125rem;
    }

    .list-meta {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        flex-wrap: wrap;
        font-size: 0.5rem;
        color: var(--text-tertiary);
    }

    .list-badge {
        font-size: 0.4375rem;
        font-weight: 600;
        padding: 0.0625rem 0.3125rem;
        border-radius: 1rem;
    }

    .list-badge.active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .list-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .list-badge.draft { background: rgba(100, 116, 139, 0.1); color: #64748b; }

    .list-link {
        color: var(--text-tertiary);
        width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
    }

    .list-link:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 0.75rem;
        color: var(--text-tertiary);
    }

    .empty-state i {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 0.5625rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .charts-grid {
            grid-template-columns: 1fr;
        }
        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .cards-grid {
            grid-template-columns: 1fr;
        }
        .actions-grid {
            grid-template-columns: 1fr;
        }
        .welcome-banner {
            flex-direction: column;
            text-align: center;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card, .chart-card, .widget-card, .actions-wrapper {
        animation: fadeInUp 0.3s ease forwards;
    }
</style>

@php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('super-admin');
    $isAdmin = $user->hasRole('admin');
    $isEditor = $user->hasRole('editor');
    $isSupport = $user->hasRole('support');
    $isTechnician = $user->hasRole('technician');
    $isViewer = $user->hasRole('viewer');

    // Message selon l'heure
    $hour = date('H');
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Bonjour';
        $emoji = '🌅';
        $message = 'Belle journée qui commence !';
    } elseif ($hour >= 12 && $hour < 14) {
        $greeting = 'Bon appétit';
        $emoji = '🍽️';
        $message = 'Prenez le temps de déjeuner.';
    } elseif ($hour >= 14 && $hour < 18) {
        $greeting = 'Bon après-midi';
        $emoji = '☀️';
        $message = 'Continuez votre bonne lancée !';
    } elseif ($hour >= 18 && $hour < 22) {
        $greeting = 'Bonsoir';
        $emoji = '🌙';
        $message = 'Une belle soirée de travail.';
    } else {
        $greeting = 'Bonne nuit';
        $emoji = '💤';
        $message = 'Reposez-vous bien !';
    }

    // Permissions
    $canViewPortfolio = $user->can('portfolio.view');
    $canViewBlog = $user->can('blog.view');
    $canViewContacts = $user->can('contact.view');
    $canViewUsers = $user->can('users.view');
    $canViewClients = $user->can('clients.view');
    $canViewBilling = $user->can('billing.view');
    $canViewTickets = $user->can('tickets.view');
    $canViewServices = $user->can('services.view');
    $canViewMaintenance = $user->can('maintenance.view');
    $canViewInterventions = $user->can('interventions.view');
    $canViewAllInterventions = $user->can('interventions.view.all');

    // Permissions de création
    $canCreatePortfolio = $user->can('portfolio.create');
    $canCreateBlog = $user->can('blog.create');
    $canCreateUsers = $user->can('users.create');
    $canCreateMaintenance = $user->can('maintenance.create');
    $canCreateServices = $user->can('services.create');
    $canCreateClients = $user->can('clients.create');

    // Stats pour technicien
    $myPendingInterventions = 0;
    $myCompletedInterventions = 0;
    if ($isTechnician && $canViewMaintenance) {
        $myPendingInterventions = \App\Models\Intervention::where('technician_id', $user->id)->pending()->count();
        $myCompletedInterventions = \App\Models\Intervention::where('technician_id', $user->id)
            ->completed()
            ->whereMonth('end_date', now()->month)
            ->count();
    }
@endphp

<div class="dashboard-container">
    <!-- Welcome Banner avec message personnalisé -->
    <div class="welcome-banner">
        <div class="welcome-content">
            <h2>{{ $greeting }}, {{ $user->name ?? 'Administrateur' }} {{ $emoji }}</h2>
            <p>{{ $message }}</p>
        </div>
        <div class="welcome-date">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
            <span style="margin-left: 0.25rem;">|</span>
            <i class="fas fa-clock"></i>
            <span>{{ \Carbon\Carbon::now()->format('H:i') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        @if($canViewPortfolio)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary"><i class="fas fa-briefcase"></i></div>
                <span class="stat-badge">+{{ $newPortfolio }}</span>
            </div>
            <div class="stat-value">{{ number_format($totalPortfolio) }}</div>
            <div class="stat-label">Projets</div>
            <div class="stat-footer">
                <span>Publiés: {{ $publishedPortfolio }}</span>
                <span>Brouillons: {{ $totalPortfolio - $publishedPortfolio }}</span>
            </div>
        </div>
        @endif

        @if($canViewBlog)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info"><i class="fas fa-newspaper"></i></div>
                <span class="stat-badge">+{{ $newBlogPosts }}</span>
            </div>
            <div class="stat-value">{{ number_format($totalBlogPosts) }}</div>
            <div class="stat-label">Articles</div>
            <div class="stat-footer">
                <span>Publiés: {{ $publishedPosts }}</span>
                <span>Brouillons: {{ $totalBlogPosts - $publishedPosts }}</span>
            </div>
        </div>
        @endif

        @if($canViewContacts)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success"><i class="fas fa-envelope"></i></div>
                @if($unreadContacts > 0)
                <span class="stat-badge warning">{{ $unreadContacts }}</span>
                @endif
            </div>
            <div class="stat-value">{{ number_format($totalContacts) }}</div>
            <div class="stat-label">Messages</div>
            <div class="stat-footer">
                <span>Lus: {{ $totalContacts - $unreadContacts }}</span>
                <span>Non lus: {{ $unreadContacts }}</span>
            </div>
        </div>
        @endif

        @if($canViewUsers)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon purple"><i class="fas fa-users"></i></div>
                <span class="stat-badge">+{{ $newUsers }}</span>
            </div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Utilisateurs</div>
            <div class="stat-footer">
                <span>Admins: {{ $adminCount }}</span>
                <span>Tickets: {{ $openTickets }}</span>
            </div>
        </div>
        @endif

        @if($canViewClients)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning"><i class="fas fa-building"></i></div>
                <span class="stat-badge">+{{ $newClients }}</span>
            </div>
            <div class="stat-value">{{ number_format($totalClients) }}</div>
            <div class="stat-label">Clients</div>
        </div>
        @endif

        @if($canViewBilling)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success"><i class="fas fa-credit-card"></i></div>
            </div>
            <div class="stat-value">{{ number_format($totalInvoiced, 0, ',', ' ') }}</div>
            <div class="stat-label">CA (FCFA)</div>
            <div class="stat-footer">
                <span>Payé: {{ number_format($totalPaid, 0, ',', ' ') }}</span>
                <span>Impayé: {{ number_format($outstandingAmount, 0, ',', ' ') }}</span>
            </div>
        </div>
        @endif

        @if($canViewTickets)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon danger"><i class="fas fa-ticket-alt"></i></div>
                @if($openTickets > 0)
                <span class="stat-badge danger">{{ $openTickets }}</span>
                @endif
            </div>
            <div class="stat-value">{{ number_format($openTickets) }}</div>
            <div class="stat-label">Tickets</div>
        </div>
        @endif

        @if($canViewServices)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon slate"><i class="fas fa-cogs"></i></div>
            </div>
            <div class="stat-value">{{ number_format($totalServices) }}</div>
            <div class="stat-label">Services</div>
        </div>
        @endif

        @if($isTechnician && $canViewMaintenance && !$canViewAllInterventions)
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning"><i class="fas fa-tools"></i></div>
                @if($myPendingInterventions > 0)
                <span class="stat-badge danger">{{ $myPendingInterventions }}</span>
                @endif
            </div>
            <div class="stat-value">{{ $myCompletedInterventions }}</div>
            <div class="stat-label">Mes interventions</div>
            <div class="stat-footer">
                <span>Terminées: {{ $myCompletedInterventions }}</span>
                <span>En attente: {{ $myPendingInterventions }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Rapides -->
    @php
        $hasActions = $canCreatePortfolio || $canCreateBlog || $canCreateUsers || $canCreateMaintenance || $canCreateServices || $canCreateClients;
    @endphp
    @if($hasActions)
    <div class="actions-wrapper">
        <div class="actions-header">
            <div class="section-title">
                <i class="fas fa-bolt"></i>
                <h3>Actions rapides</h3>
            </div>
        </div>
        <div class="actions-grid">
            @if($canCreatePortfolio)
            <a href="{{ route('admin.portfolio.create') }}" class="action-btn">
                <div class="action-btn-icon primary"><i class="fas fa-plus"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Nouveau projet</div>
                    <div class="action-btn-desc">Ajouter un projet</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
            @if($canCreateBlog)
            <a href="{{ route('admin.blog.create') }}" class="action-btn">
                <div class="action-btn-icon info"><i class="fas fa-pen"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Nouvel article</div>
                    <div class="action-btn-desc">Rédiger un article</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
            @if($canCreateUsers)
            <a href="{{ route('admin.users.create') }}" class="action-btn">
                <div class="action-btn-icon purple"><i class="fas fa-user-plus"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Nouvel utilisateur</div>
                    <div class="action-btn-desc">Ajouter un admin</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
            @if($canCreateMaintenance)
            <a href="{{ route('admin.maintenance.interventions.create') }}" class="action-btn">
                <div class="action-btn-icon warning"><i class="fas fa-tools"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Intervention</div>
                    <div class="action-btn-desc">Nouvelle intervention</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
            @if($canCreateServices)
            <a href="{{ route('admin.services.create') }}" class="action-btn">
                <div class="action-btn-icon slate"><i class="fas fa-cog"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Nouveau service</div>
                    <div class="action-btn-desc">Ajouter un service</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
            @if($canCreateClients)
            <a href="{{ route('admin.clients.create') }}" class="action-btn">
                <div class="action-btn-icon success"><i class="fas fa-building"></i></div>
                <div class="action-btn-text">
                    <div class="action-btn-title">Nouveau client</div>
                    <div class="action-btn-desc">Ajouter un client</div>
                </div>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: var(--text-tertiary);"></i>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Charts Section -->
    @if(($canViewPortfolio || $canViewBlog) && ($isSuperAdmin || $isAdmin || $isEditor))
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-title">
                <i class="fas fa-chart-line"></i> Évolution mensuelle
            </div>
            <canvas id="activityChart" style="width:100%; height:160px;"></canvas>
        </div>
        @if($canViewPortfolio)
        <div class="chart-card">
            <div class="chart-title">
                <i class="fas fa-chart-pie"></i> Catégories de projets
            </div>
            <canvas id="categoryChart" style="width:100%; height:160px;"></canvas>
        </div>
        @endif
    </div>
    @endif

    <!-- Contenu récent -->
    @if($canViewPortfolio || $canViewContacts || $canViewBlog || $canViewUsers || ($canViewInterventions && $recentInterventions->count() > 0))
    <div class="cards-grid">
        @if($canViewPortfolio && $recentPortfolio->count() > 0)
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title"><i class="fas fa-clock"></i> Projets récents</div>
                <a href="{{ route('admin.portfolio.index') }}" class="section-link">Voir</a>
            </div>
            <div class="widget-body">
                @foreach($recentPortfolio->take(4) as $project)
                <div class="list-item">
                    <div class="list-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="list-content">
                        <div class="list-title">{{ Str::limit($project->title, 28) }}</div>
                        <div class="list-meta">
                            <span>{{ $project->created_at->diffForHumans() }}</span>
                            <span class="list-badge {{ $project->is_active ? 'active' : 'draft' }}">
                                {{ $project->is_active ? 'Publié' : 'Brouillon' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.portfolio.edit', $project) }}" class="list-link"><i class="fas fa-edit"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($canViewContacts && $recentContacts->count() > 0)
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title"><i class="fas fa-inbox"></i> Messages récents</div>
                <a href="{{ route('admin.contacts.index') }}" class="section-link">Voir</a>
            </div>
            <div class="widget-body">
                @foreach($recentContacts->take(4) as $contact)
                <div class="list-item">
                    <div class="list-icon"><i class="fas fa-user"></i></div>
                    <div class="list-content">
                        <div class="list-title">{{ $contact->name }}</div>
                        <div class="list-meta">
                            <span>{{ $contact->created_at->diffForHumans() }}</span>
                            @if(!$contact->is_read)<span class="list-badge pending">Non lu</span>@endif
                        </div>
                    </div>
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="list-link"><i class="fas fa-envelope-open-text"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($canViewBlog && $recentPosts->count() > 0)
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title"><i class="fas fa-newspaper"></i> Articles récents</div>
                <a href="{{ route('admin.blog.index') }}" class="section-link">Voir</a>
            </div>
            <div class="widget-body">
                @foreach($recentPosts->take(4) as $post)
                <div class="list-item">
                    <div class="list-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="list-content">
                        <div class="list-title">{{ Str::limit($post->title, 28) }}</div>
                        <div class="list-meta">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            <span class="list-badge {{ $post->is_published ? 'active' : 'draft' }}">
                                {{ $post->is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.blog.edit', $post) }}" class="list-link"><i class="fas fa-edit"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($canViewInterventions && $recentInterventions->count() > 0)
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title"><i class="fas fa-tools"></i>
                    @if($canViewAllInterventions) Interventions
                    @elseif($isTechnician) Mes interventions
                    @else Interventions
                    @endif
                </div>
                <a href="{{ route('admin.maintenance.interventions.index') }}" class="section-link">Voir</a>
            </div>
            <div class="widget-body">
                @foreach($recentInterventions->take(4) as $intervention)
                <div class="list-item">
                    <div class="list-icon"><i class="fas fa-wrench"></i></div>
                    <div class="list-content">
                        <div class="list-title">{{ $intervention->intervention_number }}</div>
                        <div class="list-meta">
                            <span>{{ $intervention->created_at->diffForHumans() }}</span>
                            <span class="list-badge
                                @if($intervention->status === 'completed') completed
                                @elseif($intervention->status === 'in_progress') in-progress
                                @else pending
                                @endif">
                                {{ $intervention->status_label }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.maintenance.interventions.show', $intervention) }}" class="list-link"><i class="fas fa-eye"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($canViewUsers && $recentUsers->count() > 0)
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title"><i class="fas fa-user-plus"></i> Nouveaux utilisateurs</div>
                <a href="{{ route('admin.users.index') }}" class="section-link">Voir</a>
            </div>
            <div class="widget-body">
                @foreach($recentUsers->take(4) as $userItem)
                <div class="list-item">
                    <div class="list-icon"><i class="fas fa-user-circle"></i></div>
                    <div class="list-content">
                        <div class="list-title">{{ $userItem->name }}</div>
                        <div class="list-meta">
                            <span>{{ $userItem->created_at->diffForHumans() }}</span>
                            <span>{{ $userItem->email }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.edit', $userItem) }}" class="list-link"><i class="fas fa-edit"></i></a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(($canViewPortfolio || $canViewBlog) && ($isSuperAdmin || $isAdmin || $isEditor))
    var portfolioData = {{ json_encode($portfolioMonthly) }};
    var blogData = {{ json_encode($blogMonthly) }};
    var labelsData = {{ json_encode($months) }};
    var categoryLabelsData = {{ json_encode($categoryLabels) }};
    var categoryDataData = {{ json_encode($categoryData) }};

    var ctx = document.getElementById('activityChart');
    if (ctx) {
        ctx = ctx.getContext('2d');
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var textColor = isDark ? '#fafafa' : '#111827';
        var gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

        var datasets = [];
        @if($canViewPortfolio)
        datasets.push({
            label: 'Projets', data: portfolioData, borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.05)', borderWidth: 1.5, tension: 0.4, fill: true,
            pointBackgroundColor: '#3b82f6', pointBorderColor: '#3b82f6', pointRadius: 2, pointHoverRadius: 4
        });
        @endif
        @if($canViewBlog)
        datasets.push({
            label: 'Articles', data: blogData, borderColor: '#06b6d4',
            backgroundColor: 'rgba(6, 182, 212, 0.05)', borderWidth: 1.5, tension: 0.4, fill: true,
            pointBackgroundColor: '#06b6d4', pointBorderColor: '#06b6d4', pointRadius: 2, pointHoverRadius: 4
        });
        @endif

        new Chart(ctx, {
            type: 'line', data: { labels: labelsData, datasets: datasets },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { color: textColor, usePointStyle: true, boxWidth: 8, font: { size: 9 } } },
                    tooltip: { backgroundColor: isDark ? '#1f1f24' : '#fff', titleColor: textColor, bodyColor: textColor }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#64748b', font: { size: 8 } }, grid: { color: gridColor } },
                    x: { ticks: { color: '#64748b', font: { size: 8 } }, grid: { display: false } }
                }
            }
        });
    }

    var categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && {{ $canViewPortfolio ? 'true' : 'false' }}) {
        categoryCtx = categoryCtx.getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: { labels: categoryLabelsData, datasets: [{ data: categoryDataData, backgroundColor: ['#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6'], borderWidth: 0, hoverOffset: 8 }] },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, font: { size: 8 }, usePointStyle: true, boxWidth: 6 } },
                    tooltip: { backgroundColor: isDark ? '#1f1f24' : '#fff', titleColor: textColor, bodyColor: textColor }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
