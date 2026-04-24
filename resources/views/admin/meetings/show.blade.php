{{-- resources/views/admin/meetings/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $meeting->title . ' - NovaTech Admin')
@section('page-title', 'Détail de la réunion')

@push('styles')
<style>
    .breadcrumb { display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; color:var(--text-tertiary); margin-bottom:1.25rem; flex-wrap:wrap; }
    .breadcrumb a { color:var(--text-tertiary); text-decoration:none; } .breadcrumb a:hover { color:var(--brand-primary); }

    .meeting-hero { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; padding:1.5rem; margin-bottom:1.5rem; }
    .meeting-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .meeting-type-icon { width:52px; height:52px; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; font-size:1.375rem; flex-shrink:0; background:rgba(59,130,246,0.1); color:var(--brand-primary); }
    .meeting-title { font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 0.5rem; }
    .meeting-meta { display:flex; flex-wrap:wrap; gap:0.5rem; }

    .badge { display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; font-size:0.75rem; font-weight:500; border-radius:9999px; }
    .badge-active { background:rgba(16,185,129,0.1); color:#10b981; }
    .badge-info { background:rgba(59,130,246,0.1); color:#3b82f6; }
    .badge-warning { background:rgba(245,158,11,0.1); color:#f59e0b; }
    .badge-secondary { background:rgba(107,114,128,0.1); color:#9ca3af; }
    .badge-inactive { background:rgba(239,68,68,0.1); color:#ef4444; }
    .badge-completed { background:rgba(139,92,246,0.1); color:#8b5cf6; }

    .grid-layout { display:grid; grid-template-columns:2fr 1fr; gap:1rem; }
    @media (max-width:768px) { .grid-layout { grid-template-columns:1fr; } }

    .card { background:var(--bg-secondary); border:1px solid var(--border-light); border-radius:0.75rem; margin-bottom:1rem; }
    .card-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; }
    .card-header h3 { font-size:0.9375rem; font-weight:600; margin:0; color:var(--text-primary); }
    .card-body { padding:1.5rem; }

    .info-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
    .info-label { font-size:0.6875rem; text-transform:uppercase; font-weight:600; color:var(--text-tertiary); margin-bottom:0.25rem; }
    .info-value { font-size:0.875rem; color:var(--text-primary); font-weight:500; }

    .participant-chip { display:inline-flex; align-items:center; gap:0.5rem; padding:0.375rem 0.75rem; background:var(--bg-tertiary); border:1px solid var(--border-light); border-radius:9999px; font-size:0.8125rem; color:var(--text-secondary); }
    .participant-avatar { width:24px; height:24px; border-radius:50%; background:linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.65rem; font-weight:700; }

    .btn-primary { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--brand-primary); color:white; border-radius:0.5rem; font-size:0.875rem; font-weight:500; border:none; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .btn-primary:hover { background:var(--brand-primary-hover); }
    .btn-secondary-sm { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:var(--bg-tertiary); color:var(--text-secondary); border:1px solid var(--border-light); border-radius:0.5rem; font-size:0.8125rem; text-decoration:none; transition:all 0.2s; }
    .btn-secondary-sm:hover { background:var(--bg-hover); color:var(--brand-primary); border-color:var(--brand-primary); }

    .minutes-content { background:var(--bg-tertiary); border-radius:0.5rem; padding:1.25rem; font-size:0.875rem; color:var(--text-secondary); line-height:1.8; white-space:pre-wrap; }
    .agenda-content { background:var(--bg-tertiary); border-radius:0.5rem; padding:1.25rem; font-size:0.875rem; color:var(--text-secondary); line-height:1.8; white-space:pre-wrap; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.projects.index') }}">Projets</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.meetings.index', $project) }}">Réunions</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ Str::limit($meeting->title, 40) }}</span>
</div>

@if(session('success'))
<div style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:0.5rem; padding:0.875rem 1.25rem; margin-bottom:1rem; color:#10b981; display:flex; align-items:center; gap:0.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Hero --}}
<div class="meeting-hero">
    <div class="meeting-hero-top">
        <div style="display:flex; gap:1rem; align-items:flex-start;">
            <div class="meeting-type-icon">
                @php
                    $typeIcons = ['kickoff'=>'fa-rocket','weekly'=>'fa-repeat','review'=>'fa-magnifying-glass','demo'=>'fa-display','retrospective'=>'fa-rotate-left','emergency'=>'fa-circle-exclamation','other'=>'fa-video'];
                @endphp
                <i class="fas {{ $typeIcons[$meeting->type] ?? 'fa-video' }}"></i>
            </div>
            <div>
                <h1 class="meeting-title">{{ $meeting->title }}</h1>
                <div class="meeting-meta">
                    <span class="badge {{ $meeting->status_badge_class }}">{{ $meeting->status_label }}</span>
                    <span class="badge badge-secondary">{{ $meeting->type_label }}</span>
                    <span class="badge badge-info">{{ $meeting->mode_label }}</span>
                </div>
                @if($meeting->description)
                    <p style="margin:0.75rem 0 0; color:var(--text-secondary); font-size:0.875rem; line-height:1.6;">{{ $meeting->description }}</p>
                @endif
            </div>
        </div>
        <div style="display:flex; gap:0.625rem; flex-wrap:wrap;">
            @if($meeting->meeting_url && in_array($meeting->status, ['scheduled','in_progress']))
            <a href="{{ $meeting->meeting_url }}" target="_blank" class="btn-primary">
                <i class="fas fa-video"></i> Rejoindre
            </a>
            @endif
            @can('meetings.edit')
            <a href="{{ route('admin.projects.meetings.edit', [$project, $meeting]) }}" class="btn-secondary-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endcan
        </div>
    </div>
</div>

<div class="grid-layout">
    <div>
        {{-- Ordre du jour --}}
        @if($meeting->agenda)
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Ordre du jour</h3>
            </div>
            <div class="card-body">
                <div class="agenda-content">{{ $meeting->agenda }}</div>
            </div>
        </div>
        @endif

        {{-- Compte-rendu --}}
        @if($meeting->minutes || $meeting->status === 'completed')
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-file-alt" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Compte-rendu</h3>
                @can('meetings.edit')
                @if(!$meeting->minutes)
                <a href="{{ route('admin.projects.meetings.edit', [$project, $meeting]) }}" style="font-size:0.75rem; color:var(--brand-primary); text-decoration:none;">+ Rédiger</a>
                @endif
                @endcan
            </div>
            <div class="card-body">
                @if($meeting->minutes)
                    <div class="minutes-content">{{ $meeting->minutes }}</div>
                @else
                    <p style="text-align:center; color:var(--text-tertiary); padding:1rem 0;">Aucun compte-rendu rédigé</p>
                @endif
            </div>
        </div>
        @endif

        {{-- Actions --}}
        @if($meeting->action_items)
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-tasks" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Actions à prendre</h3>
            </div>
            <div class="card-body">
                <div class="minutes-content">{{ $meeting->action_items }}</div>
            </div>
        </div>
        @endif
    </div>

    <div>
        {{-- Informations --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Informations</h3>
            </div>
            <div class="card-body">
                <div style="display:flex; flex-direction:column; gap:0.875rem;">
                    <div>
                        <div class="info-label">Date</div>
                        <div class="info-value">{{ $meeting->scheduled_at->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="info-label">Heure</div>
                        <div class="info-value">{{ $meeting->scheduled_at->format('H:i') }}</div>
                    </div>
                    <div>
                        <div class="info-label">Durée</div>
                        <div class="info-value">{{ $meeting->duration_formatted }}</div>
                    </div>
                    <div>
                        <div class="info-label">Organisateur</div>
                        <div class="info-value">{{ $meeting->organizer?->name ?? '-' }}</div>
                    </div>
                    @if($meeting->location)
                    <div>
                        <div class="info-label">Lieu</div>
                        <div class="info-value">{{ $meeting->location }}</div>
                    </div>
                    @endif
                    @if($meeting->meeting_url)
                    <div>
                        <div class="info-label">Lien</div>
                        <div class="info-value">
                            <a href="{{ $meeting->meeting_url }}" target="_blank" style="color:var(--brand-primary); font-size:0.8125rem; word-break:break-all;">{{ Str::limit($meeting->meeting_url, 40) }}</a>
                        </div>
                    </div>
                    @endif
                    <div>
                        <div class="info-label">Projet</div>
                        <div class="info-value">
                            <a href="{{ route('admin.projects.show', $project) }}" style="color:var(--brand-primary); text-decoration:none;">{{ $project->name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Participants --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-users" style="color:var(--brand-primary); margin-right:0.5rem;"></i> Participants ({{ $meeting->participants->count() }})</h3>
            </div>
            <div class="card-body">
                @forelse($meeting->participants as $participant)
                <div class="participant-chip" style="display:flex; margin-bottom:0.5rem; width:100%;">
                    <div class="participant-avatar">{{ strtoupper(substr($participant->name, 0, 2)) }}</div>
                    {{ $participant->name }}
                    @if($participant->pivot->status !== 'invited')
                        <span style="margin-left:auto; font-size:0.7rem; color:var(--text-tertiary);">{{ ucfirst($participant->pivot->status) }}</span>
                    @endif
                </div>
                @empty
                <p style="color:var(--text-tertiary); font-size:0.875rem;">Aucun participant</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
