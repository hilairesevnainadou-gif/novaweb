<?php

// app/Http/Controllers/Admin/ProjectController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Tableau de bord des projets
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Vérification des permissions
        $canViewAllProjects = $user->can('projects.view.all');
        $canViewProjects = $user->can('projects.view');

        if (! $canViewProjects) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        if ($canViewAllProjects) {
            $projects = Project::with('projectManager')->active()->get();
            $pendingReviewTasks = Task::pendingReview()->with(['project', 'assignee'])->get();
        } else {
            // L'utilisateur ne voit que les projets où il est chef de projet ou assigné à des tâches
            $projects = Project::where('project_manager_id', $user->id)
                ->orWhereHas('tasks', function ($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                })
                ->active()
                ->get();
            $pendingReviewTasks = Task::pendingReview()
                ->whereHas('project', function ($q) use ($user) {
                    $q->where('project_manager_id', $user->id);
                })
                ->get();
        }

        $stats = [
            'total_projects' => Project::active()->count(),
            'in_progress_projects' => Project::byStatus('in_progress')->count(),
            'completed_projects' => Project::byStatus('completed')->count(),
            'overdue_projects' => Project::where('end_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', Task::STATUS_COMPLETED)->count(),
            'meetings_today' => Meeting::today()->count(),
        ];

        $recentActivities = ProjectActivity::with(['project', 'user'])
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.projects.dashboard', compact('projects', 'stats', 'recentActivities', 'pendingReviewTasks'));
    }

    /**
     * Afficher la liste des projets
     */
    public function index(Request $request)
    {
        $user       = auth()->user();
        $canViewAll = $user->can('projects.view.all');

        if (! $user->can('projects.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        // Contrainte de scope (projets concernant l'utilisateur si pas de vue globale)
        $scopeQuery = function ($q) use ($user, $canViewAll): void {
            if (! $canViewAll) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('project_manager_id', $user->id)
                          ->orWhereHas('projectMembers', fn ($mq) => $mq->where('user_id', $user->id))
                          ->orWhereHas('tasks', fn ($tq) => $tq->where('assigned_to', $user->id)
                              ->orWhere('created_by', $user->id));
                });
            }
        };

        $query = Project::with(['client', 'projectManager'])->tap($scopeQuery);

        // Filtres
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('project_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }

        if ($request->filled('project_manager_id') && $canViewAll) {
            $query->where('project_manager_id', $request->project_manager_id);
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        // Statistiques scoped au périmètre de l'utilisateur
        $sb    = Project::query()->tap($scopeQuery);
        $stats = [
            'total'     => (clone $sb)->count(),
            'active'    => (clone $sb)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'completed' => (clone $sb)->where('status', 'completed')->count(),
            'on_hold'   => (clone $sb)->where('status', 'review')->count(),
        ];

        $statuses = [
            'planning'    => 'Planification',
            'in_progress' => 'En cours',
            'review'      => 'En revue',
            'completed'   => 'Terminé',
            'cancelled'   => 'Annulé',
        ];

        $types = [
            'web'      => 'Site Web / App Web',
            'mobile'   => 'Application Mobile',
            'software' => 'Logiciel Desktop',
            'other'    => 'Autre',
        ];

        $priorities = [
            'low'      => 'Basse',
            'medium'   => 'Moyenne',
            'high'     => 'Haute',
            'critical' => 'Critique',
        ];

        $clients = Client::active()->orderBy('name')->get();

        $projectManagers = User::permission(['projects.view.all', 'projects.create', 'projects.edit'])->get();

        $filters = [
            'search'             => $request->string('search')->toString(),
            'status'             => $request->string('status')->toString(),
            'priority'           => $request->string('priority')->toString(),
            'type'               => $request->string('type')->toString(),
            'client_id'          => $request->string('client_id')->toString(),
            'project_manager_id' => $request->string('project_manager_id')->toString(),
        ];

        return view('admin.projects.index', compact(
            'projects', 'statuses', 'types', 'priorities',
            'projectManagers', 'stats', 'clients', 'filters', 'canViewAll'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = auth()->user();

        if (! $user->can('projects.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer un projet.');
        }

        $clients = Client::active()->get();

        // Récupérer les utilisateurs qui peuvent être chefs de projet (via rôles ou permissions directes)
        $projectManagers = User::permission(['projects.view.all', 'projects.create', 'projects.edit'])->get();

        $statuses = [
            'planning' => 'Planification',
            'in_progress' => 'En cours',
            'review' => 'En revue',
        ];

        $types = [
            'web' => 'Site Web / Application Web',
            'mobile' => 'Application Mobile',
            'software' => 'Logiciel Desktop',
            'other' => 'Autre',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'critical' => 'Critique',
        ];

        return view('admin.projects.create', compact('clients', 'projectManagers', 'statuses', 'types', 'priorities'));
    }

    /**
     * Enregistrer un nouveau projet
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('projects.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer un projet.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:web,mobile,software,other',
            'client_id' => 'nullable|exists:clients,id',
            'project_manager_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'priority' => 'required|string|in:low,medium,high,critical',
            'repository_url' => 'nullable|url',
            'production_url' => 'nullable|url',
            'staging_url' => 'nullable|url',
            'technologies' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $validated['status'] = 'planning';
            $validated['is_active'] = true;
            $validated['progress_percentage'] = 0;

            $project = Project::create($validated);

            // Ajouter l'activité
            ProjectActivity::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'activity_type' => 'project_created',
                'description' => "Projet '{$project->name}' créé par ".$user->name,
            ]);

            DB::commit();

            return redirect()->route('admin.projects.index')
                ->with('success', 'Projet créé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la création du projet: '.$e->getMessage());
        }
    }

    /**
     * Afficher les détails d'un projet
     */
    public function show(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('projects.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir ce projet.');
        }

        // Vérifier si l'utilisateur a accès à ce projet spécifique
        if (! $user->can('projects.view.all') && ! $project->isMember($user->id)) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $project->load([
            'client',
            'projectManager',
            'tasks.assignee',
            'meetings',
            'members',
            'documents.uploader',
        ]);

        $activities = $project->activities()
            ->with('user')
            ->latest()
            ->paginate(15, ['*'], 'act_page')
            ->withQueryString();

        // Statistiques
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', Task::STATUS_COMPLETED)->count(),
            'in_progress_tasks' => $project->tasks()->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'pending_review_tasks' => $project->tasks()->where('status', Task::STATUS_REVIEW)->count(),
            'overdue_tasks' => $project->tasks()->overdue()->count(),
            'total_time_spent' => $project->tasks()->sum('actual_hours'),
            'upcoming_meetings' => $project->meetings()->upcoming()->get(),
        ];

        // Graphique des tâches par statut
        $taskStatsByStatus = $project->tasks()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $allUsers = User::orderBy('name')->get();
        $memberIds = $project->members->pluck('id')->push($project->project_manager_id)->filter()->unique();
        $nonMembers = $allUsers->whereNotIn('id', $memberIds->toArray())->values();

        return view('admin.projects.show', compact('project', 'stats', 'taskStatsByStatus', 'nonMembers', 'activities'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('projects.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier ce projet.');
        }

        if (! $user->can('projects.view.all') && $project->project_manager_id !== $user->id) {
            abort(403, 'Vous ne pouvez modifier que vos propres projets.');
        }

        $clients = Client::active()->get();

        $projectManagers = User::permission(['projects.view.all', 'projects.create', 'projects.edit'])->get();

        $statuses = [
            'planning' => 'Planification',
            'in_progress' => 'En cours',
            'review' => 'En revue',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
        ];

        $types = [
            'web' => 'Site Web / Application Web',
            'mobile' => 'Application Mobile',
            'software' => 'Logiciel Desktop',
            'other' => 'Autre',
        ];

        $priorities = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
            'critical' => 'Critique',
        ];

        return view('admin.projects.edit', compact('project', 'clients', 'projectManagers', 'statuses', 'types', 'priorities'));
    }

    /**
     * Mettre à jour un projet
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        if (! $user->can('projects.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier ce projet.');
        }

        if (! $user->can('projects.view.all') && $project->project_manager_id !== $user->id) {
            abort(403, 'Vous ne pouvez modifier que vos propres projets.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:web,mobile,software,other',
            'client_id' => 'nullable|exists:clients,id',
            'project_manager_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:planning,in_progress,review,completed,cancelled',
            'priority' => 'required|string|in:low,medium,high,critical',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'repository_url' => 'nullable|url',
            'production_url' => 'nullable|url',
            'staging_url' => 'nullable|url',
            'technologies' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $project->status;
            $project->update($validated);

            // Log du changement de statut
            if ($oldStatus !== $project->status) {
                ProjectActivity::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'activity_type' => 'status_changed',
                    'description' => "Statut du projet changé de {$oldStatus} à {$project->status} par ".$user->name,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.projects.show', $project)
                ->with('success', 'Projet mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erreur lors de la mise à jour: '.$e->getMessage());
        }
    }

    /**
     * Supprimer un projet
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('projects.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer ce projet.');
        }

        if (! $user->can('projects.view.all') && $project->project_manager_id !== $user->id) {
            abort(403, 'Vous ne pouvez supprimer que vos propres projets.');
        }

        DB::beginTransaction();

        try {
            $projectName = $project->name;
            $project->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Projet '{$projectName}' supprimé avec succès.",
                ]);
            }

            return redirect()->route('admin.projects.index')
                ->with('success', "Projet '{$projectName}' supprimé avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression: '.$e->getMessage());
        }
    }

    /**
     * Mettre à jour la progression
     */
    public function updateProgress(Request $request, Project $project)
    {
        $user = auth()->user();

        if (! $user->can('projects.edit')) {
            return response()->json(['error' => 'Permission refusée'], 403);
        }

        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $project->update(['progress_percentage' => $request->progress_percentage]);

        ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_type' => 'progress_updated',
            'description' => "Progression mise à jour à {$request->progress_percentage}% par ".$user->name,
        ]);

        return response()->json(['success' => true]);
    }
}
