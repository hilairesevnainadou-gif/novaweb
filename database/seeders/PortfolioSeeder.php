<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;

class PortfolioSeeder extends Seeder
{
    public function run()
    {
        $portfolios = [
            [
                'title' => 'Site Vitrine - Restaurant Le Gourmet',
                'slug' => 'site-vitrine-restaurant-le-gourmet',
                'description' => 'Création d\'un site vitrine moderne pour un restaurant gastronomique.',
                'content' => '<h3>Projet complet de site vitrine</h3><p>Développement d\'un site web responsive avec réservation en ligne et galerie photos.</p>',
                'image' => 'assets/images/portfolio-01.jpg',
                'images' => ['assets/images/portfolio-01.jpg', 'assets/images/portfolio-02.jpg'],
                'client' => 'Restaurant Le Gourmet',
                'category' => 'site-vitrine',
                'url' => 'https://restaurant-legourmet.com',
                'date' => '2023-05-15',
                'technologies' => ['Laravel', 'Bootstrap', 'JavaScript', 'MySQL'],
                'work_done' => 'Conception UI/UX, développement frontend et backend, intégration système de réservation, optimisation SEO.',
                'project_type' => 'internal',
                'is_featured' => true,
                'order' => 1
            ],
            [
                'title' => 'Boutique E-commerce - Mode Africaine',
                'slug' => 'boutique-e-commerce-mode-africaine',
                'description' => 'Plateforme e-commerce pour une boutique de vêtements africains.',
                'content' => '<h3>Solution e-commerce complète</h3><p>Développement d\'une boutique en ligne avec gestion des stocks et paiements sécurisés.</p>',
                'image' => 'assets/images/portfolio-02.jpg',
                'images' => ['assets/images/portfolio-02.jpg'],
                'client' => 'Mode Africaine SARL',
                'category' => 'e-commerce',
                'url' => 'https://mode-africaine.com',
                'date' => '2023-08-20',
                'technologies' => ['Laravel', 'Vue.js', 'Stripe', 'MySQL'],
                'work_done' => 'Architecture e-commerce, intégration paiement, gestion de stock, dashboard admin, optimisation mobile.',
                'project_type' => 'internal',
                'is_featured' => true,
                'order' => 2
            ],
            [
                'title' => 'Application Web - Gestion Scolaire',
                'slug' => 'application-web-gestion-scolaire',
                'description' => 'Système de gestion scolaire pour une école privée.',
                'content' => '<h3>Application de gestion complète</h3><p>Développement d\'une plateforme de gestion des étudiants, professeurs et notes.</p>',
                'image' => 'assets/images/portfolio-03.jpg',
                'images' => ['assets/images/portfolio-03.jpg'],
                'client' => 'École Excellence',
                'category' => 'application-web',
                'url' => 'https://gestion.ecole-excellence.com',
                'date' => '2023-11-10',
                'technologies' => ['Laravel', 'Livewire', 'Chart.js', 'MySQL'],
                'work_done' => 'Conception base de données, développement modules gestion, reporting automatique, interface intuitive.',
                'project_type' => 'external',
                'is_featured' => true,
                'order' => 3
            ],
            [
                'title' => 'Optimisation SEO - Agence Immobilière',
                'slug' => 'optimisation-seo-agence-immobiliere',
                'description' => 'Optimisation SEO et performance pour un site d\'agence immobilière.',
                'content' => '<h3>Audit et optimisation complète</h3><p>Amélioration des performances et référencement naturel.</p>',
                'image' => 'assets/images/portfolio-04.jpg',
                'images' => ['assets/images/portfolio-04.jpg'],
                'client' => 'ImmoPro Bénin',
                'category' => 'optimisation',
                'url' => 'https://immopro-benin.com',
                'date' => '2024-01-15',
                'technologies' => ['SEO', 'Performance', 'Analytics', 'GTmetrix'],
                'work_done' => 'Audit technique SEO, optimisation vitesse, amélioration contenu, suivi analytics.',
                'project_type' => 'internal',
                'is_featured' => false,
                'order' => 4
            ]
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }

        $this->command->info('✅ Portfolio créé avec succès !');
    }
}
