<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:0|max:5',
            'avatar' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['avatar']);

        // Gestion de la note 0 (non noté)
        $data['rating'] = $request->rating == 0 ? null : $request->rating;

        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        // Gestion du statut actif
        $data['is_active'] = $request->has('is_active');

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Témoignage ajouté avec succès');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:0|max:5',
            'avatar' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'delete_avatar' => 'boolean'
        ]);

        $data = $request->except(['avatar', 'delete_avatar']);

        // Gestion de la note 0 (non noté)
        $data['rating'] = $request->rating == 0 ? null : $request->rating;

        // Gestion de la suppression de l'avatar existant
        if ($request->has('delete_avatar') && $request->delete_avatar == 1) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
                $data['avatar'] = null;
            }
        }

        // Gestion du nouvel avatar
        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        // Gestion du statut actif
        $data['is_active'] = $request->has('is_active');

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Témoignage mis à jour avec succès');
    }

    public function destroy(Testimonial $testimonial)
    {
        try {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $testimonial->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Témoignage supprimé avec succès'
                ]);
            }

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Témoignage supprimé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur suppression témoignage: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }

            return redirect()->route('admin.testimonials.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    public function toggleActive(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_active' => !$testimonial->is_active]);
            $status = $testimonial->is_active ? 'activé' : 'désactivé';

            return response()->json([
                'success' => true,
                'message' => "Témoignage {$status} avec succès",
                'is_active' => $testimonial->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur toggle témoignage: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération'
            ], 500);
        }
    }
}
