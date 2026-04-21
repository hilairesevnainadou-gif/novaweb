<?php
// resources/views/novatechweb/views/services.blade.php
?>
@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $company->name ?? 'Nova Tech Bénin' }} - Nos Services | Agence Web & Solutions Digitales
@endsection

@section('content')

<!-- ========== HERO SIMILAIRE À WELCOME ========== -->
<section class="services-hero">
    <div class="hero-overlay"></div>
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="hero-content">
            <h1>Nos <span class="highlight">Services</span></h1>
            <p>Des solutions digitales sur mesure pour propulser votre activité en ligne. <strong>On vous accompagne de A à Z</strong>, de la réflexion à la mise en ligne.</p>
            <div class="hero-actions">
                <a href="{{ route('home') }}#contact" class="btn btn-primary">
                    Discutons de votre projet
                </a>
                <a href="#process" class="btn btn-outline">
                    Comment on travaille ?
                </a>
            </div>
            <div class="hero-guarantee">
                Devis gratuit sans engagement
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="#f8fafc" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- ========== INTRODUCTION ========== -->
<section class="section story">
    <div class="container">
        <div class="story-grid">
            <div class="story-text">
                <span class="label">Notre expertise</span>
                <h2>On crée des solutions <span class="accent">qui vous ressemblent</span></h2>
                <p>Que vous soyez une startup, une PME ou une grande entreprise, nous vous accompagnons dans votre transformation digitale avec des solutions performantes, modernes et fiables.</p>
                <p>Notre approche est simple : on écoute, on comprend votre métier, et on construit ensemble la solution la plus adaptée à vos besoins et votre budget.</p>
                <div class="story-signature">
                    <span class="signature-text">L'équipe Nova Tech</span>
                </div>
            </div>
            <div class="story-image">
                <img src="{{ asset('assets/images/team-working.png') }}" alt="Nos services" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES : CARROUSEL (COMME WELCOME) ========== -->
