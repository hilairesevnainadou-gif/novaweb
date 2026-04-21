<?php
// app/Models/TeamMember.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'photo',
        'bio',
        'quote',
        'skills',
        'email',
        'linkedin',
        'github',
        'twitter',
        'facebook',
        'order',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'skills' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer'
    ];

    // Scope pour les membres actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour les membres en vedette
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope pour l'ordre
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Accesseur pour l'URL de la photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    // Accesseur pour les compétences formatées
    public function getSkillsListAttribute()
    {
        if (is_array($this->skills)) {
            return $this->skills;
        }
        return json_decode($this->skills, true) ?? [];
    }
}
