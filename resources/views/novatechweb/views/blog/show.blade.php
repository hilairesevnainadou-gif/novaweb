@extends('novatechweb.views.layouts.app')

@section('title', ($post->title ?? 'Article') . ' - Blog ' . ($company->name ?? 'Nova Tech Bénin'))

@section('meta')
@if(isset($post))
<meta name="description" content="{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 160) }}">
@if($post->meta_keywords)
<meta name="keywords" content="{{ $post->meta_keywords }}">
@endif
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 200) }}">
@php
    $ogImage = asset('assets/images/blog-default.jpg');
    if($post->image) {
        if(filter_var($post->image, FILTER_VALIDATE_URL)) {
            $ogImage = $post->image;
        } else {
            $cleanImage = str_replace(['storage/', 'blog/'], '', $post->image);
            $ogImage = asset('storage/blog/' . $cleanImage);
        }
    }
@endphp
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:type" content="article">
@if($post->published_at)
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
@endif
<meta property="article:author" content="{{ $company->name ?? 'Nova Tech Bénin' }}">
@if($post->category)
<meta property="article:section" content="{{ $post->category }}">
@endif
@endif
@endsection

@push('styles')
<style>
/* ========== BLOG SHOW STYLES - PRÉFIXÉS ========== */

/* Hero Section */
.blog-show-hero {
    position: relative;
    min-height: 550px;
    display: flex;
    align-items: center;
    margin-top: 0;
    overflow: hidden;
}

.blog-show-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    filter: blur(2px);
    transform: scale(1.05);
}

.blog-show-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.75) 100%);
}

.blog-show-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    padding: 60px 0;
    max-width: 900px;
    margin: 0 auto;
}

.blog-show-hero-category {
    display: inline-block;
    background: var(--primary, #6366f1);
    color: white;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
}

.blog-show-hero-title {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1.3;
    margin-bottom: 25px;
}

.blog-show-hero-meta {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}

.blog-show-hero-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.blog-show-author-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent, #e11d48));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: white;
}

.blog-show-author-info {
    text-align: left;
}

.blog-show-author-name {
    display: block;
    font-weight: 600;
    font-size: 14px;
}

.blog-show-author-title {
    display: block;
    font-size: 11px;
    color: rgba(255,255,255,0.7);
}

.blog-show-hero-date,
.blog-show-hero-read {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: rgba(255,255,255,0.8);
}

/* Progress Bar */
.blog-show-progress-container {
    position: sticky;
    top: 70px;
    height: 3px;
    background: #e2e8f0;
    z-index: 100;
}

.blog-show-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--accent, #e11d48));
    width: 0%;
    transition: width 0.1s ease;
}

/* Main Content */
.blog-show-main {
    padding: 60px 0;
    background: #f8fafc;
}

/* Sidebar Left */
.blog-show-sidebar-left {
    position: sticky;
    top: 100px;
}

.blog-show-toc-card,
.blog-show-share-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.blog-show-toc-header,
.blog-show-share-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.blog-show-toc-header i,
.blog-show-share-header i {
    color: var(--primary);
    font-size: 18px;
}

.blog-show-toc-header h4,
.blog-show-share-header h4 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.blog-show-toc-body {
    max-height: 300px;
    overflow-y: auto;
}

.blog-show-toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.blog-show-toc-list li {
    margin-bottom: 10px;
}

.blog-show-toc-list a {
    display: block;
    padding: 6px 0 6px 12px;
    color: #475569;
    text-decoration: none;
    font-size: 13px;
    border-left: 2px solid #e2e8f0;
    transition: all 0.2s;
}

.blog-show-toc-list a:hover {
    color: var(--primary);
    border-left-color: var(--primary);
    padding-left: 16px;
}

.blog-show-toc-list a.active {
    color: var(--primary);
    border-left-color: var(--primary);
    font-weight: 500;
}

/* Share Buttons */
.blog-show-share-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.blog-show-share-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s;
    cursor: pointer;
    border: none;
    background: #f1f5f9;
    color: #475569;
}

