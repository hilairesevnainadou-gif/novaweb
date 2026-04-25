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
        'avatar',
        'is_active',
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
            'is_active' => 'boolean',
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



// ===== RELATIONS DU MODULE PROJETS =====

/**
 * Projets dont l'utilisateur est chef de projet
 */
public function managedProjects()
{
    return $this->hasMany(Project::class, 'project_manager_id');
}

/**
 * Tâches assignées à l'utilisateur
 */
public function assignedTasks()
{
    return $this->hasMany(Task::class, 'assigned_to');
}

/**
 * Tâches créées par l'utilisateur
 */
public function createdTasks()
{
    return $this->hasMany(Task::class, 'created_by');
}

/**
 * Tâches reviewées par l'utilisateur
 */
public function reviewedTasks()
{
    return $this->hasMany(Task::class, 'reviewed_by');
}

/**
 * Commentaires de l'utilisateur
 */
public function taskComments()
{
    return $this->hasMany(TaskComment::class);
}

/**
 * Entrées de temps de l'utilisateur
 */
public function timeEntries()
{
    return $this->hasMany(TimeEntry::class);
}

/**
 * Réunions organisées par l'utilisateur
 */
public function organizedMeetings()
{
    return $this->hasMany(Meeting::class, 'organizer_id');
}

/**
 * Discussions créées par l'utilisateur
 */
public function discussions()
{
    return $this->hasMany(ProjectDiscussion::class);
}

/**
 * Messages de discussion de l'utilisateur
 */
public function discussionMessages()
{
    return $this->hasMany(DiscussionMessage::class);
}

/**
 * Activités de l'utilisateur
 */
public function activities()
{
    return $this->hasMany(ProjectActivity::class);
}

/**
 * Fichiers attachés par l'utilisateur
 */
public function attachments()
{
    return $this->hasMany(TaskAttachment::class);
}
}
