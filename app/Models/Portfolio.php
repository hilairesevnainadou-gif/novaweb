<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'images',
        'client',
        'category',
        'url',
        'date',
        'technologies',
        'work_done',
        'project_type',
        'is_featured',
        'is_active',
        'order',
        'duration',     // Nouveau champ
        'team_size'     // Nouveau champ
    ];

    protected $casts = [
        'images' => 'array',
        'technologies' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('date', 'desc');
    }

    public function scopeForNovaTech($query)
    {
        return $query->where('project_type', 'internal');
    }

    public function scopeExternal($query)
    {
        return $query->where('project_type', 'external');
    }

    public function getCategoryBadgeClass()
    {
        $classes = [
            'site-vitrine' => 'badge-web',
            'e-commerce' => 'badge-ecommerce',
            'application-web' => 'badge-app',
            'maintenance' => 'badge-maintenance',
            'optimisation' => 'badge-optimization'
        ];

        return $classes[$this->category] ?? 'badge-default';
    }
}
