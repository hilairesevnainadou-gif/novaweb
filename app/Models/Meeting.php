<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'organizer_id',
        'title',
        'description',
        'type',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration_minutes',
        'location',
        'meeting_url',
        'meeting_mode',
        'agenda',
        'minutes',
        'action_items',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at'     => 'datetime',
            'started_at'       => 'datetime',
            'ended_at'         => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    /* ─── Constantes ─────────────────────────────────────────────── */

    const TYPES = [
        'kickoff'       => 'Lancement',
        'weekly'        => 'Hebdomadaire',
        'review'        => 'Revue',
        'demo'          => 'Démo',
        'retrospective' => 'Rétrospective',
        'emergency'     => 'Urgence',
        'other'         => 'Autre',
    ];

    const STATUSES = [
        'scheduled'  => 'Planifiée',
        'in_progress' => 'En cours',
        'completed'  => 'Terminée',
        'cancelled'  => 'Annulée',
        'postponed'  => 'Reportée',
    ];

    const MODES = [
        'in_person' => 'Présentiel',
        'online'    => 'En ligne',
        'hybrid'    => 'Hybride',
    ];

    /* ─── Relations ──────────────────────────────────────────────── */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_participants')
            ->withPivot('status')
            ->withTimestamps();
    }

    /* ─── Scopes ─────────────────────────────────────────────────── */

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /* ─── Accessors ──────────────────────────────────────────────── */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'completed'   => 'badge-active',
            'in_progress' => 'badge-info',
            'cancelled'   => 'badge-inactive',
            'postponed'   => 'badge-warning',
            default       => 'badge-secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getModeLabelAttribute(): string
    {
        return self::MODES[$this->meeting_mode] ?? ucfirst($this->meeting_mode);
    }

    public function getDurationFormattedAttribute(): string
    {
        if (! $this->duration_minutes) {
            return '-';
        }
        $h = intdiv($this->duration_minutes, 60);
        $m = $this->duration_minutes % 60;

        return $h > 0 ? "{$h}h" . ($m > 0 ? " {$m}min" : '') : "{$m}min";
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->scheduled_at->isFuture() && $this->status === 'scheduled';
    }
}
