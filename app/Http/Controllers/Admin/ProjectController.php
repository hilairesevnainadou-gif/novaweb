<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total'     => Project::count(),
            'active'    => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'overdue'   => Project::whereNotNull('end_date')
                ->where('end_date', '<', now()->toDateString())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
        ];

        $recentProjects = Project::with(['client', 'manager', 'tasks'])
            ->latest()
            ->take(6)
            ->get();

        $upcomingDeadlines = Project::with(['client'])
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now()->toDateString())
            ->whereIn('status', ['planning', 'active', 'on_hold'])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        $statusBreakdown = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.projects.dashboard', compact(
            'stats',
            'recentProjects',
            'upcomingDeadlines',
            'statusBreakdown'
        ));
    }

    public function index(Request $request): View
    {
        $query = Project::with(['client', 'manager', 'tasks']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('project_number', 'like', "%{$search}%");
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
            $query->where('client_id', $request->client_id);
        }

        if (! auth()->user()->can('projects.view.all')) {
            $userId = auth()->id();
            $query->where(function ($q) use ($userId) {
                $q->where('manager_id', $userId)
                    ->orWhereHas('members', fn ($m) => $m->where('user_id', $userId));
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'     => Project::count(),
            'active'    => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold'   => Project::where('status', 'on_hold')->count(),
        ];

        $clients = Client::orderBy('name')->get(['id', 'company_name', 'name']);

        return view('admin.projects.index', compact('projects', 'stats', 'clients'));
    }

    public function create(): View
    {
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $users   = User::orderBy('name')->get(['id', 'name', 'avatar']);

        return view('admin.projects.create', compact('clients', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'client_id'   => ['nullable', 'exists:clients,id'],
            'manager_id'  => ['nullable', 'exists:users,id'],
            'status'      => ['required', 'in:planning,active,on_hold,completed,cancelled'],
            'priority'    => ['required', 'in:low,medium,high,urgent'],
            'type'        => ['required', 'in:web,mobile,design,consulting,maintenance,other'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget'      => ['nullable', 'numeric', 'min:0'],
            'color'       => ['nullable', 'string', 'max:7'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:50'],
            'is_billable' => ['boolean'],
            'members'     => ['nullable', 'array'],
            'members.*'   => ['exists:users,id'],
        ]);

        $validated['is_billable'] = $request->boolean('is_billable', true);
        $validated['progress']    = 0;

        $project = Project::create($validated);

        if (! empty($validated['members'])) {
            $project->members()->sync($validated['members']);
        }

        $project->logActivity('created', "Projet « {$project->name} » créé");

        return redirect()->route('admin.projects.show', $project)
            ->with('success', "Projet « {$project->name} » créé avec succès.");
    }

    public function show(Project $project): View
    {
        $project->load([
            'client',
            'manager',
            'members',
            'tasks.assignedTo',
            'meetings.organizer',
            'activities.user',
        ]);

        $taskStats = [
            'total'       => $project->tasks->count(),
            'todo'        => $project->tasks->where('status', 'todo')->count(),
            'in_progress' => $project->tasks->where('status', 'in_progress')->count(),
            'review'      => $project->tasks->where('status', 'review')->count(),
            'done'        => $project->tasks->where('status', 'done')->count(),
        ];

        $upcomingMeetings = $project->meetings()
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->take(3)
            ->get();

        return view('admin.projects.show', compact('project', 'taskStats', 'upcomingMeetings'));
    }

    public function edit(Project $project): View
    {
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $users   = User::orderBy('name')->get(['id', 'name', 'avatar']);

        $project->load('members');

        return view('admin.projects.create', compact('project', 'clients', 'users'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'client_id'      => ['nullable', 'exists:clients,id'],
            'manager_id'     => ['nullable', 'exists:users,id'],
            'status'         => ['required', 'in:planning,active,on_hold,completed,cancelled'],
            'priority'       => ['required', 'in:low,medium,high,urgent'],
            'type'           => ['required', 'in:web,mobile,design,consulting,maintenance,other'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget'         => ['nullable', 'numeric', 'min:0'],
            'progress'       => ['nullable', 'integer', 'min:0', 'max:100'],
            'color'          => ['nullable', 'string', 'max:7'],
            'technologies'   => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:50'],
            'is_billable'    => ['boolean'],
            'members'        => ['nullable', 'array'],
            'members.*'      => ['exists:users,id'],
            'notes'          => ['nullable', 'string'],
        ]);

        $validated['is_billable'] = $request->boolean('is_billable', true);

        $oldStatus = $project->status;

        $project->update($validated);

        if (array_key_exists('members', $validated)) {
            $project->members()->sync($validated['members'] ?? []);
        }

        if ($oldStatus !== $project->status) {
            $project->logActivity('updated', "Statut changé de « {$oldStatus} » à « {$project->status} »");
        } else {
            $project->logActivity('updated', "Projet « {$project->name} » mis à jour");
        }

        return redirect()->route('admin.projects.show', $project)
            ->with('success', "Projet mis à jour avec succès.");
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['success' => true, 'message' => 'Projet supprimé avec succès.']);
    }
}
