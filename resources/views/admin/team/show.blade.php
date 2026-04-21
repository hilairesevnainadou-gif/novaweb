{{-- resources/views/admin/team/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $teamMember->name . ' - NovaTech Admin')
@section('page-title', $teamMember->name)

@push('styles')
<style>
    /* ============================================
       TEAM MEMBER SHOW — aligned with design system
    ============================================ */

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
    }

    .breadcrumb a {
        color: var(--text-tertiary);
        transition: color var(--transition-fast);
    }

    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.6rem; }

    /* ── Action bar ── */
    .action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .action-bar-left { display: flex; gap: 0.625rem; }

    /* ── Buttons — reuse layout conventions ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all var(--transition-fast);
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--brand-primary);
        color: #fff;
    }
    .btn-primary:hover { background: var(--brand-primary-hover); }

    .btn-secondary {
        background: var(--bg-tertiary);
        color: var(--text-primary);
        border: 1px solid var(--border-light);
    }
    .btn-secondary:hover {
        background: var(--bg-hover);
        border-color: var(--border-medium);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--brand-error);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.18);
    }

    /* ── Cards ── */
    .card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-xs);
    }

    .card-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-tertiary);
        margin-bottom: 1rem;
        padding-bottom: 0.625rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .card-title i { color: var(--brand-primary); }

    /* ── Hero header ── */
    .member-hero {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: var(--shadow-xs);
        position: relative;
        overflow: hidden;
    }

    /* subtle gradient accent top-left */
    .member-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .member-avatar {
        width: 88px;
        height: 88px;
        border-radius: var(--radius-full);
        object-fit: cover;
        border: 3px solid var(--border-medium);
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .member-avatar-placeholder {
        width: 88px;
        height: 88px;
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        flex-shrink: 0;
        border: 3px solid var(--border-medium);
        position: relative;
        z-index: 1;
    }

    .member-hero-info {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .member-hero-info h2 {
        font-size: 1.375rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: var(--text-primary);
    }

    .member-position {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--brand-primary);
        margin-bottom: 0.75rem;
    }

    .badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* ── Badges ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: var(--radius-full);
        white-space: nowrap;
    }

    .badge-active   { background: rgba(16,185,129,0.12); color: var(--brand-success); }
    .badge-inactive { background: rgba(239,68,68,0.12);  color: var(--brand-error);   }
    .badge-featured { background: rgba(245,158,11,0.12); color: var(--brand-warning); }

    /* ── Info grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {}

    .info-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        word-break: break-word;
    }

    .info-value a {
        color: var(--brand-primary);
        transition: color var(--transition-fast);
    }
    .info-value a:hover { color: var(--brand-primary-hover); }

    /* ── Bio / Quote ── */
    .bio-text {
        font-size: 0.875rem;
        line-height: 1.75;
        color: var(--text-secondary);
    }

    .quote-text {
        font-size: 0.9375rem;
        font-style: italic;
        color: var(--text-secondary);
        line-height: 1.7;
        border-left: 3px solid var(--brand-primary);
        padding-left: 1rem;
        margin: 0;
    }

    /* ── Skills ── */
    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .skill-tag {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
        transition: border-color var(--transition-fast);
    }
    .skill-tag:hover { border-color: var(--brand-primary); }

    /* ── Social links ── */
    .social-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: var(--radius-md);
        background: var(--bg-tertiary);
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        font-size: 0.8125rem;
        font-weight: 500;
        transition: all var(--transition-fast);
        text-decoration: none;
    }

    .social-link:hover {
        background: var(--bg-hover);
        color: var(--brand-primary);
        border-color: var(--brand-primary);
    }

    .social-link i { font-size: 1rem; }

    /* ── Delete form ── */
    .delete-form { display: inline; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .member-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .action-bar { flex-direction: column; align-items: stretch; }
        .action-bar-left { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.team.index') }}">Équipe</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $teamMember->name }}</span>
</nav>

{{-- Action bar --}}
<div class="action-bar">
    <div class="action-bar-left">
        <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <a href="{{ route('admin.team.edit', $teamMember) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
    </div>
    <form class="delete-form" method="POST" action="{{ route('admin.team.destroy', $teamMember) }}"
          onsubmit="return confirm('Supprimer ce membre définitivement ?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </form>
</div>

{{-- Hero --}}
<div class="member-hero">
    @if($teamMember->photo)
        <img src="{{ asset('storage/' . $teamMember->photo) }}"
             alt="{{ $teamMember->name }}"
             class="member-avatar">
    @else
        <div class="member-avatar-placeholder">
            {{ strtoupper(substr($teamMember->name, 0, 2)) }}
        </div>
    @endif

    <div class="member-hero-info">
        <h2>{{ $teamMember->name }}</h2>
        <div class="member-position">{{ $teamMember->position }}</div>
        <div class="badge-row">
            @if($teamMember->is_active)
                <span class="badge badge-active">
                    <i class="fas fa-circle" style="font-size:0.45rem;"></i> Actif
                </span>
            @else
                <span class="badge badge-inactive">
                    <i class="fas fa-circle" style="font-size:0.45rem;"></i> Inactif
                </span>
            @endif
            @if($teamMember->is_featured)
                <span class="badge badge-featured">
                    <i class="fas fa-star"></i> À la une
                </span>
            @endif
        </div>
    </div>
</div>

{{-- Personal info --}}
<div class="card">
    <div class="card-title">
        <i class="fas fa-user"></i> Informations personnelles
    </div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nom complet</div>
            <div class="info-value">{{ $teamMember->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Poste</div>
            <div class="info-value">{{ $teamMember->position }}</div>
        </div>
        @if($teamMember->email)
        <div class="info-item">
            <div class="info-label">Email</div>
            <div class="info-value">
                <a href="mailto:{{ $teamMember->email }}">{{ $teamMember->email }}</a>
            </div>
        </div>
        @endif
        <div class="info-item">
            <div class="info-label">Ordre d'affichage</div>
            <div class="info-value">{{ $teamMember->order ?? '—' }}</div>
        </div>
    </div>
</div>

{{-- Biography --}}
@if($teamMember->bio)
<div class="card">
    <div class="card-title">
        <i class="fas fa-align-left"></i> Biographie
    </div>
    <p class="bio-text">{{ $teamMember->bio }}</p>
</div>
@endif

{{-- Quote --}}
@if($teamMember->quote)
<div class="card">
    <div class="card-title">
        <i class="fas fa-quote-left"></i> Citation
    </div>
    <p class="quote-text">{{ $teamMember->quote }}</p>
</div>
@endif

{{-- Skills --}}
@if($teamMember->skills && count($teamMember->skills) > 0)
<div class="card">
    <div class="card-title">
        <i class="fas fa-code"></i> Compétences
    </div>
    <div class="skills-list">
        @foreach($teamMember->skills as $skill)
            <span class="skill-tag">{{ $skill }}</span>
        @endforeach
    </div>
</div>
@endif

{{-- Social links --}}
@if($teamMember->linkedin || $teamMember->github || $teamMember->twitter || $teamMember->facebook)
<div class="card">
    <div class="card-title">
        <i class="fas fa-share-alt"></i> Réseaux sociaux
    </div>
    <div class="social-links">
        @if($teamMember->linkedin)
        <a href="{{ $teamMember->linkedin }}" target="_blank" rel="noopener" class="social-link">
            <i class="fab fa-linkedin"></i> LinkedIn
        </a>
        @endif
        @if($teamMember->github)
        <a href="{{ $teamMember->github }}" target="_blank" rel="noopener" class="social-link">
            <i class="fab fa-github"></i> GitHub
        </a>
        @endif
        @if($teamMember->twitter)
        <a href="{{ $teamMember->twitter }}" target="_blank" rel="noopener" class="social-link">
            <i class="fab fa-twitter"></i> Twitter
        </a>
        @endif
        @if($teamMember->facebook)
        <a href="{{ $teamMember->facebook }}" target="_blank" rel="noopener" class="social-link">
            <i class="fab fa-facebook"></i> Facebook
        </a>
        @endif
    </div>
</div>
@endif

{{-- System info --}}
<div class="card">
    <div class="card-title">
        <i class="fas fa-clock"></i> Informations système
    </div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Date d'ajout</div>
            <div class="info-value">{{ $teamMember->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Dernière modification</div>
            <div class="info-value">{{ $teamMember->updated_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>
</div>

@endsection
