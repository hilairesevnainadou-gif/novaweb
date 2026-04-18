<?php
// app/Http/Controllers/Admin/BlogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Newsletter;
use App\Mail\NewBlogPostMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = ['Technologie', 'Développement', 'Design', 'Marketing', 'Entreprise', 'Actualités', 'Tutoriel', 'News'];
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'reading_time' => 'nullable|string|max:50',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'is_featured' => 'nullable|boolean',
            'send_newsletter' => 'nullable|boolean' // Nouveau champ
        ]);

        $data = $request->except(['image', 'send_newsletter']);

        // Génération du slug
        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        } else {
            $data['slug'] = Str::slug($request->title);
        }

        // Vérifier l'unicité du slug
        $originalSlug = $data['slug'];
        $count = 1;
        while (BlogPost::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        // Gestion du statut de publication
        $isPublished = $request->has('is_published') && $request->is_published == '1';
        $data['is_published'] = $isPublished;

        // Gestion de la date de publication
        $sendNewsletter = false;
        if ($isPublished) {
            if ($request->filled('published_at')) {
                $data['published_at'] = Carbon::parse($request->published_at);
            } else {
                $data['published_at'] = Carbon::now();
            }

            // Vérifier si on doit envoyer la newsletter
            $sendNewsletter = $request->has('send_newsletter') && $request->send_newsletter == '1';
        } else {
            $data['published_at'] = null;
        }

        // Gestion de la mise en avant
        $data['is_featured'] = $request->has('is_featured') && $request->is_featured == '1';

        $blogPost = BlogPost::create($data);

        // Envoyer la newsletter si demandé
        if ($sendNewsletter && $blogPost->is_published) {
            $this->sendNewsletterNotification($blogPost);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Article créé avec succès']);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Article créé avec succès');
    }

    public function edit(BlogPost $blog)
    {
        $categories = ['Technologie', 'Développement', 'Design', 'Marketing', 'Entreprise', 'Actualités', 'Tutoriel', 'News'];
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'reading_time' => 'nullable|string|max:50',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'is_featured' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
            'send_newsletter' => 'nullable|boolean' // Nouveau champ
        ]);

        $data = $request->except(['image', 'remove_image', 'send_newsletter']);
        $sendNewsletter = $request->has('send_newsletter') && $request->send_newsletter == '1';
        $wasPublished = $blog->is_published;

        // Gestion du slug
        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        } elseif ($request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title);
        } else {
            $data['slug'] = $blog->slug;
        }

        // Vérifier l'unicité du slug si modifié
        if ($data['slug'] !== $blog->slug) {
            $originalSlug = $data['slug'];
            $count = 1;
            while (BlogPost::where('slug', $data['slug'])->where('id', '!=', $blog->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Gestion de la suppression de l'image
        if ($request->remove_image == '1') {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = null;
        }

        // Gestion de la nouvelle image
        if ($request->hasFile('image')) {
            if ($blog->image && $request->remove_image != '1') {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        // Gestion du statut de publication
        $isPublished = $request->has('is_published') && $request->is_published == '1';

        // Si l'article était publié et qu'on le dépublie
        if ($wasPublished && !$isPublished) {
            $data['is_published'] = false;
            $data['published_at'] = null;
        }
        // Si l'article n'était pas publié et qu'on le publie (NOUVEAU)
        elseif (!$wasPublished && $isPublished) {
            $data['is_published'] = true;
            // Utiliser la date choisie par l'utilisateur ou la date actuelle
            if ($request->filled('published_at')) {
                $data['published_at'] = Carbon::parse($request->published_at);
            } else {
                $data['published_at'] = Carbon::now();
            }
        }
        // Si l'article reste dans le même état de publication
        else {
            $data['is_published'] = $blog->is_published;

            // Si publié et l'utilisateur a modifié la date
            if ($isPublished && $request->filled('published_at')) {
                $data['published_at'] = Carbon::parse($request->published_at);
            }
        }

        // Gestion de la mise en avant
        $data['is_featured'] = $request->has('is_featured') && $request->is_featured == '1';

        $blog->update($data);

        // Envoyer la newsletter UNIQUEMENT si l'article vient d'être publié et que l'option est cochée
        if ($sendNewsletter && !$wasPublished && $blog->is_published) {
            $this->sendNewsletterNotification($blog);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Article mis à jour avec succès']);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Article mis à jour avec succès');
    }

    /**
     * Publier ou dépublier un article (AJAX)
     */
    public function togglePublish(Request $request, BlogPost $blog)
    {
        try {
            $wasPublished = $blog->is_published;
            $sendNewsletter = $request->has('send_newsletter') && $request->send_newsletter == '1';

            if ($wasPublished) {
                $blog->update([
                    'is_published' => false,
                    'published_at' => null
                ]);
                $message = 'Article dépublié avec succès';
                $status = 'unpublished';
            } else {
                $blog->update([
                    'is_published' => true,
                    'published_at' => Carbon::now()
                ]);
                $message = 'Article publié avec succès';
                $status = 'published';

                // Envoyer la newsletter si demandé
                if ($sendNewsletter) {
                    $this->sendNewsletterNotification($blog);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status,
                'published_at' => $blog->published_at ? $blog->published_at->format('d/m/Y à H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer une notification newsletter pour un nouvel article
     */
    protected function sendNewsletterNotification(BlogPost $post)
    {
        try {
            // Récupérer tous les abonnés actifs
            $subscribers = Newsletter::active()->get();

            if ($subscribers->isEmpty()) {
                Log::info('Aucun abonné à la newsletter pour envoyer la notification');
                return;
            }

            // Envoyer l'email à chaque abonné
            // En production, utilisez une queue pour éviter de ralentir la requête
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->queue(new NewBlogPostMail($post, $subscriber));
            }

            Log::info('Newsletter envoyée pour l\'article: ' . $post->title . ' à ' . $subscribers->count() . ' abonnés');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la newsletter: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, BlogPost $blog)
    {
        try {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }

            $blog->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article supprimé avec succès'
                ]);
            }

            return redirect()->route('blog.index')
                ->with('success', 'Article supprimé avec succès');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('blog.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Dupliquer un article
     */
    public function duplicate(Request $request, BlogPost $blog)
    {
        try {
            $newPost = $blog->replicate();
            $newPost->title = $blog->title . ' (copie)';
            $newPost->slug = Str::slug($blog->title . '-copie');
            $newPost->is_published = false;
            $newPost->published_at = null;
            $newPost->created_at = Carbon::now();
            $newPost->updated_at = Carbon::now();

            // Vérifier l'unicité du slug
            $originalSlug = $newPost->slug;
            $count = 1;
            while (BlogPost::where('slug', $newPost->slug)->exists()) {
                $newPost->slug = $originalSlug . '-' . $count++;
            }

            $newPost->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article dupliqué avec succès',
                    'id' => $newPost->id
                ]);
            }

            return redirect()->route('admin.blog.edit', $newPost)
                ->with('success', 'Article dupliqué avec succès');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('blog.index')
                ->with('error', 'Erreur lors de la duplication');
        }
    }
}
