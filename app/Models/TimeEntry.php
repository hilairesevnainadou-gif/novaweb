<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'description',
        'date',
        'minutes',
        'is_billable',
    ];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'minutes'     => 'integer',
            'is_billable' => 'boolean',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHoursAttribute(): float
    {
        return round($this->minutes / 60, 2);
    }

    public function getDurationFormattedAttribute(): string
    {
        $h = intdiv($this->minutes, 60);
        $m = $this->minutes % 60;

        return $h > 0 ? "{$h}h" . ($m > 0 ? " {$m}min" : '') : "{$m}min";
    }
}
