<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CompanyInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les articles publiés
        $query = BlogPost::where('is_published', true);

        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filtrer par catégorie
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Trier
        if ($request->has('sort')) {
            if ($request->sort == 'oldest') {
                $query->orderBy('published_at');
            } else {
                $query->orderByDesc('published_at');
            }
        } else {
            $query->orderByDesc('published_at');
        }

        $posts = $query->paginate(9);

        // Récupérer les catégories
        $categories = $this->getCategoriesFromPosts();

        // Récupérer les articles populaires
        $popularPosts = $this->getPopularPosts();

        $company = CompanyInfo::first();

        return view('novatechweb.views.blog.index', compact('posts', 'company', 'categories', 'popularPosts'));
    }

    private function getCategoriesFromPosts()
    {
        try {
            if (!Schema::hasTable('blog_posts') || !Schema::hasColumn('blog_posts', 'category')) {
                return collect();
            }

            $categories = BlogPost::where('is_published', true)
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->get()
                ->map(function($item) {
                    return (object) [
                        'name' => $item->category,
                        'slug' => Str::slug($item->category),
                        'count' => (int) $item->count
                    ];
                });

            return $categories;

        } catch (\Exception $e) {
            Log::error('Erreur getCategoriesFromPosts: ' . $e->getMessage());
            return collect();
        }
    }

    private function getPopularPosts()
    {
        try {
            $query = BlogPost::where('is_published', true);

            if (Schema::hasColumn('blog_posts', 'views')) {
                $query->orderByDesc('views');
            } else {
                $query->orderByDesc('published_at');
            }

            return $query->limit(5)->get();

        } catch (\Exception $e) {
            Log::error('Erreur getPopularPosts: ' . $e->getMessage());
            return collect();
        }
    }

    public function show(string $slug)
    {
        try {
            $post = BlogPost::where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();

            // Incrémenter les vues si le champ existe
            if (Schema::hasColumn('blog_posts', 'views')) {
                $post->increment('views');
            }

            // Récupérer l'article précédent
            $prevPost = BlogPost::where('is_published', true)
                ->where('published_at', '<', $post->published_at)
                ->orderByDesc('published_at')
                ->first();

            // Récupérer l'article suivant
            $nextPost = BlogPost::where('is_published', true)
                ->where('published_at', '>', $post->published_at)
                ->orderBy('published_at')
                ->first();

            // Si pas de date de publication, utiliser l'ID
            if (!$prevPost) {
                $prevPost = BlogPost::where('is_published', true)
                    ->where('id', '<', $post->id)
                    ->orderByDesc('id')
                    ->first();
            }

            if (!$nextPost) {
                $nextPost = BlogPost::where('is_published', true)
                    ->where('id', '>', $post->id)
                    ->orderBy('id')
                    ->first();
            }

            // Récupérer les articles similaires
            $relatedPosts = $this->getRelatedPosts($post);

            $company = CompanyInfo::first();

            return view('novatechweb.views.blog.show', compact('post', 'company', 'relatedPosts', 'prevPost', 'nextPost'));

        } catch (\Exception $e) {
            Log::error('Erreur show blog: ' . $e->getMessage());
            abort(404);
        }
    }

    private function getRelatedPosts($post)
    {
        try {
            $relatedPosts = collect();

            if ($post->category) {
                $relatedPosts = BlogPost::where('is_published', true)
                    ->where('id', '!=', $post->id)
                    ->where('category', $post->category)
                    ->orderByDesc('published_at')
                    ->limit(3)
                    ->get();
            }

            if ($relatedPosts->count() < 3) {
                $additionalPosts = BlogPost::where('is_published', true)
                    ->where('id', '!=', $post->id)
                    ->whereNotIn('id', $relatedPosts->pluck('id'))
                    ->orderByDesc('published_at')
                    ->limit(3 - $relatedPosts->count())
                    ->get();

                $relatedPosts = $relatedPosts->merge($additionalPosts);
            }

            return $relatedPosts;

        } catch (\Exception $e) {
            Log::error('Erreur getRelatedPosts: ' . $e->getMessage());
            return BlogPost::where('is_published', true)
                ->where('id', '!=', $post->id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        }
    }
}
