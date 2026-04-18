<?php
// app/Models/TicketMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMessage extends Model
{
    use HasFactory;

    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_admin',
        'attachment'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'attachment' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec le ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accesseur pour le message formaté (avec nl2br)
     */
    public function getFormattedMessageAttribute()
    {
        return nl2br(e($this->message));
    }

    /**
     * Accesseur pour la date formatée
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    /**
     * Accesseur pour la date courte
     */
    public function getCreatedAtShortAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accesseur pour savoir si le message est de l'admin
     */
    public function getIsAdminMessageAttribute()
    {
        return $this->is_admin || ($this->user && $this->user->hasRole(['super-admin', 'admin', 'support']));
    }

    /**
     * Accesseur pour la classe CSS du message
     */
    public function getMessageClassAttribute()
    {
        if ($this->is_admin || $this->isAdminMessage) {
            return 'admin-message';
        }
        return 'user-message';
    }

    /**
     * Accesseur pour l'avatar de l'utilisateur
     */
    public function getUserAvatarAttribute()
    {
        if ($this->user) {
            return $this->user->initials();
        }
        return '?';
    }

    /**
     * Accesseur pour le nom de l'utilisateur
     */
    public function getUserNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return 'Utilisateur inconnu';
    }

    /**
     * Vérifier si le message a des pièces jointes
     */
    public function hasAttachments()
    {
        return !empty($this->attachment) && count($this->attachment) > 0;
    }
}
