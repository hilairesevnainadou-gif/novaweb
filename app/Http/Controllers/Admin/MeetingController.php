<?php
// app/Http/Controllers/Admin/MeetingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    /**
     * Liste des réunions d'un projet
     */
    public function index(Project $project)
    {
        $user = auth()->user();

        if (!$user->can('meetings.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        $meetings = Meeting::with(['project', 'organizer'])
            ->where('project_id', $project->id)
            ->orderBy('meeting_date', 'desc')
            ->paginate(20);

        return view('admin.meetings.index', compact('meetings', 'project'));
    }

    /**
     * Liste globale des réunions
     */
    public function globalIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('meetings.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        $query = Meeting::with(['project', 'organizer']);

        // Restriction selon les permissions
        if (!$user->can('meetings.view.all')) {
            $query->where(function($q) use ($user) {
                $q->where('organizer_id', $user->id)
                  ->orWhereJsonContains('attendees', $user->id)
                  ->orWhereHas('project', function($pq) use ($user) {
                      $pq->where('project_manager_id', $user->id);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('meeting_date', today());
            } elseif ($request->period === 'upcoming') {
                $query->where('meeting_date', '>', now());
            } elseif ($request->period === 'past') {
                $query->where('meeting_date', '<', now());
            }
        }

        $meetings = $query->orderBy('meeting_date', 'desc')->paginate(20);
        $projects = Project::active()->get();

        return view('admin.meetings.global-index', compact('meetings', 'projects'));
    }

    /**
     * Formulaire de création
     */
    public function create(Project $project)
    {
        $user = auth()->user();

        if (!$user->can('meetings.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une réunion.');
        }

        $users = User::all();

        return view('admin.meetings.create', compact('project', 'users'));
    }

    /**
     * Enregistrer une réunion
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        if (!$user->can('meetings.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une réunion.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'meeting_link' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id'
        ]);

        $validated['project_id'] = $project->id;
        $validated['organizer_id'] = $user->id;
        $validated['status'] = Meeting::STATUS_SCHEDULED;

        DB::beginTransaction();

        try {
            $meeting = Meeting::create($validated);

            // Ajouter l'activité
            $project->activities()->create([
                'user_id' => $user->id,
                'activity_type' => 'meeting_scheduled',
                'description' => "Réunion '{$meeting->title}' planifiée par " . $user->name,
                'metadata' => ['meeting_id' => $meeting->id]
            ]);

            DB::commit();

            return redirect()->route('admin.projects.meetings.index', $project->id)
                ->with('success', 'Réunion planifiée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la planification: ' . $e->getMessage());
        }
    }

    /**
     * Afficher une réunion
     */
    public function show(Meeting $meeting)
    {
        $user = auth()->user();

        if (!$user->can('meetings.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette réunion.');
        }

        // Vérifier l'accès à la réunion
        if (!$user->can('meetings.view.all')) {
            $isOrganizer = $meeting->organizer_id == $user->id;
            $isAttendee = in_array($user->id, $meeting->attendees ?? []);
            $isProjectManager = $meeting->project->project_manager_id == $user->id;

            if (!$isOrganizer && !$isAttendee && !$isProjectManager) {
                abort(403, 'Vous n\'avez pas accès à cette réunion.');
            }
        }

        $meeting->load(['project', 'organizer']);

        return view('admin.meetings.show', compact('meeting'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Meeting $meeting)
    {
        $user = auth()->user();

        if (!$user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        if (!$user->can('meetings.edit.all') && $meeting->organizer_id !== $user->id) {
            abort(403, 'Vous ne pouvez modifier que vos propres réunions.');
        }

        $users = User::all();

        return view('admin.meetings.edit', compact('meeting', 'users'));
    }

    /**
     * Mettre à jour une réunion
     */
    public function update(Request $request, Meeting $meeting)
    {
        $user = auth()->user();

        if (!$user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        if (!$user->can('meetings.edit.all') && $meeting->organizer_id !== $user->id) {
            abort(403, 'Vous ne pouvez modifier que vos propres réunions.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'meeting_link' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'status' => 'required|string',
            'minutes' => 'nullable|string',
            'decisions' => 'nullable|string',
            'action_items' => 'nullable|array'
        ]);

        $meeting->update($validated);

        return redirect()->route('admin.meetings.show', $meeting)
            ->with('success', 'Réunion mise à jour avec succès.');
    }

    /**
     * Ajouter le compte-rendu
     */
    public function addMinutes(Request $request, Meeting $meeting)
    {
        $user = auth()->user();

        if (!$user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        $request->validate([
            'minutes' => 'required|string',
            'decisions' => 'nullable|string',
            'action_items' => 'nullable|array'
        ]);

        $meeting->update([
            'minutes' => $request->minutes,
            'decisions' => $request->decisions,
            'action_items' => $request->action_items,
            'status' => Meeting::STATUS_COMPLETED
        ]);

        return redirect()->route('admin.meetings.show', $meeting)
            ->with('success', 'Compte-rendu ajouté avec succès.');
    }

    /**
     * Supprimer une réunion
     */
    public function destroy(Meeting $meeting)
    {
        $user = auth()->user();

        if (!$user->can('meetings.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer cette réunion.');
        }

        if (!$user->can('meetings.delete.all') && $meeting->organizer_id !== $user->id) {
            abort(403, 'Vous ne pouvez supprimer que vos propres réunions.');
        }

        $meeting->delete();

        return redirect()->route('admin.meetings.global-index')
            ->with('success', 'Réunion supprimée avec succès.');
    }
}
