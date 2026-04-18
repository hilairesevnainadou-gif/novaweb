<?php
// app/Mail/NewBlogPostMail.php

namespace App\Mail;

use App\Models\BlogPost;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBlogPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(BlogPost $post, Newsletter $subscriber)
    {
        $this->post = $post;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Nouvel article sur notre blog: ' . $this->post->title)
                    ->markdown('emails.newsletter.new-post');
    }
}
