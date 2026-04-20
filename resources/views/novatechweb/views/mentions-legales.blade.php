@extends('novatechweb.views.layouts.app')

@section('title')
    {{ $company->name ?? 'Nova Tech Bénin' }} - Mentions légales
@endsection

@section('content')

<!-- ========== HERO ========== -->
<section class="page-hero">
    <div class="hero-overlay"></div>
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="hero-content">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Accueil</a>
                <span class="separator">/</span>
                <span class="current">Mentions légales</span>
            </nav>
            <h1>Mentions <span class="highlight">légales</span></h1>
            <p>Conformément aux dispositions de la loi n° 2017-20 du 20 avril 2018 sur l'économie numérique</p>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="#f8fafc" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- ========== CONTENU MENTIONS LÉGALES ========== -->
<section class="section legal-section">
    <div class="container">
        <div class="legal-grid">
            <!-- Éditeur du site -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-building"></i>
                </div>
                <h2>Éditeur du site</h2>
                <div class="legal-card-content">
                    <p><strong>{{ $company->name ?? 'Nova Tech Bénin' }}</strong></p>
                    <p><i class="fa fa-gavel"></i> Forme juridique : {{ $company->legal_form ?? 'SARL' }}</p>
                    <p><i class="fa fa-money"></i> Capital social : {{ $company->capital ?? '1 000 000 FCFA' }}</p>
                    <p><i class="fa fa-id-card"></i> RCCM : {{ $company->rccm ?? 'RB/COT/2023/XXXX' }}</p>
                    <p><i class="fa fa-file-text"></i> IFU : {{ $company->ifu ?? '3202300000000' }}</p>
                    <p><i class="fa fa-map-marker"></i> Siège social : {{ $company->legal_address ?? $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
                    <p><i class="fa fa-envelope"></i> Email : <a href="mailto:{{ $company->email ?? 'contact@novatech.bj' }}">{{ $company->email ?? 'contact@novatech.bj' }}</a></p>
                    <p><i class="fa fa-phone"></i> Téléphone : <a href="tel:{{ $company->phone ?? '+22966185595' }}">{{ $company->phone ?? '+229 66 18 55 95' }}</a></p>
                    <p><i class="fa fa-user-tie"></i> Directeur de publication : {{ $company->director ?? 'Le Directeur Général' }}</p>
                    @if($company->cnie_number ?? false)
                    <p><i class="fa fa-id-card"></i> CNIE : {{ $company->cnie_number }}</p>
                    @endif
                    @if($company->trade_register ?? false)
                    <p><i class="fa fa-book"></i> Registre du commerce : {{ $company->trade_register }}</p>
                    @endif
                    @if($company->vat_number ?? false)
                    <p><i class="fa fa-percent"></i> N° TVA : {{ $company->vat_number }}</p>
                    @endif
                </div>
            </div>

            <!-- Hébergement -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-server"></i>
                </div>
                <h2>Hébergement</h2>
                <div class="legal-card-content">
                    <p><strong>{{ $company->hosting_name ?? 'Hostinger International Ltd' }}</strong></p>
                    <p><i class="fa fa-map-marker"></i> Adresse : {{ $company->hosting_address ?? 'Šeimininkių g. 3, LT-09231 Vilnius, Lituanie' }}</p>
                    <p><i class="fa fa-phone"></i> Téléphone : {{ $company->hosting_phone ?? '+370 645 03378' }}</p>
                    <p><i class="fa fa-globe"></i> Site web : <a href="{{ $company->hosting_url ?? 'https://www.hostinger.com' }}" target="_blank" rel="noopener noreferrer">{{ $company->hosting_url ?? 'www.hostinger.com' }}</a></p>
                </div>
            </div>

            <!-- Propriété intellectuelle -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-copyright"></i>
                </div>
                <h2>Propriété intellectuelle</h2>
                <div class="legal-card-content">
                    <p>L'ensemble des éléments composant ce site (textes, images, logos, icônes, vidéos, etc.) sont la propriété exclusive de <strong>{{ $company->name ?? 'Nova Tech Bénin' }}</strong> sauf mention contraire.</p>
                    <p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable.</p>
                </div>
            </div>

            <!-- Responsabilité -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <h2>Responsabilité</h2>
                <div class="legal-card-content">
                    <p>{{ $company->name ?? 'Nova Tech Bénin' }} met tout en œuvre pour offrir aux utilisateurs des informations et des outils disponibles et vérifiés, mais ne saurait être tenu pour responsable des erreurs, d'une absence de disponibilité des informations et/ou de la présence de virus sur son site.</p>
                </div>
            </div>

            <!-- Données personnelles -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-lock"></i>
                </div>
                <h2>Données personnelles</h2>
                <div class="legal-card-content">
                    <p>Conformément à la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, de modification et de suppression des données qui vous concernent.</p>
                    <p>Vous pouvez exercer ce droit en nous contactant à : <a href="mailto:{{ $company->email ?? 'contact@novatech.bj' }}">{{ $company->email ?? 'contact@novatech.bj' }}</a></p>
                    @if($company->data_protection_officer ?? false)
                    <p><i class="fa fa-user-shield"></i> Délégué à la protection des données : {{ $company->data_protection_officer }}</p>
                    @endif
                </div>
            </div>

            <!-- Cookies -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-cookie-bite"></i>
                </div>
                <h2>Cookies</h2>
                <div class="legal-card-content">
                    <p>Le site utilise des cookies pour améliorer l'expérience utilisateur. En naviguant sur notre site, vous acceptez l'utilisation de cookies.</p>
                    <p>Vous pouvez à tout moment paramétrer votre navigateur pour refuser les cookies.</p>
                </div>
            </div>

            <!-- Droit applicable -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-gavel"></i>
                </div>
                <h2>Droit applicable</h2>
                <div class="legal-card-content">
                    <p>Les présentes mentions légales sont régies par le droit béninois. En cas de litige, les tribunaux de Cotonou sont seuls compétents.</p>
                </div>
            </div>

            <!-- Nous contacter -->
            <div class="legal-card">
                <div class="legal-card-icon">
                    <i class="fa fa-envelope"></i>
                </div>
                <h2>Nous contacter</h2>
                <div class="legal-card-content">
                    <p>Pour toute question relative aux présentes mentions légales, vous pouvez nous contacter :</p>
                    <p><i class="fa fa-envelope"></i> Email : <a href="mailto:{{ $company->email ?? 'contact@novatech.bj' }}">{{ $company->email ?? 'contact@novatech.bj' }}</a></p>
                    <p><i class="fa fa-phone"></i> Téléphone : <a href="tel:{{ $company->phone ?? '+22966185595' }}">{{ $company->phone ?? '+229 66 18 55 95' }}</a></p>
                    <p><i class="fa fa-map-marker"></i> Adresse : {{ $company->legal_address ?? $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
                </div>
            </div>
        </div>

        <div class="legal-update">
            <i class="fa fa-clock-o"></i>
            <span>Dernière mise à jour : {{ date('d/m/Y') }}</span>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ========== PAGE HERO ========== */
.page-hero {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    padding: 140px 0 100px;
    overflow: hidden;
}

.page-hero .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.15), transparent);
}

.page-hero .hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.page-hero .container {
    position: relative;
    z-index: 2;
}

.page-hero .hero-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
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

.breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
}

