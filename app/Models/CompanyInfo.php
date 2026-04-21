<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{

 protected $table = 'company_infos';

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
        'web_development_percentage',
        'it_maintenance_percentage',
        'client_satisfaction_percentage',
        'happy_clients_count',
        'projects_completed',
        'support_hours',

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
        'legal_form',
        'capital',
        'rccm',
        'ifu',
        'director',
        'legal_address',

        // Informations d'hébergement
        'hosting_name',
        'hosting_address',
        'hosting_phone',
        'hosting_url',

        // Mentions supplémentaires
        'data_protection_officer',
        'cnie_number',
        'trade_register',
        'vat_number',

        // ========== INFORMATIONS BANCAIRES ==========
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_iban',
        'bank_swift',
        'mobile_money_number',
        'mobile_money_operator',
        'payment_instructions',
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
