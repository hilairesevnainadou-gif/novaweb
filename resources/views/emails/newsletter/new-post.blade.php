{{-- resources/views/emails/newsletter/new-post.blade.php --}}
@component('mail::message')
# Nouvel article publié !

Bonjour {{ $subscriber->email }},

Nous sommes ravis de vous annoncer la publication d'un nouvel article sur notre blog.

## {{ $post->title }}

**Catégorie :** {{ $post->category }}
@if($post->reading_time)
**Temps de lecture :** {{ $post->reading_time }}
@endif

{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}

@component('mail::button', ['url' => route('blog.show', $post->slug)])
Lire l'article
@endcomponent

@component('mail::subcopy')
Si vous ne souhaitez plus recevoir nos notifications, vous pouvez vous désabonner en cliquant [ici]({{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}).
@endcomponent

Merci de nous suivre !

{{ config('app.name') }}
@endcomponent