@if(isset($services) && !$services->isEmpty())
<section class="section services">
    <div class="container">
        <div class="section-header">
            <span class="label">Ce qu'on peut construire ensemble</span>
            <h2>Découvrez <span class="accent">nos prestations</span></h2>
            <p class="subtitle">Des solutions complètes pour tous vos besoins digitaux</p>
        </div>

        <div class="carousel-container" id="servicesCarousel">
            <div class="carousel-track">
                @foreach($services as $service)
                <div class="carousel-slide">
                    <div class="service-card">
                        <div class="service-icon">
                            @if($service->icon)
                                <i class="fa {{ $service->icon }}" style="color: {{ $service->icon_color ?? '#6366f1' }}; font-size: 40px;"></i>
                            @else
                                <i class="fa fa-code" style="color: #6366f1; font-size: 40px;"></i>
                            @endif
                        </div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ Str::limit($service->description, 100) }}</p>
                        @if($service->features && is_array($service->features) && count($service->features) > 0)
                        <ul class="service-features-list">
                            @foreach(array_slice($service->features, 0, 3) as $feature)
                            <li><i class="fa fa-check-circle"></i> {{ Str::limit($feature, 50) }}</li>
                            @endforeach
                        </ul>
                        @endif
                        <div class="service-price">À partir de sur devis</div>
                        <a href="{{ route('home') }}#contact" class="service-link">Demander un devis →</a>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('servicesCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('servicesCarousel', 1)">›</button>
            <div class="carousel-dots" id="servicesDots"></div>
        </div>
    </div>
</section>
@endif

<!-- ========== PROCESSUS DE TRAVAIL ========== -->
<section id="process" class="section why-us">
    <div class="container">
        <div class="why-grid">
            <div class="why-image">
                <img src="{{ asset('assets/images/team-meeting.png') }}" alt="Notre processus de travail" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
            <div class="why-content">
                <span class="label">Comment on travaille</span>
                <h2>Un processus <span class="accent">simple et transparent</span></h2>
                <div class="why-list">
                    <div class="why-item">
                        <div class="why-number">01</div>
                        <div class="why-text">
                            <h3>On vous écoute</h3>
                            <p>On prend le temps de comprendre votre projet, vos objectifs et votre budget.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">02</div>
                        <div class="why-text">
                            <h3>On vous propose une solution</h3>
                            <p>On vous fait un devis clair et on vous présente les maquettes de votre futur site.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">03</div>
                        <div class="why-text">
                            <h3>On développe et on teste</h3>
                            <p>On construit votre projet et on le teste sur tous les supports.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">04</div>
                        <div class="why-text">
                            <h3>On vous forme et on vous accompagne</h3>
                            <p>On vous forme à l'utilisation et on reste disponibles après la livraison.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== NOS OUTILS & TECHNOLOGIES - CARROUSEL SANS CATÉGORISATION ========== -->
@if(isset($tools) && !$tools->isEmpty())
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

<!-- ========== PACKAGES INDICATIFS ========== -->
<section class="section services">
    <div class="container">
        <div class="section-header">
            <span class="label">Nos offres</span>
            <h2>Des formules <span class="accent">adaptées à votre budget</span></h2>
            <p class="subtitle">Des packs clairs et transparents. Devis personnalisé sur demande.</p>
        </div>

        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3>Site Vitrine</h3>
                    <div class="pricing-price">
                        <span class="currency">à partir de</span>
                        <span class="price">170 000 FCFA</span>
                    </div>
                </div>
                <div class="pricing-body">
                    <ul>
                        <li><i class="fa fa-check"></i> Design personnalisé responsive</li>
                        <li><i class="fa fa-check"></i> Jusqu'à 8 pages</li>
                        <li><i class="fa fa-check"></i> Formulaire de contact</li>
                        <li><i class="fa fa-check"></i> Optimisation SEO de base</li>
                        <li><i class="fa fa-check"></i> Hébergement 1 an offert</li>
                        <li><i class="fa fa-check"></i> Formation à l'administration</li>
                    </ul>
                </div>
                <div class="pricing-footer">
                    <a href="{{ route('home') }}#contact" class="btn-pricing">Demander un devis</a>
                </div>
            </div>

            <div class="pricing-card featured">
                <div class="pricing-badge">Populaire</div>
                <div class="pricing-header">
                    <h3>E-commerce</h3>
                    <div class="pricing-price">
                        <span class="currency">à partir de</span>
                        <span class="price">350 000 FCFA</span>
                    </div>
                </div>
                <div class="pricing-body">
                    <ul>
                        <li><i class="fa fa-check"></i> Boutique en ligne complète</li>
                        <li><i class="fa fa-check"></i> Catalogue de produits illimité</li>
                        <li><i class="fa fa-check"></i> Paiement sécurisé intégré</li>
                        <li><i class="fa fa-check"></i> Gestion des stocks</li>
                        <li><i class="fa fa-check"></i> Dashboard administrateur</li>
                        <li><i class="fa fa-check"></i> Support 30 jours inclus</li>
                    </ul>
                </div>
                <div class="pricing-footer">
                    <a href="{{ route('home') }}#contact" class="btn-pricing">Demander un devis</a>
                </div>
            </div>

            <div class="pricing-card">
                <div class="pricing-header">
                    <h3>Application Web</h3>
                    <div class="pricing-price">
                        <span class="currency">à partir de</span>
                        <span class="price">Sur devis</span>
                    </div>
                </div>
                <div class="pricing-body">
                    <ul>
                        <li><i class="fa fa-check"></i> Application sur mesure</li>
                        <li><i class="fa fa-check"></i> Interface administrateur</li>
                        <li><i class="fa fa-check"></i> Authentification sécurisée</li>
                        <li><i class="fa fa-check"></i> Base de données personnalisée</li>
                        <li><i class="fa fa-check"></i> API RESTful</li>
                        <li><i class="fa fa-check"></i> Maintenance 3 mois incluse</li>
                    </ul>
                </div>
                <div class="pricing-footer">
                    <a href="{{ route('home') }}#contact" class="btn-pricing">Demander un devis</a>
                </div>
            </div>
        </div>

        <div class="pricing-note">
            <p><i class="fa fa-info-circle"></i> Ces tarifs sont indicatifs. Le prix final dépend de vos besoins spécifiques. Contactez-nous pour un devis personnalisé et gratuit.</p>
        </div>
    </div>
</section>

<!-- ========== FAQ (DYNAMIQUE AVEC CKEDITOR) ========== -->
@if(isset($faqs) && !$faqs->isEmpty())
<section class="section faq-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Questions fréquentes</span>
            <h2>On répond à vos <span class="accent">questions</span></h2>
        </div>

        <div class="faq-grid">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <h3>{{ $faq->question }}</h3>
                <div class="faq-answer">{!! $faq->answer !!}</div>
            </div>
            @endforeach
        </div>

        <div class="faq-more">
            <a href="{{ route('home') }}#contact" class="btn btn-outline">Vous avez d'autres questions ? Contactez-nous</a>
        </div>
    </div>
</section>
@endif

<!-- ========== TÉMOIGNAGES : CARROUSEL (COMME WELCOME) ========== -->
@if(isset($testimonials) && !$testimonials->isEmpty())
<section class="section testimonials">
    <div class="container">
        <div class="section-header">
            <span class="label">Ils témoignent</span>
            <h2>Ce qu'ils disent de <span class="accent">notre travail</span></h2>
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
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
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

/* ========== SERVICES HERO ========== */
.services-hero {
    min-height: 70vh;
    background-image: url('{{ asset("assets/images/hero-bg.png") }}');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.services-hero .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(15,23,42,0.8) 50%, rgba(0,0,0,0.85) 100%);
}

