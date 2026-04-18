<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'title' => 'Audit & Maintenance PC',
                'description' => 'Diagnostic complet et maintenance de vos équipements informatiques pour garantir des performances optimales.',
                'icon' => 'fa-laptop',
                'icon_color' => '#667eea',
                'features' => [
                    'Audit gratuit des PC',
                    'Maintenance matérielle',
                    'Optimisation des performances',
                    'Suppression virus et pannes'
                ],
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Création de Sites Web',
                'description' => 'Conception de sites web modernes, responsives et optimisés pour booster votre présence en ligne.',
                'icon' => 'fa-globe',
                'icon_color' => '#f5576c',
                'features' => [
                    'Sites vitrine professionnels',
                    'Boutiques e-commerce',
                    'Design responsive',
                    'SEO optimisé'
                ],
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'E-commerce Solutions',
                'description' => 'Développement de boutiques en ligne performantes pour vendre vos produits et services 24/7.',
                'icon' => 'fa-shopping-cart',
                'icon_color' => '#00f2fe',
                'features' => [
                    'Plateforme e-commerce',
                    'Paiement sécurisé',
                    'Gestion des stocks',
                    'Interface intuitive'
                ],
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Maintenance Web',
                'description' => 'Suivi régulier et maintenance de votre site web pour assurer sa disponibilité et sa sécurité.',
                'icon' => 'fa-wrench',
                'icon_color' => '#fee140',
                'features' => [
                    'Mises à jour régulières',
                    'Sauvegardes automatiques',
                    'Monitoring 24/7',
                    'Support technique'
                ],
                'order' => 4,
                'is_active' => true
            ],
            [
                'title' => 'Optimisation & Performance',
                'description' => 'Amélioration des performances de votre site pour une expérience utilisateur exceptionnelle.',
                'icon' => 'fa-line-chart',
                'icon_color' => '#fed6e3',
                'features' => [
                    'Optimisation vitesse',
                    'Référencement SEO',
                    'Analyse performance',
                    'Optimisation mobile'
                ],
                'order' => 5,
                'is_active' => true
            ],
            [
                'title' => 'Sécurité & Protection',
                'description' => 'Protection complète de vos données et systèmes contre les menaces et cyberattaques.',
                'icon' => 'fa-shield',
                'icon_color' => '#fecfef',
                'features' => [
                    'Audit de sécurité',
                    'Protection antivirus',
                    'Certificat SSL',
                    'Pare-feu avancé'
                ],
                'order' => 6,
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('✅ Services créés avec succès !');
    }
}
