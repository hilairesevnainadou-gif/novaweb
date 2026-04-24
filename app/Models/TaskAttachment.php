<?php
// app/Models/TaskAttachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'original_name',
        'path',
        'size',
        'mime_type'
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

    // Accesseurs
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getSizeFormattedAttribute()
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getFileIconAttribute()
    {
        $mime = $this->mime_type;
        if (str_starts_with($mime, 'image/')) return 'fas fa-image';
        if (str_starts_with($mime, 'video/')) return 'fas fa-video';
        if (str_starts_with($mime, 'audio/')) return 'fas fa-music';
        if (str_contains($mime, 'pdf')) return 'fas fa-file-pdf';
        if (str_contains($mime, 'word')) return 'fas fa-file-word';
        if (str_contains($mime, 'excel')) return 'fas fa-file-excel';
        if (str_contains($mime, 'zip') || str_contains($mime, 'rar')) return 'fas fa-file-archive';
        return 'fas fa-file';
    }
}
