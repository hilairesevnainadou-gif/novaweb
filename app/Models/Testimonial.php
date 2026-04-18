<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'company',
        'content',
        'rating',
        'avatar',
        'is_active',
        'order'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean'
    ];

    // Scope pour les témoignages actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour l'ordre (si vous avez un champ order)
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
