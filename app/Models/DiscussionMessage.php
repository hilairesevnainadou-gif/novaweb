<?php
// app/Models/DiscussionMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'message',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    // Relations
    public function discussion()
    {
        return $this->belongsTo(ProjectDiscussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesseurs
    public function getFormattedMessageAttribute()
    {
        return nl2br(e($this->message));
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Utilisateur inconnu';
    }

    public function getUserAvatarAttribute()
    {
        return $this->user?->initials() ?? '?';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function getCreatedAtShortAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
