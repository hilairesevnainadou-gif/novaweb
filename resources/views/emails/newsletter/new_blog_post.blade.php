{{-- resources/views/emails/new-post-notification.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel article - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        .email-wrapper {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #1e293b;
            color: white;
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.8;
            font-size: 14px;
        }
        .content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .article-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 20px 0 10px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
        }
        .post-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .post-info p {
            margin: 8px 0;
        }
        .post-info strong {
            color: #475569;
        }
        .excerpt {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            border-radius: 0 8px 8px 0;
            padding: 20px;
            margin: 20px 0;
            color: #475569;
            font-style: italic;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin: 16px 0;
        }
        .button:hover {
            background: #1d4ed8;
        }
        .unsubscribe-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 24px 0 0 0;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .unsubscribe-box a {
            color: #2563eb;
            text-decoration: none;
        }
        .unsubscribe-box a:hover {
            text-decoration: underline;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
        .text-center {
            text-align: center;
        }
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <h1>NOUVEL ARTICLE</h1>
                <p>{{ config('app.name') }}</p>
            </div>

            <div class="content">
                <div class="greeting">
                    Bonjour,
                </div>

                <p>Nous sommes ravis de vous annoncer la publication d'un nouvel article sur notre blog.</p>

                <hr>

                <div class="article-title">
                    {{ $post->title }}
                </div>

                @if($post->category || $post->reading_time)
                <div class="post-info">
                    @if($post->category)
                    <p><strong>Categorie :</strong> {{ $post->category }}</p>
                    @endif
                    @if($post->reading_time)
                    <p><strong>Temps de lecture :</strong> {{ $post->reading_time }}</p>
                    @endif
                </div>
                @endif

                <div class="excerpt">
                    {{ $excerpt }}
                </div>

                <div class="text-center">
                    <a href="{{ route('blog.show', $post->slug) }}" class="button">Lire l'article</a>
                </div>

                <hr>

                <div class="unsubscribe-box">
                    <p>Vous recevez cet email car vous etes abonne(e) a la newsletter de <strong>{{ config('app.name') }}</strong>.</p>
                    <p>Si vous ne souhaitez plus recevoir nos notifications, cliquez <a href="{{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}">ici pour vous desabonner</a>.</p>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits reserves.</p>
                <p>Cet email a ete envoye automatiquement, merci de ne pas y repondre.</p>
            </div>
        </div>
    </div>
</body>
</html>
