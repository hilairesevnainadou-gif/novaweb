<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::first();

        // Si aucune info n'existe, créer un enregistrement par défaut
        if (!$companyInfo) {
            $companyInfo = CompanyInfo::create([
                'name' => config('app.name', 'NovaTech'),
            ]);
        }

        return view('admin.settings.index', compact('companyInfo'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_email' => 'nullable|email',
            'site_phone' => 'nullable|string',
            'site_address' => 'nullable|string',
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        // Mapper les données du formulaire vers le modèle
        $settings->name = $request->site_name;
        $settings->description = $request->site_description;
        $settings->email = $request->site_email;
        $settings->phone = $request->site_phone;
        $settings->address = $request->site_address;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres généraux mis à jour avec succès');
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whatsapp' => 'nullable|string'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->facebook = $request->facebook;
        $settings->twitter = $request->twitter;
        $settings->instagram = $request->instagram;
        $settings->linkedin = $request->linkedin;
        $settings->youtube = $request->youtube;
        $settings->whatsapp = $request->whatsapp;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Liens sociaux mis à jour avec succès');
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'site_keywords' => 'nullable|string',
            'site_meta_description' => 'nullable|string|max:160',
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->meta_keywords = $request->site_keywords;
        $settings->meta_description = $request->site_meta_description;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres SEO mis à jour avec succès');
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:1024'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        // Gestion du logo
        if ($request->hasFile('site_logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }

            // Générer un nom unique pour le logo
            $logoFile = $request->file('site_logo');
            $logoName = 'logo_' . time() . '_' . Str::random(10) . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $logoFile->storeAs('settings', $logoName, 'public');
            $settings->logo = $logoPath;
        }

        // Gestion du favicon
        if ($request->hasFile('site_favicon')) {
            // Supprimer l'ancien favicon s'il existe
            if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
                Storage::disk('public')->delete($settings->favicon);
            }

            // Générer un nom unique pour le favicon
            $faviconFile = $request->file('site_favicon');
            $faviconName = 'favicon_' . time() . '_' . Str::random(10) . '.' . $faviconFile->getClientOriginalExtension();
            $faviconPath = $faviconFile->storeAs('settings', $faviconName, 'public');
            $settings->favicon = $faviconPath;
        }

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Identité visuelle mise à jour avec succès');
    }

    public function removeImage(Request $request)
    {
        $request->validate([
            'field' => 'required|in:logo,favicon'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune configuration trouvée'
            ], 404);
        }

        $field = $request->field;

        // Vérifier si le champ existe et a une valeur
        if ($settings->$field && Storage::disk('public')->exists($settings->$field)) {
            Storage::disk('public')->delete($settings->$field);
            $settings->$field = null;
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aucune image à supprimer'
        ], 404);
    }
}
