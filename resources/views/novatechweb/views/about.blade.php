<?php
// resources/views/novatechweb/views/about.blade.php
?>
@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $company->name ?? 'Nova Tech Bénin' }} - À propos | Notre histoire, notre mission
@endsection

@section('content')

<!-- ========== HERO SIMILAIRE À SERVICES ========== -->
<section class="about-hero">
    <div class="hero-overlay"></div>
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="hero-content">
            <h1>À <span class="highlight">propos</span></h1>
            <p>Découvrez qui nous sommes, notre mission et nos valeurs. <strong>On a créé Nova Tech parce que le web peut être simple</strong>, accessible et performant pour tous.</p>
            <div class="hero-actions">
                <a href="{{ route('home') }}#contact" class="btn btn-primary">
                    Discutons de votre projet
                </a>
                <a href="{{ route('services') }}" class="btn btn-outline">
                    Découvrir nos services
                </a>
            </div>
            <div class="hero-guarantee">
                <i class="fa fa-heart"></i> Une équipe passionnée à votre service
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="#f8fafc" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- ========== NOTRE HISTOIRE ========== -->
<section class="section story">
    <div class="container">
        <div class="story-grid">
            <div class="story-text">
                <span class="label">Notre histoire</span>
                <h2>{!! $company->about_title ?? 'On a créé Nova Tech parce que <span class="accent">le web peut être simple</span>' !!}</h2>
                <p>{{ $company->about_description_1 ?? 'Nous sommes une petite équipe de passionnés basée au Bénin. On aime ce qu\'on fait, et ça se voit. Chaque projet est unique, chaque client est un ami qu\'on aide à réussir en ligne.' }}</p>
                <p>{{ $company->about_description_2 ?? 'Pas de machines, pas de templates copiés-collés. Juste du travail fait avec le cœur, de l\'écoute et des résultats qui vous ressemblent.' }}</p>
                <div class="story-signature">
                    <span class="signature-text">L'équipe Nova Tech</span>
                </div>
            </div>
            <div class="story-image">
                <img src="{{ $company->about_image ? asset('storage/' . $company->about_image) : asset('assets/images/team-working.png') }}" alt="Notre équipe" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
        </div>
    </div>
</section>

<!-- ========== CHIFFRES CLÉS ========== -->
@if(($company->years_experience ?? false) || ($projectsCount ?? false) || ($clientsCount ?? false) || ($teamCount ?? false))
<section class="section stats-section">
    <div class="container">
        <div class="stats-grid">
            @if($company->years_experience ?? false)
            <div class="stat-card">
                <div class="stat-number">{{ $company->years_experience }}+</div>
                <div class="stat-label">Années d'expérience</div>
            </div>
            @endif
            @if($projectsCount ?? false)
            <div class="stat-card">
                <div class="stat-number">{{ $projectsCount }}+</div>
                <div class="stat-label">Projets réalisés</div>
            </div>
            @endif
            @if($clientsCount ?? false)
            <div class="stat-card">
                <div class="stat-number">{{ $clientsCount }}+</div>
                <div class="stat-label">Clients satisfaits</div>
            </div>
            @endif
            @if($teamCount ?? false)
            <div class="stat-card">
                <div class="stat-number">{{ $teamCount }}</div>
                <div class="stat-label">Experts passionnés</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- ========== MISSION, VISION & VALEURS ========== -->
@if(($company->mission ?? false) || ($company->vision ?? false) || ($company->values ?? false))
<section class="section values-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Notre ADN</span>
            <h2>Mission, Vision <span class="accent">& Valeurs</span></h2>
            <p class="subtitle">Ce qui nous guide au quotidien</p>
        </div>

        <div class="values-grid">
            @if($company->mission ?? false)
            <div class="value-card">
                <div class="value-icon">
                    <i class="fa fa-bullseye"></i>
                </div>
                <h3>Notre Mission</h3>
                <p>{{ $company->mission }}</p>
            </div>
            @endif

            @if($company->vision ?? false)
            <div class="value-card">
                <div class="value-icon">
                    <i class="fa fa-eye"></i>
                </div>
                <h3>Notre Vision</h3>
                <p>{{ $company->vision }}</p>
            </div>
            @endif

            @if($company->values ?? false)
            <div class="value-card">
                <div class="value-icon">
                    <i class="fa fa-heart"></i>
                </div>
                <h3>Nos Valeurs</h3>
                <p>{{ $company->values }}</p>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- ========== POURQUOI NOUS CHOISIR ========== -->
