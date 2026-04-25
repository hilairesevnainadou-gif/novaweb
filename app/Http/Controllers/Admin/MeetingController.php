<?php

// app/Http/Controllers/Admin/MeetingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Project;
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

        if (! $user->can('meetings.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        // Vérifier l'accès au projet pour les utilisateurs sans vue globale
        if (! $user->can('meetings.view.all')) {
            $hasAccess = $project->project_manager_id === $user->id
                || $project->projectMembers()->where('user_id', $user->id)->exists()
                || $project->tasks()->where(fn ($q) => $q
                    ->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id)
                )->exists();

            if (! $hasAccess) {
                abort(403, 'Vous n\'avez pas accès aux réunions de ce projet.');
            }
        }

        $meetings = Meeting::with(['project', 'organizer'])
            ->where('project_id', $project->id)
            ->orderBy('meeting_date', 'desc')
            ->paginate(20);

        $base = Meeting::where('project_id', $project->id);

        $stats = [
            'upcoming' => (clone $base)->where('meeting_date', '>', now())->where('status', 'scheduled')->count(),
            'today' => (clone $base)->whereDate('meeting_date', today())->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
        ];

        return view('admin.meetings.index', compact('meetings', 'project', 'stats'));
    }

    /**
     * Liste globale des réunions
     */
    public function globalIndex(Request $request)
    {
        $user       = auth()->user();
        $canViewAll = $user->can('meetings.view.all');

        if (! $user->can('meetings.view')) {
            abort(403);
        }

        // Scope : réunions où l'utilisateur est organisateur ou participant
        // Les utilisateurs avec meetings.view.all voient toutes les réunions
        $scopeQuery = function ($q) use ($user, $canViewAll): void {
            if (! $canViewAll) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('organizer_id', $user->id)
                          ->orWhereJsonContains('attendees', $user->id)
                          ->orWhereJsonContains('attendees', (string) $user->id);
                });
            }
        };

        // Stats calculées sur le scope de l'utilisateur (sans les filtres actifs)
        $sb = Meeting::query()->tap($scopeQuery);
        $stats = [
            'total'     => (clone $sb)->count(),
            'upcoming'  => (clone $sb)->upcoming()->count(),
            'today'     => (clone $sb)->today()->count(),
            'completed' => (clone $sb)->where('status', Meeting::STATUS_COMPLETED)->count(),
            'cancelled' => (clone $sb)->where('status', Meeting::STATUS_CANCELLED)->count(),
        ];

        // Query principale avec filtres
        $query = Meeting::with(['project', 'organizer'])->tap($scopeQuery);

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn ($q) => $q->where('title', 'like', $s)->orWhere('location', 'like', $s));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('period')) {
            match ($request->period) {
                'today'    => $query->today(),
                'week'     => $query->thisWeek(),
                'upcoming' => $query->upcoming(),
                'past'     => $query->past(),
                default    => null,
            };
        }

        // Filtre rôle (canViewAll uniquement)
        if ($canViewAll && $request->filled('my_role')) {
            if ($request->my_role === 'organizer') {
                $query->where('organizer_id', $user->id);
            } elseif ($request->my_role === 'attendee') {
                $query->whereJsonContains('attendees', $user->id);
            }
        }

        $meetings = $query->orderBy('meeting_date', 'desc')->paginate(15)->withQueryString();
        $projects = $this->accessibleProjects($user);

        return view('admin.meetings.global-index', compact('meetings', 'projects', 'stats', 'canViewAll'));
    }

    /**
     * Retourne les projets accessibles à l'utilisateur pour le sélecteur de réunion.
     */
    private function accessibleProjects(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $query = Project::with(['client', 'projectManager'])->active();

        if (! $user->can('projects.view.all')) {
            $query->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('projectMembers', fn ($pmq) => $pmq->where('user_id', $user->id))
                  ->orWhereHas('tasks', fn ($tq) => $tq->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id));
            });
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Formulaire de création global (sans projet fixe)
     */
    public function createGlobal()
    {
        $user = auth()->user();

        if (! $user->can('meetings.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une réunion.');
        }

        $projects         = $this->accessibleProjects($user);
        $users            = User::orderBy('name')->get();
        $projectMemberIds = [];

        return view('admin.meetings.create', compact('projects', 'users', 'projectMemberIds'));
    }

    /**
     * Enregistrer une réunion créée depuis la vue globale
     */
    public function storeGlobal(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('meetings.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une réunion.');
        }

        $validated = $request->validate([
            'project_id'       => 'required|exists:projects,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'meeting_date'     => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'meeting_link'     => 'nullable|url',
            'location'         => 'nullable|string|max:255',
            'attendees'        => 'nullable|array',
            'attendees.*'      => 'exists:users,id',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        if (isset($validated['attendees'])) {
            $validated['attendees'] = array_map('intval', $validated['attendees']);
        }

        $validated['organizer_id'] = $user->id;
        $validated['status']       = Meeting::STATUS_SCHEDULED;

        DB::beginTransaction();

        try {
            $meeting = Meeting::create($validated);

            $project->activities()->create([
                'user_id'       => $user->id,
                'activity_type' => 'meeting_scheduled',
                'description'   => "Réunion '{$meeting->title}' planifiée par ".$user->name,
                'metadata'      => ['meeting_id' => $meeting->id],
            ]);

            DB::commit();

            return redirect()->route('admin.projects.meetings.index', $project->id)
                ->with('success', 'Réunion planifiée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la planification: '.$e->getMessage());
        }
    }

    /**
     * Formulaire de création
     */
    public function create(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('meetings.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une réunion.');
        }

        // Vérifier que l'utilisateur a accès à ce projet s'il n'a pas la vue globale
        if (! $user->can('meetings.view.all')) {
            $hasAccess = $project->project_manager_id === $user->id
                || $project->projectMembers()->where('user_id', $user->id)->exists()
                || $project->tasks()->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id);
                })->exists();

            if (! $hasAccess) {
                abort(403, 'Vous n\'avez pas accès aux réunions de ce projet.');
            }
        }

        // Membres du projet (membres explicites + chef + intervenants sur les tâches)
        $projectMemberIds = collect([$project->project_manager_id])
            ->merge($project->projectMembers()->pluck('user_id'))
            ->merge($project->tasks()->pluck('assigned_to')->filter())
            ->merge($project->tasks()->pluck('created_by')->filter())
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        // Membres du projet en premier, puis les autres
        $projectMembers = User::whereIn('id', $projectMemberIds)->orderBy('name')->get();
        $otherUsers     = User::whereNotIn('id', array_merge($projectMemberIds, [0]))->orderBy('name')->get();
        $users          = $projectMembers->merge($otherUsers);

        return view('admin.meetings.create', compact('project', 'users', 'projectMemberIds'));
    }

    /**
     * Enregistrer une réunion
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        if (! $user->can('meetings.create')) {
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
            'attendees.*' => 'exists:users,id',
        ]);

        if (isset($validated['attendees'])) {
            $validated['attendees'] = array_map('intval', $validated['attendees']);
        }

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
                'description' => "Réunion '{$meeting->title}' planifiée par ".$user->name,
                'metadata' => ['meeting_id' => $meeting->id],
            ]);

            DB::commit();

            return redirect()->route('admin.projects.meetings.index', $project->id)
                ->with('success', 'Réunion planifiée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la planification: '.$e->getMessage());
        }
    }

    /**
     * Afficher une réunion
     */
    public function show(Meeting $meeting)
    {
        $user = auth()->user();

        if (! $user->can('meetings.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette réunion.');
        }

        // Vérifier l'accès à la réunion
        if (! $user->can('meetings.view.all')) {
            $isOrganizer      = $meeting->organizer_id == $user->id;
            $isAttendee       = in_array($user->id, $meeting->attendees ?? []);
            $isProjectManager = $meeting->project?->project_manager_id == $user->id;
            $isProjectMember  = $meeting->project?->projectMembers()->where('user_id', $user->id)->exists();

            if (! $isOrganizer && ! $isAttendee && ! $isProjectManager && ! $isProjectMember) {
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

        if (! $user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        if (! $user->can('meetings.edit.all') && $meeting->organizer_id !== $user->id) {
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

        if (! $user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        if (! $user->can('meetings.edit.all') && $meeting->organizer_id !== $user->id) {
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
            'action_items' => 'nullable|array',
        ]);

        if (isset($validated['attendees'])) {
            $validated['attendees'] = array_map('intval', $validated['attendees']);
        }

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

        if (! $user->can('meetings.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette réunion.');
        }

        $request->validate([
            'minutes' => 'required|string',
            'decisions' => 'nullable|string',
            'action_items' => 'nullable|array',
        ]);

        $meeting->update([
            'minutes' => $request->minutes,
            'decisions' => $request->decisions,
            'action_items' => $request->action_items,
            'status' => Meeting::STATUS_COMPLETED,
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

        if (! $user->can('meetings.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer cette réunion.');
        }

        if (! $user->can('meetings.delete.all') && $meeting->organizer_id !== $user->id) {
            abort(403, 'Vous ne pouvez supprimer que vos propres réunions.');
        }

        $meeting->delete();

        return redirect()->route('admin.meetings.global-index')
            ->with('success', 'Réunion supprimée avec succès.');
    }
}
