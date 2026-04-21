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
            'slogan' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_email' => 'nullable|email',
            'site_phone' => 'nullable|string',
            'site_address' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        // Mapper les données du formulaire vers le modèle
        $settings->name = $request->site_name;
        $settings->slogan = $request->slogan;
        $settings->description = $request->site_description;
        $settings->email = $request->site_email;
        $settings->phone = $request->site_phone;
        $settings->address = $request->site_address;
        $settings->website = $request->website;

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
            'site_favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:1024',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'about_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        // Gestion du logo
        if ($request->hasFile('site_logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $logoFile = $request->file('site_logo');
            $logoName = 'logo_' . time() . '_' . Str::random(10) . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $logoFile->storeAs('settings', $logoName, 'public');
            $settings->logo = $logoPath;
        }

        // Gestion du favicon
        if ($request->hasFile('site_favicon')) {
            if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $faviconFile = $request->file('site_favicon');
            $faviconName = 'favicon_' . time() . '_' . Str::random(10) . '.' . $faviconFile->getClientOriginalExtension();
            $faviconPath = $faviconFile->storeAs('settings', $faviconName, 'public');
            $settings->favicon = $faviconPath;
        }

        // Gestion de l'image de bannière
        if ($request->hasFile('banner_image')) {
            if ($settings->banner_image && Storage::disk('public')->exists($settings->banner_image)) {
                Storage::disk('public')->delete($settings->banner_image);
            }
            $bannerFile = $request->file('banner_image');
            $bannerName = 'banner_' . time() . '_' . Str::random(10) . '.' . $bannerFile->getClientOriginalExtension();
            $bannerPath = $bannerFile->storeAs('settings', $bannerName, 'public');
            $settings->banner_image = $bannerPath;
        }

        // Gestion de l'image À propos
        if ($request->hasFile('about_image')) {
            if ($settings->about_image && Storage::disk('public')->exists($settings->about_image)) {
                Storage::disk('public')->delete($settings->about_image);
            }
            $aboutFile = $request->file('about_image');
            $aboutName = 'about_' . time() . '_' . Str::random(10) . '.' . $aboutFile->getClientOriginalExtension();
            $aboutPath = $aboutFile->storeAs('settings', $aboutName, 'public');
            $settings->about_image = $aboutPath;
        }

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Identité visuelle mise à jour avec succès');
    }

    public function updateLegal(Request $request)
    {
        $request->validate([
            'legal_form' => 'nullable|string|max:255',
            'capital' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'ifu' => 'nullable|string|max:255',
            'director' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'legal_address' => 'nullable|string',
            'data_protection_officer' => 'nullable|string|max:255',
            'hosting_name' => 'nullable|string|max:255',
            'hosting_address' => 'nullable|string',
            'hosting_phone' => 'nullable|string|max:255',
            'hosting_url' => 'nullable|url'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->legal_form = $request->legal_form;
        $settings->capital = $request->capital;
        $settings->rccm = $request->rccm;
        $settings->ifu = $request->ifu;
        $settings->director = $request->director;
        $settings->vat_number = $request->vat_number;
        $settings->legal_address = $request->legal_address;
        $settings->data_protection_officer = $request->data_protection_officer;
        $settings->hosting_name = $request->hosting_name;
        $settings->hosting_address = $request->hosting_address;
        $settings->hosting_phone = $request->hosting_phone;
        $settings->hosting_url = $request->hosting_url;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Mentions légales mises à jour avec succès');
    }

    public function updateContact(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|string'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->address = $request->address;
        $settings->latitude = $request->latitude;
        $settings->longitude = $request->longitude;
        $settings->google_maps_url = $request->google_maps_url;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Informations de contact mises à jour avec succès');
    }

    public function updateHours(Request $request)
    {
        $request->validate([
            'opening_hours' => 'nullable|string|max:255',
            'opening_hours_weekend' => 'nullable|string|max:255'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->opening_hours = $request->opening_hours;
        $settings->opening_hours_weekend = $request->opening_hours_weekend;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Horaires d\'ouverture mis à jour avec succès');
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'nullable|string|max:255',
            'about_description_1' => 'nullable|string',
            'about_description_2' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'values' => 'nullable|string',
            'years_experience' => 'nullable|integer'
        ]);

        $settings = CompanyInfo::first();

        if (!$settings) {
            $settings = new CompanyInfo();
        }

        $settings->about_title = $request->about_title;
        $settings->about_description_1 = $request->about_description_1;
        $settings->about_description_2 = $request->about_description_2;
        $settings->mission = $request->mission;
        $settings->vision = $request->vision;
        $settings->values = $request->values;
        $settings->years_experience = $request->years_experience;

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Section "À propos" mise à jour avec succès');
    }

    public function removeImage(Request $request)
    {
        $request->validate([
            'field' => 'required|in:logo,favicon,banner_image,about_image'
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

    public function updateBanking(Request $request)
{
    $request->validate([
        'bank_name' => 'nullable|string|max:255',
        'bank_account_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:255',
        'bank_iban' => 'nullable|string|max:255',
        'bank_swift' => 'nullable|string|max:255',
        'mobile_money_number' => 'nullable|string|max:255',
        'mobile_money_operator' => 'nullable|string|max:50',
        'payment_instructions' => 'nullable|string'
    ]);

    $settings = CompanyInfo::first();

    if (!$settings) {
        $settings = new CompanyInfo();
    }

    $settings->bank_name = $request->bank_name;
    $settings->bank_account_name = $request->bank_account_name;
    $settings->bank_account_number = $request->bank_account_number;
    $settings->bank_iban = $request->bank_iban;
    $settings->bank_swift = $request->bank_swift;
    $settings->mobile_money_number = $request->mobile_money_number;
    $settings->mobile_money_operator = $request->mobile_money_operator;
    $settings->payment_instructions = $request->payment_instructions;

    $settings->save();

    return redirect()->route('admin.settings.index')
        ->with('success', 'Informations bancaires mises à jour avec succès');
}
}
