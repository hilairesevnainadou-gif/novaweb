<?php
// app/Models/Device.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'name',
        'brand',
        'model',
        'serial_number',
        'category',
        'purchase_date',
        'warranty_end_date',
        'status',
        'location',
        'client_id',
        'technical_specs',
        'image',
        'is_active',
        'order'  // Ajouter ce champ si vous voulez un ordre personnalisé
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_end_date' => 'date',
        'technical_specs' => 'array',
        'is_active' => 'boolean'
    ];

    // Statuts possibles
    const STATUS_OPERATIONAL = 'operational';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_REPAIR = 'repair';
    const STATUS_OUT_OF_SERVICE = 'out_of_service';

    // Catégories
    const CATEGORY_COMPUTER = 'computer';
    const CATEGORY_PRINTER = 'printer';
    const CATEGORY_NETWORK = 'network';
    const CATEGORY_PHONE = 'phone';
    const CATEGORY_OTHER = 'other';

    // ===== SCOPES =====

    /**
     * Filtrer les appareils actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtrer les appareils opérationnels
     */
    public function scopeOperational($query)
    {
        return $query->where('status', self::STATUS_OPERATIONAL);
    }

    /**
     * Filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Trier par ordre puis par nom
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Trier par date de création récente
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ===== RELATIONS =====

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function currentIntervention()
    {
        return $this->hasOne(Intervention::class)->whereIn('status', ['pending', 'in_progress'])->latest();
    }

    // ===== ACCESSORS =====

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_OPERATIONAL => 'Opérationnel',
            self::STATUS_MAINTENANCE => 'En maintenance',
            self::STATUS_REPAIR => 'En réparation',
            self::STATUS_OUT_OF_SERVICE => 'Hors service',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_OPERATIONAL => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_MAINTENANCE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            self::STATUS_REPAIR => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            self::STATUS_OUT_OF_SERVICE => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            self::CATEGORY_COMPUTER => 'Ordinateur',
            self::CATEGORY_PRINTER => 'Imprimante',
            self::CATEGORY_NETWORK => 'Réseau',
            self::CATEGORY_PHONE => 'Téléphonie',
            self::CATEGORY_OTHER => 'Autre',
            default => 'Non catégorisé'
        };
    }

    public function getWarrantyActiveAttribute()
    {
        return $this->warranty_end_date && $this->warranty_end_date->isFuture();
    }

    public function getFullNameAttribute()
    {
        return $this->brand && $this->model
            ? "{$this->brand} {$this->model} - {$this->name}"
            : $this->name;
    }
}
