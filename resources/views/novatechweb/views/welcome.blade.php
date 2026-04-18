@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $company->name ?? 'Nova Tech Bénin' }} - Agence Web & Solutions Digitales
@endsection

@section('content')

<!-- ========== HERO : GRANDE BANNIÈRE AVEC IMAGE BACKGROUND ========== -->
<section class="hero-banner">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1>Donnez vie à vos idées<br>avec <span class="highlight">des gens qui vous écoutent</span></h1>
            <p>Pas de jargon, pas de technique compliquée. Juste une équipe passionnée qui construit votre site web comme si c'était le sien.</p>
            <div class="hero-actions">
                <a href="#contact" class="btn btn-primary">Parlons de votre projet →</a>
            </div>
        </div>
    </div>
</section>

<!-- ========== PRÉSENTATION : NOTRE HISTOIRE ========== -->
<section id="about" class="section story">
    <div class="container">
        <div class="story-grid">
            <div class="story-text">
                <span class="label">Notre histoire</span>
                <h2>On a créé Nova Tech parce que <span class="accent">le web peut être simple</span></h2>
                <p>Nous sommes une petite équipe de passionnés basée au Bénin. On aime ce qu'on fait, et ça se voit. Chaque projet est unique, chaque client est un ami qu'on aide à réussir en ligne.</p>
                <p>Pas de machines, pas de templates copiés-collés. Juste du travail fait avec le cœur, de l'écoute et des résultats qui vous ressemblent.</p>
                <div class="story-signature">
                    <span class="signature-text">L'équipe Nova Tech</span>
                </div>
            </div>
            <div class="story-image">
                <img src="{{ asset('assets/images/team-working.png') }}" alt="Notre équipe au travail" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES : CARROUSEL ========== -->
<section id="services" class="section services">
    <div class="container">
        <div class="section-header">
            <span class="label">Nos services</span>
            <h2>Ce qu'on peut <span class="accent">construire ensemble</span></h2>
            <p class="subtitle">Des solutions adaptées à votre budget et vos envies</p>
        </div>

        @if(isset($services) && !$services->isEmpty())
        <div class="carousel-container" id="servicesCarousel">
            <div class="carousel-track">
                @foreach($services as $service)
                <div class="carousel-slide">
                    <div class="service-card">
                        <h3>{{ $service->title }}</h3>
                        <p>{{ Str::limit($service->description, 120) }}</p>
                        <div class="service-price">À partir de <strong>sur devis</strong></div>
                        <a href="#contact" class="service-link">En savoir plus →</a>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('servicesCarousel', -1)">
                <i class="fa fa-chevron-left"></i>
            </button>
            <button class="carousel-btn next" onclick="moveCarousel('servicesCarousel', 1)">
                <i class="fa fa-chevron-right"></i>
            </button>
            <div class="carousel-dots" id="servicesDots"></div>
        </div>
        @else
        <div class="empty-state">
            <p>Nos services arrivent bientôt</p>
        </div>
        @endif
    </div>
</section>

