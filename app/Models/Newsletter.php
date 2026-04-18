<?php
// app/Models/Newsletter.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'unsubscribe_token',
        'is_active',
        'subscribed_at',
        'unsubscribed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Boot method to generate token automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->unsubscribe_token)) {
                $model->unsubscribe_token = Str::random(64);
            }
            if (empty($model->subscribed_at)) {
                $model->subscribed_at = now();
            }
        });
    }

    // Scope pour les abonnés actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour les abonnés inactifs
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
