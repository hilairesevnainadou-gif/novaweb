<?php
// app/Models/TimeEntry.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'project_id',
        'date',
        'hours',
        'description',
        'is_billable'
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'is_billable' => 'boolean'
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeForWeek($query, $date = null)
    {
        $date = $date ? now()->parse($date) : now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();
        return $query->whereBetween('date', [$start, $end]);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeNonBillable($query)
    {
        return $query->where('is_billable', false);
    }

    // Accesseurs
    public function getDateFormattedAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function getHoursFormattedAttribute()
    {
        $hours = floor($this->hours);
        $minutes = round(($this->hours - $hours) * 60);
        if ($minutes > 0) {
            return "{$hours}h {$minutes}min";
        }
        return "{$hours}h";
    }

    public function getIsTodayAttribute()
    {
        return $this->date->isToday();
    }

    public function getIsThisWeekAttribute()
    {
        return $this->date->isCurrentWeek();
    }
}
