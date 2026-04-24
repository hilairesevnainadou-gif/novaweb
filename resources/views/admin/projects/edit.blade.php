{{-- resources/views/admin/projects/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Créer un projet - NovaTech Admin')
@section('page-title', 'Créer un nouveau projet')

@push('styles')
<style>
    .form-container {
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .form-control {
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-light);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    select.form-control {
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        color: #ef4444;
        font-size: 0.875rem;
    }

    .help-text {
        font-size: 0.7rem;
        color: var(--text-tertiary);
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')

@php
    $user = auth()->user();
    $isEdit = isset($project);
    $route = $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store');
    $method = $isEdit ? 'PUT' : 'POST';
    $title = $isEdit ? 'Modifier le projet' : 'Créer un nouveau projet';
@endphp

<div class="form-container">
    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">{{ $title }}</h2>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin: 0; padding-left: 1rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ $route }}" method="POST">
        @csrf
        @method($method)

        <div class="form-grid">
            <!-- Nom du projet -->
            <div class="form-group-full">
                <label class="form-label">Nom du projet <span class="required">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $project->name ?? '') }}" required>
                <div class="help-text">Nom descriptif du projet</div>
            </div>

            <!-- Description -->
            <div class="form-group-full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $project->description ?? '') }}</textarea>
                <div class="help-text">Description détaillée du projet</div>
            </div>

            <!-- Type et Priorité -->
            <div class="form-group">
                <label class="form-label">Type de projet <span class="required">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="">Sélectionner un type</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $project->type ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Priorité <span class="required">*</span></label>
                <select name="priority" class="form-control" required>
                    <option value="">Sélectionner une priorité</option>
                    @foreach($priorities as $value => $label)
                        <option value="{{ $value }}" {{ old('priority', $project->priority ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Client et Chef de projet -->
            <div class="form-group">
                <label class="form-label">Client</label>
                <select name="client_id" class="form-control">
                    <option value="">Sélectionner un client</option>
                    @foreach($clients ?? [] as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $project->client_id ?? '') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} {{ $client->company_name ? '('.$client->company_name.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Chef de projet <span class="required">*</span></label>
                <select name="project_manager_id" class="form-control" required>
                    <option value="">Sélectionner un chef de projet</option>
                    @foreach($projectManagers ?? [] as $pm)
                        <option value="{{ $pm->id }}" {{ old('project_manager_id', $project->project_manager_id ?? '') == $pm->id ? 'selected' : '' }}>
                            {{ $pm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dates -->
            <div class="form-group">
                <label class="form-label">Date de début</label>
                <input type="date" name="start_date" class="form-control"
                       value="{{ old('start_date', isset($project) && $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Date de fin estimée</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ old('end_date', isset($project) && $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                <div class="help-text">Date de livraison estimée</div>
            </div>

            <!-- Budget et Heures -->
            <div class="form-group">
                <label class="form-label">Budget estimé (FCFA)</label>
                <input type="number" step="0.01" name="budget" class="form-control"
                       value="{{ old('budget', $project->budget ?? '') }}" placeholder="0">
            </div>

            <div class="form-group">
                <label class="form-label">Heures estimées</label>
                <input type="number" step="0.5" name="estimated_hours" class="form-control"
                       value="{{ old('estimated_hours', $project->estimated_hours ?? '') }}" placeholder="0">
            </div>

            <!-- URLs -->
            <div class="form-group">
                <label class="form-label">URL du Repository</label>
                <input type="url" name="repository_url" class="form-control"
                       value="{{ old('repository_url', $project->repository_url ?? '') }}" placeholder="https://github.com/...">
            </div>

            <div class="form-group">
                <label class="form-label">URL de production</label>
                <input type="url" name="production_url" class="form-control"
                       value="{{ old('production_url', $project->production_url ?? '') }}" placeholder="https://...">
            </div>

            <div class="form-group-full">
                <label class="form-label">Technologies utilisées</label>
                <input type="text" name="technologies" class="form-control"
                       value="{{ old('technologies', isset($project) && $project->technologies ? implode(', ', $project->technologies) : '') }}"
                       placeholder="PHP, Laravel, MySQL, React, ...">
                <div class="help-text">Séparez les technologies par des virgules</div>
            </div>

            <!-- Statut (uniquement en édition) -->
            @if($isEdit)
            <div class="form-group">
                <label class="form-label">Statut <span class="required">*</span></label>
                <select name="status" class="form-control" required>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $project->status ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Progression (%)</label>
                <input type="number" name="progress_percentage" class="form-control"
                       value="{{ old('progress_percentage', $project->progress_percentage ?? 0) }}" min="0" max="100">
            </div>
            @endif
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.projects.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Mettre à jour' : 'Créer le projet' }}
            </button>
        </div>
    </form>
</div>

@endsection
