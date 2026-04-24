<?php
// app/Models/ProjectDiscussion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_resolved'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_resolved' => 'boolean'
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

    public function messages()
    {
        return $this->hasMany(DiscussionMessage::class);
    }

    // Scopes
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    // Accesseurs
    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Utilisateur inconnu';
    }

    public function getMessagesCountAttribute()
    {
        return $this->messages()->count();
    }

    public function getLastMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    // Méthodes
    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }

    public function resolve()
    {
        $this->update(['is_resolved' => true]);
    }

    public function reopen()
    {
        $this->update(['is_resolved' => false]);
    }
}
