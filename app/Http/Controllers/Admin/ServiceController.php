<?php
// app/Http/Controllers/Admin/ServiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $icons = [
    // Informatique & Technique
    'laptop-code' => 'Ordinateur',
    'desktop' => 'Bureau',
    'code' => 'Développement',
    'cloud-upload-alt' => 'Cloud',
    'server' => 'Serveur',
    'database' => 'Base de données',
    'microchip' => 'IoT',
    'network-wired' => 'Réseau',

    // Sécurité
    'shield-alt' => 'Sécurité',
    'lock' => 'Verrouillage',
    'user-shield' => 'Protection',

    // Analyse & Data
    'chart-line' => 'Analyse',
    'chart-bar' => 'Statistiques',
    'chart-pie' => 'Tableau de bord',
    'brain' => 'Intelligence',

    // Marketing & Communication
    'bullhorn' => 'Marketing',
    'megaphone' => 'Communication',
    'envelope' => 'Email',
    'share-alt' => 'Réseaux sociaux',
    'ad' => 'Publicité',

    // Design
    'paint-brush' => 'Design',
    'pencil-ruler' => 'Maquette',
    'palette' => 'Couleurs',
    'vector-square' => 'Vectoriel',

    // Mobile & Web
    'mobile-alt' => 'Mobile',
    'tablet-alt' => 'Tablette',
    'globe' => 'Web',

    // Performance
    'rocket' => 'Performance',
    'tachometer-alt' => 'Rapidité',
    'bolt' => 'Énergie',

    // Support
    'users' => 'Support client',
    'headset' => 'Assistance',
    'comments' => 'Chat',

    // Innovation
    'lightbulb' => 'Innovation',
    'cogs' => 'Automatisation',
    'robot' => 'IA',

    // E-commerce
    'shopping-cart' => 'E-commerce',
    'credit-card' => 'Paiement',
    'truck' => 'Livraison',

    // SEO
    'search' => 'SEO',
    'google' => 'Google',
    'chart-simple' => 'Analytics'
];

        $iconColors = [
            'indigo' => 'Indigo',
            'cyan' => 'Cyan',
            'emerald' => 'Emeraude',
            'rose' => 'Rose',
            'amber' => 'Ambre',
            'violet' => 'Violet'
        ];

        return view('admin.services.create', compact('icons', 'iconColors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'icon' => 'required|string',
            'icon_color' => 'required|string',
            'features' => 'nullable|array',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service créé avec succès');
    }

    public function edit(Service $service)
    {
        $icons = [
    // Informatique & Technique
    'laptop-code' => 'Ordinateur',
    'desktop' => 'Bureau',
    'code' => 'Développement',
    'cloud-upload-alt' => 'Cloud',
    'server' => 'Serveur',
    'database' => 'Base de données',
    'microchip' => 'IoT',
    'network-wired' => 'Réseau',

    // Sécurité
    'shield-alt' => 'Sécurité',
    'lock' => 'Verrouillage',
    'user-shield' => 'Protection',

    // Analyse & Data
    'chart-line' => 'Analyse',
    'chart-bar' => 'Statistiques',
    'chart-pie' => 'Tableau de bord',
    'brain' => 'Intelligence',

    // Marketing & Communication
    'bullhorn' => 'Marketing',
    'megaphone' => 'Communication',
    'envelope' => 'Email',
    'share-alt' => 'Réseaux sociaux',
    'ad' => 'Publicité',

    // Design
    'paint-brush' => 'Design',
    'pencil-ruler' => 'Maquette',
    'palette' => 'Couleurs',
    'vector-square' => 'Vectoriel',

    // Mobile & Web
    'mobile-alt' => 'Mobile',
    'tablet-alt' => 'Tablette',
    'globe' => 'Web',

    // Performance
    'rocket' => 'Performance',
    'tachometer-alt' => 'Rapidité',
    'bolt' => 'Énergie',

    // Support
    'users' => 'Support client',
    'headset' => 'Assistance',
    'comments' => 'Chat',

    // Innovation
    'lightbulb' => 'Innovation',
    'cogs' => 'Automatisation',
    'robot' => 'IA',

    // E-commerce
    'shopping-cart' => 'E-commerce',
    'credit-card' => 'Paiement',
    'truck' => 'Livraison',

    // SEO
    'search' => 'SEO',
    'google' => 'Google',
    'chart-simple' => 'Analytics'
];

        $iconColors = [
            'indigo' => 'Indigo',
            'cyan' => 'Cyan',
            'emerald' => 'Emeraude',
            'rose' => 'Rose',
            'amber' => 'Ambre',
            'violet' => 'Violet'
        ];

        return view('admin.services.edit', compact('service', 'icons', 'iconColors'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'icon' => 'required|string',
            'icon_color' => 'required|string',
            'features' => 'nullable|array',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service mis à jour avec succès');
    }

    public function destroy(Service $service)
{
    try {
        $service->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service supprimé avec succès');

    } catch (\Exception $e) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('admin.services.index')
            ->with('error', 'Erreur lors de la suppression');
    }
}

    // public function toggleActive(Service $service)
    // {
    //     $service->update(['is_active' => !$service->is_active]);

    //     $status = $service->is_active ? 'activé' : 'désactivé';
    //     return redirect()->route('admin.services.index')
    //         ->with('success', "Service {$status} avec succès");
    // }

    // Dans app/Http/Controllers/Admin/ServiceController.php

public function updateOrder(Request $request, Service $service)
{
    $request->validate([
        'order' => 'integer|nullable'
    ]);

    $service->update(['order' => $request->order]);

    return redirect()->back()->with('success', 'Ordre mis à jour avec succès');
}

public function toggleActive(Service $service)
{
    $service->update(['is_active' => !$service->is_active]);

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $service->is_active ? 'Service activé avec succès' : 'Service désactivé avec succès'
        ]);
    }

    return redirect()->route('admin.services.index')
        ->with('success', $service->is_active ? 'Service activé avec succès' : 'Service désactivé avec succès');
}

public function reorder(Request $request)
{
    $request->validate([
        'order' => 'required|array',
        'order.*' => 'integer|exists:services,id'
    ]);

    foreach ($request->order as $index => $serviceId) {
        Service::where('id', $serviceId)->update(['order' => $index + 1]);
    }

    return response()->json(['success' => true, 'message' => 'Ordre mis à jour']);
}
}
