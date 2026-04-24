<?php
// app/Models/TaskComment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'attachments',
        'parent_id'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    // Relations
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(TaskComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(TaskComment::class, 'parent_id');
    }

    // Accesseurs
    public function getFormattedCommentAttribute()
    {
        return nl2br(e($this->comment));
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Utilisateur inconnu';
    }

    public function getUserAvatarAttribute()
    {
        return $this->user?->initials() ?? '?';
    }
}
