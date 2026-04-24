{{-- resources/views/admin/meetings/partials/meeting-list.blade.php --}}
{{-- Composant réutilisable pour afficher une liste de réunions --}}
{{-- Variables attendues: $meetings (collection), $showProject (bool, optionnel) --}}

@foreach($meetings as $meeting)
<div style="display:flex; align-items:flex-start; gap:0.875rem; padding:0.875rem 0; border-bottom:1px solid var(--border-light);">
    {{-- Date box --}}
    <div style="background:var(--bg-tertiary); border-radius:0.5rem; padding:0.5rem 0.75rem; text-align:center; flex-shrink:0; min-width:52px; border:1px solid var(--border-light);">
        <div style="font-size:1.25rem; font-weight:700; color:var(--text-primary); line-height:1;">{{ $meeting->scheduled_at->format('d') }}</div>
        <div style="font-size:0.65rem; text-transform:uppercase; color:var(--text-tertiary); margin-top:0.125rem;">{{ $meeting->scheduled_at->translatedFormat('M') }}</div>
    </div>

    {{-- Info --}}
    <div style="flex:1; min-width:0;">
        <div style="font-weight:600; color:var(--text-primary); font-size:0.875rem; margin-bottom:0.25rem;">
            <a href="{{ route('admin.projects.meetings.show', [$meeting->project, $meeting]) }}" style="color:inherit; text-decoration:none;">
                {{ $meeting->title }}
            </a>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:0.375rem; margin-bottom:0.375rem;">
            <span style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; background:rgba(107,114,128,0.1); border-radius:9999px; font-size:0.7rem; color:var(--text-tertiary);">
                <i class="fas fa-clock"></i> {{ $meeting->scheduled_at->format('H:i') }}
            </span>
            @if($meeting->duration_minutes)
            <span style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; background:rgba(107,114,128,0.1); border-radius:9999px; font-size:0.7rem; color:var(--text-tertiary);">
                <i class="fas fa-hourglass-half"></i> {{ $meeting->duration_formatted }}
            </span>
            @endif
            @if($meeting->meeting_mode === 'online' && $meeting->meeting_url)
            <a href="{{ $meeting->meeting_url }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; background:rgba(59,130,246,0.1); border-radius:9999px; font-size:0.7rem; color:#3b82f6; text-decoration:none;">
                <i class="fas fa-video"></i> Rejoindre
            </a>
            @elseif($meeting->location)
            <span style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; background:rgba(107,114,128,0.1); border-radius:9999px; font-size:0.7rem; color:var(--text-tertiary);">
                <i class="fas fa-location-dot"></i> {{ Str::limit($meeting->location, 25) }}
            </span>
            @endif
        </div>
        @if(isset($showProject) && $showProject && $meeting->project)
        <div style="font-size:0.75rem; color:var(--text-tertiary);">
            <i class="fas fa-folder"></i>
            <a href="{{ route('admin.projects.show', $meeting->project) }}" style="color:var(--brand-primary); text-decoration:none;">{{ $meeting->project->name }}</a>
        </div>
        @endif
    </div>

    {{-- Badge + actions --}}
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.375rem; flex-shrink:0;">
        <span style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.625rem; font-size:0.7rem; font-weight:500; border-radius:9999px;
            @if($meeting->status === 'completed') background:rgba(16,185,129,0.1); color:#10b981;
            @elseif($meeting->status === 'cancelled') background:rgba(239,68,68,0.1); color:#ef4444;
            @elseif($meeting->status === 'postponed') background:rgba(245,158,11,0.1); color:#f59e0b;
            @else background:rgba(107,114,128,0.1); color:#9ca3af; @endif">
            {{ $meeting->status_label }}
        </span>
        <a href="{{ route('admin.projects.meetings.show', [$meeting->project, $meeting]) }}" style="color:var(--text-tertiary); font-size:0.75rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='var(--text-tertiary)'">
            <i class="fas fa-eye"></i>
        </a>
    </div>
</div>
@endforeach
