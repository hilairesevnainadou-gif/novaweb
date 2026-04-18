<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInfo;

class CompanyInfoSeeder extends Seeder
{
    public function run()
    {
        // Vérifier si la table a déjà des données
        if (CompanyInfo::count() === 0) {
            $companyInfo = [
                // Identité
                'name' => 'Nova Tech Bénin',
                'slogan' => 'Solutions Informatiques & Web',

                // Contact
                'email' => 'contact@novatech.bj',
                'phone' => '+229 66 18 55 95',
                'address' => 'Cotonou, Bénin',

                // Médias
                'logo' => 'assets/images/logo-v3 (2).png',
                'banner_image' => 'assets/images/slider-dec-v3.png',
                'about_image' => 'assets/images/about-dec-v3.png',

                // Banner / Hero
                'hero_title' => 'Solutions Informatiques & Web',
                'hero_description' => "Audit • Maintenance • Création • Optimisation\nVotre partenaire de confiance pour tous vos besoins informatiques et web au Bénin.",

                // About Section
                'about_title' => 'Solutions Informatiques & Web de Qualité',
                'about_description_1' => 'Nova Tech Bénin est votre partenaire de confiance pour tous vos besoins en solutions informatiques et web.',
                'about_description_2' => 'Notre mission est de transformer vos défis technologiques en opportunités de croissance grâce à des solutions innovantes et un accompagnement personnalisé.',

                // Réseaux sociaux
                'facebook' => 'https://facebook.com/novatechbenin',
                'twitter' => 'https://twitter.com/novatechbenin',
                'whatsapp' => 'https://wa.me/22966185595',
                'instagram' => 'https://instagram.com/novatechbenin',
                'linkedin' => 'https://linkedin.com/company/novatechbenin',
                'youtube' => null,

                // Statistiques
                'years_experience' => 5,

                // Horaires
                'opening_hours' => 'Lundi - Vendredi: 8h00 - 18h00',
                'opening_hours_weekend' => 'Samedi: 9h00 - 13h00',

                // Localisation
                'latitude' => 6.3716,
                'longitude' => 2.3912,
                'google_maps_url' => 'https://maps.google.com/maps?q=Abomey-Calavi,+Benin&t=&z=13&ie=UTF8&iwloc=&output=embed',

                // Informations supplémentaires
                'website' => 'https://novatech.bj',
                'mission' => 'Fournir des solutions technologiques innovantes qui propulsent la croissance des entreprises.',
                'vision' => 'Devenir le partenaire technologique de référence au Bénin et en Afrique de l\'Ouest.',
                'values' => 'Innovation, Qualité, Fiabilité, Service Client, Excellence Technique',

                // SEO
                'meta_description' => 'Nova Tech Bénin - Solutions informatiques et web au Bénin. Audit, maintenance, création de sites web, e-commerce, optimisation SEO.',
                'meta_keywords' => 'informatique Bénin, site web Bénin, développement web, maintenance informatique, e-commerce Bénin, SEO Bénin',
            ];

            CompanyInfo::create($companyInfo);

            $this->command->info('✅ Données de l\'entreprise créées avec succès !');
        } else {
            // Mettre à jour les données existantes si besoin
            $company = CompanyInfo::first();

            // Mettre à jour seulement les champs qui pourraient manquer
            $updates = [
                'slogan' => 'Solutions Informatiques & Web',
                'hero_title' => 'Solutions Informatiques & Web',
                'hero_description' => "Audit • Maintenance • Création • Optimisation\nVotre partenaire de confiance pour tous vos besoins informatiques et web au Bénin.",
                'banner_image' => 'assets/images/slider-dec-v3.png',
                'about_title' => 'Solutions Informatiques & Web de Qualité',
                'about_description_1' => 'Nova Tech Bénin est votre partenaire de confiance pour tous vos besoins en solutions informatiques et web.',
                'about_description_2' => 'Notre mission est de transformer vos défis technologiques en opportunités de croissance grâce à des solutions innovantes et un accompagnement personnalisé.',
                'about_image' => 'assets/images/about-dec-v3.png',
                'years_experience' => 5,
           // Dans CompanyInfoSeeder.php, ajoutez dans le tableau $companyInfo :
'web_development_percentage' => 90,
'it_maintenance_percentage' => 85,
'client_satisfaction_percentage' => 95,
'happy_clients_count' => 150,
'projects_completed' => 200,
'support_hours' => 24,
            ];

            $company->update($updates);

            $this->command->info('✅ Données de l\'entreprise mises à jour avec succès !');
        }
    }
}
