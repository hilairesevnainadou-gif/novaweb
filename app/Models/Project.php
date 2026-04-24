<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_number',
        'name',
        'slug',
        'description',
        'client_id',
        'manager_id',
        'status',
        'priority',
        'type',
        'start_date',
        'end_date',
        'actual_end_date',
        'budget',
        'spent_amount',
        'progress',
        'color',
        'cover_image',
        'technologies',
        'tags',
        'notes',
        'is_billable',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'start_date'      => 'date',
            'end_date'        => 'date',
            'actual_end_date' => 'date',
            'budget'          => 'decimal:2',
            'spent_amount'    => 'decimal:2',
            'progress'        => 'integer',
            'is_billable'     => 'boolean',
            'is_public'       => 'boolean',
            'technologies'    => 'array',
            'tags'            => 'array',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Project $project): void {
            if (empty($project->project_number)) {
                $prefix = 'PRJ-' . now()->format('Ym') . '-';
                $last   = static::withTrashed()
                    ->where('project_number', 'like', $prefix . '%')
                    ->latest('id')
                    ->value('project_number');
                $next           = $last ? (int) substr($last, -4) + 1 : 1;
                $project->project_number = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
            }

            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name) . '-' . Str::random(4);
            }
        });
    }

    /* ─── Constantes ─────────────────────────────────────────────── */

    const STATUSES = [
        'planning'  => 'Planification',
        'active'    => 'Actif',
        'on_hold'   => 'En pause',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé',
    ];

    const PRIORITIES = [
        'low'    => 'Faible',
        'medium' => 'Moyen',
        'high'   => 'Élevé',
        'urgent' => 'Urgent',
    ];

    const TYPES = [
        'web'         => 'Site Web',
        'mobile'      => 'Application Mobile',
        'design'      => 'Design / UI',
        'consulting'  => 'Consulting',
        'maintenance' => 'Maintenance',
        'other'       => 'Autre',
    ];

    /* ─── Relations ──────────────────────────────────────────────── */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ProjectDiscussion::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class)->latest();
    }

    /* ─── Scopes ─────────────────────────────────────────────────── */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /* ─── Accessors ──────────────────────────────────────────────── */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'badge-active',
            'completed' => 'badge-completed',
            'on_hold'   => 'badge-warning',
            'cancelled' => 'badge-inactive',
            default     => 'badge-info',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst($this->priority);
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'badge-danger',
            'high'   => 'badge-warning',
            'medium' => 'badge-info',
            default  => 'badge-secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date
            && $this->end_date->isPast()
            && ! in_array($this->status, ['completed', 'cancelled']);
    }

    public function getTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'done')->count();
    }

    public function getBudgetRemainingAttribute(): float
    {
        return max(0, (float) $this->budget - (float) $this->spent_amount);
    }

    public function logActivity(string $action, string $description, ?User $user = null, array $properties = []): void
    {
        $this->activities()->create([
            'user_id'     => $user?->id ?? auth()->id(),
            'action'      => $action,
            'description' => $description,
            'properties'  => $properties ?: null,
        ]);
    }
}
