@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $portfolio->title }} - {{ $company->name ?? 'Nova Tech Bénin' }}
@endsection

@section('content')

<!-- ========== HERO SECTION ========== -->
<section class="portfolio-hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="hero-content" data-aos="fade-up">
                    <span class="project-category">
                        @php
                            $categoryNames = [
                                'site-vitrine' => 'Site Vitrine',
                                'e-commerce' => 'E-commerce',
                                'application-web' => 'Application Web',
                                'maintenance' => 'Maintenance',
                                'optimisation' => 'Optimisation',
                                'autre' => 'Autre'
                            ];
                        @endphp
                        {{ $categoryNames[$portfolio->category] ?? 'Projet Web' }}
                    </span>
                    <h1 class="project-title">{{ $portfolio->title }}</h1>
                    <p class="project-subtitle">{{ Str::limit($portfolio->description ?? 'Découvrez ce projet réalisé par Nova Tech Bénin', 150) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== MAIN CONTENT ========== -->
<section class="portfolio-content">
    <div class="container">
        <!-- Project Meta Bar -->
        <div class="row">
            <div class="col-lg-12">
                <div class="project-meta-bar" data-aos="fade-up">
                    <div class="meta-item-bar">
                        <i class="fa fa-user-circle"></i>
                        <div>
                            <span class="meta-label">Client</span>
                            <strong>{{ $portfolio->client ?? 'Nova Tech Bénin' }}</strong>
                        </div>
                    </div>
                    <div class="meta-item-bar">
                        <i class="fa fa-calendar"></i>
                        <div>
                            <span class="meta-label">Date</span>
                            <strong>{{ $portfolio->date ? $portfolio->date->format('M Y') : date('M Y') }}</strong>
                        </div>
                    </div>
                    <div class="meta-item-bar">
                        <i class="fa fa-tags"></i>
                        <div>
                            <span class="meta-label">Catégorie</span>
                            <strong>{{ $categoryNames[$portfolio->category] ?? 'Projet Web' }}</strong>
                        </div>
                    </div>
                    @if($portfolio->url)
                    <a href="{{ $portfolio->url }}" target="_blank" class="btn-visit-project" rel="noopener noreferrer">
                        <i class="fa fa-external-link"></i> Voir le projet
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <div class="row">
            <div class="col-lg-12">
                <div class="featured-showcase" data-aos="zoom-in" data-aos-delay="200">
                    <div class="showcase-inner">
                        @php
                            $imagePath = $portfolio->image ?? '';
                            if (!empty($imagePath)) {
                                if (!str_starts_with($imagePath, 'portfolio/') && !str_starts_with($imagePath, 'storage/')) {
                                    $imagePath = 'portfolio/' . $imagePath;
                                }
                                $imagePath = str_replace('storage/', '', $imagePath);
                                $imageUrl = asset('storage/' . $imagePath);
                            } else {
                                $imageUrl = asset('assets/images/portfolio-placeholder.jpg');
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $portfolio->title }}" class="featured-image" onerror="this.src='{{ asset('assets/images/portfolio-placeholder.jpg') }}'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="row content-grid">
            <!-- Main Column -->
            <div class="col-lg-8">
                <!-- Project Overview -->
                <div class="content-card" data-aos="fade-up">
                    <div class="card-header">
                        <h2 class="card-title">À propos du projet</h2>
                        <div class="title-underline"></div>
                    </div>
                    <div class="card-body">
                        <p class="lead-text">{{ $portfolio->description ?? 'Aucune description disponible pour ce projet.' }}</p>
                    </div>
                </div>

                <!-- Travaux réalisés -->
                @if($portfolio->work_done)
                <div class="solution-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="solution-icon">
                        <i class="fa fa-lightbulb-o"></i>
                    </div>
                    <div class="solution-content">
                        <h3 class="solution-title">Notre Solution</h3>
                        <div class="solution-text">{!! nl2br(e($portfolio->work_done)) !!}</div>
                    </div>
                </div>
                @endif

                <!-- Contenu détaillé -->
                @if($portfolio->content)
                <div class="content-card rich-content" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body">
                        {!! $portfolio->content !!}
                    </div>
                </div>
                @endif

                <!-- Technologies Used -->
                @php
                    $technologies = [];
                    if (!empty($portfolio->technologies)) {
                        if (is_array($portfolio->technologies)) {
                            $technologies = $portfolio->technologies;
                        } elseif (is_string($portfolio->technologies)) {
                            $technologies = json_decode($portfolio->technologies, true) ?? [];
                        }
                    }
                @endphp

                @if(!empty($technologies) && count($technologies) > 0)
                <div class="content-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header">
                        <h2 class="card-title">Technologies utilisées</h2>
                        <div class="title-underline"></div>
                    </div>
                    <div class="card-body">
                        <div class="tech-grid">
                            @foreach($technologies as $tech)
                            <div class="tech-badge">
                                <i class="fa fa-code"></i>
                                <span>{{ $tech }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-sticky">
                    <!-- CTA Card -->
                    <div class="cta-card" data-aos="fade-left">
                        <div class="cta-icon">
                            <i class="fa fa-comments"></i>
                        </div>
                        <h3 class="cta-title">Intéressé par un projet similaire ?</h3>
                        <p class="cta-text">Discutons de votre vision et créons ensemble quelque chose d'exceptionnel.</p>
                        <a href="{{ route('home') }}#contact" class="btn-cta-secondary">
                            <span>Demander un devis</span>
                            <i class="fa fa-paper-plane"></i>
                        </a>
                    </div>

                    <!-- Share Card -->
                    <div class="share-card" data-aos="fade-left" data-aos-delay="100">
                        <h4 class="share-title">Partager ce projet</h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="share-btn facebook" rel="noopener noreferrer">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $portfolio->title }}" target="_blank" class="share-btn twitter" rel="noopener noreferrer">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}" target="_blank" class="share-btn linkedin" rel="noopener noreferrer">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="https://wa.me/?text={{ $portfolio->title }}%20{{ url()->current() }}" target="_blank" class="share-btn whatsapp" rel="noopener noreferrer">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="info-card-simple" data-aos="fade-left" data-aos-delay="200">
                        <h4 class="info-card-title">Informations</h4>
                        <div class="info-list">
                            <div class="info-row">
                                <span class="info-icon-small"><i class="fa fa-calendar"></i></span>
                                <span class="info-label">Durée</span>
                                <span class="info-value">{{ $portfolio->duration ?? '2-3 semaines' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-icon-small"><i class="fa fa-users"></i></span>
                                <span class="info-label">Équipe</span>
                                <span class="info-value">{{ $portfolio->team_size ?? '2-3 personnes' }}</span>
                            </div>
                            @if($portfolio->url)
                            <div class="info-row">
                                <span class="info-icon-small"><i class="fa fa-link"></i></span>
                                <span class="info-label">Statut</span>
                                <span class="info-value">
                                    <span class="status-badge">En ligne</span>
                                </span>
                            </div>
                            @endif
                            <div class="info-row">
                                <span class="info-icon-small"><i class="fa fa-tag"></i></span>
                                <span class="info-label">Catégorie</span>
                                <span class="info-value">{{ $categoryNames[$portfolio->category] ?? 'Projet Web' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Section -->
        @php
            $galleryImages = [];
            if (!empty($portfolio->images)) {
                if (is_array($portfolio->images)) {
                    $galleryImages = $portfolio->images;
                } elseif (is_string($portfolio->images)) {
                    $galleryImages = json_decode($portfolio->images, true) ?? [];
                }
            }
        @endphp

        @if(!empty($galleryImages) && count($galleryImages) > 0)
        <div class="gallery-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-header" data-aos="fade-up">
                        <h2 class="section-title">Galerie du projet</h2>
                        <p class="section-subtitle">Découvrez les différentes facettes de ce projet</p>
                        <div class="title-underline-center"></div>
                    </div>
                </div>
            </div>

            <div class="gallery-grid">
                @foreach($galleryImages as $index => $img)
                    @php
                        $galleryPath = $img;
                        if (!empty($galleryPath)) {
                            if (!str_starts_with($galleryPath, 'portfolio/') && !str_starts_with($galleryPath, 'storage/')) {
                                $galleryPath = 'portfolio/' . $galleryPath;
                            }
                            $galleryPath = str_replace('storage/', '', $galleryPath);
                            $galleryUrl = asset('storage/' . $galleryPath);
                        } else {
                            $galleryUrl = asset('assets/images/portfolio-placeholder.jpg');
                        }
                    @endphp
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="gallery-image-wrapper">
                            <img src="{{ $galleryUrl }}" alt="{{ $portfolio->title }} - Aperçu {{ $index + 1 }}" class="gallery-image" loading="lazy" onerror="this.src='{{ asset('assets/images/portfolio-placeholder.jpg') }}'">
                            <div class="gallery-overlay">
                                <i class="fa fa-search-plus"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Navigation -->
        <div class="row">
            <div class="col-lg-12">
                <div class="project-navigation" data-aos="fade-up">
                    <a href="{{ route('portfolio.index') }}" class="btn-back">
                        <i class="fa fa-arrow-left"></i>
                        <span>Retour aux projets</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ===================================
   VARIABLES
=================================== */
:root {
    --primary-color: #6366f1;
    --secondary-color: #4f46e5;
    --accent-color: #06b6d4;
    --dark-color: #0f172a;
    --gray-color: #475569;
    --light-gray: #f8fafc;
    --border-color: #e2e8f0;
    --white: #ffffff;
    --transition: all 0.3s ease;
    --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
    --shadow-md: 0 5px 20px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
}

/* ===================================
   HERO SECTION
=================================== */
.portfolio-hero {
    position: relative;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 160px 0 80px;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    background-size: cover;
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: var(--white);
}

.project-category {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 8px 24px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.project-title {
    font-size: 48px;
    font-weight: 800;
    margin: 20px 0;
    line-height: 1.2;
    color: var(--white);
}

.project-subtitle {
    font-size: 18px;
    line-height: 1.8;
    opacity: 0.95;
    max-width: 700px;
    margin: 20px auto 0;
    color: rgba(255,255,255,0.9);
}

/* ===================================
   PROJECT META BAR
=================================== */
.project-meta-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--white);
    padding: 30px 40px;
    border-radius: 20px;
    margin: 40px 0;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    gap: 20px;
    flex-wrap: wrap;
}

.meta-item-bar {
    display: flex;
    align-items: center;
    gap: 15px;
}

.meta-item-bar i {
    font-size: 28px;
    color: var(--primary-color);
}

.meta-item-bar div {
    display: flex;
    flex-direction: column;
}

.meta-label {
    font-size: 12px;
    color: var(--gray-color);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.meta-item-bar strong {
    font-size: 16px;
    color: var(--dark-color);
    font-weight: 700;
    margin-top: 2px;
}

.btn-visit-project {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--white);
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 20px rgba(99,102,241,0.3);
}

.btn-visit-project:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(99,102,241,0.4);
    color: var(--white);
}

/* ===================================
   CONTENT SECTION
=================================== */
.portfolio-content {
    padding: 40px 0 80px;
    background: var(--light-gray);
}

.featured-showcase {
    margin-bottom: 60px;
    background: var(--white);
    border-radius: 25px;
    padding: 60px 40px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}

.showcase-inner {
    max-width: 600px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.featured-image {
    width: 100%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    display: block;
    filter: drop-shadow(0 10px 30px rgba(0,0,0,0.15));
    transition: transform 0.3s ease;
}

.featured-showcase:hover .featured-image {
    transform: scale(1.05);
}

.content-grid {
    margin-top: 20px;
}

/* ===================================
   CONTENT CARDS
=================================== */
.content-card {
    background: var(--white);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.content-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
}

.card-header {
    margin-bottom: 30px;
}

.card-title {
    font-size: 28px;
    font-weight: 800;
    color: var(--dark-color);
    margin: 0;
}

.title-underline {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    margin-top: 15px;
    border-radius: 2px;
}

.lead-text {
    font-size: 18px;
    line-height: 1.8;
    color: var(--gray-color);
    margin: 0;
}

/* ===================================
   SOLUTION SECTION
=================================== */
.solution-section {
    background: linear-gradient(135deg, rgba(99,102,241,0.05) 0%, rgba(79,70,229,0.05) 100%);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    display: flex;
    gap: 25px;
    border: 2px solid rgba(99,102,241,0.1);
}

.solution-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 30px;
    flex-shrink: 0;
    box-shadow: 0 10px 30px rgba(99,102,241,0.3);
}

.solution-content {
    flex: 1;
}

.solution-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0 0 15px;
}

.solution-text {
    font-size: 16px;
    line-height: 1.8;
    color: var(--gray-color);
    margin: 0;
}

/* ===================================
   RICH CONTENT
=================================== */
.rich-content h1, .rich-content h2, .rich-content h3 {
    color: var(--dark-color);
    font-weight: 700;
    margin: 30px 0 15px;
}

.rich-content p {
    font-size: 16px;
    line-height: 1.8;
    color: var(--gray-color);
    margin-bottom: 15px;
}

.rich-content ul, .rich-content ol {
    margin: 20px 0;
    padding-left: 25px;
}

.rich-content li {
    font-size: 16px;
    line-height: 1.8;
    color: var(--gray-color);
    margin-bottom: 10px;
}

/* ===================================
   TECHNOLOGIES
=================================== */
.tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}

