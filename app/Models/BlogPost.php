<?php
// app/Models/BlogPost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image',
        'published_at',
        'is_published',
        'reading_time',
        'meta_keywords',
        'meta_description',
        'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean'
    ];

    /**
     * Scope pour les articles publiés
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at');
    }

    /**
     * Scope pour les articles en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
