<?php
// app/Http/Controllers/Admin/PortfolioController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::ordered()->paginate(10);
        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

public function store(Request $request)
{
    $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'nullable|string',
        'client' => 'nullable|string|max:255',
        'category' => 'required|string|in:site-vitrine,e-commerce,application-web,maintenance,optimisation,autre',
        'url' => 'nullable|url',
        'date' => 'nullable|date',
        'technologies' => 'nullable|json',
        'work_done' => 'nullable|string',
        'project_type' => 'required|in:internal,external',
        'is_featured' => 'sometimes|boolean',
        'is_active' => 'sometimes|boolean',
        'order' => 'nullable|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'duration' => 'nullable|string|max:255',
        'team_size' => 'nullable|string|max:255'
    ];

    $messages = [
        'title.required' => 'Le titre du projet est requis',
        'description.required' => 'La description est requise',
        'category.required' => 'La catégorie est requise',
        'category.in' => 'La catégorie sélectionnée n\'est pas valide',
        'project_type.required' => 'Le type de projet est requis',
        'image.image' => 'Le fichier doit être une image',
        'image.max' => 'L\'image ne doit pas dépasser 2MB',
        'images.*.image' => 'Chaque fichier doit être une image',
        'images.*.max' => 'Chaque image ne doit pas dépasser 2MB'
    ];

    $request->validate($rules, $messages);

    $data = $request->except(['image', 'images']);
    $data['slug'] = Str::slug($request->title);

    // Valeurs par défaut
    $data['duration'] = $request->duration ?? '2-3 semaines';
    $data['team_size'] = $request->team_size ?? '2-3 personnes';
    $data['is_featured'] = $request->has('is_featured');
    $data['is_active'] = $request->has('is_active');
    $data['order'] = $request->order ?? 0;

    // Décoder le JSON des technologies
    if ($request->filled('technologies')) {
        $technologies = json_decode($request->input('technologies'), true);
        $data['technologies'] = is_array($technologies) ? $technologies : [];
    }

    // Gestion de l'image principale
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('portfolio', 'public');
        $data['image'] = $imagePath;
    }

    // Gestion de la galerie d'images
    if ($request->hasFile('images')) {
        $images = [];
        foreach ($request->file('images') as $image) {
            $images[] = $image->store('portfolio/gallery', 'public');
        }
        $data['images'] = $images;
    }

    Portfolio::create($data);

    return redirect()->route('admin.portfolio.index')
        ->with('success', 'Projet ajouté avec succès');
}

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolio.edit', compact('portfolio'));
    }

 public function update(Request $request, Portfolio $portfolio)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'nullable|string',
        'client' => 'nullable|string|max:255',
        'category' => 'required|string',
        'url' => 'nullable|url',
        'date' => 'nullable|date',
        'technologies' => 'nullable|json',
        'work_done' => 'nullable|string',
        'project_type' => 'required|in:internal,external',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'nullable|integer',
        'image' => 'nullable|image|max:2048',
        'images' => 'nullable|array',
        'images.*' => 'image|max:2048',
        'duration' => 'nullable|string|max:255',
        'team_size' => 'nullable|string|max:255'
    ]);

    $data = $request->except(['image', 'images']);

    // Valeurs par défaut pour les nouveaux champs
    $data['duration'] = $request->duration ?? '2-3 semaines';
    $data['team_size'] = $request->team_size ?? '2-3 personnes';

    // Mise à jour du slug si le titre change
    if ($request->title !== $portfolio->title) {
        $data['slug'] = Str::slug($request->title);
    }

    // Gestion des technologies (JSON → array)
    if ($request->has('technologies')) {
        $technologies = json_decode($request->input('technologies'), true);
        $data['technologies'] = is_array($technologies) ? $technologies : [];
    }

    // Image principale - Ne supprime que si nouvelle image
    if ($request->hasFile('image')) {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $data['image'] = $request->file('image')->store('portfolio', 'public');
    } else {
        // Conserver l'image existante
        $data['image'] = $portfolio->image;
    }

    // Galerie images - Ne remplace que si nouvelles images
    if ($request->hasFile('images')) {
        // Supprimer les anciennes images
        if ($portfolio->images && is_array($portfolio->images)) {
            foreach ($portfolio->images as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        // Upload des nouvelles images
        $images = [];
        foreach ($request->file('images') as $image) {
            $images[] = $image->store('portfolio/gallery', 'public');
        }
        $data['images'] = $images;
    } else {
        // Conserver les images existantes
        $data['images'] = $portfolio->images;
    }

    $portfolio->update($data);

    return redirect()->route('admin.portfolio.index')
        ->with('success', 'Projet mis à jour avec succès');
}

    /**
     * Toggle le statut du portfolio (publish/unpublish)
     */
    public function toggleStatus(Portfolio $portfolio)
{
    $portfolio->update(['is_active' => !$portfolio->is_active]);

    return response()->json([
        'success' => true,
        'is_active' => $portfolio->is_active,
        'message' => $portfolio->is_active ? 'Projet publié avec succès' : 'Projet dépublié avec succès'
    ]);
}

    public function publish(Portfolio $portfolio)
    {
        $portfolio->update(['is_active' => !$portfolio->is_active]);

        $status = $portfolio->is_active ? 'publié' : 'dépublié';
        return redirect()->route('admin.portfolio.index')
            ->with('success', "Projet {$status} avec succès");
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }

        if ($portfolio->images) {
            foreach ($portfolio->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $portfolio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Projet supprimé avec succès'
        ]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Portfolio::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