.blog-show-share-btn i {
    width: 18px;
    font-size: 14px;
}

.blog-show-share-btn:hover {
    transform: translateX(3px);
}

.blog-show-share-btn.facebook:hover { background: #3b5998; color: white; }
.blog-show-share-btn.twitter:hover { background: #1da1f2; color: white; }
.blog-show-share-btn.linkedin:hover { background: #0077b5; color: white; }
.blog-show-share-btn.copy:hover { background: var(--primary); color: white; }

/* Article Content */
.blog-show-article-content {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.blog-show-article-excerpt {
    background: #f1f5f9;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 35px;
    display: flex;
    gap: 15px;
    border-left: 4px solid var(--primary);
}

.blog-show-article-excerpt i {
    font-size: 24px;
    color: var(--primary);
    opacity: 0.5;
}

.blog-show-article-excerpt p {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #475569;
    margin: 0;
    font-style: italic;
}

.blog-show-article-body {
    font-size: 1rem;
    line-height: 1.8;
    color: #334155;
}

.blog-show-article-body h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 45px 0 20px;
    padding-top: 20px;
    color: #0f172a;
}

.blog-show-article-body h3 {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 35px 0 15px;
    color: #1e293b;
}

.blog-show-article-body h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 25px 0 12px;
    color: #334155;
}

.blog-show-article-body p {
    margin-bottom: 20px;
}

.blog-show-article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 30px 0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.blog-show-article-body blockquote {
    border-left: 4px solid var(--primary);
    padding: 20px 28px;
    margin: 30px 0;
    background: #f8fafc;
    border-radius: 12px;
    font-style: italic;
    color: #475569;
    font-size: 1.05rem;
}

/* Tags */
.blog-show-article-tags {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 25px 0;
    margin-top: 35px;
    border-top: 1px solid #e2e8f0;
}

.blog-show-article-tags i {
    color: var(--primary);
    font-size: 16px;
}

.blog-show-tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.blog-show-tag {
    padding: 5px 14px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 50px;
    font-size: 12px;
    transition: all 0.2s;
}

.blog-show-tag:hover {
    background: var(--primary);
    color: white;
}

/* Author Box */
.blog-show-author-box {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-top: 30px;
    display: flex;
    gap: 25px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.blog-show-author-avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent, #e11d48));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.blog-show-author-details h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #0f172a;
}

.blog-show-author-details p {
    font-size: 0.9rem;
    line-height: 1.6;
    color: #475569;
    margin-bottom: 15px;
}

.blog-show-author-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

/* Navigation */
.blog-show-article-navigation {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 30px;
}

.blog-show-nav-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.blog-show-nav-card:not(.disabled):hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    border-color: var(--primary);
}

.blog-show-nav-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.blog-show-nav-label {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 8px;
}

.blog-show-nav-label i {
    font-size: 11px;
}

.blog-show-nav-title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
}

.blog-show-nav-next {
    text-align: right;
}

/* Sidebar Right */
.blog-show-sidebar-right {
    position: sticky;
    top: 100px;
}

.blog-show-related-card,
.blog-show-newsletter-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.blog-show-related-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.blog-show-related-header i {
    color: var(--primary);
    font-size: 18px;
}

.blog-show-related-header h4 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.blog-show-related-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.blog-show-related-item {
    display: flex;
    gap: 12px;
    text-decoration: none;
    transition: all 0.2s;
}

.blog-show-related-item:hover {
    transform: translateX(3px);
}

