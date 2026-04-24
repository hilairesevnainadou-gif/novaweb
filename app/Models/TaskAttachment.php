<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'size',
        'path',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
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

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) {
            return $bytes . ' o';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' Ko';
        }

        return round($bytes / 1048576, 1) . ' Mo';
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            str_starts_with($this->mime_type ?? '', 'image/') => 'fa-image',
            str_contains($this->mime_type ?? '', 'pdf')       => 'fa-file-pdf',
            str_contains($this->mime_type ?? '', 'word')      => 'fa-file-word',
            str_contains($this->mime_type ?? '', 'excel')     => 'fa-file-excel',
            str_contains($this->mime_type ?? '', 'zip')       => 'fa-file-zipper',
            default                                           => 'fa-file',
        };
    }
}
