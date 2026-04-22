<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserInvitation extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at', 'accepted_at'];

    protected $casts = [
        'expires_at'   => 'datetime',
        'accepted_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }

    public static function generateFor(User $user): self
    {
        self::where('user_id', $user->id)
            ->whereNull('accepted_at')
            ->delete();

        return self::create([
            'user_id'    => $user->id,
            'token'      => Str::random(64),
            'expires_at' => now()->addHours(72),
        ]);
    }
}