.blog-show-related-img {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.blog-show-related-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-show-related-info h5 {
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0 0 5px 0;
    color: #1e293b;
    line-height: 1.4;
}

.blog-show-related-info span {
    font-size: 0.7rem;
    color: #64748b;
}

/* Newsletter Card */
.blog-show-newsletter-card {
    text-align: center;
}

.blog-show-newsletter-icon {
    width: 50px;
    height: 50px;
    background: rgba(99,102,241,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.blog-show-newsletter-icon i {
    font-size: 24px;
    color: var(--primary);
}

.blog-show-newsletter-card h4 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #0f172a;
}

.blog-show-newsletter-card p {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 15px;
}

.blog-show-newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.blog-show-newsletter-form input {
    padding: 12px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    outline: none;
    transition: all 0.2s;
}

.blog-show-newsletter-form input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

.blog-show-newsletter-form button {
    padding: 12px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.blog-show-newsletter-form button:hover {
    background: #4f46e5;
    transform: translateY(-2px);
}

.blog-show-newsletter-form button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Message de notification */
.newsletter-message {
    margin-top: 10px;
    padding: 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    display: none;
}

.newsletter-message.success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.newsletter-message.error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* CTA Section - Boutons corrigés */
.blog-show-cta-section {
    padding: 70px 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

.blog-show-cta-wrapper {
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}

.blog-show-cta-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.blog-show-cta-icon i {
    font-size: 30px;
    color: white;
}

.blog-show-cta-wrapper h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
    margin-bottom: 15px;
}

.blog-show-cta-wrapper p {
    color: rgba(255,255,255,0.7);
    margin-bottom: 30px;
}

.blog-show-cta-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

/* Bouton Primaire */
.blog-show-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--primary, #6366f1), var(--primary-dark, #4f46e5));
    color: white !important;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.blog-show-btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark, #4f46e5), var(--primary, #6366f1));
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    color: white !important;
    text-decoration: none;
}

.blog-show-btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
}

/* Bouton Secondaire */
.blog-show-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    color: white !important;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.4);
    cursor: pointer;
}

.blog-show-btn-secondary:hover {
    border-color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-3px);
    color: white !important;
    text-decoration: none;
}

.blog-show-btn-secondary:active {
    transform: translateY(0);
}

/* États focus pour accessibilité */
.blog-show-btn-primary:focus,
.blog-show-btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5);
}

