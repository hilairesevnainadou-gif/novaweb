<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'icon',
        'icon_color',
        'features',
        'order',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    // Ajoutez cette méthode scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
