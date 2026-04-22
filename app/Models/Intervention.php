<?php
// app/Models/Intervention.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'intervention_number',
        'device_id',
        'client_id',
        'technician_id',
        'title',
        'description',
        'problem_type',
        'problem_description',
        'solution',
        'status',
        'priority',
        'evolution_level',
        'estimated_cost',
        'actual_cost',
        'start_date',
        'end_date',
        'scheduled_date',
        'duration_minutes',
        'parts_used',
        'notes',
        'attachments',
        'signature',
        'client_rated',
        'client_rating',
        'client_feedback'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'duration_minutes' => 'integer',
        'parts_used' => 'array',
        'attachments' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'scheduled_date' => 'datetime',
        'client_rated' => 'boolean',
        'client_rating' => 'integer'
    ];

    // Statuts
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Priorités
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';
    const PRIORITY_CRITICAL = 'critical';

    // Types de problème
    const PROBLEM_HARDWARE = 'hardware';
    const PROBLEM_SOFTWARE = 'software';
    const PROBLEM_NETWORK = 'network';
    const PROBLEM_ELECTRICAL = 'electrical';
    const PROBLEM_MECHANICAL = 'mechanical';
    const PROBLEM_OTHER = 'other';

    // Niveaux d'évolution
    const EVOLUTION_LEVEL_1 = 1; // Diagnostic
    const EVOLUTION_LEVEL_2 = 2; // Intervention simple
    const EVOLUTION_LEVEL_3 = 3; // Intervention complexe
    const EVOLUTION_LEVEL_4 = 4; // Intervention majeure
    const EVOLUTION_LEVEL_5 = 5; // Intervention critique

    // Génération automatique du numéro d'intervention
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($intervention) {
            if (!$intervention->intervention_number) {
                $year = date('Y');
                $month = date('m');
                $last = static::whereYear('created_at', $year)->count();
                $intervention->intervention_number = 'INT-' . $year . $month . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relations
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function evolutionHistory()
    {
        return $this->hasMany(InterventionEvolution::class);
    }

    public function expenses()
    {
        return $this->hasMany(InterventionExpense::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }


    public function scopeByTechnician($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT, self::PRIORITY_CRITICAL]);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_APPROVED => 'Approuvée',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            self::STATUS_APPROVED => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::STATUS_IN_PROGRESS => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Basse',
            self::PRIORITY_MEDIUM => 'Moyenne',
            self::PRIORITY_HIGH => 'Haute',
            self::PRIORITY_URGENT => 'Urgente',
            self::PRIORITY_CRITICAL => 'Critique',
            default => 'Non définie'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            self::PRIORITY_MEDIUM => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            self::PRIORITY_URGENT => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            self::PRIORITY_CRITICAL => 'bg-red-600 text-white dark:bg-red-700',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getProblemTypeLabelAttribute()
    {
        return match($this->problem_type) {
            self::PROBLEM_HARDWARE => 'Matériel',
            self::PROBLEM_SOFTWARE => 'Logiciel',
            self::PROBLEM_NETWORK => 'Réseau',
            self::PROBLEM_ELECTRICAL => 'Électrique',
            self::PROBLEM_MECHANICAL => 'Mécanique',
            self::PROBLEM_OTHER => 'Autre',
            default => 'Non spécifié'
        };
    }

    public function getEvolutionLevelLabelAttribute()
    {
        return match($this->evolution_level) {
            self::EVOLUTION_LEVEL_1 => 'Niveau 1 - Diagnostic',
            self::EVOLUTION_LEVEL_2 => 'Niveau 2 - Intervention simple',
            self::EVOLUTION_LEVEL_3 => 'Niveau 3 - Intervention complexe',
            self::EVOLUTION_LEVEL_4 => 'Niveau 4 - Intervention majeure',
            self::EVOLUTION_LEVEL_5 => 'Niveau 5 - Intervention critique',
            default => 'Non défini'
        };
    }

    public function getCostVarianceAttribute()
    {
        if ($this->estimated_cost <= 0) return 0;
        return (($this->actual_cost - $this->estimated_cost) / $this->estimated_cost) * 100;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== self::STATUS_COMPLETED &&
               $this->scheduled_date &&
               $this->scheduled_date->isPast();
    }

    public function getDurationFormattedAttribute()
    {
        if (!$this->duration_minutes) return null;

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}min" : "{$hours}h";
        }
        return "{$minutes}min";
    }


/**
 * Scope pour les interventions terminées
 */
public function scopeCompleted($query)
{
    return $query->where('status', self::STATUS_COMPLETED);
}

/**
 * Scope pour les interventions terminées ce mois-ci
 */
public function scopeCompletedThisMonth($query)
{
    return $query->where('status', self::STATUS_COMPLETED)
                 ->whereMonth('end_date', now()->month)
                 ->whereYear('end_date', now()->year);
}
}
