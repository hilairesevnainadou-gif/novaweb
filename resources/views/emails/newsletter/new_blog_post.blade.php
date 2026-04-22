@component('mail::message')

# Nouvel article publié !

Bonjour,

Nous sommes ravis de vous annoncer la publication d'un nouvel article sur notre blog.

---

## {{ $post->title }}

@if($post->category)
**Catégorie :** {{ $post->category }}
@endif

@if($post->reading_time)
**Temps de lecture :** {{ $post->reading_time }}
@endif

{{ $excerpt }}

@component('mail::button', ['url' => route('blog.show', $post->slug), 'color' => 'primary'])
Lire l'article →
@endcomponent

---

@component('mail::subcopy')
Vous recevez cet email car vous êtes abonné(e) à la newsletter de **{{ config('app.name') }}**.
Si vous ne souhaitez plus recevoir nos notifications, cliquez
[ici pour vous désabonner]({{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}).
@endcomponent

@endcomponent
