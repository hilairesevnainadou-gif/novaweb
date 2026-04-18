@extends('admin.layouts.app')

@section('title', 'Dashboard - NovaTech Admin')
@section('page-title', 'Tableau de bord')

@section('content')
<style>
    /* Dashboard specific styles - Compatible avec le thème */
    .dashboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dashboard-stat-card {
        background: var(--bg-secondary, #ffffff);
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--border-light, #e5e7eb);
        transition: all 0.3s ease;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }

    [data-theme="dark"] .dashboard-stat-card {
        background: #1f1f24;
        border-color: #27272a;
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.portfolio { background: rgba(99, 102, 241, 0.1); }
    .stat-icon.blog { background: rgba(6, 182, 212, 0.1); }
    .stat-icon.messages { background: rgba(16, 185, 129, 0.1); }
    .stat-icon.users { background: rgba(139, 92, 246, 0.1); }

    .stat-icon.portfolio i { color: #6366f1; }
    .stat-icon.blog i { color: #06b6d4; }
    .stat-icon.messages i { color: #10b981; }
    .stat-icon.users i { color: #8b5cf6; }

    [data-theme="dark"] .stat-icon.portfolio { background: rgba(99, 102, 241, 0.2); }
    [data-theme="dark"] .stat-icon.blog { background: rgba(6, 182, 212, 0.2); }
    [data-theme="dark"] .stat-icon.messages { background: rgba(16, 185, 129, 0.2); }
    [data-theme="dark"] .stat-icon.users { background: rgba(139, 92, 246, 0.2); }

    .stat-badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    [data-theme="dark"] .stat-badge {
        background: rgba(16, 185, 129, 0.2);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary, #111827);
        margin-bottom: 0.25rem;
    }

    [data-theme="dark"] .stat-value {
        color: #fafafa;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary, #6b7280);
    }

    .stat-progress {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-light, #e5e7eb);
    }

    [data-theme="dark"] .stat-progress {
        border-top-color: #27272a;
    }

    .progress-text {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--text-tertiary, #9ca3af);
        margin-bottom: 0.5rem;
    }

    .progress-bar-container {
        width: 100%;
        background: var(--bg-tertiary, #f3f4f6);
        border-radius: 9999px;
        height: 0.375rem;
        overflow: hidden;
    }

    [data-theme="dark"] .progress-bar-container {
        background: #3f3f46;
    }

    .progress-bar {
        height: 0.375rem;
        border-radius: 9999px;
        transition: width 0.5s ease;
    }

    .progress-bar.primary { background: linear-gradient(90deg, #6366f1, #818cf8); }
    .progress-bar.cyan { background: linear-gradient(90deg, #06b6d4, #22d3ee); }

    /* Charts section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: var(--bg-secondary, #ffffff);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid var(--border-light, #e5e7eb);
    }

    [data-theme="dark"] .chart-card {
        background: #1f1f24;
        border-color: #27272a;
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary, #111827);
    }

    [data-theme="dark"] .chart-title {
        color: #fafafa;
    }

    .chart-select {
        font-size: 0.875rem;
        border: 1px solid var(--border-light, #e5e7eb);
        border-radius: 0.5rem;
        padding: 0.375rem 0.75rem;
        background: var(--bg-secondary, #ffffff);
        color: var(--text-primary, #111827);
        cursor: pointer;
    }

    [data-theme="dark"] .chart-select {
        background: #27272a;
        border-color: #3f3f46;
        color: #fafafa;
    }

    /* Actions grid */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: var(--bg-secondary, #ffffff);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid var(--border-light, #e5e7eb);
    }

    [data-theme="dark"] .action-card {
        background: #1f1f24;
        border-color: #27272a;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }

    .action-item:hover {
        background: var(--bg-hover, #f3f4f6);
    }

    [data-theme="dark"] .action-item:hover {
        background: #27272a;
    }

    .action-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .action-item:hover .action-icon {
        transform: scale(1.1);
    }

    .action-icon.portfolio { background: rgba(99, 102, 241, 0.1); }
    .action-icon.blog { background: rgba(6, 182, 212, 0.1); }
    .action-icon.users { background: rgba(139, 92, 246, 0.1); }
    .action-icon.services { background: rgba(245, 158, 11, 0.1); }

    .action-icon.portfolio i { color: #6366f1; }
    .action-icon.blog i { color: #06b6d4; }
    .action-icon.users i { color: #8b5cf6; }
    .action-icon.services i { color: #f59e0b; }

    .action-text p {
        font-weight: 500;
        color: var(--text-primary, #111827);
        margin-bottom: 0.125rem;
    }

    [data-theme="dark"] .action-text p {
        color: #fafafa;
    }

    .action-text small {
        font-size: 0.75rem;
        color: var(--text-secondary, #6b7280);
    }

    /* Lists */
    .list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .list-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary, #111827);
    }

    [data-theme="dark"] .list-title {
        color: #fafafa;
    }

    .list-link {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6366f1;
        text-decoration: none;
    }

    .list-link:hover {
        color: #4f46e5;
    }

    .recent-list {
        max-height: 24rem;
        overflow-y: auto;
    }

    .recent-item {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-light, #e5e7eb);
        transition: background 0.2s ease;
    }

    .recent-item:hover {
        background: var(--bg-hover, #f9fafb);
    }

    [data-theme="dark"] .recent-item {
        border-bottom-color: #27272a;
    }

    [data-theme="dark"] .recent-item:hover {
        background: #27272a;
    }

    .recent-content {
        display: flex;
        align-items: start;
        justify-content: space-between;
    }

    .recent-info {
        flex: 1;
        min-width: 0;
    }

    .recent-title {
        font-weight: 500;
        color: var(--text-primary, #111827);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    [data-theme="dark"] .recent-title {
        color: #fafafa;
    }

    .recent-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
        flex-wrap: wrap;
    }

    .recent-date {
        font-size: 0.75rem;
        color: var(--text-tertiary, #9ca3af);
    }

    .status-badge {
        font-size: 0.7rem;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
    }

    .status-badge.published {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge.draft {
        background: var(--bg-tertiary, #f3f4f6);
        color: var(--text-secondary, #6b7280);
    }

    [data-theme="dark"] .status-badge.draft {
        background: #3f3f46;
        color: #a1a1aa;
    }

    .recent-action {
        color: var(--text-tertiary, #9ca3af);
        transition: color 0.2s ease;
        margin-left: 0.75rem;
    }

    .recent-action:hover {
        color: #6366f1;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--text-tertiary, #9ca3af);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    /* User avatar in recent users */
    .user-avatar-small {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 500;
        color: var(--text-primary, #111827);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    [data-theme="dark"] .user-name {
        color: #fafafa;
    }

    .user-email {
        font-size: 0.75rem;
        color: var(--text-secondary, #6b7280);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-date {
        font-size: 0.75rem;
        color: var(--text-tertiary, #9ca3af);
        text-align: right;
    }

    .admin-badge {
        font-size: 0.7rem;
        color: #a855f7;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-stats-grid {
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
        .dashboard-stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .actions-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .chart-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .chart-select {
            width: 100%;
        }
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dashboard-stat-card,
    .chart-card,
    .action-card {
        animation: slideIn 0.3s ease forwards;
    }

    .dashboard-stat-card:nth-child(1) { animation-delay: 0.05s; }
    .dashboard-stat-card:nth-child(2) { animation-delay: 0.1s; }
    .dashboard-stat-card:nth-child(3) { animation-delay: 0.15s; }
    .dashboard-stat-card:nth-child(4) { animation-delay: 0.2s; }

    /* Welcome banner */
    .welcome-banner {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
        border-radius: 1rem;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        animation: slideIn 0.3s ease forwards;
    }

    @media (max-width: 768px) {
        .welcome-banner {
            padding: 1rem 1.25rem;
        }
    }
</style>

<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    Bonjour, {{ auth()->user()->name ?? 'Administrateur' }} 👋
                </h2>
                <p class="text-white/80 text-sm md:text-base">
                    Voici ce qui se passe sur votre plateforme aujourd'hui.
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium text-white">
                    {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="dashboard-stats-grid">
        <!-- Portfolio Card -->
        <div class="dashboard-stat-card">
            <div class="stat-header">
                <div class="stat-icon portfolio">
                    <i class="fas fa-briefcase"></i>
                </div>
                <span class="stat-badge">
                    <i class="fas fa-arrow-up text-xs"></i> +{{ $newPortfolio ?? 0 }}
                </span>
            </div>
            <div class="stat-value">{{ $totalPortfolio ?? 0 }}</div>
            <div class="stat-label">Projets Portfolio</div>
            <div class="stat-progress">
                <div class="progress-text">
                    <span>Publiés: {{ $publishedPortfolio ?? 0 }}</span>
                    <span>Brouillons: {{ ($totalPortfolio ?? 0) - ($publishedPortfolio ?? 0) }}</span>
                </div>
                <div class="progress-bar-container">
                    @php $publishedPercent = ($totalPortfolio ?? 0) > 0 ? (($publishedPortfolio ?? 0) / ($totalPortfolio ?? 0)) * 100 : 0 @endphp
                    <div class="progress-bar primary" style="width: {{ $publishedPercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Blog Card -->
        <div class="dashboard-stat-card">
            <div class="stat-header">
                <div class="stat-icon blog">
                    <i class="fas fa-newspaper"></i>
                </div>
                <span class="stat-badge">
                    <i class="fas fa-arrow-up text-xs"></i> +{{ $newBlogPosts ?? 0 }}
                </span>
            </div>
            <div class="stat-value">{{ $totalBlogPosts ?? 0 }}</div>
            <div class="stat-label">Articles Blog</div>
            <div class="stat-progress">
                <div class="progress-text">
                    <span>Publiés: {{ $publishedPosts ?? 0 }}</span>
                    <span>Brouillons: {{ ($totalBlogPosts ?? 0) - ($publishedPosts ?? 0) }}</span>
                </div>
                <div class="progress-bar-container">
                    @php $publishedPercent = ($totalBlogPosts ?? 0) > 0 ? (($publishedPosts ?? 0) / ($totalBlogPosts ?? 0)) * 100 : 0 @endphp
                    <div class="progress-bar cyan" style="width: {{ $publishedPercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="dashboard-stat-card">
            <div class="stat-header">
                <div class="stat-icon messages">
                    <i class="fas fa-envelope"></i>
                </div>
                @if(($unreadContacts ?? 0) > 0)
                    <span class="stat-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-circle text-xs"></i> {{ $unreadContacts }} non lus
                    </span>
                @endif
            </div>
            <div class="stat-value">{{ $totalContacts ?? 0 }}</div>
            <div class="stat-label">Messages reçus</div>
            <div class="stat-progress">
                <div class="progress-text">
                    <span>Lus: {{ ($totalContacts ?? 0) - ($unreadContacts ?? 0) }}</span>
                    <span>Non lus: {{ $unreadContacts ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="dashboard-stat-card">
            <div class="stat-header">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <span class="stat-badge">
                    <i class="fas fa-user-plus text-xs"></i> +{{ $newUsers ?? 0 }}
                </span>
            </div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Utilisateurs inscrits</div>
            <div class="stat-progress">
                <div class="progress-text">
                    <span>Admins: {{ $adminCount ?? 0 }}</span>
                    <span>Tickets ouverts: {{ $openTickets ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Monthly Activity Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Activité mensuelle</h3>
                <select id="chartType" class="chart-select">
                    <option value="both">Les deux</option>
                    <option value="portfolio">Projets uniquement</option>
                    <option value="blog">Articles uniquement</option>
                </select>
            </div>
            <canvas id="activityChart" height="250"></canvas>
        </div>

        <!-- Portfolio Categories Chart -->
        <div class="chart-card">
            <h3 class="chart-title" style="margin-bottom: 1.5rem;">Catégories de projets</h3>
            <canvas id="categoryChart" height="250"></canvas>
        </div>
    </div>

    <!-- Quick Actions & Recent Items -->
    <div class="actions-grid">
        <!-- Quick Actions -->
        <div class="action-card">
            <div class="list-header">
                <h3 class="list-title">Actions rapides</h3>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.portfolio.create') }}" class="action-item">
                    <div class="action-icon portfolio">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="action-text">
                        <p>Ajouter un projet</p>
                        <small>Nouveau projet portfolio</small>
                    </div>
                </a>
                <a href="{{ route('admin.blog.create') }}" class="action-item">
                    <div class="action-icon blog">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div class="action-text">
                        <p>Nouvel article</p>
                        <small>Rédiger un article de blog</small>
                    </div>
                </a>
                <a href="{{ route('admin.users.create') }}" class="action-item">
                    <div class="action-icon users">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-text">
                        <p>Ajouter un utilisateur</p>
                        <small>Nouvel administrateur</small>
                    </div>
                </a>
                <a href="{{ route('admin.services.index') }}" class="action-item">
                    <div class="action-icon services">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="action-text">
                        <p>Gérer les services</p>
                        <small>Ajouter ou modifier un service</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Portfolio Items -->
        <div class="action-card">
            <div class="list-header">
                <h3 class="list-title">Derniers projets</h3>
                <a href="{{ route('admin.portfolio.index') }}" class="list-link">Voir tout →</a>
            </div>
            <div class="recent-list">
                @forelse(($recentPortfolio ?? []) as $project)
                <div class="recent-item">
                    <div class="recent-content">
                        <div class="recent-info">
                            <p class="recent-title">{{ $project->title }}</p>
                            <div class="recent-meta">
                                <span class="recent-date">
                                    <i class="far fa-calendar-alt"></i> {{ $project->created_at->diffForHumans() }}
                                </span>
                                @if($project->is_active ?? false)
                                    <span class="status-badge published">Publié</span>
                                @else
                                    <span class="status-badge draft">Brouillon</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.portfolio.edit', $project) }}" class="recent-action">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Aucun projet pour le moment</p>
                    <a href="{{ route('admin.portfolio.create') }}" class="list-link" style="display: inline-block; margin-top: 0.5rem;">
                        Créer votre premier projet →
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="action-card">
            <div class="list-header">
                <h3 class="list-title">Derniers messages</h3>
                <a href="{{ route('admin.contacts.index') }}" class="list-link">Voir tout →</a>
            </div>
            <div class="recent-list">
                @forelse(($recentContacts ?? []) as $contact)
                <div class="recent-item">
                    <div class="recent-content">
                        <div class="recent-info">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                <p class="recent-title" style="font-weight: 600;">{{ $contact->name }}</p>
                                @if(!($contact->is_read ?? false))
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </div>
                            <p class="recent-title" style="font-weight: normal; font-size: 0.875rem; color: var(--text-secondary);">
                                {{ $contact->subject ?? 'Nouveau message' }}
                            </p>
                            <p class="recent-date" style="margin-top: 0.25rem;">{{ $contact->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="recent-action">
                            <i class="fas fa-envelope-open-text"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun message</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Blog Posts & Users -->
    <div class="actions-grid">
        <!-- Recent Blog Posts -->
        <div class="action-card">
            <div class="list-header">
                <h3 class="list-title">Articles récents</h3>
                <a href="{{ route('admin.blog.index') }}" class="list-link">Voir tout →</a>
            </div>
            <div>
                @forelse(($recentPosts ?? []) as $post)
                <div class="recent-item">
                    <div class="recent-content" style="gap: 0.75rem;">
                        @if($post->featured_image ?? false)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="" style="width: 3rem; height: 3rem; border-radius: 0.5rem; object-fit: cover; flex-shrink: 0;">
                        @else
                            <div style="width: 3rem; height: 3rem; border-radius: 0.5rem; background: linear-gradient(135deg, #06b6d4, #3b82f6); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                        @endif
                        <div class="recent-info">
                            <p class="recent-title">{{ $post->title }}</p>
                            <div class="recent-meta">
                                <span class="recent-date">{{ $post->created_at->diffForHumans() }}</span>
                                @if($post->is_published ?? false)
                                    <span class="status-badge published">Publié</span>
                                @else
                                    <span class="status-badge draft">Brouillon</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.blog.edit', $post) }}" class="recent-action">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <p>Aucun article pour le moment</p>
                    <a href="{{ route('admin.blog.create') }}" class="list-link" style="display: inline-block; margin-top: 0.5rem;">
                        Écrire un article →
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="action-card">
            <div class="list-header">
                <h3 class="list-title">Nouveaux utilisateurs</h3>
                <a href="{{ route('admin.users.index') }}" class="list-link">Voir tout →</a>
            </div>
            <div>
                @forelse(($recentUsers ?? []) as $user)
                <div class="recent-item">
                    <div class="recent-content" style="gap: 0.75rem;">
                        <div class="user-avatar-small">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="user-info">
                            <p class="user-name">{{ $user->name }}</p>
                            <p class="user-email">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="user-date">{{ $user->created_at->diffForHumans() }}</p>
                            @if($user->is_admin ?? false)
                                <p class="admin-badge" style="text-align: right;">Admin</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Aucun utilisateur pour le moment</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des données PHP vers JavaScript
    var portfolioData = {{ json_encode($chartPortfolio ?? array_fill(0, 12, 0)) }};
    var blogData = {{ json_encode($chartBlog ?? array_fill(0, 12, 0)) }};
    var labelsData = {{ json_encode($chartLabels ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']) }};
    var categoryLabelsData = {{ json_encode($categoryLabels ?? ['Site Vitrine', 'E-commerce', 'Application Web', 'Maintenance', 'Autres']) }};
    var categoryDataData = {{ json_encode($categoryData ?? [0, 0, 0, 0, 0]) }};

    // ===== ACTIVITY CHART =====
    var ctx = document.getElementById('activityChart').getContext('2d');
    var currentChart = null;

    function getChartColors() {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            text: isDark ? '#fafafa' : '#111827',
            textSecondary: isDark ? '#a1a1aa' : '#6b7280',
            grid: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
        };
    }

    function createChart(type) {
        if (currentChart) {
            currentChart.destroy();
        }

        var colors = getChartColors();
        var datasets = [];

        if (type === 'portfolio' || type === 'both') {
            datasets.push({
                label: 'Projets',
                data: portfolioData,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#6366f1',
                pointRadius: 3,
                pointHoverRadius: 5
            });
        }

        if (type === 'blog' || type === 'both') {
            datasets.push({
                label: 'Articles',
                data: blogData,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#06b6d4',
                pointRadius: 3,
                pointHoverRadius: 5
            });
        }

        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.text,
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: colors.text === '#111827' ? '#ffffff' : '#1f1f24',
                        titleColor: colors.text,
                        bodyColor: colors.textSecondary,
                        borderColor: colors.grid,
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: colors.textSecondary
                        },
                        grid: {
                            color: colors.grid,
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.textSecondary
                        },
                        grid: {
                            color: colors.grid,
                            drawBorder: false,
                            display: false
                        }
                    }
                }
            }
        });
    }

    createChart('both');

    var chartTypeSelect = document.getElementById('chartType');
    if (chartTypeSelect) {
        chartTypeSelect.addEventListener('change', function() {
            createChart(this.value);
        });
    }

    // ===== CATEGORY CHART =====
    var categoryCtx = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = null;

    function createCategoryChart() {
        var colors = getChartColors();

        if (categoryChart) {
            categoryChart.destroy();
        }

        categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabelsData,
                datasets: [{
                    data: categoryDataData,
                    backgroundColor: [
                        '#6366f1',
                        '#06b6d4',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: colors.text,
                            font: { size: 11 },
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.text === '#111827' ? '#ffffff' : '#1f1f24',
                        titleColor: colors.text,
                        bodyColor: colors.textSecondary,
                        borderColor: colors.grid,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    createCategoryChart();

    // Theme change handler
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'data-theme') {
                var selectValue = chartTypeSelect ? chartTypeSelect.value : 'both';
                createChart(selectValue);
                createCategoryChart();
            }
        });
    });

    observer.observe(document.documentElement, { attributes: true });
});
</script>
@endpush
