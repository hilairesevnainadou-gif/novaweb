<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $fillable = [
        // Identité & Informations de base
        'name',
        'slogan',

        // Contact
        'email',
        'phone',
        'address',

        // Médias & Images
        'logo',
        'banner_image',
        'about_image',
        'favicon',

        // Banner / Hero Section
        'hero_title',
        'hero_description',

        // About Section
        'about_title',
        'about_description_1',
        'about_description_2',

        // Réseaux sociaux
        'facebook',
        'twitter',
        'whatsapp',
        'instagram',
        'linkedin',
        'youtube',

        // Description et métadonnées
        'description',
        'meta_description',
        'meta_keywords',

        // Statistiques
        'years_experience',

        // Horaires
        'opening_hours',
        'opening_hours_weekend',

        // Localisation
        'latitude',
        'longitude',
        'google_maps_url',

        // Informations supplémentaires
        'website',
        'mission',
        'vision',
        'values',
    ];
}