.blog-show-btn-secondary:focus {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 576px) {
    .blog-show-btn-primary,
    .blog-show-btn-secondary {
        padding: 10px 20px;
        font-size: 0.85rem;
    }

    .blog-show-cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .blog-show-btn-primary,
    .blog-show-btn-secondary {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
}

/* Responsive */
@media (max-width: 992px) {
    .blog-show-hero-title { font-size: 2rem; }
    .blog-show-article-content { padding: 30px; }
    .blog-show-author-box { flex-direction: column; text-align: center; }
    .blog-show-author-avatar-lg { margin: 0 auto; }
    .blog-show-article-navigation { grid-template-columns: 1fr; }
    .blog-show-nav-card { text-align: center; }
    .blog-show-nav-next { text-align: center; }
}

@media (max-width: 768px) {
    .blog-show-hero { min-height: 450px; }
    .blog-show-hero-title { font-size: 1.6rem; }
    .blog-show-hero-meta { flex-direction: column; gap: 12px; }
    .blog-show-hero-author { justify-content: center; }
    .blog-show-article-content { padding: 20px; }
    .blog-show-article-excerpt { flex-direction: column; text-align: center; }
    .blog-show-article-excerpt i { margin: 0 auto; }
    .blog-show-cta-wrapper h3 { font-size: 1.4rem; }
    .blog-show-sidebar-left,
    .blog-show-sidebar-right { position: static; }
}

@media (max-width: 576px) {
    .blog-show-hero-title { font-size: 1.3rem; }
    .blog-show-article-excerpt p { font-size: 0.95rem; }
    .blog-show-article-body h2 { font-size: 1.4rem; }
    .blog-show-article-body h3 { font-size: 1.2rem; }
    .blog-show-share-buttons { flex-direction: row; flex-wrap: wrap; }
    .blog-show-share-btn { flex: 1; justify-content: center; }
}
</style>
@endpush

@section('content')

<!-- ========== HERO SECTION ========== -->
@php
    $heroImage = asset('assets/images/blog-default.jpg');
    if(isset($post) && $post->image) {
        if(filter_var($post->image, FILTER_VALIDATE_URL)) {
            $heroImage = $post->image;
        } else {
            $cleanImage = str_replace(['storage/', 'blog/'], '', $post->image);
            $heroImage = asset('storage/blog/' . $cleanImage);
        }
    }
@endphp

<section class="blog-show-hero">
    <div class="blog-show-hero-bg" style="background-image: url('{{ $heroImage }}');"></div>
    <div class="blog-show-hero-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="blog-show-hero-content">
                    <span class="blog-show-hero-category">{{ $post->category ?? 'Article' }}</span>
                    <h1 class="blog-show-hero-title">{{ $post->title }}</h1>
                    <div class="blog-show-hero-meta">
                        <div class="blog-show-hero-author">
                            <div class="blog-show-author-avatar">{{ substr($company->name ?? 'NT', 0, 1) }}</div>
                            <div class="blog-show-author-info">
                                <span class="blog-show-author-name">{{ $company->name ?? 'Nova Tech Bénin' }}</span>
                                <span class="blog-show-author-title">Expert Web</span>
                            </div>
                        </div>
                        <div class="blog-show-hero-date">
                            <i class="fa fa-calendar"></i>
                            <span>{{ $post->published_at ? $post->published_at->format('d F Y') : ($post->created_at->format('d F Y') ?? 'Date non disponible') }}</span>
                        </div>
                        <div class="blog-show-hero-read">
                            <i class="fa fa-clock-o"></i>
                            <span>{{ $post->reading_time ?? (ceil(str_word_count(strip_tags($post->content)) / 200) . ' min') }} de lecture</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== PROGRESS BAR ========== -->
<div class="blog-show-progress-container">
    <div class="blog-show-progress-bar" id="blogShowProgressBar"></div>
</div>

<!-- ========== MAIN CONTENT ========== -->
<section class="blog-show-main">
    <div class="container">
        <div class="row g-5">
            <!-- Sidebar Left - Table of Contents -->
            <div class="col-lg-3 order-lg-1 order-2">
                <aside class="blog-show-sidebar-left">
                    <div class="blog-show-toc-card" id="blogShowTocCard">
                        <div class="blog-show-toc-header">
                            <i class="fa fa-list"></i>
                            <h4>Sommaire</h4>
                        </div>
                        <div class="blog-show-toc-body" id="blogShowTableOfContents">
                            <p class="text-muted small">Chargement...</p>
                        </div>
                    </div>

                    <div class="blog-show-share-card">
                        <div class="blog-show-share-header">
                            <i class="fa fa-share-alt"></i>
                            <h4>Partager</h4>
                        </div>
                        <div class="blog-show-share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="blog-show-share-btn facebook">
                                <i class="fa fa-facebook"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="blog-show-share-btn twitter">
                                <i class="fa fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="blog-show-share-btn linkedin">
                                <i class="fa fa-linkedin"></i>
                                <span>LinkedIn</span>
                            </a>
                            <button onclick="blogShowCopyPageUrl()" class="blog-show-share-btn copy">
                                <i class="fa fa-link"></i>
                                <span>Copier</span>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Main Article Content -->
            <div class="col-lg-6 order-lg-2 order-1">
                <article class="blog-show-article-content">
                    @if($post->excerpt)
                    <div class="blog-show-article-excerpt">
                        <i class="fa fa-quote-left"></i>
                        <p>{{ $post->excerpt }}</p>
                    </div>
                    @endif

                    <div class="blog-show-article-body" id="blogShowArticleBody">
                        {!! $post->content !!}
                    </div>

                    @if($post->meta_keywords)
                    <div class="blog-show-article-tags">
                        <i class="fa fa-tags"></i>
                        <span>Tags :</span>
                        <div class="blog-show-tags-list">
                            @foreach(explode(',', $post->meta_keywords) as $tag)
                                @if(trim($tag))
                                <span class="blog-show-tag">#{{ trim($tag) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </article>

                <!-- Author Box -->
                <div class="blog-show-author-box">
                    <div class="blog-show-author-avatar-lg">{{ substr($company->name ?? 'NT', 0, 1) }}</div>
                    <div class="blog-show-author-details">
                        <h4>{{ $company->name ?? 'Nova Tech Bénin' }}</h4>
                        <p>{{ $company->description ?? 'Agence web spécialisée dans la création de sites internet, applications web et solutions digitales sur mesure.' }}</p>
                        <a href="{{ route('blog.index') }}" class="blog-show-author-link">
                            <i class="fa fa-newspaper-o"></i> Voir tous les articles
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="blog-show-article-navigation">
                    @if($prevPost)
                    <a href="{{ route('blog.show', $prevPost->slug) }}" class="blog-show-nav-card blog-show-nav-prev">
                        <span class="blog-show-nav-label"><i class="fa fa-arrow-left"></i> Article précédent</span>
                        <span class="blog-show-nav-title">{{ Str::limit($prevPost->title, 60) }}</span>
                    </a>
                    @else
                    <div class="blog-show-nav-card disabled">
                        <span class="blog-show-nav-label"><i class="fa fa-arrow-left"></i> Article précédent</span>
                        <span class="blog-show-nav-title">Aucun article</span>
                    </div>
                    @endif

                    @if($nextPost)
                    <a href="{{ route('blog.show', $nextPost->slug) }}" class="blog-show-nav-card blog-show-nav-next">
                        <span class="blog-show-nav-label">Article suivant <i class="fa fa-arrow-right"></i></span>
                        <span class="blog-show-nav-title">{{ Str::limit($nextPost->title, 60) }}</span>
                    </a>
                    @else
                    <div class="blog-show-nav-card disabled">
                        <span class="blog-show-nav-label">Article suivant <i class="fa fa-arrow-right"></i></span>
                        <span class="blog-show-nav-title">Aucun article</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Right - Related Posts -->
            <div class="col-lg-3 order-lg-3 order-3">
                <aside class="blog-show-sidebar-right">
                    @if($relatedPosts && $relatedPosts->count() > 0)
                    <div class="blog-show-related-card">
                        <div class="blog-show-related-header">
                            <i class="fa fa-thumbs-up"></i>
                            <h4>Vous aimerez aussi</h4>
                        </div>
                        <div class="blog-show-related-list">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}" class="blog-show-related-item">
                                @php
                                    $relatedImage = asset('assets/images/blog-default.jpg');
                                    if($related->image) {
                                        if(filter_var($related->image, FILTER_VALIDATE_URL)) {
                                            $relatedImage = $related->image;
                                        } else {
                                            $cleanImage = str_replace(['storage/', 'blog/'], '', $related->image);
                                            $relatedImage = asset('storage/blog/' . $cleanImage);
                                        }
                                    }
                                @endphp
                                <div class="blog-show-related-img">
                                    <img src="{{ $relatedImage }}" alt="{{ $related->title }}">
                                </div>
                                <div class="blog-show-related-info">
                                    <h5>{{ Str::limit($related->title, 50) }}</h5>
                                    <span><i class="fa fa-calendar"></i> {{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="blog-show-newsletter-card">
                        <div class="blog-show-newsletter-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h4>Newsletter</h4>
                        <p>Recevez nos derniers articles directement dans votre boîte mail.</p>
                        <form class="blog-show-newsletter-form" id="blogShowNewsletterForm" method="POST">
                            @csrf
                            <input type="email" id="newsletterEmail" name="email" placeholder="Votre email" required>
                            <div id="newsletterMessage" class="newsletter-message"></div>
                            <button type="submit" id="newsletterSubmitBtn">S'abonner</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<!-- ========== CTA SECTION ========== -->
<section class="blog-show-cta-section">
    <div class="container">
        <div class="blog-show-cta-wrapper">
            <div class="blog-show-cta-icon">
                <i class="fa fa-comments"></i>
            </div>
            <h3>Vous avez un projet web ?</h3>
            <p>Parlons de votre idée et créons ensemble quelque chose de formidable.</p>
            <div class="blog-show-cta-buttons">
                <a href="{{ route('home') }}#contact" class="blog-show-btn-primary">
                    Démarrer un projet <i class="fa fa-arrow-right"></i>
                </a>
                <a href="{{ route('blog.index') }}" class="blog-show-btn-secondary">
                    <i class="fa fa-list"></i> Tous les articles
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Copier l'URL
function blogShowCopyPageUrl() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Lien copié dans le presse-papier !');
    }).catch(() => {
        prompt('Copiez le lien manuellement :', window.location.href);
    });
}

