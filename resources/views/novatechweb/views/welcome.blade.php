<?php
// resources/views/novatechweb/views/welcome.blade.php
?>
@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $company->name ?? 'Nova Tech Bénin' }} - Agence Web & Solutions Digitales
@endsection

@section('content')

<!-- ========== HERO : VERSION PSYCHOLOGIQUE OPTIMISÉE ========== -->
<section class="hero-banner">
    <div class="hero-overlay"></div>
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="hero-content">
            <h1>{{ $company->hero_title ?? 'Vous voulez <span class="highlight">plus de clients ?</span><br>On vous offre la solution' }}</h1>
            <p>{{ $company->hero_description ?? 'Arrêtez de perdre du temps et de l\'argent avec des promesses non tenues. Notre équipe de passionnés construit pour vous un <strong>site web ou une application qui vous ressemble et qui rapporte</strong>. Simple, transparent, efficace.' }}</p>
            <div class="hero-actions">
                <a href="#contact" class="btn btn-primary">
                    Discutons de votre projet
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline">
                    En savoir plus sur nous
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

<!-- ========== PRÉSENTATION : NOTRE HISTOIRE ========== -->
<section id="about" class="section story">
    <div class="container">
        <div class="story-grid">
            <div class="story-text">
                <span class="label">Notre histoire</span>
                <h2>{{ $company->about_title ?? 'On a créé Nova Tech parce que <span class="accent">le web peut être simple</span>' }}</h2>
                <p>{{ $company->about_description_1 ?? 'Nous sommes une petite équipe de passionnés basée au Bénin. On aime ce qu\'on fait, et ça se voit. Chaque projet est unique, chaque client est un ami qu\'on aide à réussir en ligne.' }}</p>
                <p>{{ $company->about_description_2 ?? 'Pas de machines, pas de templates copiés-collés. Juste du travail fait avec le cœur, de l\'écoute et des résultats qui vous ressemblent.' }}</p>
                <div class="story-footer">
                    <div class="story-signature">
                        <span class="signature-text">{{ $company->signature_text ?? 'L\'équipe Nova Tech' }}</span>
                    </div>
                    <a href="{{ route('about') }}" class="btn-about-more">
                        En savoir plus sur notre histoire <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="story-image">
                <img src="{{ $company->about_image ? asset('storage/' . $company->about_image) : asset('assets/images/team-working.png') }}" alt="Notre équipe au travail" onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES : CARROUSEL ========== -->
<section class="section services">
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
                        <div class="service-icon">
                            @if($service->icon)
                                <i class="fa {{ $service->icon }}" style="color: {{ $service->icon_color ?? '#6366f1' }}"></i>
                            @else
                                <i class="fa fa-code" style="color: #6366f1"></i>
                            @endif
                        </div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ Str::limit($service->description, 100) }}</p>
                        <div class="service-price">À partir de sur devis</div>
                        <a href="{{ route('services') }}" class="service-link">En savoir plus →</a>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('servicesCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('servicesCarousel', 1)">›</button>
            <div class="carousel-dots" id="servicesDots"></div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('services') }}" class="btn btn-outline-primary">Voir tous nos services →</a>
        </div>
        @else
        <div class="empty-state">
            <p>Nos services arrivent bientôt</p>
        </div>
        @endif
    </div>
</section>

<!-- ========== PORTFOLIO : CARROUSEL ========== -->
<section class="section portfolio-bg">
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
                            'application-mobile' => 'Application Mobile',
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
                                <p class="project-description">{{ Str::limit($portfolio->description ?? 'Un projet réalisé avec passion pour un client satisfait.', 60) }}</p>
                                @if($portfolio->client)
                                <div class="project-client">
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
            <button class="carousel-btn prev" onclick="moveCarousel('portfolioCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('portfolioCarousel', 1)">›</button>
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

<!-- ========== NOS OUTILS & TECHNOLOGIES - CARROUSEL ========== -->
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

