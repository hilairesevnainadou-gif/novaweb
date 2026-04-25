<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'type', // web, mobile, software, other
        'client_id',
        'project_manager_id',
        'start_date',
        'end_date',
        'estimated_hours',
        'actual_hours',
        'budget',
        'status', // planning, in_progress, review, completed, cancelled
        'priority', // low, medium, high, critical
        'progress_percentage',
        'repository_url',
        'production_url',
        'staging_url',
        'technologies',
        'attachments',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'budget' => 'decimal:2',
        'progress_percentage' => 'integer',
        'technologies' => 'array',
        'attachments' => 'array',
        'is_active' => 'boolean',
    ];

    // Statuts
    const STATUS_PLANNING = 'planning';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_REVIEW = 'review';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    // Types
    const TYPE_WEB = 'web';

    const TYPE_MOBILE = 'mobile';

    const TYPE_SOFTWARE = 'software';

    const TYPE_OTHER = 'other';

    // Priorités
    const PRIORITY_LOW = 'low';

    const PRIORITY_MEDIUM = 'medium';

    const PRIORITY_HIGH = 'high';

    const PRIORITY_CRITICAL = 'critical';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (! $project->project_number) {
                $year = date('Y');
                $month = date('m');
                $last = static::whereYear('created_at', $year)->count();
                $project->project_number = 'PROJ-'.$year.$month.'-'.str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function projectDiscussions()
    {
        return $this->hasMany(ProjectDiscussion::class);
    }

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class)->latest();
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot(['role', 'added_by'])
            ->withTimestamps();
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class)->latest();
    }

    public function isMember(int $userId): bool
    {
        if ($this->project_manager_id === $userId) {
            return true;
        }

        return $this->members()->where('users.id', $userId)->exists();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProjectManager($query, $userId)
    {
        return $query->where('project_manager_id', $userId);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'Planification',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_REVIEW => 'En revue',
            self::STATUS_COMPLETED => 'Terminé',
            self::STATUS_CANCELLED => 'Annulé',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::STATUS_REVIEW => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'Basse',
            self::PRIORITY_MEDIUM => 'Moyenne',
            self::PRIORITY_HIGH => 'Haute',
            self::PRIORITY_CRITICAL => 'Critique',
            default => 'Non définie'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'bg-gray-100 text-gray-800',
            self::PRIORITY_MEDIUM => 'bg-blue-100 text-blue-800',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-800',
            self::PRIORITY_CRITICAL => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            self::TYPE_WEB => 'Site Web / Application Web',
            self::TYPE_MOBILE => 'Application Mobile',
            self::TYPE_SOFTWARE => 'Logiciel Desktop',
            self::TYPE_OTHER => 'Autre',
            default => 'Non défini'
        };
    }

    public function getProgressColorAttribute()
    {
        $progress = $this->progress_percentage ?? 0;
        if ($progress < 30) {
            return 'bg-red-500';
        }
        if ($progress < 70) {
            return 'bg-yellow-500';
        }

        return 'bg-green-500';
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== self::STATUS_COMPLETED &&
               $this->end_date &&
               $this->end_date->isPast();
    }

    public function getRemainingDaysAttribute()
    {
        if (! $this->end_date || $this->end_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->end_date);
    }

    public function getTotalTasksAttribute()
    {
        return $this->tasks()->count();
    }

    public function getCompletedTasksAttribute()
    {
        return $this->tasks()->where('status', Task::STATUS_COMPLETED)->count();
    }

    public function getProgressFromTasksAttribute()
    {
        if ($this->total_tasks === 0) {
            return 0;
        }

        return round(($this->completed_tasks / $this->total_tasks) * 100);
    }

    public function getTotalTimeSpentAttribute()
    {
        return $this->timeEntries()->sum('hours');
    }

    public function getBudgetVarianceAttribute()
    {
        if (! $this->budget || $this->budget <= 0) {
            return 0;
        }
        $totalCost = $this->timeEntries()->sum('hours') * 50; // Exemple: 50€/heure

        return round((($totalCost - $this->budget) / $this->budget) * 100, 2);
    }
}
