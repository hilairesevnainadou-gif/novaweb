@extends('admin.layouts.app')

@section('title', 'Modifier la réunion · NovaTech Admin')
@section('page-title', 'Modifier la réunion')

@push('styles')
<style>
    /* ── Layout ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.74rem;
        color: var(--text-tertiary);
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .breadcrumb a { color: var(--text-tertiary); text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--brand-primary); }
    .breadcrumb i { font-size: 0.55rem; }

    /* ── Cards ── */
    .form-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: visible;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
    }

    .form-card:last-child { margin-bottom: 0; }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid var(--border-light);
        background: rgba(255,255,255,.02);
        border-radius: 14px 14px 0 0;
    }

    .form-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        flex-shrink: 0;
    }

    .icon-blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .icon-purple { background: rgba(139,92,246,.15);  color: #a78bfa; }
    .icon-green  { background: rgba(16,185,129,.15);  color: #34d399; }
    .icon-amber  { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .icon-red    { background: rgba(239,68,68,.15);   color: #f87171; }

    .form-card-title { font-size: 0.8rem; font-weight: 700; color: var(--text-primary); }
    .form-card-desc  { font-size: 0.67rem; color: var(--text-tertiary); margin-left: auto; }
    .form-card-body  { padding: 1.1rem; }

    /* ── Grid ── */
    .fg-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.8rem; }
    .fg-full { grid-column: 1 / -1; }

    /* ── Field ── */
    .field { display: flex; flex-direction: column; gap: 0.32rem; }

    .field label {
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-tertiary);
    }

    .field label .req { color: #f87171; margin-left: 0.2rem; }
    .field-hint { font-size: 0.63rem; color: var(--text-disabled); line-height: 1.3; }

    /* ── Inputs ── */
    .fi {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-height: 38px;
        transition: border-color .18s, box-shadow .18s, background .18s;
        outline: none;
        width: 100%;
    }

    .fi:hover  { border-color: var(--border-heavy); background: var(--bg-elevated); }
    .fi:focus  { border-color: var(--brand-primary); background: var(--bg-elevated); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi::placeholder { color: var(--text-disabled); font-size: 0.77rem; }
    textarea.fi { resize: vertical; min-height: 80px; max-height: 220px; line-height: 1.5; }
    select.fi   { cursor: pointer; }

    .fi.is-invalid { border-color: #f87171; background: rgba(239,68,68,.05); }
    .fi.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
    .invalid-msg { font-size: 0.65rem; color: #f87171; margin-top: 0.1rem; }

    /* ── Action bar ── */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.1rem;
        border-top: 1px solid var(--border-light);
        background: rgba(255,255,255,.015);
        border-radius: 0 0 14px 14px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.52rem 1rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }
    .btn-ghost:hover { background: var(--bg-tertiary); color: var(--text-primary); }

    .btn-save {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { background: var(--brand-primary-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(59,130,246,.4); }

    /* ── Alert ── */
    .alert-errors {
        display: flex;
        gap: 0.75rem;
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.25);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.1rem;
    }
    .alert-errors .alert-icon { font-size: 1rem; color: #f87171; margin-top: 0.05rem; flex-shrink: 0; }
    .alert-errors ul { margin: 0; padding-left: 1.1rem; font-size: 0.78rem; color: #fca5a5; line-height: 1.6; }
    .alert-errors .alert-title { font-size: 0.78rem; font-weight: 700; color: #f87171; margin-bottom: 0.3rem; }

    /* ── Participants multi-select ── */
    .participants-wrap { position: relative; }

    .participants-input-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem;
        min-height: 42px;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        cursor: text;
        transition: border-color .18s, box-shadow .18s;
    }
    .participants-input-box:focus-within {
        border-color: var(--brand-primary);
        background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    .participant-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        background: rgba(59,130,246,.15);
        border: 1px solid rgba(59,130,246,.3);
        color: #60a5fa;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .participant-chip .remove-chip {
        cursor: pointer;
        opacity: 0.7;
        font-size: 0.6rem;
        background: none;
        border: none;
        color: inherit;
        padding: 0;
        line-height: 1;
        transition: opacity .15s;
    }
    .participant-chip .remove-chip:hover { opacity: 1; }

    #participant-search {
        border: none;
        outline: none;
        background: transparent;
        color: var(--text-primary);
        font-size: 0.8rem;
        font-family: inherit;
        min-width: 140px;
        flex: 1;
        padding: 0.1rem 0;
    }
    #participant-search::placeholder { color: var(--text-disabled); font-size: 0.77rem; }

    .participants-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 300;
        background: var(--bg-primary);
        border: 1px solid var(--border-medium);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        max-height: 220px;
        overflow-y: auto;
    }
    .participants-dropdown.open { display: block; animation: ddIn .15s ease; }

    @keyframes ddIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pd-option {
        display: flex;
        flex-direction: column;
        padding: 0.5rem 0.85rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border-light);
        transition: background .13s;
    }
    .pd-option:last-child { border-bottom: none; }
    .pd-option:hover { background: var(--bg-hover); }
    .pd-option .pd-name  { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .pd-option .pd-email { font-size: 0.68rem; color: var(--text-tertiary); }
    .pd-no-result { padding: 0.65rem 0.85rem; font-size: 0.78rem; color: var(--text-tertiary); text-align: center; }

    /* ── Sidebar ── */
    .sidebar-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
        position: sticky;
        top: 80px;
    }
    .sidebar-card:last-child { margin-bottom: 0; }
    .sidebar-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-tertiary);
        background: rgba(255,255,255,.02);
    }
    .sidebar-body { padding: 0.85rem 1rem; }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-light);
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row:first-child { padding-top: 0; }

    .info-row-icon {
        width: 28px; height: 28px;
        border-radius: 7px;
        background: var(--bg-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: var(--text-tertiary);
        flex-shrink: 0;
        margin-top: 1px;
    }
    .info-row-content { flex: 1; min-width: 0; }
    .info-row-label { font-size: 0.62rem; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.06em; font-weight: 700; }
    .info-row-value { font-size: 0.78rem; font-weight: 600; color: var(--text-primary); margin-top: 0.08rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ── Duration options ── */
    .duration-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .duration-option { display: none; }
    .duration-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.55rem 0.3rem;
        border-radius: 8px;
        border: 1px solid var(--border-medium);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        text-align: center;
        line-height: 1.3;
    }
    .duration-label span { font-size: 0.62rem; color: var(--text-tertiary); }
    .duration-option:checked + .duration-label {
        background: rgba(59,130,246,.15);
        border-color: rgba(59,130,246,.4);
        color: #60a5fa;
    }
    .duration-option:checked + .duration-label span { color: #93c5fd; }

    /* ── Status badge ── */
    .badge-scheduled   { color: #34d399; }
    .badge-in_progress { color: #60a5fa; }
    .badge-completed   { color: var(--text-tertiary); }
    .badge-cancelled   { color: #f87171; }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .create-layout { grid-template-columns: 1fr; }
        .sidebar-card  { position: static; }
    }
    @media (max-width: 640px) {
        .fg-2 { grid-template-columns: 1fr; }
        .duration-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb">
    <a href="{{ route('admin.projects.show', $meeting->project) }}">{{ $meeting->project->name }}</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.projects.meetings.index', $meeting->project) }}">Réunions</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.meetings.show', $meeting) }}">{{ Str::limit($meeting->title, 30) }}</a>
    <i class="fas fa-chevron-right"></i>
    <span>Modifier</span>
</nav>

{{-- Validation errors --}}
@if($errors->any())
<div class="alert-errors">
    <div class="alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
    <div>
        <div class="alert-title">{{ $errors->count() }} erreur(s) à corriger</div>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@can('meetings.edit')
<form action="{{ route('admin.meetings.update', $meeting) }}" method="POST" id="meeting-form">
    @csrf
    @method('PUT')

    {{-- Hidden attendees[] inputs (populated by JS) --}}
    <div id="attendees-container">
        @foreach(old('attendees', $meeting->attendees ?? []) as $aid)
            <input type="hidden" name="attendees[]" value="{{ $aid }}">
        @endforeach
    </div>

    <div class="create-layout">

        {{-- ══════ MAIN ══════ --}}
        <div>

            {{-- Section 1: Détails --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-blue"><i class="fas fa-calendar-alt"></i></div>
                    <span class="form-card-title">Détails de la réunion</span>
                    <span class="form-card-desc">Informations principales</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Titre <span class="req">*</span></label>
                            <input type="text" name="title" class="fi {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title', $meeting->title) }}"
                                   placeholder="Ex : Revue technique hebdomadaire" required autofocus>
                            @error('title')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Description / Ordre du jour</label>
                            <textarea name="description" class="fi {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Points à aborder durant la réunion…">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="fg-2">
                            <div class="field">
                                <label>Date et heure <span class="req">*</span></label>
                                <input type="text" id="meeting_date" name="meeting_date"
                                       class="fi {{ $errors->has('meeting_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('meeting_date', $meeting->meeting_date?->format('Y-m-d H:i:s')) }}"
                                       placeholder="Sélectionnez une date…" required>
                                @error('meeting_date')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label>Lieu</label>
                                <input type="text" name="location" class="fi {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                       value="{{ old('location', $meeting->location) }}"
                                       placeholder="Salle de conf., Bureau 12…">
                                @error('location')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label>Lien de réunion</label>
                            <input type="url" name="meeting_link" class="fi {{ $errors->has('meeting_link') ? 'is-invalid' : '' }}"
                                   value="{{ old('meeting_link', $meeting->meeting_link) }}"
                                   placeholder="https://meet.google.com/ ou https://zoom.us/…">
                            <div class="field-hint">Pour les réunions à distance (Google Meet, Zoom, Teams…)</div>
                            @error('meeting_link')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 2: Durée --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-amber"><i class="fas fa-clock"></i></div>
                    <span class="form-card-title">Durée</span>
                    <span class="form-card-desc">Temps estimé</span>
                </div>
                <div class="form-card-body">
                    <div class="field">
                        <label>Durée de la réunion <span class="req">*</span></label>
                        <div class="duration-grid">
                            @php
                                $durations = [
                                    15  => ['15 min',   ''],
                                    30  => ['30 min',   '½ h'],
                                    45  => ['45 min',   '¾ h'],
                                    60  => ['1 h',      ''],
                                    90  => ['1 h 30',   ''],
                                    120 => ['2 h',      ''],
                                    180 => ['3 h',      ''],
                                    240 => ['4 h',      'demi-journée'],
                                ];
                                $currentDuration = old('duration_minutes', $meeting->duration_minutes ?? 60);
                            @endphp
                            @foreach($durations as $minutes => [$label, $sub])
                                <div>
                                    <input type="radio" name="duration_minutes" id="dur_{{ $minutes }}"
                                           class="duration-option" value="{{ $minutes }}"
                                           {{ $currentDuration == $minutes ? 'checked' : '' }}>
                                    <label for="dur_{{ $minutes }}" class="duration-label">
                                        {{ $label }}
                                        @if($sub)<span>{{ $sub }}</span>@endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('duration_minutes')<div class="invalid-msg" style="margin-top:.4rem"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Participants --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-purple"><i class="fas fa-users"></i></div>
                    <span class="form-card-title">Participants</span>
                    <span class="form-card-desc">Personnes invitées</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Ajouter des participants</label>
                            <div class="participants-wrap">
                                <div class="participants-input-box" id="participants-box" onclick="document.getElementById('participant-search').focus()">
                                    <div id="chips-container"></div>
                                    <input type="text" id="participant-search"
                                           placeholder="Rechercher un participant…"
                                           autocomplete="off">
                                </div>
                                <div class="participants-dropdown" id="participants-dropdown"></div>
                            </div>
                            <div class="field-hint">Tapez un nom ou e-mail pour rechercher</div>
                            @error('attendees')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Section 4: Statut & Compte-rendu --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon icon-green"><i class="fas fa-circle-check"></i></div>
                    <span class="form-card-title">Statut & Compte-rendu</span>
                    <span class="form-card-desc">État et notes de réunion</span>
                </div>
                <div class="form-card-body">
                    <div style="display:flex;flex-direction:column;gap:.8rem">

                        <div class="field">
                            <label>Statut <span class="req">*</span></label>
                            <select name="status" class="fi {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="scheduled"   {{ old('status', $meeting->status) === 'scheduled'   ? 'selected' : '' }}>Planifiée</option>
                                <option value="in_progress" {{ old('status', $meeting->status) === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed"   {{ old('status', $meeting->status) === 'completed'   ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled"   {{ old('status', $meeting->status) === 'cancelled'   ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('status')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Compte-rendu / Minutes</label>
                            <textarea name="minutes" class="fi {{ $errors->has('minutes') ? 'is-invalid' : '' }}"
                                      placeholder="Résumé de ce qui a été discuté…" style="min-height:100px">{{ old('minutes', $meeting->minutes) }}</textarea>
                            @error('minutes')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Décisions prises</label>
                            <textarea name="decisions" class="fi {{ $errors->has('decisions') ? 'is-invalid' : '' }}"
                                      placeholder="Décisions importantes actées lors de la réunion…">{{ old('decisions', $meeting->decisions) }}</textarea>
                            @error('decisions')<div class="invalid-msg"><i class="fas fa-circle-exclamation" style="font-size:.6rem"></i> {{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Action bar --}}
                <div class="action-bar">
                    <div>
                        <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-ghost">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-floppy-disk"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end main --}}

        {{-- ══════ SIDEBAR ══════ --}}
        <div>
            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-circle-info" style="margin-right:.35rem"></i>Réunion</div>
                <div class="sidebar-body">

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Organisateur</div>
                            <div class="info-row-value">{{ $meeting->organizer->name }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Projet</div>
                            <div class="info-row-value">{{ $meeting->project->name }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-calendar"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Date actuelle</div>
                            <div class="info-row-value">{{ $meeting->meeting_date->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-users"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Participants</div>
                            <div class="info-row-value">{{ count($meeting->attendees ?? []) }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-row-icon"><i class="fas fa-circle-dot"></i></div>
                        <div class="info-row-content">
                            <div class="info-row-label">Statut actuel</div>
                            <div class="info-row-value badge-{{ $meeting->status }}">{{ $meeting->status_label }}</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-header"><i class="fas fa-lightbulb" style="margin-right:.35rem"></i>Conseils</div>
                <div class="sidebar-body">
                    <p style="font-size:.74rem;color:var(--text-secondary);line-height:1.55;margin:0 0 .65rem">
                        Vérifiez la disponibilité des participants avant de modifier la date.
                    </p>
                    <p style="font-size:.74rem;color:var(--text-secondary);line-height:1.55;margin:0">
                        Passez le statut à <strong>Terminée</strong> pour archiver la réunion et y ajouter un compte-rendu.
                    </p>
                </div>
            </div>
        </div>

    </div>{{-- end layout --}}
</form>
@endcan

@cannot('meetings.edit')
<div style="text-align:center;padding:3rem 1rem;color:var(--text-tertiary)">
    <i class="fas fa-lock" style="font-size:2.5rem;margin-bottom:1rem;display:block"></i>
    <h3 style="margin:0 0 .5rem;color:var(--text-secondary)">Accès refusé</h3>
    <p style="margin:0 0 1.5rem">Vous n'avez pas la permission de modifier cette réunion.</p>
    <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> Voir la réunion
    </a>
</div>
@endcannot

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
(function () {

    /* ── Flatpickr date/time ── */
    flatpickr('#meeting_date', {
        locale: 'fr',
        enableTime: true,
        dateFormat: 'Y-m-d H:i:s',
        time_24hr: true,
        minuteIncrement: 15,
        allowInput: false,
    });

    /* ── Participants multi-select ── */
    @php
        $usersJson = $users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])->values();
        $currentAttendeeIds = array_map('intval', old('attendees', $meeting->attendees ?? []));
    @endphp

    const ALL_USERS      = @json($usersJson);
    const CURRENT_IDS    = @json($currentAttendeeIds);

    const searchInput    = document.getElementById('participant-search');
    const dropdown       = document.getElementById('participants-dropdown');
    const chipsContainer = document.getElementById('chips-container');
    const attendeesCont  = document.getElementById('attendees-container');

    let selected = [];

    /* Pre-load existing attendees */
    CURRENT_IDS.forEach(id => {
        const user = ALL_USERS.find(u => u.id === id);
        if (user) { selected.push(user); }
    });
    renderAll();

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    function syncHiddenInputs() {
        attendeesCont.innerHTML = '';
        selected.forEach(u => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'attendees[]';
            inp.value = u.id;
            attendeesCont.appendChild(inp);
        });
    }

    function renderChips() {
        chipsContainer.innerHTML = selected.map(u =>
            `<span class="participant-chip">
                ${escHtml(u.name)}
                <button type="button" class="remove-chip" data-id="${u.id}">&times;</button>
            </span>`
        ).join('');

        chipsContainer.querySelectorAll('.remove-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                selected = selected.filter(u => u.id !== id);
                renderAll();
            });
        });

        searchInput.placeholder = selected.length ? 'Ajouter…' : 'Rechercher un participant…';
    }

    function renderDropdown(q = '') {
        const lq = q.toLowerCase().trim();
        const filtered = ALL_USERS.filter(u =>
            !selected.some(s => s.id === u.id) &&
            (!lq || u.name.toLowerCase().includes(lq) || u.email.toLowerCase().includes(lq))
        );

        if (filtered.length === 0) {
            dropdown.innerHTML = '<div class="pd-no-result">Aucun résultat</div>';
        } else {
            dropdown.innerHTML = filtered.map(u =>
                `<div class="pd-option" data-id="${u.id}" data-name="${escHtml(u.name)}" data-email="${escHtml(u.email)}">
                    <span class="pd-name">${escHtml(u.name)}</span>
                    <span class="pd-email">${escHtml(u.email)}</span>
                </div>`
            ).join('');

            dropdown.querySelectorAll('.pd-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    selected.push({ id: parseInt(opt.dataset.id), name: opt.dataset.name, email: opt.dataset.email });
                    searchInput.value = '';
                    dropdown.classList.remove('open');
                    renderAll();
                });
            });
        }

        dropdown.classList.add('open');
    }

    function renderAll() {
        renderChips();
        syncHiddenInputs();
    }

    searchInput.addEventListener('focus', () => renderDropdown(searchInput.value));
    searchInput.addEventListener('input', () => renderDropdown(searchInput.value));

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });

})();
</script>
@endpush