<!-- ========== TÉMOIGNAGES : CARROUSEL ========== -->
<section class="section testimonials">
    <div class="container">
        <div class="section-header">
            <span class="label">Ils témoignent</span>
            <h2>Ce qu'ils disent de <span class="accent">notre travail</span></h2>
        </div>

        @if(isset($testimonials) && !$testimonials->isEmpty())
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
        @else
        <div class="carousel-container" id="testimonialsCarousel">
            <div class="carousel-track">
                <div class="carousel-slide">
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
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="rating-text">5/5</span>
                        </div>
                    </div>
                </div>
                <div class="carousel-slide">
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
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-empty">☆</span>
                            <span class="rating-text">4/5</span>
                        </div>
                    </div>
                </div>
                <div class="carousel-slide">
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
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="rating-text">5/5</span>
                        </div>
                    </div>
                </div>
                <div class="carousel-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>"Très professionnel et à l'écoute. Le site est exactement ce que je voulais. Je recommande vivement !"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-img">
                                <div class="author-placeholder">SK</div>
                            </div>
                            <div class="author-details">
                                <strong>Sophie K.</strong>
                                <span>Consultante - Cotonou</span>
                            </div>
                        </div>
                        <div class="testimonial-rating">
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="star star-filled">★</span>
                            <span class="rating-text">5/5</span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-btn prev" onclick="moveCarousel('testimonialsCarousel', -1)">‹</button>
            <button class="carousel-btn next" onclick="moveCarousel('testimonialsCarousel', 1)">›</button>
            <div class="carousel-dots" id="testimonialsDots"></div>
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
                            <p>Votre projet en ligne en 2-4 semaines. Pas de promesses en l'air.</p>
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
                <div class="why-footer">
                    <a href="{{ route('about') }}" class="btn-why-more">
                        Découvrir nos valeurs <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FAQ (DYNAMIQUE DEPUIS LA BDD AVEC CKEDITOR) ========== -->
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
            <a href="#contact" class="btn btn-outline-primary">Vous avez d'autres questions ? Contactez-nous</a>
        </div>
    </div>
</section>
@else
<section class="section faq-section">
    <div class="container">
        <div class="section-header">
            <span class="label">Questions fréquentes</span>
            <h2>On répond à vos <span class="accent">questions</span></h2>
        </div>

        <div class="faq-grid">
            <div class="faq-item">
                <h3>Ça coûte combien un site web ou une application ?</h3>
                <p>Chaque projet est différent. On vous fait un devis personnalisé et gratuit après avoir échangé avec vous. Pas de surprise, pas de frais cachés.</p>
            </div>
            <div class="faq-item">
                <h3>Je ne connais rien au web, c'est possible ?</h3>
                <p>Bien sûr ! On vous accompagne pas à pas, on explique tout simplement. Vous n'avez besoin d'aucune compétence technique.</p>
            </div>
            <div class="faq-item">
                <h3>Combien de temps pour avoir mon site ou application ?</h3>
                <p>Comptez 2 à 3 semaines pour un site vitrine, 4 à 6 semaines pour une boutique en ligne ou une application.</p>
            </div>
            <div class="faq-item">
                <h3>Et après, vous nous laissez ?</h3>
                <p>Jamais ! On vous forme, on reste disponibles pour les questions, les modifications, les pépins.</p>
            </div>
        </div>

        <div class="faq-more">
            <a href="#contact" class="btn btn-outline-primary">Vous avez d'autres questions ? Contactez-nous</a>
        </div>
    </div>
