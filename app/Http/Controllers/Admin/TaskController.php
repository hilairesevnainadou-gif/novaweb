<?php

// app/Http/Controllers/Admin/TaskController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Liste des tâches d'un projet
     */
    public function index(Request $request, ?Project $project = null)
    {
        $user = auth()->user();
        $canViewAll = $user->can('tasks.view.all');

        if (! $user->can('tasks.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        // Contrainte de scope (tâches concernant l'utilisateur si pas de vue globale)
        $scopeQuery = function ($q) use ($user, $canViewAll): void {
            if (! $canViewAll) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('assigned_to', $user->id)
                        ->orWhere('created_by', $user->id)
                        ->orWhereHas('project', fn ($pq) => $pq->where('project_manager_id', $user->id));
                });
            }
        };

        $query = Task::with(['project.client', 'assignee', 'creator'])->tap($scopeQuery);

        if ($project) {
            $query->where('project_id', $project->id);
        }

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($request->filled('client_id')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('client_id', $request->integer('client_id'));
            });
        }

        if ($request->filled('assigned_to') && $canViewAll) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('task_number', 'like', '%'.$request->search.'%');
            });
        }

        $tasks = $query->orderBy('order')->paginate(20)->withQueryString();

        // Statistiques scoped au périmètre de l'utilisateur
        $sb = Task::query()->tap($scopeQuery);
        $stats = [
            'total' => (clone $sb)->count(),
            'in_progress' => (clone $sb)->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'completed' => (clone $sb)->where('status', Task::STATUS_COMPLETED)->count(),
            'overdue' => (clone $sb)->overdue()->count(),
        ];

        $statuses = [
            'todo' => 'À faire',
            'in_progress' => 'En cours',
            'review' => 'En revue',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Terminée',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'urgent' => 'Urgente',
        ];

        $users = User::all();

        $projectsQuery = Project::query()
            ->with('client')
            ->orderBy('name');

        if ($project) {
            $projectsQuery->where('id', $project->id);
        }

        if (! $user->can('tasks.view.all')) {
            $projectsQuery->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                    ->orWhereHas('tasks', function ($tq) use ($user) {
                        $tq->where('assigned_to', $user->id)
                            ->orWhere('created_by', $user->id);
                    });
            });
        }

        $projects = $projectsQuery->get();

        $clientsQuery = Client::query()
            ->whereIn('id', $projects->pluck('client_id')->filter()->unique()->values())
            ->orderBy('name');

        $clients = $clientsQuery->get();

        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'priority' => $request->string('priority')->toString(),
            'assigned_to' => $request->string('assigned_to')->toString(),
            'project_id' => $request->string('project_id')->toString(),
            'client_id' => $request->string('client_id')->toString(),
        ];

        return view('admin.tasks.index', compact(
            'tasks',
            'statuses',
            'priorities',
            'users',
            'project',
            'stats',
            'projects',
            'clients',
            'filters',
            'canViewAll'
        ));
    }

    /**
     * Liste globale des tâches
     */
    public function globalIndex(Request $request)
    {
        return $this->index($request, null);
    }

    /**
     * Retourne les tâches parentes (sans parent) d'un projet en JSON pour le sélecteur.
     */
    public function parentTasksJson(Project $project): JsonResponse
    {
        $user = auth()->user();

        if (! $user->can('tasks.create')) {
            abort(403);
        }

        $tasks = $project->tasks()
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get(['id', 'title', 'task_number']);

        return response()->json($tasks);
    }

    /**
     * Retourne les projets accessibles à l'utilisateur pour le sélecteur de tâche.
     */
    private function accessibleProjects(\App\Models\User $user): \Illuminate\Database\Eloquent\Collection
    {
        $query = Project::with(['client', 'projectManager'])->active();

        if (! $user->can('projects.view.all')) {
            $query->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('tasks', fn ($tq) => $tq->where('assigned_to', $user->id)
                      ->orWhere('created_by', $user->id));
            });
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Formulaire de création avec projet spécifique
     */
    public function create(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('tasks.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une tâche.');
        }

        $projects = $this->accessibleProjects($user);
        $users = User::all();

        $taskTypes = [
            'feature' => 'Fonctionnalité',
            'bug' => 'Bug / Correction',
            'task' => 'Tâche',
            'research' => 'Recherche',
            'design' => 'Design',
            'testing' => 'Test',
            'documentation' => 'Documentation',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'urgent' => 'Urgente',
        ];

        $statuses = [
            'todo' => 'À faire',
            'in_progress' => 'En cours',
            'review' => 'En revue',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];

        $parentTasks = $project->tasks()->whereNull('parent_id')->get();

        return view('admin.tasks.create', compact('projects', 'users', 'taskTypes', 'priorities', 'parentTasks', 'project', 'statuses'));
    }

    /**
     * Formulaire de création sans projet spécifique
     */
    public function createWithoutProject()
    {
        $user = auth()->user();

        if (! $user->can('tasks.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une tâche.');
        }

        $projects = $this->accessibleProjects($user);
        $users = User::all();

        $taskTypes = [
            'feature' => 'Fonctionnalité',
            'bug' => 'Bug / Correction',
            'task' => 'Tâche',
            'research' => 'Recherche',
            'design' => 'Design',
            'testing' => 'Test',
            'documentation' => 'Documentation',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'urgent' => 'Urgente',
        ];

        $statuses = [
            'todo' => 'À faire',
            'in_progress' => 'En cours',
            'review' => 'En revue',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];

        $parentTasks = collect();

        return view('admin.tasks.create', compact('projects', 'users', 'taskTypes', 'priorities', 'parentTasks', 'statuses'));
    }

    /**
     * Enregistrer une tâche pour un projet spécifique
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        if (! $user->can('tasks.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une tâche.');
        }

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'task_type' => 'required|string',
            'priority' => 'required|string',
            'estimated_hours' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['project_id'] = $project->id;
        $validated['created_by'] = $user->id;
        $validated['status'] = Task::STATUS_TODO;

        DB::beginTransaction();

        try {
            $task = Task::create($validated);

            // Ajouter l'activité
            $task->project->activities()->create([
                'user_id' => $user->id,
                'activity_type' => 'task_created',
                'description' => "Tâche '{$task->title}' créée par ".$user->name,
                'metadata' => ['task_id' => $task->id],
            ]);

            DB::commit();

            return redirect()->route('admin.projects.tasks.index', $project->id)
                ->with('success', 'Tâche créée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la création: '.$e->getMessage());
        }
    }

    /**
     * Enregistrer une tâche sans projet spécifique
     */
    public function storeWithoutProject(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('tasks.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer une tâche.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'task_type' => 'required|string',
            'priority' => 'required|string',
            'estimated_hours' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['created_by'] = $user->id;
        $validated['status'] = Task::STATUS_TODO;

        DB::beginTransaction();

        try {
            $task = Task::create($validated);

            // Ajouter l'activité
            $task->project->activities()->create([
                'user_id' => $user->id,
                'activity_type' => 'task_created',
                'description' => "Tâche '{$task->title}' créée par ".$user->name,
                'metadata' => ['task_id' => $task->id],
            ]);

            DB::commit();

            return redirect()->route('admin.tasks.show', $task)
                ->with('success', 'Tâche créée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la création: '.$e->getMessage());
        }
    }

    /**
     * Afficher une tâche
     */
    public function show(Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette tâche.');
        }

        // Vérifier l'accès à la tâche
        if (! $user->can('tasks.view.all')) {
            $isAssignee = $task->assigned_to == $user->id;
            $isCreator = $task->created_by == $user->id;
            $isProjectManager = $task->project->project_manager_id == $user->id;

            if (! $isAssignee && ! $isCreator && ! $isProjectManager) {
                abort(403, 'Vous n\'avez pas accès à cette tâche.');
            }
        }

        $task->load(['assignee', 'creator', 'reviewer', 'comments.user', 'timeEntries.user', 'subtasks']);

        $totalTimeSpent = $task->timeEntries()->sum('hours');

        return view('admin.tasks.show', compact('task', 'totalTimeSpent'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette tâche.');
        }

        $projects = Project::active()->get();
        $users = User::all();

        $taskTypes = [
            'feature' => 'Fonctionnalité',
            'bug' => 'Bug / Correction',
            'task' => 'Tâche',
            'research' => 'Recherche',
            'design' => 'Design',
            'testing' => 'Test',
            'documentation' => 'Documentation',
        ];

        $statuses = [
            'todo' => 'À faire',
            'in_progress' => 'En cours',
            'review' => 'En revue',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'urgent' => 'Urgente',
        ];

        $parentTasks = $task->project->tasks()->whereNull('parent_id')->where('id', '!=', $task->id)->get();

        return view('admin.tasks.edit', compact('task', 'projects', 'users', 'taskTypes', 'statuses', 'priorities', 'parentTasks'));
    }

    /**
     * Mettre à jour une tâche
     */
    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier cette tâche.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'task_type' => 'required|string',
            'priority' => 'required|string',
            'status' => 'required|string',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $task->status;
            $task->update($validated);

            if ($oldStatus !== $task->status) {
                $task->project->activities()->create([
                    'user_id' => $user->id,
                    'activity_type' => 'task_status_changed',
                    'description' => "Statut de la tâche '{$task->title}' changé de {$oldStatus} à {$task->status}",
                    'metadata' => ['task_id' => $task->id],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.tasks.show', $task)
                ->with('success', 'Tâche mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la mise à jour: '.$e->getMessage());
        }
    }

    /**
     * Marquer une tâche comme terminée
     */
    public function markAsCompleted(Request $request, Task $task)
    {
        $user = auth()->user();

        $isAssignee = $task->assigned_to == $user->id;
        $isProjectManager = $task->project->project_manager_id == $user->id;

        if (! $isAssignee && ! $isProjectManager && ! $user->can('tasks.edit')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'completion_notes' => 'nullable|string',
        ]);

        $task->markAsCompleted($user->id, $request->completion_notes);

        return response()->json(['success' => true, 'message' => 'Tâche soumise pour revue.']);
    }

    /**
     * Approuver une tâche
     */
    public function approve(Request $request, Task $task)
    {
        $user = auth()->user();

        $isProjectManager = $task->project->project_manager_id == $user->id;

        if (! $isProjectManager && ! $user->can('tasks.approve')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'review_notes' => 'nullable|string',
        ]);

        $task->approveTask($user->id, $request->review_notes);

        return response()->json(['success' => true, 'message' => 'Tâche approuvée avec succès.']);
    }

    /**
     * Rejeter une tâche
     */
    public function reject(Request $request, Task $task)
    {
        $user = auth()->user();

        $isProjectManager = $task->project->project_manager_id == $user->id;

        if (! $isProjectManager && ! $user->can('tasks.approve')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'review_notes' => 'required|string|min:10',
        ]);

        $task->rejectTask($user->id, $request->review_notes);

        return response()->json(['success' => true, 'message' => 'Tâche rejetée.']);
    }

    /**
     * Supprimer une tâche
     */
    public function destroy(Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer cette tâche.');
        }

        $projectId = $task->project_id;
        $taskTitle = $task->title;

        $task->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Tâche '{$taskTitle}' supprimée.",
            ]);
        }

        return redirect()->route('admin.projects.tasks.index', $projectId)
            ->with('success', "Tâche '{$taskTitle}' supprimée.");
    }

    /**
     * Vue Kanban des tâches
     */
    public function kanban(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('tasks.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        $tasks = Task::where('project_id', $project->id)
            ->with('assignee')
            ->get()
            ->groupBy('status');

        $statuses = [
            'todo' => ['label' => 'À faire', 'color' => 'gray'],
            'in_progress' => ['label' => 'En cours', 'color' => 'blue'],
            'review' => ['label' => 'En revue', 'color' => 'yellow'],
            'approved' => ['label' => 'Approuvée', 'color' => 'green'],
            'rejected' => ['label' => 'Rejetée', 'color' => 'red'],
            'completed' => ['label' => 'Terminée', 'color' => 'purple'],
        ];

        // Garantir la présence de toutes les colonnes pour éviter "Undefined array key"
        $tasks = collect($statuses)->keys()->mapWithKeys(function ($statusKey) use ($tasks) {
            return [$statusKey => $tasks->get($statusKey, collect())];
        });

        return view('admin.tasks.kanban', compact('project', 'tasks', 'statuses'));
    }

    /**
     * Mettre à jour l'ordre et/ou le statut via Kanban.
     *
     * Deux modes sont supportés :
     *  - Changement de colonne (drag cross-colonne) :
     *      Payload : { task_id: int, status: string, order?: int }
     *  - Réordonnancement dans une même colonne :
     *      Payload : { tasks: [id1, id2, id3, ...], status?: string }
     */
    public function updateOrder(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas la permission de modifier une tâche.',
            ], 403);
        }

        $validStatuses = [
            Task::STATUS_TODO,
            Task::STATUS_IN_PROGRESS,
            Task::STATUS_REVIEW,
            Task::STATUS_APPROVED,
            Task::STATUS_REJECTED,
            Task::STATUS_COMPLETED,
            Task::STATUS_CANCELLED,
        ];

        $validated = $request->validate([
            'task_id' => 'nullable|integer|exists:tasks,id',
            'status' => ['nullable', 'string', 'in:'.implode(',', $validStatuses)],
            'order' => 'nullable|integer|min:0',
            'tasks' => 'nullable|array',
            'tasks.*' => 'integer|exists:tasks,id',
        ]);

        DB::beginTransaction();

        try {
            $updatedTask = null;

            // --- Mode 1 : changement de colonne (statut) ---
            if (! empty($validated['task_id']) && ! empty($validated['status'])) {
                $task = Task::findOrFail($validated['task_id']);

                // Vérification d'accès niveau tâche
                if (! $user->can('tasks.view.all')) {
                    $isAssignee = $task->assigned_to === $user->id;
                    $isCreator = $task->created_by === $user->id;
                    $isProjectManager = optional($task->project)->project_manager_id === $user->id;

                    if (! $isAssignee && ! $isCreator && ! $isProjectManager) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Vous n\'avez pas accès à cette tâche.',
                        ], 403);
                    }
                }

                $oldStatus = $task->status;

                $task->status = $validated['status'];
                if (array_key_exists('order', $validated) && $validated['order'] !== null) {
                    $task->order = (int) $validated['order'];
                }

                // Champs additionnels selon la transition
                if ($oldStatus !== $task->status) {
                    if ($task->status === Task::STATUS_IN_PROGRESS && empty($task->start_date)) {
                        $task->start_date = now();
                    }

                    if ($task->status === Task::STATUS_COMPLETED) {
                        $task->completed_at = $task->completed_at ?? now();
                        $task->completed_by = $task->completed_by ?? $user->id;
                        $task->is_approved = true;
                    }
                }

                $task->save();

                if ($oldStatus !== $task->status && $task->project) {
                    $task->project->activities()->create([
                        'user_id' => $user->id,
                        'activity_type' => 'task_status_changed',
                        'description' => "Statut de la tâche '{$task->title}' changé de ".
                                           ($oldStatus ?? '—')." à {$task->status} par {$user->name}",
                        'metadata' => [
                            'task_id' => $task->id,
                            'old_status' => $oldStatus,
                            'new_status' => $task->status,
                        ],
                    ]);
                }

                $updatedTask = $task->fresh(['assignee']);
            }

            // --- Mode 2 : réordonnancement (bulk) ---
            if (! empty($validated['tasks']) && is_array($validated['tasks'])) {
                foreach ($validated['tasks'] as $index => $taskId) {
                    $update = ['order' => $index];
                    if (! empty($validated['status'])) {
                        $update['status'] = $validated['status'];
                    }
                    Task::where('id', $taskId)->update($update);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tâche mise à jour.',
                'task' => $updatedTask ? [
                    'id' => $updatedTask->id,
                    'status' => $updatedTask->status,
                    'status_label' => $updatedTask->status_label,
                    'order' => $updatedTask->order,
                ] : null,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ajouter un commentaire
     */
    public function storeComment(Request $request, Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'comment' => 'required|string|min:2',
        ]);

        $comment = $task->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'parent_id' => null,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'comment' => $comment]);
        }

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Répondre à un commentaire
     */
    public function replyToComment(Request $request, Task $task, TaskComment $comment)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'comment' => 'required|string|min:2',
        ]);

        $reply = $task->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'parent_id' => $comment->id,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'reply' => $reply]);
        }

        return back()->with('success', 'Réponse ajoutée avec succès.');
    }

    /**
     * Supprimer un commentaire
     */
    public function deleteComment(TaskComment $comment)
    {
        $user = auth()->user();

        if ($comment->user_id !== $user->id && ! $user->can('tasks.delete')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $comment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Ajouter une entrée de temps
     */
    public function addTimeEntry(Request $request, Task $task)
    {
        $user = auth()->user();

        if (! $user->can('tasks.edit')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:24',
            'description' => 'nullable|string',
        ]);

        $timeEntry = $task->timeEntries()->create([
            'user_id' => $user->id,
            'project_id' => $task->project_id,
            'date' => $request->date,
            'hours' => $request->hours,
            'description' => $request->description,
            'is_billable' => true,
        ]);

        // Mettre à jour les heures réelles de la tâche
        $totalHours = $task->timeEntries()->sum('hours');
        $task->update(['actual_hours' => $totalHours]);

        // Mettre à jour les heures réelles du projet
        $projectTotalHours = $task->project->timeEntries()->sum('hours');
        $task->project->update(['actual_hours' => $projectTotalHours]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'timeEntry' => $timeEntry]);
        }

        return back()->with('success', 'Temps ajouté avec succès.');
    }

    /**
     * Supprimer une entrée de temps
     */
    public function deleteTimeEntry(TimeEntry $entry)
    {
        $user = auth()->user();

        if ($entry->user_id !== $user->id && ! $user->can('tasks.delete')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $task = $entry->task;
        $entry->delete();

        // Mettre à jour les heures réelles de la tâche
        $totalHours = $task->timeEntries()->sum('hours');
        $task->update(['actual_hours' => $totalHours]);

        // Mettre à jour les heures réelles du projet
        $projectTotalHours = $task->project->timeEntries()->sum('hours');
        $task->project->update(['actual_hours' => $projectTotalHours]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Entrée de temps supprimée avec succès.');
    }
}
