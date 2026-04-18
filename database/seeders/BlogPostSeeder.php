<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        BlogPost::truncate(); // optionnel si tu veux vider avant

        BlogPost::create([
            'title' => 'Pourquoi un site web est indispensable pour une entreprise',
            'slug' => Str::slug('Pourquoi un site web est indispensable pour une entreprise'),
            'excerpt' => 'Découvrez pourquoi un site web professionnel est un outil clé pour développer votre activité.',
            'content' => '
                <p>Aujourd’hui, avoir un site web est essentiel pour toute entreprise souhaitant gagner en visibilité et en crédibilité.</p>
                <p>Chez <strong>NovaTech</strong>, nous concevons des sites vitrines, e‑commerce et des applications web sur mesure.</p>
            ',
            'category' => 'Web',
            'image' => 'assets/images/blog-post-03.jpg',
            'published_at' => Carbon::now(),
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'Maintenance informatique : pourquoi est‑elle importante ?',
            'slug' => Str::slug('Maintenance informatique : pourquoi est-elle importante ?'),
            'excerpt' => 'La maintenance informatique permet de prévenir les pannes et d’optimiser les performances.',
            'content' => '
                <p>La maintenance informatique garantit la stabilité et la sécurité de vos équipements.</p>
                <p>NovaTech propose des services de maintenance préventive et corrective.</p>
            ',
            'category' => 'Informatique',
            'image' => 'assets/images/blog-post-02.jpg',
            'published_at' => Carbon::now()->subDays(3),
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'Application web sur mesure : quels avantages ?',
            'slug' => Str::slug('Application web sur mesure : quels avantages ?'),
            'excerpt' => 'Une application web sur mesure répond exactement aux besoins de votre entreprise.',
            'content' => '
                <p>Les applications web personnalisées offrent flexibilité et évolutivité.</p>
                <p>NovaTech développe des solutions adaptées à chaque client.</p>
            ',
            'category' => 'Développement',
            'image' => 'assets/images/blog-post-01.jpg',
            'published_at' => Carbon::now()->subWeek(),
            'is_published' => true,
        ]);
    }
}
