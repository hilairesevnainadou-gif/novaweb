<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'filename',
        'original_name',
        'path',
        'size',
        'mime_type',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    public function getFileIconAttribute(): string
    {
        $mime = $this->mime_type;
        if (str_starts_with($mime, 'image/')) {
            return 'fas fa-file-image';
        }
        if (str_contains($mime, 'pdf')) {
            return 'fas fa-file-pdf';
        }
        if (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'fas fa-file-word';
        }
        if (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return 'fas fa-file-excel';
        }
        if (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation')) {
            return 'fas fa-file-powerpoint';
        }
        if (str_contains($mime, 'zip') || str_contains($mime, 'rar') || str_contains($mime, 'tar')) {
            return 'fas fa-file-zipper';
        }
        if (str_starts_with($mime, 'text/')) {
            return 'fas fa-file-lines';
        }

        return 'fas fa-file';
    }

    public function getFileIconColorAttribute(): string
    {
        $mime = $this->mime_type;
        if (str_starts_with($mime, 'image/')) {
            return '#34d399';
        }
        if (str_contains($mime, 'pdf')) {
            return '#f87171';
        }
        if (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return '#60a5fa';
        }
        if (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return '#34d399';
        }
        if (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation')) {
            return '#fb923c';
        }

        return '#94a3b8';
    }
}
