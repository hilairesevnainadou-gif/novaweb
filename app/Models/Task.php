<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'parent_id',
        'task_number',
        'title',
        'description',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'task_type',
        'estimated_hours',
        'actual_hours',
        'start_date',
        'due_date',
        'completed_at',
        'completed_by',
        'completion_notes',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'is_approved',
        'attachments',
        'order'
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'is_approved' => 'boolean',
        'attachments' => 'array',
        'order' => 'integer'
    ];

    // Statuts
    const STATUS_TODO = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Priorités
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Types de tâches
    const TYPE_FEATURE = 'feature';
    const TYPE_BUG = 'bug';
    const TYPE_TASK = 'task';
    const TYPE_RESEARCH = 'research';
    const TYPE_DESIGN = 'design';
    const TYPE_TESTING = 'testing';
    const TYPE_DOCUMENTATION = 'documentation';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (!$task->task_number) {
                $project = Project::find($task->project_id);
                if ($project) {
                    $taskCount = static::where('project_id', $task->project_id)->count();
                    $task->task_number = $project->project_number . '-T' . str_pad($taskCount + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAssignee($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_REVIEW);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED])
                     ->where('due_date', '<', now());
    }

    public function scopeForReviewBy($query, $userId)
    {
        return $query->where('status', self::STATUS_REVIEW)
                     ->whereHas('project', function($q) use ($userId) {
                         $q->where('project_manager_id', $userId);
                     });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'asc');
    }

    public function scopeTodo($query)
    {
        return $query->where('status', self::STATUS_TODO);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_TODO => 'À faire',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_REVIEW => 'En revue',
            self::STATUS_APPROVED => 'Approuvée',
            self::STATUS_REJECTED => 'Rejetée',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_TODO => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::STATUS_REVIEW => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            self::STATUS_COMPLETED => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
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
            default => 'Non définie'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'bg-gray-100 text-gray-800',
            self::PRIORITY_MEDIUM => 'bg-blue-100 text-blue-800',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-800',
            self::PRIORITY_URGENT => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->task_type) {
            self::TYPE_FEATURE => 'Fonctionnalité',
            self::TYPE_BUG => 'Bug / Correction',
            self::TYPE_TASK => 'Tâche',
            self::TYPE_RESEARCH => 'Recherche',
            self::TYPE_DESIGN => 'Design',
            self::TYPE_TESTING => 'Test',
            self::TYPE_DOCUMENTATION => 'Documentation',
            default => 'Tâche'
        };
    }

    public function getIsOverdueAttribute()
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED]) &&
               $this->due_date &&
               $this->due_date->isPast();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->estimated_hours <= 0) return 0;
        return round(($this->actual_hours / $this->estimated_hours) * 100);
    }

    public function getRemainingHoursAttribute()
    {
        return max(0, $this->estimated_hours - $this->actual_hours);
    }

    public function getTimeVarianceAttribute()
    {
        if ($this->estimated_hours <= 0) return 0;
        return round((($this->actual_hours - $this->estimated_hours) / $this->estimated_hours) * 100, 2);
    }

    // Méthodes
    public function markAsCompleted($userId, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_REVIEW,
            'completed_at' => now(),
            'completed_by' => $userId,
            'completion_notes' => $notes
        ]);

        $this->project->activities()->create([
            'user_id' => $userId,
            'activity_type' => 'task_completed',
            'description' => "Tâche '{$this->title}' marquée comme terminée par " . ($this->assignee?->name ?? 'Utilisateur'),
            'metadata' => ['task_id' => $this->id]
        ]);
    }

    public function approveTask($userId, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'is_approved' => true,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_notes' => $notes
        ]);

        $this->project->activities()->create([
            'user_id' => $userId,
            'activity_type' => 'task_approved',
            'description' => "Tâche '{$this->title}' approuvée par " . ($this->reviewer?->name ?? 'Utilisateur'),
            'metadata' => ['task_id' => $this->id]
        ]);
    }

    public function rejectTask($userId, $reason)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_notes' => $reason
        ]);

        $this->project->activities()->create([
            'user_id' => $userId,
            'activity_type' => 'task_rejected',
            'description' => "Tâche '{$this->title}' rejetée par " . ($this->reviewer?->name ?? 'Utilisateur') . " : {$reason}",
            'metadata' => ['task_id' => $this->id]
        ]);
    }

    public function startProgress()
    {
        if ($this->status === self::STATUS_TODO) {
            $this->update([
                'status' => self::STATUS_IN_PROGRESS,
                'start_date' => $this->start_date ?? now()
            ]);
        }
    }

    public function addTimeEntry($userId, $hours, $description = null, $date = null)
    {
        return $this->timeEntries()->create([
            'user_id' => $userId,
            'project_id' => $this->project_id,
            'hours' => $hours,
            'description' => $description,
            'date' => $date ?? now(),
            'is_billable' => true
        ]);
    }
}
