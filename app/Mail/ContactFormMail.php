<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $isCopy;

    public function __construct($data, $isCopy = false)
    {
        $this->data = $data;
        $this->isCopy = $isCopy;
    }

    public function build()
    {
        $subject = $this->isCopy
            ? 'Copie de votre message à Nova Tech Bénin'
            : 'Nouveau message de contact - ' . $this->data['name'];

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($subject)
                    ->markdown('emails.contact-form')
                    ->with([
                        'data' => $this->data,
                        'isCopy' => $this->isCopy,
                    ]);
    }
}
