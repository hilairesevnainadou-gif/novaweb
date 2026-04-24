<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectActivity extends Model
{
    use HasFactory;

    public $timestamps = true;

    const UPDATED_AT = null;

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created'   => 'fa-plus-circle',
            'updated'   => 'fa-pen',
            'deleted'   => 'fa-trash',
            'completed' => 'fa-check-circle',
            'approved'  => 'fa-thumbs-up',
            'rejected'  => 'fa-thumbs-down',
            'commented' => 'fa-comment',
            'uploaded'  => 'fa-upload',
            default     => 'fa-circle-dot',
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created'   => '#10b981',
            'deleted'   => '#ef4444',
            'completed' => '#10b981',
            'approved'  => '#10b981',
            'rejected'  => '#ef4444',
            default     => '#3b82f6',
        };
    }
}
