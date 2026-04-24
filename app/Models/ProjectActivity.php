<?php
// app/Models/ProjectActivity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'activity_type',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Accesseurs
    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Système';
    }

    public function getUserAvatarAttribute()
    {
        return $this->user?->initials() ?? 'S';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function getActivityIconAttribute()
    {
        return match($this->activity_type) {
            'project_created' => 'fas fa-plus-circle text-green-500',
            'status_changed' => 'fas fa-exchange-alt text-blue-500',
            'progress_updated' => 'fas fa-chart-line text-yellow-500',
            'task_created' => 'fas fa-tasks text-purple-500',
            'task_completed' => 'fas fa-check-circle text-green-500',
            'task_approved' => 'fas fa-thumbs-up text-green-500',
            'task_rejected' => 'fas fa-thumbs-down text-red-500',
            'task_status_changed' => 'fas fa-sync-alt text-blue-500',
            'meeting_scheduled' => 'fas fa-calendar-plus text-indigo-500',
            default => 'fas fa-info-circle text-gray-500'
        };
    }
}
