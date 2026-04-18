<?php
// app/Models/Ticket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
        'priority',
        'assigned_to',
        'closed_at'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'status' => 'string',
        'priority' => 'string'
    ];

    // Statuts possibles
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CLOSED = 'closed';
    const STATUS_RESOLVED = 'resolved';

    // Priorités possibles
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accesseurs
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_OPEN => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::STATUS_RESOLVED => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_CLOSED => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            self::PRIORITY_MEDIUM => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            self::PRIORITY_URGENT => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
