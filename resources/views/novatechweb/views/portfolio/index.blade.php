@extends('novatechweb.views.layouts.app')

@section('title')
    Notre Portfolio - {{ $company->name ?? 'Nova Tech Bénin' }}
@endsection

@section('content')

<!-- ========== PORTFOLIO SECTION ========== -->
<div id="portfolio" class="portfolio-section">
    <div class="container">
        <div class="portfolio-header">
            <span class="label">Nos réalisations</span>
            <h2>Des projets qui <span class="accent">font la différence</span></h2>
            <p class="subtitle">Découvrez les sites web et applications que nous avons créés pour nos clients au Bénin et ailleurs.</p>
        </div>

        <!-- Filtres -->
        <div class="portfolio-filters">
            <button class="filter-btn active" data-filter="all">Tous les projets</button>
            <button class="filter-btn" data-filter="site-vitrine">Sites Vitrine</button>
            <button class="filter-btn" data-filter="e-commerce">E-commerce</button>
            <button class="filter-btn" data-filter="application-web">Applications Web</button>
            <button class="filter-btn" data-filter="maintenance">Maintenance</button>
            <button class="filter-btn" data-filter="optimisation">Optimisation</button>
            <button class="filter-btn" data-filter="autre">Autre</button>
        </div>

        <!-- Grille des projets -->
        <div class="portfolio-grid" id="portfolio-grid">
            @forelse($portfolios as $portfolio)
                @php
                    $categoryNames = [
                        'site-vitrine' => 'Site Vitrine',
                        'e-commerce' => 'E-commerce',
                        'application-web' => 'Application Web',
                        'maintenance' => 'Maintenance',
                        'optimisation' => 'Optimisation',
                        'autre' => 'Autre'
                    ];
                    $categoryName = $categoryNames[$portfolio->category] ?? 'Projet Web';

                    // Gestion des technologies (array ou JSON)
                    $technologies = [];
                    if (is_array($portfolio->technologies)) {
                        $technologies = $portfolio->technologies;
                    } elseif (is_string($portfolio->technologies)) {
                        $technologies = json_decode($portfolio->technologies, true) ?? [];
                    }

                    // Gestion de l'image
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

                <div class="portfolio-item" data-category="{{ $portfolio->category }}">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $portfolio->title }}"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('assets/images/portfolio-placeholder.jpg') }}'">
                            <div class="project-category-badge">{{ $categoryName }}</div>
                            <div class="project-overlay">
                                <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="project-link">
                                    <i class="fa fa-eye"></i>
                                    <span>Voir le projet</span>
                                </a>
                            </div>
                        </div>
                        <div class="project-info">
                            <h3>{{ $portfolio->title }}</h3>
                            <p>{{ Str::limit($portfolio->description ?? 'Un projet réalisé avec passion pour un client satisfait.', 80) }}</p>

                            @if(!empty($technologies) && count($technologies) > 0)
                            <div class="project-tech">
                                @foreach(array_slice($technologies, 0, 3) as $tech)
                                    <span class="tech-tag">{{ $tech }}</span>
                                @endforeach
                                @if(count($technologies) > 3)
                                    <span class="tech-tag">+{{ count($technologies) - 3 }}</span>
                                @endif
                            </div>
                            @endif

                            <div class="project-footer">
                                <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="btn-details">
                                    En savoir plus <i class="fa fa-arrow-right"></i>
                                </a>
                                @if($portfolio->url)
                                    <a href="{{ $portfolio->url }}" target="_blank" class="btn-live" rel="noopener noreferrer">
                                        <i class="fa fa-external-link"></i> Live
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-portfolio">
                    <i class="fa fa-folder-open"></i>
                    <h3>Portfolio en cours de construction</h3>
                    <p>Nos réalisations arrivent très bientôt. En attendant, contactez-nous pour découvrir nos travaux.</p>
                    <a href="{{ route('home') }}#contact" class="btn btn-primary">Soumettre une demande</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($portfolios->hasPages())
        <div class="pagination-wrapper">
            {{ $portfolios->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ========== FAQ SECTION ========== -->
<section class="faq-section">
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

<!-- ========== CTA POUR PROJET ========== -->
<section class="cta-project">
    <div class="container">
        <div class="cta-content">
            <h3>Vous avez un projet en tête ?</h3>
            <p>Parlons de votre idée et créons ensemble quelque chose de formidable.</p>
            <a href="{{ route('home') }}#contact" class="btn btn-primary">Discutons de votre projet →</a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ========== PORTFOLIO SECTION STYLES ========== */

.portfolio-section {
    padding: 100px 0 80px;
    background: var(--bg-light);
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* En-tête */
.portfolio-header {
    text-align: center;
    margin-bottom: 50px;
}

.portfolio-header .label {
    display: inline-block;
    background: rgba(99,102,241,0.1);
    color: var(--primary);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
}

.portfolio-header h2 {
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-dark);
}

.portfolio-header h2 .accent {
    color: var(--accent);
}

.portfolio-header .subtitle {
    color: var(--text-gray);
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto;
}

/* Filtres */
.portfolio-filters {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 50px;
}

.filter-btn {
    background: white;
    border: 2px solid var(--border-light);
    padding: 10px 24px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.filter-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Grille Portfolio */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}

/* Carte Projet */
.project-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.project-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 35px -12px rgba(0,0,0,0.15);
}

/* Image du projet */
.project-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.08);
}

/* Badge catégorie */
.project-category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--primary);
    color: white;
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

/* Overlay au survol */
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