<section class="section why-us">
    <div class="container">
        <div class="why-grid">
            <div class="why-image">
                <img src="{{ asset('assets/images/team-meeting.png') }}" alt="Pourquoi nous choisir" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
            <div class="why-content">
                <span class="label">Nos atouts</span>
                <h2>Pourquoi nous <span class="accent">choisir ?</span></h2>
                <div class="why-list">
                    <div class="why-item">
                        <div class="why-number">01</div>
                        <div class="why-text">
                            <h3>Support réactif</h3>
                            <p>Une équipe disponible pour vous accompagner à chaque étape de votre projet.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">02</div>
                        <div class="why-text">
                            <h3>Livraison dans les délais</h3>
                            <p>Nous respectons nos engagements et livrons vos projets à temps.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">03</div>
                        <div class="why-text">
                            <h3>Résultats garantis</h3>
                            <p>Des solutions qui performent et vous aident à atteindre vos objectifs.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">04</div>
                        <div class="why-text">
                            <h3>Relation de confiance</h3>
                            <p>Un accompagnement personnalisé et transparent tout au long de notre collaboration.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== NOTRE ÉQUIPE (TEAM MEMBERS) - CARROUSEL ========== -->
@if(isset($team) && $team->count() > 0)
<section class="section team-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Notre équipe</span>
            <h2>Les talents <span class="accent">derrière Nova Tech</span></h2>
            <p class="subtitle">Des passionnés à votre service</p>
        </div>

        <div class="carousel-container" id="teamCarousel">
            <div class="carousel-track">
                @foreach($team as $member)
                <div class="carousel-slide">
                    <div class="team-card">
                        <div class="team-image">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}">
                            @else
                                <div class="team-image-placeholder">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                            @endif
                        </div>
                        <div class="team-info">
                            <h3>{{ $member->name }}</h3>
                            <span class="team-position">{{ $member->position }}</span>
                            <p class="team-bio">{{ Str::limit($member->bio ?? '', 100) }}</p>
                            @if($member->email || $member->linkedin || $member->twitter)
                            <div class="team-social">
                                @if($member->email)
                                <a href="mailto:{{ $member->email }}" class="team-social-link"><i class="fa fa-envelope"></i></a>
                                @endif
                                @if($member->linkedin)
                                <a href="{{ $member->linkedin }}" target="_blank" class="team-social-link"><i class="fa fa-linkedin"></i></a>
                                @endif
                                @if($member->twitter)
                                <a href="{{ $member->twitter }}" target="_blank" class="team-social-link"><i class="fa fa-twitter"></i></a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('teamCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('teamCarousel', 1)">›</button>
            <div class="carousel-dots" id="teamDots"></div>
        </div>
    </div>
</section>
@endif

<!-- ========== NOS TECHNOLOGIES - CARROUSEL ========== -->
@if(isset($tools) && $tools->count() > 0)
<section class="section tools-carousel-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Notre boîte à outils</span>
            <h2>Les technologies <span class="accent">que nous maîtrisons</span></h2>
            <p class="subtitle">Des outils modernes pour des projets d'exception</p>
        </div>

        <div class="carousel-container" id="toolsCarousel">
            <div class="carousel-track">
                @foreach($tools as $tool)
                <div class="carousel-slide">
                    <div class="tool-card" @if($tool->website_url) onclick="window.open('{{ $tool->website_url }}', '_blank')" @endif>
                        <div class="tool-card-icon">
                            @if($tool->logo)
                                <img src="{{ asset('storage/' . $tool->logo) }}" alt="{{ $tool->name }}" class="tool-card-logo">
                            @elseif($tool->icon)
                                <i class="{{ $tool->icon }}" style="color: {{ $tool->icon_color ?? '#6366f1' }}"></i>
                            @else
                                <i class="fa fa-code" style="color: #6366f1"></i>
                            @endif
                        </div>
                        <div class="tool-card-name">{{ $tool->name }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('toolsCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('toolsCarousel', 1)">›</button>
            <div class="carousel-dots" id="toolsDots"></div>
        </div>
    </div>
</section>
@endif

<!-- ========== TÉMOIGNAGES - CARROUSEL ========== -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="section testimonials">
    <div class="container">
        <div class="section-header">
            <span class="label">Ils parlent de nous</span>
            <h2>Ce que nos <span class="accent">clients disent</span></h2>
        </div>

        <div class="carousel-container" id="testimonialsCarousel">
            <div class="carousel-track">
                @foreach($testimonials as $testimonial)
                <div class="carousel-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>"{{ Str::limit($testimonial->content, 150) }}"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-img">
                                @if($testimonial->avatar)
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}">
                                @else
                                    <div class="author-placeholder">
                                        {{ strtoupper(substr($testimonial->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="author-details">
                                <strong>{{ $testimonial->name }}</strong>
                                @if($testimonial->position || $testimonial->company)
                                <span>
                                    @if($testimonial->position){{ $testimonial->position }}@endif
                                    @if($testimonial->position && $testimonial->company) - @endif
                                    @if($testimonial->company){{ $testimonial->company }}@endif
                                </span>
                                @endif
                            </div>
                        </div>
                        @if($testimonial->rating && $testimonial->rating > 0)
                        <div class="testimonial-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <span class="star star-filled">★</span>
                                @else
                                    <span class="star star-empty">☆</span>
                                @endif
                            @endfor
                            <span class="rating-text">{{ $testimonial->rating }}/5</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('testimonialsCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('testimonialsCarousel', 1)">›</button>
            <div class="carousel-dots" id="testimonialsDots"></div>
        </div>
    </div>
</section>
@endif

<!-- ========== FAQ ========== -->
@if(isset($faqs) && $faqs->count() > 0)
<section class="section faq-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Questions fréquentes</span>
            <h2>On répond à vos <span class="accent">questions</span></h2>
        </div>

        <div class="faq-grid">
            @foreach($faqs->take(4) as $faq)
            <div class="faq-item">
                <h3>{{ $faq->question }}</h3>
                <div class="faq-answer">{!! Str::limit($faq->answer, 150) !!}</div>
            </div>
            @endforeach
        </div>

        @if($faqs->count() > 4)
        <div class="faq-more">
            <a href="{{ route('faq') }}" class="btn btn-outline">Voir toutes les FAQ</a>
        </div>
        @endif
    </div>
</section>
@endif

<!-- ========== CTA CONTACT ========== -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-content">
                <h2>Vous avez un projet <span class="accent">en tête ?</span></h2>
                <p>Discutons ensemble de vos idées et créons quelque chose de formidable.</p>
                <div class="cta-buttons">
                    <a href="{{ route('home') }}#contact" class="btn btn-primary">Contactez-nous</a>
                    <a href="{{ route('services') }}" class="btn btn-outline-light">Découvrir nos services</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ========== VARIABLES ========== */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --accent: #06b6d4;
    --accent-light: #67e8f9;
    --text-dark: #0f172a;
    --text-gray: #475569;
    --text-light: #64748b;
    --border-light: #e2e8f0;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
}

/* ========== ABOUT HERO ========== */
.about-hero {
    min-height: 70vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.about-hero .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.15), transparent);
}

.about-hero .hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.about-hero .container {
    position: relative;
    z-index: 2;
}

.about-hero .hero-content {
    max-width: 800px;
    margin: 0 auto;
    animation: fadeInUp 0.8s ease-out;
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

.about-hero .hero-content h1 {
    font-size: clamp(36px, 6vw, 56px);
    font-weight: 800;
    margin-bottom: 20px;
    color: white;
}

.about-hero .hero-content h1 .highlight {
    color: var(--accent);
    position: relative;
    display: inline-block;
}

.about-hero .hero-content p {
    font-size: 18px;
    margin-bottom: 30px;
    color: rgba(255, 255, 255, 0.9);
}

.about-hero .hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    z-index: 3;
    pointer-events: none;
}

.about-hero .hero-wave svg {
    width: 100%;
    height: auto;
    display: block;
}

/* ========== BOUTONS ========== */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
}

.btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.3);
    cursor: pointer;
}

.btn-outline:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
    transform: translateY(-3px);
}