<!-- ========== PORTFOLIO : CARROUSEL ========== -->
<section id="portfolio" class="section portfolio-bg">
    <div class="container">
        <div class="section-header light">
            <span class="label label-light">Nos réalisations</span>
            <h2>Quelques projets qu'on a <span class="accent-light">aimé réaliser</span></h2>
            <p class="subtitle-light">Découvrez les technologies utilisées et les résultats obtenus</p>
        </div>

        @if(isset($portfolios) && !$portfolios->isEmpty())
        <div class="carousel-container" id="portfolioCarousel">
            <div class="carousel-track">
                @foreach($portfolios as $portfolio)
                    @php
                        $imageUrl = asset('assets/images/portfolio-placeholder.jpg');
                        if ($portfolio->image) {
                            $imagePath = $portfolio->image;
                            if (!str_starts_with($imagePath, 'portfolio/') && !str_starts_with($imagePath, 'storage/')) {
                                $imagePath = 'portfolio/' . $imagePath;
                            }
                            $imagePath = str_replace('storage/', '', $imagePath);
                            $imageUrl = asset('storage/' . $imagePath);
                        }

                        $technologies = [];
                        if (!empty($portfolio->technologies)) {
                            if (is_array($portfolio->technologies)) {
                                $technologies = $portfolio->technologies;
                            } elseif (is_string($portfolio->technologies)) {
                                $technologies = json_decode($portfolio->technologies, true) ?? [];
                            }
                        }

                        $categoryNames = [
                            'site-vitrine' => 'Site Vitrine',
                            'e-commerce' => 'E-commerce',
                            'application-web' => 'Application Web',
                            'maintenance' => 'Maintenance',
                            'optimisation' => 'Optimisation',
                            'autre' => 'Autre'
                        ];
                        $categoryName = $categoryNames[$portfolio->category] ?? 'Projet Web';
                    @endphp
                    <div class="carousel-slide">
                        <div class="project-card">
                            <div class="project-img">
                                <img src="{{ $imageUrl }}" alt="{{ $portfolio->title }}" loading="lazy" onerror="this.src='{{ asset('assets/images/portfolio-placeholder.jpg') }}'">
                                <div class="project-overlay">
                                    <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="project-btn">Voir le détail</a>
                                </div>
                            </div>
                            <div class="project-body">
                                <span class="project-category">{{ $categoryName }}</span>
                                <h3>{{ $portfolio->title }}</h3>
                                <p class="project-description">{{ Str::limit($portfolio->description ?? 'Un projet réalisé avec passion pour un client satisfait.', 100) }}</p>
                                @if($portfolio->client)
                                <div class="project-client">
                                    <i class="fa fa-user"></i>
                                    <span>{{ $portfolio->client }}</span>
                                </div>
                                @endif
                                @if(!empty($technologies))
                                <div class="project-technologies">
                                    <div class="tech-list">
                                        @foreach(array_slice($technologies, 0, 3) as $tech)
                                        <span class="tech-tag">{{ $tech }}</span>
                                        @endforeach
                                        @if(count($technologies) > 3)
                                        <span class="tech-tag">+{{ count($technologies) - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('portfolioCarousel', -1)">
                <i class="fa fa-chevron-left"></i>
            </button>
            <button class="carousel-btn next" onclick="moveCarousel('portfolioCarousel', 1)">
                <i class="fa fa-chevron-right"></i>
            </button>
            <div class="carousel-dots" id="portfolioDots"></div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('portfolio.index') }}" class="btn btn-outline-light">Voir tous nos projets →</a>
        </div>
        @else
        <div class="empty-state light">
            <p>Nos réalisations arrivent bientôt</p>
        </div>
        @endif
    </div>
</section>

<!-- ========== TÉMOIGNAGES ========== -->
<section class="section testimonials">
    <div class="container">
        <div class="section-header">
            <span class="label">Ils témoignent</span>
            <h2>Ce qu'ils disent de <span class="accent">notre travail</span></h2>
        </div>

        @if(isset($testimonials) && !$testimonials->isEmpty())
        <div class="testimonials-grid">
            @foreach($testimonials as $testimonial)
            <div class="testimonial-card">
                <div class="testimonial-text">
                    <p>"{{ $testimonial->content }}"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-img">
                        @if($testimonial->avatar)
                            <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                 alt="{{ $testimonial->name }}"
                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
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
                            <i class="fa fa-star"></i>
                        @else
                            <i class="fa fa-star-o"></i>
                        @endif
                    @endfor
                    <span class="rating-text">{{ $testimonial->rating }}/5</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    <p>"Je suis commerçante et je n'y connaissais rien en informatique. L'équipe a été patiente, à l'écoute. Aujourd'hui mes clients me trouvent sur internet."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-img">
                        <div class="author-placeholder">FA</div>
                    </div>
                    <div class="author-details">
                        <strong>Fatima A.</strong>
                        <span>Boutique de tissus - Cotonou</span>
                    </div>
                </div>
                <div class="testimonial-rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <span class="rating-text">5/5</span>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">
                    <p>"Ils m'ont conseillé, orienté, et le résultat est magnifique. Mon chiffre d'affaires a augmenté grâce à mon nouveau site."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-img">
                        <div class="author-placeholder">MD</div>
                    </div>
                    <div class="author-details">
                        <strong>Marc D.</strong>
                        <span>Restaurateur - Porto-Novo</span>
                    </div>
                </div>
                <div class="testimonial-rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <span class="rating-text">4/5</span>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">
                    <p>"Un accompagnement exceptionnel. Ils sont disponibles, réactifs et surtout très humains. Je recommande sans hésiter."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-img">
                        <div class="author-placeholder">AB</div>
                    </div>
                    <div class="author-details">
                        <strong>Amel B.</strong>
                        <span>Profession libérale</span>
                    </div>
                </div>
                <div class="testimonial-rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <span class="rating-text">5/5</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- ========== POURQUOI NOUS ========== -->
