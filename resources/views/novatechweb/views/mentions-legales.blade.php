@extends('novatechweb.views.layouts.app')

@section('title', 'Mentions légales - ' . ($company->name ?? 'Nova Tech Bénin'))

@section('content')
<section class="legal-section">
    <div class="container">
        <div class="legal-header">
            <span class="label">Informations légales</span>
            <h1>Mentions <span class="accent">légales</span></h1>
            <p>Conformément aux dispositions de la loi n° 2017-20 du 20 avril 2018 sur l'économie numérique</p>
        </div>

        <div class="legal-content">
            <div class="legal-card">
                <h2>Éditeur du site</h2>
                <p><strong>{{ $company->name ?? 'Nova Tech Bénin' }}</strong></p>
                <p>Forme juridique : {{ $company->legal_form ?? 'SARL' }}</p>
                <p>Capital social : {{ $company->capital ?? '1 000 000 FCFA' }}</p>
                <p>RCCM : {{ $company->rccm ?? 'RB/COT/2023/XXXX' }}</p>
                <p>IFU : {{ $company->ifu ?? '3202300000000' }}</p>
                <p>Siège social : {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
                <p>Email : {{ $company->email ?? 'contact@novatech.bj' }}</p>
                <p>Téléphone : {{ $company->phone ?? '+229 66 18 55 95' }}</p>
                <p>Directeur de publication : {{ $company->director ?? 'Le Directeur Général' }}</p>
            </div>

            <div class="legal-card">
                <h2>Hébergement</h2>
                <p><strong>Hébergeur du site</strong></p>
                <p>Nom : {{ $company->hosting_name ?? 'Hostinger International Ltd' }}</p>
                <p>Adresse : {{ $company->hosting_address ?? 'Šeimininkių g. 3, LT-09231 Vilnius, Lituanie' }}</p>
                <p>Téléphone : {{ $company->hosting_phone ?? '+370 645 03378' }}</p>
                <p>Site web : <a href="{{ $company->hosting_url ?? 'https://www.hostinger.com' }}" target="_blank">{{ $company->hosting_url ?? 'https://www.hostinger.com' }}</a></p>
            </div>

            <div class="legal-card">
                <h2>Propriété intellectuelle</h2>
                <p>L'ensemble des éléments composant ce site (textes, images, logos, icônes, vidéos, etc.) sont la propriété exclusive de {{ $company->name ?? 'Nova Tech Bénin' }} sauf mention contraire. Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable.</p>
            </div>

            <div class="legal-card">
                <h2>Responsabilité</h2>
                <p>{{ $company->name ?? 'Nova Tech Bénin' }} met tout en œuvre pour offrir aux utilisateurs des informations et des outils disponibles et vérifiés, mais ne saurait être tenu pour responsable des erreurs, d'une absence de disponibilité des informations et/ou de la présence de virus sur son site.</p>
            </div>

            <div class="legal-card">
                <h2>Données personnelles</h2>
                <p>Conformément à la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, de modification et de suppression des données qui vous concernent. Vous pouvez exercer ce droit en nous contactant à : {{ $company->email ?? 'contact@novatech.bj' }}</p>
            </div>

            <div class="legal-card">
                <h2>Cookies</h2>
                <p>Le site utilise des cookies pour améliorer l'expérience utilisateur. En naviguant sur notre site, vous acceptez l'utilisation de cookies.</p>
            </div>

            <div class="legal-card">
                <h2>Droit applicable</h2>
                <p>Les présentes mentions légales sont régies par le droit béninois. En cas de litige, les tribunaux de Cotonou sont seuls compétents.</p>
            </div>

            <div class="legal-update">
                <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .legal-section {
        padding: 120px 0 80px;
        background: var(--bg-light);
    }

    .legal-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .legal-header .label {
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

    .legal-header h1 {
        font-size: clamp(32px, 4vw, 48px);
        font-weight: 800;
        margin-bottom: 16px;
        color: var(--text-dark);
    }

    .legal-header h1 .accent {
        color: var(--accent);
    }

    .legal-header p {
        color: var(--text-gray);
        max-width: 600px;
        margin: 0 auto;
    }

    .legal-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .legal-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        transition: transform 0.3s ease;
    }

    .legal-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .legal-card h2 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--primary);
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
    }

    .legal-card p {
        margin-bottom: 12px;
        line-height: 1.7;
        color: var(--text-gray);
    }

    .legal-card p:last-child {
        margin-bottom: 0;
    }

    .legal-card a {
        color: var(--primary);
        text-decoration: none;
    }

    .legal-card a:hover {
        text-decoration: underline;
    }

    .legal-update {
        text-align: center;
        margin-top: 40px;
        padding: 20px;
        background: rgba(99,102,241,0.05);
        border-radius: 12px;
        color: var(--text-light);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .legal-section {
            padding: 100px 0 60px;
        }

        .legal-card {
            padding: 20px;
        }

        .legal-card h2 {
            font-size: 18px;
        }
    }
</style>
@endpush