</section>
@endif

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
                                <option value="site-vitrine" {{ old('service') == 'site-vitrine' ? 'selected' : '' }}>Site vitrine</option>
                                <option value="ecommerce" {{ old('service') == 'ecommerce' ? 'selected' : '' }}>Boutique en ligne (E-commerce)</option>
                                <option value="application-web" {{ old('service') == 'application-web' ? 'selected' : '' }}>Application web</option>
                                <option value="application-mobile" {{ old('service') == 'application-mobile' ? 'selected' : '' }}>Application mobile</option>
                                <option value="refonte" {{ old('service') == 'refonte' ? 'selected' : '' }}>Refonte de site existant</option>
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
    min-height: 100vh;
    background-image: url('{{ $company->banner_image ? asset("storage/" . $company->banner_image) : asset("assets/images/hero-bg.png") }}');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(15,23,42,0.8) 50%, rgba(0,0,0,0.85) 100%);
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.hero-banner .container {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-content {
    max-width: 900px;
    margin: 0 auto;
    color: white;
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

.hero-content h1 {
    font-size: clamp(36px, 6vw, 64px);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    color: white;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-content h1 .highlight {
    color: var(--accent);
    position: relative;
    display: inline-block;
}

.hero-content h1 .highlight::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--primary));
    border-radius: 3px;
}

.hero-content p {
    font-size: clamp(16px, 3vw, 20px);
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    color: rgba(255,255,255,0.95);
}

.hero-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

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

.hero-guarantee {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 14px;
    color: rgba(255,255,255,0.8);
}

.hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    z-index: 3;
    pointer-events: none;
}

.hero-wave svg {
    width: 100%;
    height: auto;
    display: block;
}

/* ========== RESPONSIVE HERO ========== */
@media (max-width: 992px) {
    .hero-banner {
        min-height: 90vh;
    }
}

@media (max-width: 768px) {
    .hero-banner {
        min-height: auto;
        padding: 100px 0 60px;
    }

    .hero-content h1 {
        margin-bottom: 20px;
        font-size: 32px;
    }

    .hero-content p {
        margin-bottom: 30px;
        padding: 0 10px;
        font-size: 16px;
    }

    .hero-actions {
        gap: 15px;
        margin-bottom: 25px;
        flex-direction: column;
        align-items: center;
    }

    .btn-primary,
    .btn-outline {
        padding: 12px 28px;
        font-size: 15px;
        width: 100%;
        max-width: 280px;
    }

    .hero-guarantee {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .hero-banner {
        padding: 80px 0 50px;
    }

    .hero-content h1 {
        font-size: 28px;
        line-height: 1.3;
    }

    .hero-content p {
        font-size: 14px;
        padding: 0 5px;
    }

    .btn-primary,
    .btn-outline {
        padding: 10px 24px;
        font-size: 14px;
        max-width: 260px;
    }
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

.story-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 32px;
}

.btn-about-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-about-more:hover {
    gap: 12px;
    color: var(--primary-dark);
}

/* ========== SERVICES ========== */
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

.service-icon i {
    font-size: 40px;
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

.btn-outline-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--primary);
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 2px solid var(--primary);
    cursor: pointer;
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.text-center {
    text-align: center;
}

.mt-4 {
    margin-top: 40px;
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

/* ========== OUTILS & TECHNOLOGIES ========== */
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

.why-footer {
    margin-top: 16px;
}

.btn-why-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-why-more:hover {
    gap: 12px;
    color: var(--primary-dark);
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

.btn-block {
    width: 100%;
}

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

    .faq-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .carousel-slide {
        flex: 0 0 calc(50% - 15px);
    }
}

@media (max-width: 768px) {
    .faq-grid {
        grid-template-columns: 1fr;
    }

    .contact-card {
        padding: 30px 20px;
    }

    .carousel-container {
        padding: 0 40px;
    }

    .carousel-slide {
        flex: 0 0 100%;
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

    .story-footer {
        flex-direction: column;
        align-items: flex-start;
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
        btn.innerHTML = 'Envoi en cours...';
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

// Initialisation des carrousels
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('servicesCarousel')) initCarousel('servicesCarousel');
    if (document.getElementById('portfolioCarousel')) initCarousel('portfolioCarousel');
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

document.querySelectorAll('.service-card, .project-card, .testimonial-card, .faq-item, .tool-card, .why-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>
@endpush