.btn-outline-light {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-outline-light:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.2);
    transform: translateY(-3px);
}

.hero-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.hero-guarantee {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
}

.hero-guarantee i {
    color: var(--accent);
}

/* ========== SECTIONS GÉNÉRALES ========== */
.section {
    padding: 80px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.label {
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--primary);
    margin-bottom: 16px;
}

h2 {
    font-size: clamp(28px, 4vw, 40px);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 24px;
    color: var(--text-dark);
}

.accent {
    color: var(--accent);
}

.subtitle {
    color: var(--text-gray);
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto;
}

/* ========== NOTRE HISTOIRE ========== */
.story {
    background: var(--bg-white);
}

.story-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.story-text p {
    color: var(--text-gray);
    margin-bottom: 20px;
    line-height: 1.7;
}

.story-signature {
    margin-top: 32px;
}

.signature-text {
    font-size: 16px;
    font-style: italic;
    color: var(--primary);
    font-weight: 500;
}

.story-image img {
    width: 100%;
    border-radius: 24px;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
}

/* ========== STATS ========== */
.stats-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
    text-align: center;
}

.stat-card {
    color: white;
}

.stat-number {
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 10px;
    color: var(--accent);
}

.stat-label {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.8);
}

/* ========== VALEURS ========== */
.values-section {
    background: var(--bg-light);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.value-card {
    background: white;
    padding: 40px 30px;
    border-radius: 24px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid var(--border-light);
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.value-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.value-icon i {
    font-size: 32px;
    color: var(--primary);
}

.value-card h3 {
    font-size: 22px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.value-card p {
    color: var(--text-gray);
    line-height: 1.6;
}

/* ========== POURQUOI NOUS ========== */
.why-us {
    background: var(--bg-white);
}

.why-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.why-image img {
    width: 100%;
    border-radius: 24px;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
}

.why-list {
    margin-top: 32px;
}

.why-item {
    display: flex;
    gap: 20px;
    margin-bottom: 32px;
}

.why-number {
    font-size: 36px;
    font-weight: 800;
    color: var(--primary);
    opacity: 0.3;
    line-height: 1;
}

.why-text h3 {
    font-size: 18px;
    margin-bottom: 8px;
    color: var(--text-dark);
}

.why-text p {
    color: var(--text-gray);
    line-height: 1.6;
}

/* ========== CARROUSEL ========== */
.carousel-container {
    position: relative;
    overflow: hidden;
    padding: 0 60px;
}

.carousel-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    gap: 30px;
}

.carousel-slide {
    flex: 0 0 calc(33.333% - 20px);
    min-width: 0;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border-light);
    color: var(--text-dark);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    z-index: 10;
}

.carousel-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.carousel-btn.prev { left: 0; }
.carousel-btn.next { right: 0; }

.carousel-dots {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 40px;
}

.carousel-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--border-light);
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    padding: 0;
}

