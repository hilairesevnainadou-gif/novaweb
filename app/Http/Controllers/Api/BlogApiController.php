<?php
// app/Http/Controllers/Api/BlogApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)
            ->orderBy('published_at', 'desc');

        // Filtre par catégorie
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $limit = $request->get('limit', 10);
        $posts = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Incrémenter le compteur de vues (si vous avez ce champ)
        // $post->increment('views');

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }
}