// Barre de progression
window.addEventListener('scroll', function() {
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const progress = (scrollTop / scrollHeight) * 100;
    const progressBar = document.getElementById('blogShowProgressBar');
    if (progressBar) progressBar.style.width = progress + '%';
});

// Génération automatique du sommaire
document.addEventListener('DOMContentLoaded', function() {
    const articleBody = document.getElementById('blogShowArticleBody');
    const tocContainer = document.getElementById('blogShowTableOfContents');

    if (articleBody && tocContainer) {
        const headings = articleBody.querySelectorAll('h2, h3, h4');

        if (headings.length > 0) {
            let tocList = '<ul class="blog-show-toc-list">';

            headings.forEach((heading, index) => {
                const tagName = heading.tagName.toLowerCase();
                const title = heading.textContent;
                const id = 'blog-heading-' + index;

                heading.id = id;

                tocList += `<li class="${tagName}">
                    <a href="#${id}" data-target="${id}">${title}</a>
                </li>`;
            });

            tocList += '</ul>';
            tocContainer.innerHTML = tocList;

            // Smooth scroll
            document.querySelectorAll('.blog-show-toc-list a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.dataset.target;
                    const target = document.getElementById(targetId);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            // Highlight active section
            const tocLinks = document.querySelectorAll('.blog-show-toc-list a');

            window.addEventListener('scroll', function() {
                let current = '';
                headings.forEach(heading => {
                    const sectionTop = heading.offsetTop - 150;
                    const sectionBottom = sectionTop + heading.offsetHeight;
                    if (window.scrollY >= sectionTop && window.scrollY < sectionBottom) {
                        current = heading.id;
                    }
                });

                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.dataset.target === current) {
                        link.classList.add('active');
                    }
                });
            });
        } else {
            tocContainer.innerHTML = '<p class="text-muted small mb-0">Aucun sommaire disponible</p>';
        }
    }
});

