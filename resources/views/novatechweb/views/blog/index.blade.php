@extends('novatechweb.views.layouts.app')

@section('title')
    Blog & Actualités - {{ $company->name ?? 'Nova Tech Bénin' }}
@endsection

@section('content')

<!-- ========== BLOG PAGE ========== -->
<div id="blog" class="blog-page">
    <div class="container">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="blog-hero py-5">
                    <span class="label">Notre blog</span>
                    <h1 class="display-5 fw-bold mb-3">
                        Actualités & <span class="accent">Conseils</span>
                    </h1>
                    <p class="lead text-muted mb-4">
                        Découvrez nos conseils, tutoriels et actualités sur le développement web,
                        l'innovation digitale et les tendances technologiques.
                    </p>
                    <div class="hero-stats d-flex justify-content-center gap-4">
                        <div class="stat-item text-center">
                            <div class="stat-number">{{ $totalPosts ?? $posts->total() }}</div>
                            <div class="stat-label">Articles</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number">{{ $company->experience_years ?? 2 }}+</div>
                            <div class="stat-label">Ans d'expertise</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Contenu pratique</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="blog-sidebar">
                    <!-- Search Box -->
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-title">Recherche</h5>
                        <form action="{{ route('blog.index') }}" method="GET" class="mt-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Rechercher..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-title">Catégories</h5>
                        <div class="categories-list mt-3">
                            <a href="{{ route('blog.index') }}"
                               class="category-item {{ !request('category') ? 'active' : '' }}">
                                <span>Toutes les catégories</span>
                                <span class="badge">{{ $totalPosts ?? $posts->total() }}</span>
                            </a>
                            @php
                                $categoriesCount = [];
                                if(isset($posts) && $posts->count() > 0) {
                                    foreach($posts as $post) {
                                        if($post->category) {
                                            if(!isset($categoriesCount[$post->category])) {
                                                $categoriesCount[$post->category] = 0;
                                            }
                                            $categoriesCount[$post->category]++;
                                        }
                                    }
                                }
                            @endphp
                            @foreach($categoriesCount as $catName => $catCount)
                            <a href="{{ route('blog.index', ['category' => Str::slug($catName)]) }}"
                               class="category-item {{ request('category') == Str::slug($catName) ? 'active' : '' }}">
                                <span>{{ $catName }}</span>
                                <span class="badge">{{ $catCount }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Posts -->
                    @php
                        $popularPosts = isset($posts) && $posts->count() > 0 ? $posts->sortByDesc(function($post) {
                            return $post->views ?? 0;
                        })->take(3) : collect();
                    @endphp
                    @if($popularPosts->count() > 0)
                    <div class="sidebar-card mb-4">
                        <h5 class="sidebar-title">
                            <i class="fa fa-fire text-warning me-1"></i>Articles populaires
                        </h5>
                        <div class="popular-posts mt-3">
                            @foreach($popularPosts as $popular)
                            <div class="popular-post mb-3">
                                <div class="d-flex">
                                    <div class="popular-thumb me-2">
                                        @php
                                            $popularImage = asset('assets/images/blog-default.jpg');
                                            if(isset($popular) && $popular->image) {
                                                if(filter_var($popular->image, FILTER_VALIDATE_URL)) {
                                                    $popularImage = $popular->image;
                                                } else {
                                                    $cleanImage = str_replace(['storage/', 'blog/'], '', $popular->image);
                                                    $popularImage = asset('storage/blog/' . $cleanImage);
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $popularImage }}"
                                             alt="{{ $popular->title }}"
                                             width="60" height="60"
                                             onerror="this.src='{{ asset('assets/images/blog-default.jpg') }}'">
                                    </div>
                                    <div class="popular-content">
                                        <h6 class="mb-1">
                                            <a href="{{ route('blog.show', $popular->slug) }}">
                                                {{ Str::limit($popular->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="text-muted small">
                                            <i class="fa fa-calendar me-1"></i>
                                            {{ $popular->published_at ? $popular->published_at->format('d/m/Y') : ($popular->created_at ? $popular->created_at->format('d/m/Y') : 'Date non disponible') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Articles List -->
            <div class="col-lg-9">
                <!-- Filter Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Tous les articles</h4>
                        <p class="text-muted mb-0 small">
                            {{ $posts->total() }} article{{ $posts->total() > 1 ? 's' : '' }}
                            @if(request('search'))
                                pour "{{ request('search') }}"
                            @endif
                            @if(request('category'))
                                dans la catégorie "{{ request('category') }}"
                            @endif
                        </p>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                <i class="fa fa-sort me-1"></i>Trier
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Plus récents</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}">Plus anciens</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if($posts->count() > 0)
                    <!-- Featured Post (only on first page without filters) -->
                    @if($posts->currentPage() == 1 && !request('search') && !request('category') && $posts->count() > 0)
                        @php
                            $featured = $posts->where('is_featured', true)->first() ?? $posts->first();
                            $featuredImage = asset('assets/images/blog-default.jpg');
                            if(isset($featured) && $featured->image) {
                                if(filter_var($featured->image, FILTER_VALIDATE_URL)) {
                                    $featuredImage = $featured->image;
                                } else {
                                    $cleanImage = str_replace(['storage/', 'blog/'], '', $featured->image);
                                    $featuredImage = asset('storage/blog/' . $cleanImage);
                                }
                            }
                        @endphp
                        @if($featured)
                        <div class="featured-article mb-5">
                            <div class="card border-0 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <div class="featured-image">
                                            <img src="{{ $featuredImage }}"
                                                 alt="{{ $featured->title }}"
                                                 class="img-fluid rounded-start w-100"
                                                 style="height: 100%; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/blog-default.jpg') }}'">
                                            @if($featured->category)
                                            <div class="featured-badge">
                                                <span class="badge bg-primary">{{ $featured->category }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-2">
                                                @if($featured->is_featured)
                                                <span class="badge bg-warning text-dark me-2">
                                                    <i class="fa fa-star me-1"></i>À la une
                                                </span>
                                                @endif
                                                @if($featured->category)
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $featured->category }}
                                                </span>
                                                @endif
                                            </div>
                                            <h2 class="card-title h4 mb-3">
                                                <a href="{{ route('blog.show', $featured->slug) }}" class="text-dark text-decoration-none">
                                                    {{ $featured->title }}
                                                </a>
                                            </h2>
                                            <p class="card-text text-muted mb-4">
                                                {{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 180) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="author-info d-flex align-items-center">
                                                    <div class="author-avatar me-2">
                                                        <div class="avatar-circle">
                                                            {{ substr($company->name ?? 'NT', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="author-name small fw-bold">{{ $company->name ?? 'Nova Tech' }}</div>
                                                        <div class="post-date text-muted small">
                                                            <i class="fa fa-calendar me-1"></i>
                                                            {{ $featured->published_at ? $featured->published_at->format('d F Y') : ($featured->created_at ? $featured->created_at->format('d F Y') : 'Date non disponible') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('blog.show', $featured->slug) }}"
                                                   class="btn btn-primary btn-sm">
                                                    Lire l'article
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Regular Articles Grid -->
                        <div class="row">
                            @foreach($posts->skip(1) as $post)
                            @php
                                $postImage = asset('assets/images/blog-default.jpg');
                                if(isset($post) && $post->image) {
                                    if(filter_var($post->image, FILTER_VALIDATE_URL)) {
                                        $postImage = $post->image;
                                    } else {
                                        $cleanImage = str_replace(['storage/', 'blog/'], '', $post->image);
                                        $postImage = asset('storage/blog/' . $cleanImage);
                                    }
                                }
                            @endphp
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="article-card card h-100 border-0 shadow-sm">
                                    <div class="article-image">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            <img src="{{ $postImage }}"
                                                 alt="{{ $post->title }}"
                                                 class="card-img-top"
                                                 style="height: 200px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/blog-default.jpg') }}'">
                                        </a>
                                        @if($post->category)
                                        <div class="category-badge">
                                            <span class="badge bg-dark">
                                                {{ $post->category }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="article-meta mb-2">
                                            <div class="text-muted small">
                                                <i class="fa fa-calendar me-1"></i>
                                                {{ $post->published_at ? $post->published_at->format('d M Y') : ($post->created_at ? $post->created_at->format('d M Y') : 'Date non disponible') }}
                                                <span class="mx-2">•</span>
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $post->reading_time ?? '3 min' }}
                                            </div>
                                        </div>

                                        <h5 class="article-title">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($post->title, 65) }}
                                            </a>
                                        </h5>

                                        <p class="article-excerpt text-muted mb-3">
                                            {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                                        </p>

                                        <div class="article-footer d-flex justify-content-between align-items-center">
                                            <div class="author d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-circle-sm">
                                                        {{ substr($company->name ?? 'NT', 0, 1) }}
                                                    </div>
                                                </div>
                                                <span class="author-name small text-muted">{{ $company->name ?? 'Nova Tech' }}</span>
                                            </div>
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                               class="read-more text-primary small text-decoration-none">
                                                Lire plus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- All Articles Grid -->
                        <div class="row">
                            @foreach($posts as $post)
                            @php
                                $postImage = asset('assets/images/blog-default.jpg');
                                if(isset($post) && $post->image) {
                                    if(filter_var($post->image, FILTER_VALIDATE_URL)) {
                                        $postImage = $post->image;
                                    } else {
                                        $cleanImage = str_replace(['storage/', 'blog/'], '', $post->image);
                                        $postImage = asset('storage/blog/' . $cleanImage);
                                    }
                                }
                            @endphp
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="article-card card h-100 border-0 shadow-sm">
                                    <div class="article-image">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            <img src="{{ $postImage }}"
                                                 alt="{{ $post->title }}"
                                                 class="card-img-top"
                                                 style="height: 200px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/blog-default.jpg') }}'">
                                        </a>
                                        @if($post->category)
                                        <div class="category-badge">
                                            <span class="badge bg-dark">
                                                {{ $post->category }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="article-meta mb-2">
                                            <div class="text-muted small">
                                                <i class="fa fa-calendar me-1"></i>
                                                {{ $post->published_at ? $post->published_at->format('d M Y') : ($post->created_at ? $post->created_at->format('d M Y') : 'Date non disponible') }}
                                                <span class="mx-2">•</span>
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $post->reading_time ?? '3 min' }}
                                            </div>
                                        </div>

                                        <h5 class="article-title">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($post->title, 65) }}
                                            </a>
                                        </h5>

                                        <p class="article-excerpt text-muted mb-3">
                                            {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                                        </p>

                                        <div class="article-footer d-flex justify-content-between align-items-center">
                                            <div class="author d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-circle-sm">
                                                        {{ substr($company->name ?? 'NT', 0, 1) }}
                                                    </div>
                                                </div>
                                                <span class="author-name small text-muted">{{ $company->name ?? 'Nova Tech' }}</span>
                                            </div>
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                               class="read-more text-primary small text-decoration-none">
                                                Lire plus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if($posts->hasPages())
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    @endif

                @else
                    <!-- No Results -->
                    <div class="no-results text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon mb-3">
                                <i class="fa fa-newspaper-o fa-3x text-muted"></i>
                            </div>
                            <h3 class="mb-3">Aucun article trouvé</h3>
                            <p class="text-muted mb-4">
                                @if(request('search'))
                                    Aucun résultat pour "<strong>{{ request('search') }}</strong>".
                                    Essayez d'autres mots-clés.
                                @elseif(request('category'))
                                    Aucun article dans cette catégorie pour le moment.
                                @else
                                    Nous préparons actuellement du nouveau contenu.
                                    Revenez bientôt pour découvrir nos derniers articles.
                                @endif
                            </p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                @if(request('search') || request('category'))
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-list me-1"></i>Voir tous les articles
                                </a>
                                @endif
                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-home me-1"></i>Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ========== BLOG PAGE STYLES ========== */

.blog-page {
    padding: 100px 0 50px;
    background: var(--bg-light, #f8f9fa);
    min-height: 100vh;
}

/* Hero Section */
.blog-hero {
    padding: 2rem 0;
}

.blog-hero .label {
    display: inline-block;
    background: rgba(99,102,241,0.1);
    color: var(--primary, #6366f1);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
}

.blog-hero h1 {
    font-size: 42px;
    font-weight: 800;
    color: var(--text-dark, #1a1a2e);
}

.blog-hero h1 .accent {
    color: var(--accent, #e11d48);
}

.hero-stats .stat-item {
    padding: 0 1.5rem;
}

.hero-stats .stat-item:not(:last-child) {
    border-right: 1px solid var(--border-light, #e5e7eb);
}

.stat-number {
    font-size: 1.8rem;
    line-height: 1;
    font-weight: 800;
    color: var(--primary, #6366f1);
}

.stat-label {
    font-size: 0.85rem;
    margin-top: 0.25rem;
    color: var(--text-gray, #6b7280);
}

/* Sidebar */
.blog-sidebar {
    position: sticky;
    top: 100px;
}

.sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid var(--border-light, #e5e7eb);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.sidebar-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
}

.sidebar-title {
    font-weight: 700;
    color: var(--text-dark, #1a1a2e);
    font-size: 1rem;
    margin-bottom: 1rem;
    position: relative;
    padding-bottom: 0.5rem;
}

.sidebar-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: var(--primary, #6366f1);
    border-radius: 2px;
}

/* Categories */
.categories-list .category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    color: var(--text-gray, #6b7280);
    text-decoration: none;
    border-bottom: 1px solid var(--border-light, #e5e7eb);
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.categories-list .category-item:last-child {
    border-bottom: none;
}

.categories-list .category-item:hover {
    color: var(--primary, #6366f1);
    padding-left: 0.75rem;
    background: rgba(99,102,241,0.03);
}

.categories-list .category-item.active {
    color: var(--primary, #6366f1);
    font-weight: 600;
    background: rgba(99,102,241,0.08);
    border-radius: 8px;
}

.categories-list .badge {
    background: rgba(99,102,241,0.1);
    color: var(--primary, #6366f1);
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
}

/* Popular Posts */
.popular-posts .popular-post {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light, #e5e7eb);
}

.popular-posts .popular-post:last-child {
    border-bottom: none;
}

.popular-thumb img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.popular-content h6 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.popular-content h6 a {
    color: var(--text-dark, #1a1a2e);
    text-decoration: none;
    font-weight: 600;
}

.popular-content h6 a:hover {
    color: var(--primary, #6366f1);
}

/* Featured Article */
.featured-article .card {
    border-radius: 24px;
    overflow: hidden;
    border: none;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
}

.featured-image {
    position: relative;
    height: 100%;
    min-height: 250px;
}

.featured-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.featured-badge {
    position: absolute;
    top: 15px;
    left: 15px;
}

.author-avatar .avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary, #6366f1), var(--accent, #e11d48));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

/* Article Cards */
.article-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.article-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(99,102,241,0.15);
}

.article-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.article-card:hover .article-image img {
    transform: scale(1.1);
}

.category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
}

.article-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.article-title a {
    color: var(--text-dark, #1a1a2e);
    text-decoration: none;
    transition: color 0.3s ease;
}

.article-title a:hover {
    color: var(--primary, #6366f1);
}

.article-excerpt {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-gray, #6b7280);
}

.avatar-circle-sm {
    width: 32px;
    height: 32px;
    background: rgba(99,102,241,0.1);
    color: var(--primary, #6366f1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 600;
}

.read-more {
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
}

.read-more:after {
    content: '→';
    margin-left: 5px;
    transition: transform 0.3s ease;
}

.read-more:hover:after {
    transform: translateX(3px);
}

/* Pagination */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border: 2px solid var(--border-light, #e5e7eb);
    color: var(--primary, #6366f1);
    margin: 0 3px;
    border-radius: 10px;
    padding: 0.5rem 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-item.active .page-link {
    background: var(--primary, #6366f1);
    border-color: var(--primary, #6366f1);
    color: white;
}

.page-link:hover {
    background-color: rgba(99,102,241,0.1);
    border-color: var(--primary, #6366f1);
    color: var(--primary, #6366f1);
    transform: translateY(-2px);
}

/* Empty State */
.empty-icon {
    color: var(--text-gray, #6b7280);
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 992px) {
    .blog-page {
        padding: 80px 0 30px;
    }

    .hero-stats {
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .hero-stats .stat-item:not(:last-child) {
        border-right: none;
    }

    .blog-hero h1 {
        font-size: 32px;
    }
}

@media (max-width: 768px) {
    .blog-hero {
        padding: 1rem 0;
    }

    .blog-hero h1 {
        font-size: 28px;
    }

    .featured-image {
        height: 220px;
    }
}

@media (max-width: 576px) {
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }

    .stat-item {
        padding: 0.5rem 0;
    }

    .article-image {
        height: 180px;
    }
}
</style>
@endpush
