<?php
// app/Models/Meeting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'meeting_date',
        'duration_minutes',
        'meeting_link',
        'location',
        'organizer_id',
        'attendees',
        'minutes',
        'decisions',
        'action_items',
        'status',
        'recorded'
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'attendees' => 'array',
        'action_items' => 'array',
        'recorded' => 'boolean'
    ];

    // Statuts
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>', now())
                     ->where('status', self::STATUS_SCHEDULED);
    }

    public function scopePast($query)
    {
        return $query->where('meeting_date', '<', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('meeting_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('meeting_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'Planifiée',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::STATUS_COMPLETED => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}min" : "{$hours}h";
        }
        return "{$minutes}min";
    }

    public function getAttendeesListAttribute()
    {
        if (empty($this->attendees)) return collect();
        return User::whereIn('id', $this->attendees)->get();
    }

    public function getFormattedDateAttribute()
    {
        return $this->meeting_date->format('d/m/Y à H:i');
    }

    public function getIsTodayAttribute()
    {
        return $this->meeting_date->isToday();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->meeting_date->isFuture() && $this->status === self::STATUS_SCHEDULED;
    }

    // Méthodes
    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function start()
    {
        $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    public function complete()
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }

    public function addAttendee($userId)
    {
        $attendees = $this->attendees ?? [];
        if (!in_array($userId, $attendees)) {
            $attendees[] = $userId;
            $this->update(['attendees' => $attendees]);
        }
    }

    public function removeAttendee($userId)
    {
        $attendees = $this->attendees ?? [];
        if (($key = array_search($userId, $attendees)) !== false) {
            unset($attendees[$key]);
            $this->update(['attendees' => array_values($attendees)]);
        }
    }
}
