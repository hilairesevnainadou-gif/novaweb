<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $fillable = [
        // ========== IDENTITÉ & INFORMATIONS DE BASE ==========
        'name',
        'slogan',

        // ========== CONTACT ==========
        'email',
        'phone',
        'address',
        'whatsapp',

        // ========== MÉDIAS & IMAGES ==========
        'logo',
        'banner_image',
        'about_image',
        'favicon',

        // ========== BANNER / HERO SECTION ==========
        'hero_title',
        'hero_description',

        // ========== ABOUT SECTION ==========
        'about_title',
        'about_description_1',
        'about_description_2',

        // ========== RÉSEAUX SOCIAUX ==========
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',

        // ========== DESCRIPTION ET MÉTADONNÉES ==========
        'description',
        'meta_description',
        'meta_keywords',

        // ========== STATISTIQUES ==========
        'years_experience',

        // ========== HORAIRES ==========
        'opening_hours',
        'opening_hours_weekend',

        // ========== LOCALISATION ==========
        'latitude',
        'longitude',
        'google_maps_url',

        // ========== INFORMATIONS SUPPLÉMENTAIRES ==========
        'website',
        'mission',
        'vision',
        'values',

        // ========== MENTIONS LÉGALES ==========
        // Informations juridiques
        'legal_form',           // Forme juridique (SARL, SAS, etc.)
        'capital',              // Capital social
        'rccm',                 // Numéro RCCM
        'ifu',                  // Numéro IFU (Impôt)
        'director',             // Directeur de publication
        'legal_address',        // Adresse légale (si différente)

        // Informations d'hébergement
        'hosting_name',         // Nom de l'hébergeur
        'hosting_address',      // Adresse de l'hébergeur
        'hosting_phone',        // Téléphone de l'hébergeur
        'hosting_url',          // Site web de l'hébergeur

        // Mentions supplémentaires
        'data_protection_officer',  // DPO (Délégué à la protection des données)
        'cnie_number',              // Numéro CNIE (si applicable)
        'trade_register',           // Registre du commerce
        'vat_number',               // Numéro de TVA
    ];

    // Accesseurs pour les URLs des médias
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    public function getAboutImageUrlAttribute()
    {
        return $this->about_image ? asset('storage/' . $this->about_image) : null;
    }

    // Accesseurs avec valeurs par défaut pour les mentions légales
    public function getLegalFormAttribute($value)
    {
        return $value ?? 'SARL';
    }

    public function getCapitalAttribute($value)
    {
        return $value ?? '1 000 000 FCFA';
    }

    public function getRccmAttribute($value)
    {
        return $value ?? 'RB/COT/2023/XXXX';
    }

    public function getIfuAttribute($value)
    {
        return $value ?? '3202300000000';
    }

    public function getDirectorAttribute($value)
    {
        return $value ?? 'Le Directeur Général';
    }

    public function getHostingNameAttribute($value)
    {
        return $value ?? 'Hostinger International Ltd';
    }

    public function getHostingAddressAttribute($value)
    {
        return $value ?? 'Šeimininkių g. 3, LT-09231 Vilnius, Lituanie';
    }

    public function getHostingPhoneAttribute($value)
    {
        return $value ?? '+370 645 03378';
    }

    public function getHostingUrlAttribute($value)
    {
        return $value ?? 'https://www.hostinger.com';
    }

    // Scope pour les informations actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
