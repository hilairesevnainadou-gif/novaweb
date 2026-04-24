<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'created_by',
        'parent_id',
        'title',
        'description',
        'status',
        'priority',
        'category',
        'due_date',
        'started_at',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'order',
        'is_approved',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'due_date'     => 'date',
            'started_at'   => 'date',
            'completed_at' => 'date',
            'approved_at'  => 'datetime',
            'is_approved'  => 'boolean',
            'order'        => 'integer',
        ];
    }

    /* ─── Constantes ─────────────────────────────────────────────── */

    const STATUSES = [
        'todo'        => 'À faire',
        'in_progress' => 'En cours',
        'review'      => 'En révision',
        'done'        => 'Terminée',
        'cancelled'   => 'Annulée',
    ];

    const PRIORITIES = [
        'low'    => 'Faible',
        'medium' => 'Moyen',
        'high'   => 'Élevé',
        'urgent' => 'Urgent',
    ];

    /* ─── Relations ──────────────────────────────────────────────── */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class)->latest();
    }

    /* ─── Scopes ─────────────────────────────────────────────────── */

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'review')->whereNull('is_approved');
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->whereNotIn('status', ['done', 'cancelled']);
    }

    /* ─── Accessors ──────────────────────────────────────────────── */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'done'        => 'badge-active',
            'in_progress' => 'badge-info',
            'review'      => 'badge-warning',
            'cancelled'   => 'badge-inactive',
            default       => 'badge-secondary',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst($this->priority);
    }

    public function getPriorityIconAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'fa-circle-exclamation',
            'high'   => 'fa-arrow-up',
            'medium' => 'fa-equals',
            default  => 'fa-arrow-down',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && ! in_array($this->status, ['done', 'cancelled']);
    }

    public function getTotalLoggedHoursAttribute(): float
    {
        return round($this->timeEntries()->sum('minutes') / 60, 1);
    }
}
