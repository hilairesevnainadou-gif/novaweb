<?php
// app/Mail/NewBlogPostMail.php

namespace App\Mail;

use App\Models\BlogPost;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBlogPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public BlogPost $post;
    public Newsletter $subscriber;
    public string $excerpt;  // ← on prépare l'excerpt ici, pas dans la vue

    public function __construct(BlogPost $post, Newsletter $subscriber)
    {
        $this->post = $post;
        $this->subscriber = $subscriber;

        // Calcul de l'excerpt côté PHP, pas dans Blade
        $this->excerpt = $post->excerpt
            ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvel article : ' . $this->post->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Nom du fichier : new-post.blade.php → notation avec point et tiret impossible
            // On renomme la vue en new_blog_post.blade.php (underscore)
            view: 'emails.newsletter.new_blog_post',
        );
    }
}