// Liens externes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.blog-show-article-body a').forEach(function(link) {
        if (link.hostname && link.hostname !== window.location.hostname) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });
});

// Newsletter avec sauvegarde en base de données
document.getElementById('blogShowNewsletterForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const emailInput = document.getElementById('newsletterEmail');
    const email = emailInput.value.trim();
    const submitBtn = document.getElementById('newsletterSubmitBtn');
    const messageDiv = document.getElementById('newsletterMessage');

    // Validation simple
    if (!email || !email.includes('@') || !email.includes('.')) {
        messageDiv.textContent = 'Veuillez entrer une adresse email valide.';
        messageDiv.className = 'newsletter-message error';
        messageDiv.style.display = 'block';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
        return;
    }

    // Désactiver le bouton pendant l'envoi
    submitBtn.disabled = true;
    submitBtn.textContent = 'Abonnement en cours...';
    messageDiv.style.display = 'none';

    try {
        // Récupérer le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                          document.querySelector('input[name="_token"]')?.value;

        // Envoyer la requête AJAX
        const response = await fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        if (data.success) {
            // Succès
            messageDiv.textContent = data.message || 'Merci pour votre abonnement !';
            messageDiv.className = 'newsletter-message success';
            messageDiv.style.display = 'block';
            emailInput.value = '';

            // Réinitialiser après 3 secondes
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        } else {
            // Erreur
            messageDiv.textContent = data.message || 'Une erreur est survenue. Veuillez réessayer.';
            messageDiv.className = 'newsletter-message error';
            messageDiv.style.display = 'block';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.textContent = 'Erreur de connexion. Veuillez réessayer plus tard.';
        messageDiv.className = 'newsletter-message error';
        messageDiv.style.display = 'block';

        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    } finally {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitBtn.textContent = 'S\'abonner';
    }
});
</script>
@endpush