.carousel-dot.active {
    background: var(--primary);
    width: 28px;
    border-radius: 5px;
}

/* ========== TEAM CARD ========== */
.team-section {
    background: var(--bg-light);
}

.team-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.team-image {
    height: 250px;
    overflow: hidden;
}

.team-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-image-placeholder i {
    font-size: 80px;
    color: white;
    opacity: 0.5;
}

.team-info {
    padding: 20px;
    text-align: center;
}

.team-info h3 {
    font-size: 18px;
    margin-bottom: 5px;
    color: var(--text-dark);
}

.team-position {
    display: inline-block;
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 12px;
}

.team-bio {
    font-size: 13px;
    color: var(--text-gray);
    line-height: 1.5;
    margin-bottom: 15px;
}

.team-social {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.team-social-link {
    width: 32px;
    height: 32px;
    background: var(--bg-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    transition: all 0.3s;
}

.team-social-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

/* ========== TOOL CARD ========== */
.tools-carousel-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

.tools-carousel-section .section-header h2,
.tools-carousel-section .section-header .subtitle {
    color: white;
}

.tools-carousel-section .section-header .label {
    color: var(--accent);
}

.tool-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.tool-card:hover {
    background: rgba(99, 102, 241, 0.25);
    border-color: var(--primary);
    transform: translateY(-5px);
}

.tool-card-icon {
    margin-bottom: 15px;
}

.tool-card-icon i {
    font-size: 48px;
}

.tool-card-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
}

.tool-card-name {
    font-size: 16px;
    font-weight: 600;
    color: white;
}

/* ========== TÉMOIGNAGES ========== */
.testimonials {
    background: var(--bg-light);
}

.testimonial-card {
    background: white;
    padding: 30px;
    border-radius: 24px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-light);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.testimonial-text {
    flex-grow: 1;
}

.testimonial-text p {
    font-size: 15px;
    line-height: 1.7;
    color: var(--text-gray);
    font-style: italic;
    margin-bottom: 24px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: auto;
}

.author-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.author-placeholder {
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.author-details strong {
    display: block;
    font-size: 15px;
    color: var(--text-dark);
}

.author-details span {
    font-size: 12px;
    color: var(--text-light);
}

.testimonial-rating {
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.star {
    font-size: 14px;
}

.star-filled {
    color: #f59e0b;
}

.star-empty {
    color: #cbd5e1;
}

.rating-text {
    font-size: 11px;
    color: var(--text-light);
    margin-left: 4px;
}

/* ========== FAQ ========== */
.faq-section {
    background: var(--bg-white);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.faq-item {
    background: var(--bg-light);
    padding: 30px;
    border-radius: 20px;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.faq-item h3 {
    font-size: 18px;
    margin-bottom: 12px;
    color: var(--text-dark);
}

.faq-answer {
    color: var(--text-gray);
    line-height: 1.6;
}

.faq-more {
    text-align: center;
    margin-top: 40px;
}

/* ========== CTA ========== */
.cta-section {
    background: white;
}

.cta-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 32px;
    padding: 60px;
    text-align: center;
    color: white;
}

.cta-content h2 {
    color: white;
    margin-bottom: 20px;
}

.cta-content p {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 30px;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .section {
        padding: 60px 0;
    }

    .story-grid,
    .why-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .carousel-slide {
        flex: 0 0 calc(50% - 15px);
    }

    .faq-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .about-hero {
        min-height: 60vh;
        padding: 100px 0 60px;
    }

    .hero-actions {
        flex-direction: column;
        align-items: center;
    }

    .btn-primary,
    .btn-outline,
    .btn-outline-light {
        width: 100%;
        max-width: 280px;
    }

    .carousel-container {
        padding: 0 40px;
    }

    .carousel-slide {
        flex: 0 0 100%;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .values-grid {
        grid-template-columns: 1fr;
    }

    .cta-card {
        padding: 40px 20px;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .cta-buttons .btn {
        width: 100%;
        max-width: 280px;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Carrousel
const carousels = {};

function initCarousel(id) {
    const container = document.getElementById(id);
    if (!container) return;

    const track = container.querySelector('.carousel-track');
    const slides = track ? track.children : [];
    const dotsContainer = document.getElementById(id.replace('Carousel', 'Dots'));

    if (slides.length === 0) return;

    let currentIndex = 0;
    let autoplayInterval;

    function getItemsPerPage() {
        if (window.innerWidth <= 576) return 1;
        if (window.innerWidth <= 992) return 2;
        return 3;
    }

    function getTotalPages() {
        return Math.ceil(slides.length / getItemsPerPage());
    }

    function updateDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const total = getTotalPages();
        for (let i = 0; i < total; i++) {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot' + (i === currentIndex ? ' active' : '');
            dot.onclick = () => goToSlide(i);
            dotsContainer.appendChild(dot);
        }
    }

    function updateCarousel() {
        const itemsPerPage = getItemsPerPage();
        const slideWidth = slides[0]?.offsetWidth + 30 || 0;
        if (track) {
            track.style.transform = 'translateX(-' + (currentIndex * itemsPerPage * slideWidth) + 'px)';
        }
    }

    function goToSlide(index) {
        const maxIndex = getTotalPages() - 1;
        currentIndex = Math.max(0, Math.min(index, maxIndex));
        updateCarousel();
        updateDots();
    }

    function next() {
        const total = getTotalPages();
        goToSlide((currentIndex + 1) % total);
    }

    function prev() {
        const total = getTotalPages();
        goToSlide((currentIndex - 1 + total) % total);
    }

    function startAutoplay() {
        if (autoplayInterval) clearInterval(autoplayInterval);
        autoplayInterval = setInterval(next, 6000);
    }

    function stopAutoplay() {
        if (autoplayInterval) clearInterval(autoplayInterval);
    }

    container.addEventListener('mouseenter', stopAutoplay);
    container.addEventListener('mouseleave', startAutoplay);

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            currentIndex = 0;
            updateDots();
            updateCarousel();
        }, 250);
    });

    updateDots();
    updateCarousel();
    startAutoplay();

    carousels[id] = { next, prev };
}

function moveCarousel(id, direction) {
    if (carousels[id]) {
        direction > 0 ? carousels[id].next() : carousels[id].prev();
    }
}

// Initialisation des carrousels
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('teamCarousel')) initCarousel('teamCarousel');
    if (document.getElementById('toolsCarousel')) initCarousel('toolsCarousel');
    if (document.getElementById('testimonialsCarousel')) initCarousel('testimonialsCarousel');
});

// Animation au scroll
const observerOptions = { threshold: 0.1 };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.value-card, .team-card, .tool-card, .testimonial-card, .faq-item, .why-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});

// Animation des statistiques
const statNumbers = document.querySelectorAll('.stat-number');
const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const el = entry.target;
            const target = parseInt(el.innerText);
            if (isNaN(target)) return;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    el.innerText = target + '+';
                    clearInterval(timer);
                } else {
                    el.innerText = Math.floor(current) + '+';
                }
            }, 30);
            statObserver.unobserve(el);
        }
    });
}, { threshold: 0.5 });

statNumbers.forEach(el => statObserver.observe(el));
</script>
@endpush
