{{-- resources/views/admin/meetings/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $meeting->title . ' - NovaTech Admin')
@section('page-title', $meeting->title)

@push('styles')
<style>
    .meeting-details {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-tertiary);
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .attendees-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .attendee-badge {
        background: var(--bg-tertiary);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
    }

    .minutes-section {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-sm {
        background: var(--brand-primary);
        color: white;
    }

    .btn-secondary-sm {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 9999px;
    }
</style>
@endpush

@section('content')

<div class="action-buttons">
    <a href="{{ route('admin.projects.meetings.index', $meeting->project) }}" class="btn-sm btn-secondary-sm">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    @if(auth()->user()->can('meetings.edit') && $meeting->status !== 'completed')
    <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn-sm btn-primary-sm">
        <i class="fas fa-edit"></i> Modifier
    </a>
    @endif
</div>

<div class="meeting-details">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">{{ $meeting->title }}</h1>
            <p style="color: var(--text-secondary); margin-top: 0.25rem;">
                Projet: {{ $meeting->project->name }}
            </p>
        </div>
        <span class="badge badge-{{ $meeting->status }}">
            {{ $meeting->status_label }}
        </span>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-icon"><i class="far fa-calendar-alt"></i></div>
            <div>
                <div class="info-label">Date et heure</div>
                <div class="info-value">{{ $meeting->meeting_date->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon"><i class="far fa-clock"></i></div>
            <div>
                <div class="info-label">Durée</div>
                <div class="info-value">{{ $meeting->formatted_duration }}</div>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon"><i class="fas fa-user"></i></div>
            <div>
                <div class="info-label">Organisée par</div>
                <div class="info-value">{{ $meeting->organizer->name }}</div>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="info-label">Participants</div>
                <div class="attendees-list">
                    @forelse($meeting->attendees_list as $attendee)
                        <span class="attendee-badge">{{ $attendee->name }}</span>
                    @empty
                        <span class="attendee-badge">Aucun participant</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if($meeting->location)
    <div class="info-item" style="margin-top: 1rem;">
        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div>
            <div class="info-label">Lieu</div>
            <div class="info-value">{{ $meeting->location }}</div>
        </div>
    </div>
    @endif

    @if($meeting->meeting_link)
    <div class="info-item" style="margin-top: 1rem;">
        <div class="info-icon"><i class="fas fa-video"></i></div>
        <div>
            <div class="info-label">Lien de réunion</div>
            <div class="info-value">
                <a href="{{ $meeting->meeting_link }}" target="_blank" style="color: var(--brand-primary);">
                    {{ $meeting->meeting_link }}
                </a>
            </div>
        </div>
    </div>
    @endif

    @if($meeting->description)
    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
        <div class="info-label">Description</div>
        <p style="margin-top: 0.5rem;">{{ $meeting->description }}</p>
    </div>
    @endif
</div>

@if($meeting->minutes)
<div class="minutes-section">
    <h3 style="margin-bottom: 1rem;">Compte-rendu de la réunion</h3>

    <div style="margin-bottom: 1.5rem;">
        <div class="info-label">Notes</div>
        <div style="margin-top: 0.5rem;">{{ nl2br(e($meeting->minutes)) }}</div>
    </div>

    @if($meeting->decisions)
    <div style="margin-bottom: 1.5rem;">
        <div class="info-label">Décisions prises</div>
        <div style="margin-top: 0.5rem;">{{ nl2br(e($meeting->decisions)) }}</div>
    </div>
    @endif

    @if($meeting->action_items && count($meeting->action_items) > 0)
    <div>
        <div class="info-label">Actions à mener</div>
        <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
            @foreach($meeting->action_items as $action)
                <li style="margin-bottom: 0.25rem;">{{ $action }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@elseif($meeting->status === 'scheduled')
<div class="minutes-section" style="text-align: center;">
    <i class="fas fa-file-alt" style="font-size: 2rem; opacity: 0.5; margin-bottom: 1rem;"></i>
    <p>Aucun compte-rendu disponible pour cette réunion.</p>
    @if(auth()->user()->can('meetings.edit'))
    <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn-primary-sm btn-sm">
        <i class="fas fa-plus"></i> Ajouter un compte-rendu
    </a>
    @endif
</div>
@endif

@endsection