.services-hero .hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.services-hero .container {
    position: relative;
    z-index: 2;
}

.services-hero .hero-content {
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

.services-hero .hero-content h1 {
    font-size: clamp(36px, 6vw, 56px);
    font-weight: 800;
    margin-bottom: 20px;
    color: white;
}

.services-hero .hero-content h1 .highlight {
    color: var(--accent);
    position: relative;
    display: inline-block;
}

.services-hero .hero-content p {
    font-size: 18px;
    margin-bottom: 30px;
    color: rgba(255,255,255,0.9);
}

.services-hero .hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    z-index: 3;
    pointer-events: none;
}

.services-hero .hero-wave svg {
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
    border: 2px solid rgba(255,255,255,0.3);
    cursor: pointer;
}

.btn-outline:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
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
    color: rgba(255,255,255,0.8);
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

.label-light {
    color: rgba(255,255,255,0.9);
    background: rgba(255,255,255,0.15);
    padding: 6px 14px;
    border-radius: 50px;
    display: inline-block;
}

h2 {
    font-size: clamp(28px, 4vw, 40px);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 24px;
    color: var(--text-dark);
}

.section-header.light h2 {
    color: white;
}

.accent {
    color: var(--accent);
}

.accent-light {
    color: #67e8f9;
}

.subtitle {
    color: var(--text-gray);
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto;
}

.subtitle-light {
    color: rgba(255,255,255,0.8);
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto;
}

/* ========== SECTION HISTOIRE ========== */
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
    box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
}

/* ========== SERVICES CARROUSEL ========== */
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

