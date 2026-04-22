<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // ===== RELATIONS =====

    /**
     * Interventions assignées au technicien
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'technician_id');
    }

    /**
     * Interventions créées par l'utilisateur
     */
    public function createdInterventions()
    {
        return $this->hasMany(Intervention::class, 'created_by');
    }

    /**
     * Évolutions d'intervention enregistrées par l'utilisateur
     */
    public function interventionEvolutions()
    {
        return $this->hasMany(InterventionEvolution::class, 'user_id');
    }

    /**
     * Tickets créés par l'utilisateur
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Tickets assignés à l'utilisateur
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
}
