<?php
// app/Mail/UserPasswordResetInvitation.php

namespace App\Mail;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserPasswordResetInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public string $invitationUrl;

    public function __construct(
        public User $user,
        public UserInvitation $invitation
    ) {
        $this->invitationUrl = route('invitation.show', $invitation->token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de votre mot de passe - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-invitation',
        );
    }
}