<section class="section why-us">
    <div class="container">
        <div class="why-grid">
            <div class="why-image">
                <img src="{{ asset('assets/images/team-meeting.png') }}" alt="Notre équipe en réunion" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
            <div class="why-content">
                <span class="label">Pourquoi nous choisir</span>
                <h2>On est différents,<br><span class="accent">et c'est voulu</span></h2>
                <div class="why-list">
                    <div class="why-item">
                        <div class="why-number">01</div>
                        <div class="why-text">
                            <h3>On vous écoute vraiment</h3>
                            <p>On prend le temps de comprendre votre métier, vos clients et vos objectifs.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">02</div>
                        <div class="why-text">
                            <h3>On livre à temps</h3>
                            <p>Votre site en ligne en 2-3 semaines. Pas de promesses en l'air.</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <div class="why-number">03</div>
                        <div class="why-text">
                            <h3>On reste après la livraison</h3>
                            <p>Formation, support, corrections. On ne vous abandonne pas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FORMULAIRE DE CONTACT ========== -->
<section id="contact" class="section contact-section">
    <div class="container">
        <div class="contact-card">
            <div class="contact-header">
                <span class="label">Parlons de vous</span>
                <h2>Racontez-nous <span class="accent">votre projet</span></h2>
                <p>Remplissez le formulaire, on vous rappelle dans la journée.</p>
            </div>

            <div class="contact-grid">
                <div class="contact-infos">
                    <div class="contact-info-item">
                        <h4>Appelez-nous</h4>
                        <a href="tel:{{ $company->phone ?? '+22966185595' }}">{{ $company->phone ?? '+229 66 18 55 95' }}</a>
                    </div>
                    <div class="contact-info-item">
                        <h4>WhatsApp</h4>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp ?? '22966185595') }}">Discuter sur WhatsApp</a>
                    </div>
                    <div class="contact-info-item">
                        <h4>Email</h4>
                        <a href="mailto:{{ $company->email ?? 'contact@novatech.bj' }}">{{ $company->email ?? 'contact@novatech.bj' }}</a>
                    </div>
                    <div class="contact-info-item">
                        <h4>On se rencontre</h4>
                        <p>{{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
                    </div>
                </div>

                <div class="contact-form">
                    <form id="contactForm" action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="name" id="name" placeholder="Votre nom complet" value="{{ old('name') }}">
                                <div class="field-error" id="error-name" style="display: none;"></div>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" id="phone" placeholder="Votre téléphone" value="{{ old('phone') }}">
                                <div class="field-error" id="error-phone" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" placeholder="Votre email" value="{{ old('email') }}">
                            <div class="field-error" id="error-email" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <select name="service" id="service">
                                <option value="">Quel est votre besoin ?</option>
                                <option value="site-vitrine" {{ old('service') == 'site-vitrine' ? 'selected' : '' }}>Je veux un site vitrine</option>
                                <option value="ecommerce" {{ old('service') == 'ecommerce' ? 'selected' : '' }}>Je veux vendre en ligne</option>
                                <option value="refonte" {{ old('service') == 'refonte' ? 'selected' : '' }}>Je veux améliorer mon site actuel</option>
                                <option value="autre" {{ old('service') == 'autre' ? 'selected' : '' }}>Autre projet</option>
                            </select>
                            <div class="field-error" id="error-service" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <textarea name="message" id="message" rows="4" placeholder="Décrivez votre projet, vos idées, vos envies...">{{ old('message') }}</textarea>
                            <div class="field-error" id="error-message" style="display: none;"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Envoyer ma demande →</button>
                        <div id="formMessage" class="form-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FAQ SIMPLE ========== -->
<section class="section faq-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Questions fréquentes</span>
            <h2>On répond à vos <span class="accent">questions</span></h2>
        </div>

        <div class="faq-grid">
            <div class="faq-item">
                <h3>Ça coûte combien un site web ?</h3>
                <p>Chaque projet est différent. On vous fait un devis personnalisé et gratuit après avoir échangé avec vous. Pas de surprise, pas de frais cachés.</p>
            </div>
            <div class="faq-item">
                <h3>Je ne connais rien au web, c'est possible ?</h3>
                <p>Bien sûr ! On vous accompagne pas à pas, on explique tout simplement. Vous n'avez besoin d'aucune compétence technique.</p>
            </div>
            <div class="faq-item">
                <h3>Combien de temps pour avoir mon site ?</h3>
                <p>Comptez 2 à 3 semaines pour un site vitrine, 4 à 6 semaines pour une boutique en ligne.</p>
            </div>
            <div class="faq-item">
                <h3>Et après, vous nous laissez ?</h3>
                <p>Jamais ! On vous forme, on reste disponibles pour les questions, les modifications, les pépins.</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ========== STYLES PRINCIPAUX ========== */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --accent: #06b6d4;
    --text-dark: #0f172a;
    --text-gray: #475569;
    --text-light: #64748b;
    --border-light: #e2e8f0;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.section {
    padding: 80px 0;
}

/* ========== HERO BANNIÈRE ========== */
.hero-banner {
    min-height: 80vh;
    background-image: url('{{ asset("assets/images/hero-bg.png") }}');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.7) 100%);
}