/* ========== SERVICE CARD ========== */
.service-card {
    background: white;
    padding: 40px 30px;
    border-radius: 24px;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid var(--border-light);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.service-icon {
    margin-bottom: 20px;
}

.service-card h3 {
    font-size: 22px;
    margin-bottom: 16px;
    color: var(--text-dark);
}

.service-card p {
    color: var(--text-gray);
    margin-bottom: 24px;
    line-height: 1.6;
    flex-grow: 1;
}

.service-features-list {
    list-style: none;
    padding: 0;
    margin: 15px 0;
    text-align: left;
}

.service-features-list li {
    font-size: 13px;
    color: var(--text-gray);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.service-features-list li i {
    color: var(--primary);
    font-size: 12px;
}

.service-price {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 20px;
}

.service-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s ease;
}

.service-link:hover {
    transform: translateX(5px);
}

/* ========== POURQUOI NOUS / PROCESSUS ========== */
.why-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.why-image img {
    width: 100%;
    border-radius: 24px;
    box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
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

/* ========== OUTILS & TECHNOLOGIES - CARROUSEL ========== */
.tools-carousel-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    padding: 80px 0;
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

/* ========== PRIX ========== */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.pricing-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-light);
}

.pricing-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.pricing-card.featured {
    border: 2px solid var(--primary);
    transform: scale(1.02);
}

.pricing-card.featured:hover {
    transform: scale(1.02) translateY(-10px);
}

.pricing-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
}

.pricing-header {
    padding: 30px;
    text-align: center;
    background: var(--bg-light);
}

.pricing-header h3 {
    font-size: 24px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.pricing-price .currency {
    display: block;
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 5px;
}

.pricing-price .price {
    font-size: 28px;
    font-weight: 800;
    color: var(--primary);
}

.pricing-body {
    padding: 30px;
}

.pricing-body ul {
    list-style: none;
    padding: 0;
}

.pricing-body ul li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 14px;
    color: var(--text-gray);
}

.pricing-body ul li i {
    color: var(--primary);
    font-size: 14px;
    flex-shrink: 0;
}

.pricing-footer {
    padding: 0 30px 30px;
}

.btn-pricing {
    display: block;
    text-align: center;
    background: var(--primary);
    color: white;
    padding: 14px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-pricing:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.pricing-note {
    text-align: center;
    margin-top: 30px;
    padding: 15px;
    background: #fef3c7;
    border-radius: 12px;
    color: #92400e;
    font-size: 14px;
}

.pricing-note i {
    margin-right: 8px;
}

/* ========== FAQ ========== */
.faq-section {
    background: var(--bg-light);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.faq-item {
    background: white;
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

.faq-answer p {
    margin-bottom: 12px;
}

.faq-answer ul,
.faq-answer ol {
    margin-left: 20px;
    margin-bottom: 12px;
}

.faq-more {
    text-align: center;
    margin-top: 40px;
}

/* ========== TÉMOIGNAGES ========== */
.testimonials {
    background: var(--bg-light);
}

.testimonial-card {
    background: var(--bg-white);
    border-radius: 24px;
    padding: 30px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
    font-size: 16px;
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
    .services-hero {
        min-height: 60vh;
        padding: 100px 0 60px;
    }

    .hero-actions {
        flex-direction: column;
        align-items: center;
    }

    .btn-primary,
    .btn-outline {
        width: 100%;
        max-width: 280px;
    }

    .carousel-container {
        padding: 0 40px;
    }

    .carousel-slide {
        flex: 0 0 100%;
    }

    .pricing-card.featured {
        transform: scale(1);
    }

    .pricing-card.featured:hover {
        transform: translateY(-10px);
    }

    .tool-card {
        padding: 20px 15px;
    }

    .tool-card-icon i {
        font-size: 36px;
    }

    .tool-card-logo {
        width: 45px;
        height: 45px;
    }

    .tool-card-name {
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .pricing-grid {
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
    if (document.getElementById('servicesCarousel')) initCarousel('servicesCarousel');
    if (document.getElementById('testimonialsCarousel')) initCarousel('testimonialsCarousel');
    if (document.getElementById('toolsCarousel')) initCarousel('toolsCarousel');
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

document.querySelectorAll('.service-card, .pricing-card, .faq-item, .tool-card, .why-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>
@endpush
