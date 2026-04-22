<?php
// app/Models/BackupLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'filename',
        'type',
        'status',
        'size',
        'error_message',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
