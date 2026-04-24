<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_discussion_id',
        'user_id',
        'content',
    ];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(ProjectDiscussion::class, 'project_discussion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
