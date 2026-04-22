<?php
// app/Models/InterventionEvolution.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionEvolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'user_id',
        'previous_level',
        'new_level',
        'reason',
        'notes',
        'additional_cost',
        'time_spent_minutes'
    ];

    protected $casts = [
        'previous_level' => 'integer',
        'new_level' => 'integer',
        'additional_cost' => 'decimal:2',
        'time_spent_minutes' => 'integer'
    ];

    // Relations
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesseur
    public function getLevelChangeLabelAttribute()
    {
        if ($this->new_level > $this->previous_level) {
            return "Montée en compétence : Niveau {$this->previous_level} → {$this->new_level}";
        }
        return "Changement de niveau : Niveau {$this->previous_level} → {$this->new_level}";
    }
}