.hero-banner .container {
    position: relative;
    z-index: 2;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    color: white;
}

.hero-content h1 {
    font-size: clamp(36px, 5vw, 56px);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    color: white;
}

.hero-content h1 .highlight {
    color: var(--accent);
}

.hero-content p {
    font-size: 18px;
    margin-bottom: 32px;
    opacity: 0.9;
}

/* ========== BOUTONS ========== */
.btn-primary {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline-light {
    display: inline-block;
    background: transparent;
    color: white;
    padding: 12px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    border: 2px solid white;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: white;
    color: var(--primary);
}

.btn-block {
    width: 100%;
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

/* ========== ERREURS FORMULAIRE ========== */
.error-border {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}

.field-error {
    color: #ef4444;
    font-size: 12px;
    margin-top: 5px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-message {
    transition: all 0.3s ease;
    margin-top: 20px;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
    font-size: 14px;
}

.form-message.success {
    background-color: #d1fae5;
    color: #10b981;
    border: 1px solid #a7f3d0;
}

.form-message.error {
    background-color: #fee2e2;
    color: #ef4444;
    border: 1px solid #fecaca;
}

.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
    margin-right: 8px;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ========== TYPOGRAPHIE ========== */
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

/* ========== SERVICES ========== */
.section-header {
    text-align: center;
    margin-bottom: 50px;
}

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

/* ========== PORTFOLIO ========== */
.portfolio-bg {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

.project-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.project-card:hover {
    transform: translateY(-8px);
}

.project-img {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.project-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.project-card:hover .project-img img {
    transform: scale(1.05);
}

.project-overlay {
    position: absolute;
    inset: 0;
    background: rgba(99,102,241,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-card:hover .project-overlay {
    opacity: 1;
}

.project-btn {
    background: white;
    color: var(--primary);
    padding: 10px 20px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
}

.project-body {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.project-category {
    display: inline-block;
    background: rgba(99,102,241,0.1);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 12px;
    width: fit-content;
}

.project-body h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.project-description {
    color: var(--text-gray);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.tech-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tech-tag {
    background: var(--bg-light);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 500;
}

.project-client {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid var(--border-light);
    font-size: 13px;
    color: var(--text-gray);
}

.project-client i {
    color: var(--primary);
}

/* ========== TÉMOIGNAGES ========== */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.testimonial-card {
    background: var(--bg-white);
    border-radius: 24px;
    padding: 30px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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

/* ========== NOTES ÉTOILES ========== */
.testimonial-rating {
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.testimonial-rating i {
    font-size: 14px;
}

.testimonial-rating i.fa-star {
    color: #f59e0b;
}

.testimonial-rating i.fa-star-o {
    color: #cbd5e1;
}

.testimonial-rating .rating-text {
    font-size: 11px;
    color: var(--text-light);
    margin-left: 4px;
}

/* ========== POURQUOI NOUS ========== */
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

/* ========== CONTACT ========== */
.contact-section {
    background: var(--bg-light);
}

.contact-card {
    background: white;
    border-radius: 32px;
    padding: 50px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.contact-header {
    text-align: center;
    margin-bottom: 40px;
}

.contact-header h2 {
    margin-bottom: 8px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 50px;
}

.contact-infos {
    background: var(--bg-light);
    border-radius: 24px;
    padding: 30px;
}

.contact-info-item {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-light);
}

.contact-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
}

.contact-info-item h4 {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--primary);
    margin-bottom: 8px;
}

.contact-info-item a,
.contact-info-item p {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    text-decoration: none;
}

.contact-info-item a:hover {
    color: var(--primary);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid var(--border-light);
    border-radius: 16px;
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: inherit;
    background: var(--bg-light);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
}

/* ========== FAQ ========== */
.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.faq-item {
    background: var(--bg-white);
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

.faq-item p {
    color: var(--text-gray);
    line-height: 1.6;
}

/* ========== EMPTY STATE ========== */
.empty-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 20px;
    color: var(--text-gray);
}

.empty-state.light {
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.7);
}

.text-center {
    text-align: center;
}

.mt-4 {
    margin-top: 40px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .section {
        padding: 60px 0;
    }

    .story-grid,
    .why-grid,
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .testimonials-grid,
    .faq-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .hero-banner {
        min-height: 60vh;
    }

    .carousel-slide {
        flex: 0 0 calc(50% - 15px);
    }
}

@media (max-width: 768px) {
    .testimonials-grid,
    .faq-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .section {
        padding: 50px 0;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .contact-card {
        padding: 30px 20px;
    }

    .hero-content h1 {
        font-size: 28px;
    }

    .hero-banner {
        min-height: 50vh;
    }

    .carousel-container {
        padding: 0 40px;
    }

    .carousel-slide {
        flex: 0 0 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ========== CARROUSEL ==========
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
            dot.setAttribute('aria-label', 'Page ' + (i + 1));
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

        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.carousel-dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
        }
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

    let touchStartX = 0;
    container.addEventListener('touchstart', e => {
        touchStartX = e.touches[0].clientX;
        stopAutoplay();
    }, { passive: true });

    container.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? next() : prev();
        }
        startAutoplay();
    });

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

    carousels[id] = { next, prev, goToSlide };
}

function moveCarousel(id, direction) {
    if (carousels[id]) {
        direction > 0 ? carousels[id].next() : carousels[id].prev();
    }
}

// ========== FORMULAIRE AJAX ==========
const form = document.getElementById('contactForm');
const msg = document.getElementById('formMessage');

if (form) {
    function displayFieldErrors(errors) {
        document.querySelectorAll('.field-error').forEach(el => {
            el.style.display = 'none';
            el.innerHTML = '';
        });
        document.querySelectorAll('.error-border').forEach(el => {
            el.classList.remove('error-border');
        });

        for (const [field, messages] of Object.entries(errors)) {
            const errorDiv = document.getElementById(`error-${field}`);
            if (errorDiv) {
                errorDiv.innerHTML = '⚠️ ' + messages.join(', ');
                errorDiv.style.display = 'block';
            }

            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('error-border');
            }
        }
    }

    function showMessage(message, isSuccess = false) {
        msg.innerHTML = isSuccess ? '✓ ' + message : '⚠️ ' + message;
        msg.className = 'form-message ' + (isSuccess ? 'success' : 'error');
        msg.style.display = 'block';
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Envoi en cours...';
        msg.style.display = 'none';

        document.querySelectorAll('.field-error').forEach(el => {
            el.style.display = 'none';
            el.innerHTML = '';
        });
        document.querySelectorAll('.error-border').forEach(el => {
            el.classList.remove('error-border');
        });

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showMessage(data.message || 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les 24h.', true);
                form.reset();
                msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                if (data.errors) {
                    displayFieldErrors(data.errors);
                    showMessage(data.message || 'Veuillez corriger les erreurs du formulaire.', false);
                } else {
                    throw new Error(data.message || 'Une erreur est survenue lors de l\'envoi.');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            showMessage(error.message || 'Une erreur technique est survenue. Veuillez nous appeler directement ou réessayer plus tard.', false);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('servicesCarousel')) initCarousel('servicesCarousel');
    if (document.getElementById('portfolioCarousel')) initCarousel('portfolioCarousel');
});
</script>
@endpush