.breadcrumb a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: var(--primary-light);
}

.breadcrumb .separator {
    margin: 0 8px;
    color: rgba(255, 255, 255, 0.5);
}

.breadcrumb .current {
    color: var(--primary-light);
}

.page-hero .hero-content h1 {
    font-size: clamp(36px, 5vw, 56px);
    font-weight: 800;
    margin-bottom: 20px;
}

.page-hero .hero-content h1 .highlight {
    color: var(--accent);
}

.page-hero .hero-content p {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.8);
    max-width: 600px;
    margin: 0 auto;
}

.page-hero .hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    z-index: 3;
    pointer-events: none;
}

.page-hero .hero-wave svg {
    width: 100%;
    height: auto;
    display: block;
}

/* ========== SECTION MENTIONS LÉGALES ========== */
.legal-section {
    padding: 80px 0;
    background: var(--bg-light);
}

.legal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.legal-card {
    background: white;
    border-radius: 24px;
    padding: 30px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-light);
    height: 100%;
}

.legal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.legal-card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.legal-card-icon i {
    font-size: 28px;
    color: var(--primary);
}

.legal-card h2 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-dark);
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border-light);
}

.legal-card-content p {
    margin-bottom: 12px;
    line-height: 1.7;
    color: var(--text-gray);
    font-size: 15px;
}

.legal-card-content p:last-child {
    margin-bottom: 0;
}

.legal-card-content p i {
    width: 20px;
    color: var(--primary);
    margin-right: 8px;
}

.legal-card-content a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.3s;
}

.legal-card-content a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.legal-card-content strong {
    color: var(--text-dark);
}

.legal-update {
    text-align: center;
    margin-top: 50px;
    padding: 20px;
    background: white;
    border-radius: 16px;
    border: 1px solid var(--border-light);
    color: var(--text-light);
    font-size: 14px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.legal-update i {
    margin-right: 8px;
    color: var(--primary);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .legal-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-hero {
        padding: 100px 0 60px;
    }

    .legal-section {
        padding: 60px 0;
    }

    .legal-grid {
        grid-template-columns: 1fr;
    }

    .legal-card {
        padding: 20px;
    }

    .legal-card h2 {
        font-size: 18px;
    }

    .legal-card-icon {
        width: 50px;
        height: 50px;
    }

    .legal-card-icon i {
        font-size: 24px;
    }

    .legal-card-content p {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .legal-card-content p i {
        width: 18px;
        margin-right: 6px;
    }
}
</style>
@endpush
