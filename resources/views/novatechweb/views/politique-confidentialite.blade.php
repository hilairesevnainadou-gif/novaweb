@extends('novatechweb.views.layouts.app')

@section('title', 'Politique de confidentialité - ' . ($company->name ?? 'Nova Tech Bénin'))

@section('content')
<section class="legal-section">
    <div class="container">
        <div class="legal-header">
            <span class="label">Confidentialité</span>
            <h1>Politique de <span class="accent">confidentialité</span></h1>
            <p>Comment nous protégeons vos données personnelles</p>
        </div>

        <div class="legal-content">
            <div class="legal-card">
                <h2>1. Collecte des informations</h2>
                <p>Nous collectons les informations que vous nous fournissez lorsque vous remplissez le formulaire de contact, vous inscrivez à notre newsletter ou interagissez avec notre site. Ces informations incluent :</p>
                <ul>
                    <li>Nom et prénom</li>
                    <li>Adresse email</li>
                    <li>Numéro de téléphone</li>
                    <li>Message et demande</li>
                </ul>
            </div>

            <div class="legal-card">
                <h2>2. Utilisation des informations</h2>
                <p>Les informations que nous collectons sont utilisées pour :</p>
                <ul>
                    <li>Répondre à vos demandes de contact</li>
                    <li>Vous envoyer notre newsletter (avec votre consentement)</li>
                    <li>Améliorer notre site et nos services</li>
                    <li>Personnaliser votre expérience utilisateur</li>
                </ul>
            </div>

            <div class="legal-card">
                <h2>3. Protection des données</h2>
                <p>Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données personnelles contre tout accès non autorisé, perte, destruction ou divulgation.</p>
            </div>

            <div class="legal-card">
                <h2>4. Partage des informations</h2>
                <p>Nous ne vendons, n'échangeons ni ne transférons vos informations personnelles à des tiers sans votre consentement, sauf si la loi l'exige.</p>
            </div>

            <div class="legal-card">
                <h2>5. Cookies</h2>
                <p>Notre site utilise des cookies pour améliorer l'expérience de navigation. Les cookies sont de petits fichiers stockés sur votre appareil. Vous pouvez désactiver les cookies dans les paramètres de votre navigateur.</p>
            </div>

            <div class="legal-card">
                <h2>6. Vos droits</h2>
                <p>Conformément à la réglementation en vigueur, vous disposez des droits suivants :</p>
                <ul>
                    <li>Droit d'accès à vos données</li>
                    <li>Droit de rectification</li>
                    <li>Droit à l'effacement</li>
                    <li>Droit à la limitation du traitement</li>
                    <li>Droit à la portabilité des données</li>
                    <li>Droit d'opposition</li>
                </ul>
                <p>Pour exercer ces droits, contactez-nous à : {{ $company->email ?? 'contact@novatech.bj' }}</p>
            </div>

            <div class="legal-card">
                <h2>7. Conservation des données</h2>
                <p>Nous conservons vos données personnelles uniquement le temps nécessaire pour atteindre les objectifs pour lesquels elles ont été collectées, conformément à la législation en vigueur.</p>
            </div>

            <div class="legal-card">
                <h2>8. Modifications</h2>
                <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Les modifications seront publiées sur cette page.</p>
            </div>

            <div class="legal-card">
                <h2>9. Contact</h2>
                <p>Pour toute question concernant cette politique de confidentialité, vous pouvez nous contacter :</p>
                <p>Email : {{ $company->email ?? 'contact@novatech.bj' }}</p>
                <p>Téléphone : {{ $company->phone ?? '+229 66 18 55 95' }}</p>
                <p>Adresse : {{ $company->address ?? 'Abomey-Calavi, Bénin' }}</p>
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

    .legal-card ul {
        margin: 15px 0;
        padding-left: 25px;
    }

    .legal-card li {
        margin-bottom: 8px;
        line-height: 1.6;
        color: var(--text-gray);
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