.tech-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--light-gray);
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--dark-color);
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.tech-badge:hover {
    background: var(--white);
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.tech-badge i {
    color: var(--primary-color);
    font-size: 16px;
}

/* ===================================
   SIDEBAR
=================================== */
.sidebar-sticky {
    position: sticky;
    top: 100px;
}

/* CTA Card */
.cta-card {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 20px;
    padding: 35px;
    text-align: center;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(99,102,241,0.3);
}

.cta-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--white);
    font-size: 30px;
}

.cta-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--white);
    margin: 0 0 15px;
}

.cta-text {
    font-size: 15px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    margin: 0 0 25px;
}

.btn-cta-secondary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--white);
    color: var(--primary-color);
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.btn-cta-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: var(--primary-color);
}

/* Share Card */
.share-card {
    background: var(--white);
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    margin-bottom: 25px;
}

.share-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0 0 20px;
}

.share-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.share-btn {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 18px;
    transition: var(--transition);
    text-decoration: none;
}

.share-btn:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    color: var(--white);
}

.share-btn.facebook { background: #3b5998; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.linkedin { background: #0077b5; }
.share-btn.whatsapp { background: #25d366; }

/* Info Card */
.info-card-simple {
    background: var(--white);
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.info-card-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0 0 20px;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-icon-small {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(79,70,229,0.1));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 16px;
    flex-shrink: 0;
}

.info-row .info-label {
    flex: 1;
    font-size: 14px;
    color: var(--gray-color);
    font-weight: 500;
}

.info-row .info-value {
    font-size: 14px;
    color: var(--dark-color);
    font-weight: 600;
}

.status-badge {
    display: inline-block;
    background: #d4edda;
    color: #155724;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
}

/* ===================================
   GALLERY SECTION
=================================== */
.gallery-section {
    margin-top: 80px;
    padding-top: 80px;
    border-top: 1px solid var(--border-color);
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 36px;
    font-weight: 800;
    color: var(--dark-color);
    margin: 0 0 15px;
}

.section-subtitle {
    font-size: 18px;
    color: var(--gray-color);
    margin: 0 0 20px;
}

.title-underline-center {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    margin: 0 auto;
    border-radius: 2px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.gallery-item:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.gallery-image-wrapper {
    position: relative;
    overflow: hidden;
    padding-bottom: 75%;
}

.gallery-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(99,102,241,0.9), rgba(79,70,229,0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay i {
    font-size: 40px;
    color: var(--white);
}

/* ===================================
   NAVIGATION
=================================== */
.project-navigation {
    margin-top: 60px;
    padding: 40px 0;
    text-align: center;
    border-top: 1px solid var(--border-color);
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    color: var(--gray-color);
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    padding: 12px 30px;
    border-radius: 50px;
    border: 2px solid var(--border-color);
}

.btn-back:hover {
    color: var(--primary-color);
    border-color: var(--primary-color);
    transform: translateX(-5px);
}

/* ===================================
   RESPONSIVE
=================================== */
@media (max-width: 992px) {
    .sidebar-sticky {
        position: relative;
        top: 0;
        margin-top: 30px;
    }

    .project-title {
        font-size: 36px;
    }

    .solution-section {
        flex-direction: column;
        text-align: center;
    }

    .solution-icon {
        margin: 0 auto;
    }

    .project-meta-bar {
        flex-direction: column;
        align-items: flex-start;
    }

    .btn-visit-project {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .portfolio-hero {
        padding: 140px 0 60px;
    }

    .project-title {
        font-size: 28px;
    }

    .project-subtitle {
        font-size: 16px;
    }

    .featured-showcase {
        min-height: 300px;
        padding: 40px 20px;
    }

    .showcase-inner {
        max-width: 100%;
    }

    .featured-image {
        max-height: 300px;
    }

    .project-meta-bar {
        padding: 20px;
    }

    .content-card {
        padding: 25px;
    }

    .card-title {
        font-size: 22px;
    }

    .solution-section {
        padding: 25px;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .project-title {
        font-size: 24px;
    }

    .card-title {
        font-size: 20px;
    }

    .solution-title {
        font-size: 20px;
    }

    .featured-showcase {
        min-height: 250px;
        padding: 30px 15px;
    }

    .featured-image {
        max-height: 250px;
    }

    .tech-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    });
</script>
@endpush
