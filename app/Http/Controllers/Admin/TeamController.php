<?php
// app/Http/Controllers/Admin/TeamController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Afficher la liste des membres de l'équipe
     */
    public function index()
    {
        $teamMembers = TeamMember::ordered()->paginate(15);

        // Statistiques
        $totalMembers = TeamMember::count();
        $activeMembers = TeamMember::where('is_active', true)->count();
        $featuredMembers = TeamMember::where('is_featured', true)->count();

        return view('admin.team.index', compact('teamMembers', 'totalMembers', 'activeMembers', 'featuredMembers'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.team.create');
    }

    /**
     * Enregistrer un nouveau membre
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string',
            'quote' => 'nullable|string|max:500',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        $data = $request->except('photo', 'skills');

        // Gestion des compétences
        if ($request->has('skills') && is_array($request->skills)) {
            $data['skills'] = array_values(array_filter($request->skills));
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $photoName = 'team_' . time() . '_' . Str::random(10) . '.' . $photoFile->getClientOriginalExtension();
            $photoPath = $photoFile->storeAs('team', $photoName, 'public');
            $data['photo'] = $photoPath;
        }

        // Valeurs par défaut
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['order'] = $request->order ?? TeamMember::max('order') + 1;

        $teamMember = TeamMember::create($data);

        return redirect()->route('admin.team.index')
            ->with('success', 'Membre ajouté avec succès');
    }

    /**
     * Afficher le détail d'un membre
     */
    public function show(TeamMember $teamMember)
    {
        return view('admin.team.show', compact('teamMember'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(TeamMember $teamMember)
    {
        return view('admin.team.edit', compact('teamMember'));
    }

    /**
     * Mettre à jour un membre
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string',
            'quote' => 'nullable|string|max:500',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        $data = $request->except('photo', 'skills');

        // Gestion des compétences
        if ($request->has('skills') && is_array($request->skills)) {
            $data['skills'] = array_values(array_filter($request->skills));
        } else {
            $data['skills'] = [];
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($teamMember->photo && Storage::disk('public')->exists($teamMember->photo)) {
                Storage::disk('public')->delete($teamMember->photo);
            }

            $photoFile = $request->file('photo');
            $photoName = 'team_' . time() . '_' . Str::random(10) . '.' . $photoFile->getClientOriginalExtension();
            $photoPath = $photoFile->storeAs('team', $photoName, 'public');
            $data['photo'] = $photoPath;
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $teamMember->update($data);

        return redirect()->route('admin.team.index')
            ->with('success', 'Membre mis à jour avec succès');
    }

    /**
     * Supprimer un membre
     */
    public function destroy(TeamMember $teamMember)
    {
        try {
            // Supprimer la photo
            if ($teamMember->photo && Storage::disk('public')->exists($teamMember->photo)) {
                Storage::disk('public')->delete($teamMember->photo);
            }

            $teamMember->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Membre supprimé avec succès'
                ]);
            }

            return redirect()->route('admin.team.index')
                ->with('success', 'Membre supprimé avec succès');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.team.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Activer/Désactiver un membre (AJAX)
     */
    public function toggleActive(TeamMember $teamMember)
    {
        try {
            $teamMember->update(['is_active' => !$teamMember->is_active]);

            return response()->json([
                'success' => true,
                'message' => $teamMember->is_active ? 'Membre activé avec succès' : 'Membre désactivé avec succès',
                'data' => [
                    'is_active' => $teamMember->is_active
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre en vedette ou retirer un membre (AJAX)
     */
    public function toggleFeatured(TeamMember $teamMember)
    {
        try {
            $teamMember->update(['is_featured' => !$teamMember->is_featured]);

            return response()->json([
                'success' => true,
                'message' => $teamMember->is_featured ? 'Membre mis en vedette avec succès' : 'Membre retiré des vedettes avec succès',
                'data' => [
                    'is_featured' => $teamMember->is_featured
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réordonner les membres (AJAX)
     */
    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*' => 'integer|exists:team_members,id'
            ]);

            foreach ($request->order as $index => $memberId) {
                TeamMember::where('id', $memberId)->update(['order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ordre mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du réordonnancement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les membres en CSV
     */
    public function export()
    {
        $members = TeamMember::ordered()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="team_members_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, ['ID', 'Nom', 'Poste', 'Email', 'Bio', 'Statut', 'Vedette', 'Ordre', 'Date création']);

            // Données
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->id,
                    $member->name,
                    $member->position,
                    $member->email,
                    Str::limit($member->bio, 100),
                    $member->is_active ? 'Actif' : 'Inactif',
                    $member->is_featured ? 'Oui' : 'Non',
                    $member->order,
                    $member->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