.project-link {
    display: flex;
    align-items: center;
    gap: 10px;
    background: white;
    color: var(--primary);
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.project-link:hover {
    transform: scale(1.05);
    background: var(--primary);
    color: white;
}

/* Informations du projet */
.project-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.project-info h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-dark);
    line-height: 1.4;
}

.project-info p {
    font-size: 14px;
    color: var(--text-gray);
    line-height: 1.6;
    margin-bottom: 15px;
}

/* Technologies */
.project-tech {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
}

.tech-tag {
    background: var(--bg-light);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 500;
}

/* Footer de la carte */
.project-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid var(--border-light);
}

.btn-details {
    color: var(--primary);
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: gap 0.3s ease;
}

.btn-details:hover {
    gap: 10px;
    color: var(--primary-dark);
}

.btn-live {
    background: #25d366;
    color: white;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-live:hover {
    background: #20b859;
    transform: scale(1.02);
}

/* Pagination */
.pagination-wrapper {
    margin-top: 50px;
    display: flex;
    justify-content: center;
}

.pagination-wrapper .pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination-wrapper .page-item .page-link {
    padding: 8px 16px;
    border-radius: 8px;
    background: white;
    color: var(--text-dark);
    text-decoration: none;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.pagination-wrapper .page-item.active .page-link {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-wrapper .page-item .page-link:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* État vide */
.empty-portfolio {
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 20px;
    grid-column: 1 / -1;
}

.empty-portfolio i {
    font-size: 64px;
    color: var(--primary);
    opacity: 0.5;
    margin-bottom: 20px;
}

.empty-portfolio h3 {
    font-size: 24px;
    margin-bottom: 12px;
    color: var(--text-dark);
}

.empty-portfolio p {
    color: var(--text-gray);
    margin-bottom: 25px;
}

/* Animation filtrage */
.portfolio-item {
    transition: all 0.4s ease;
}

.portfolio-item.hide {
    display: none;
}

.portfolio-item.show {
    display: block;
    animation: fadeInUp 0.5s ease forwards;
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

/* ========== FAQ SECTION ========== */
.faq-section {
    padding: 80px 0;
    background: var(--bg-white);
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-header .label {
    display: inline-block;
    background: rgba(99,102,241,0.1);
    color: var(--primary);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
}

.section-header h2 {
    font-size: clamp(28px, 4vw, 38px);
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-dark);
}

.section-header h2 .accent {
    color: var(--accent);
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
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
}

.faq-item h3 {
    font-size: 18px;
    margin-bottom: 12px;
    color: var(--text-dark);
}

.faq-item p {
    color: var(--text-gray);
    line-height: 1.6;
    margin: 0;
}

/* ========== CTA SECTION ========== */
.cta-project {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    padding: 70px 0;
    text-align: center;
}

.cta-content h3 {
    font-size: 28px;
    color: white;
    margin-bottom: 15px;
}

.cta-content p {
    color: rgba(255,255,255,0.8);
    margin-bottom: 25px;
    font-size: 16px;
}

.cta-content .btn-primary {
    background: var(--primary);
    color: white;
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.cta-content .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    gap: 14px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .portfolio-section {
        padding: 70px 0 50px;
    }

    .portfolio-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .faq-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .portfolio-filters {
        gap: 8px;
    }

    .filter-btn {
        padding: 6px 16px;
        font-size: 12px;
    }

    .portfolio-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .project-image {
        height: 200px;
    }

    .cta-project {
        padding: 50px 0;
    }

    .cta-content h3 {
        font-size: 22px;
    }

    .faq-section {
        padding: 60px 0;
    }

    .faq-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .faq-item {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .portfolio-section {
        padding: 50px 0 40px;
    }

    .portfolio-header h2 {
        font-size: 24px;
    }

    .filter-btn {
        padding: 5px 12px;
        font-size: 11px;
    }

    .project-info h3 {
        font-size: 16px;
    }

    .project-info p {
        font-size: 13px;
    }

    .faq-item h3 {
        font-size: 16px;
    }

    .faq-item p {
        font-size: 14px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // FILTRES PORTFOLIO - CORRECTION
    // ============================================
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    console.log('Filtres trouvés:', filterButtons.length);
    console.log('Projets trouvés:', portfolioItems.length);

    function filterProjects(filterValue) {
        let visibleCount = 0;

        portfolioItems.forEach(item => {
            const category = item.getAttribute('data-category');

            if (filterValue === 'all' || category === filterValue) {
                item.classList.remove('hide');
                item.classList.add('show');
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.classList.remove('show');
                item.classList.add('hide');
                item.style.display = 'none';
            }
        });

        console.log('Projets visibles pour le filtre "' + filterValue + '":', visibleCount);

        // Afficher/masquer le message "aucun résultat"
        const grid = document.querySelector('.portfolio-grid');
        const existingNoResult = document.querySelector('.no-result-message');

        if (visibleCount === 0 && portfolioItems.length > 0) {
            if (!existingNoResult) {
                const noResultDiv = document.createElement('div');
                noResultDiv.className = 'empty-portfolio no-result-message';
                noResultDiv.style.gridColumn = '1 / -1';
                noResultDiv.innerHTML = `
                    <i class="fa fa-search"></i>
                    <h3>Aucun projet trouvé</h3>
                    <p>Aucun projet ne correspond à ce filtre. Essayez un autre filtre.</p>
                `;
                grid.appendChild(noResultDiv);
            }
        } else if (existingNoResult) {
            existingNoResult.remove();
        }
    }

    if (filterButtons.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Mettre à jour la classe active
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');
                filterProjects(filterValue);
            });
        });
    }

    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.portfolio-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
});
</script>
@endpush
