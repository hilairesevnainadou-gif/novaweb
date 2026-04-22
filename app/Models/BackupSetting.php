<?php
// app/Models/BackupSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'backup_email',
        'backup_frequency',
        'backup_time',
        'auto_clean_days',
        'backup_type',
        'backup_path',
        'is_active',
        'last_backup_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_backup_at' => 'datetime'
    ];
}
