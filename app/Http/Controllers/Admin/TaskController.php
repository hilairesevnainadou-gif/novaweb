<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TimeEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function globalIndex(Request $request): View
    {
        $query = Task::with(['project', 'assignedTo', 'createdBy']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if (! auth()->user()->can('tasks.view.all')) {
            $query->where('assigned_to', auth()->id());
        }

        $tasks = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'       => Task::count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'review'      => Task::where('status', 'review')->count(),
            'overdue'     => Task::overdue()->count(),
        ];

        $projects = Project::orderBy('name')->get(['id', 'name']);

        return view('admin.tasks.index', compact('tasks', 'stats', 'projects'));
    }

    public function index(Project $project, Request $request): View
    {
        $query = $project->tasks()->with(['assignedTo', 'createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.tasks.index', compact('project', 'tasks'));
    }

    public function kanban(Project $project): View
    {
        $columns = [
            'todo'        => Task::where('project_id', $project->id)->where('status', 'todo')->with('assignedTo')->orderBy('order')->get(),
            'in_progress' => Task::where('project_id', $project->id)->where('status', 'in_progress')->with('assignedTo')->orderBy('order')->get(),
            'review'      => Task::where('project_id', $project->id)->where('status', 'review')->with('assignedTo')->orderBy('order')->get(),
            'done'        => Task::where('project_id', $project->id)->where('status', 'done')->with('assignedTo')->orderBy('order')->get(),
        ];

        $project->load(['members', 'manager']);

        return view('admin.tasks.kanban', compact('project', 'columns'));
    }

    public function create(Project $project): View
    {
        $users   = $project->members()->get()->push($project->manager)->filter()->unique('id');
        $tasks   = $project->tasks()->whereNull('parent_id')->get(['id', 'title']);

        return view('admin.tasks.create', compact('project', 'users', 'tasks'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'assigned_to'      => ['nullable', 'exists:users,id'],
            'parent_id'        => ['nullable', 'exists:tasks,id'],
            'status'           => ['required', 'in:todo,in_progress,review,done,cancelled'],
            'priority'         => ['required', 'in:low,medium,high,urgent'],
            'category'         => ['nullable', 'string', 'max:100'],
            'due_date'         => ['nullable', 'date'],
            'estimated_hours'  => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['project_id'] = $project->id;
        $validated['created_by'] = auth()->id();
        $validated['order']      = $project->tasks()->where('status', $validated['status'])->max('order') + 1;

        $task = Task::create($validated);

        $project->logActivity('created', "Tâche « {$task->title} » créée");

        return redirect()->route('admin.projects.tasks.show', [$project, $task])
            ->with('success', "Tâche créée avec succès.");
    }

    public function show(Project $project, Task $task): View
    {
        $task->load(['assignedTo', 'createdBy', 'approvedBy', 'parent', 'subtasks.assignedTo', 'comments.user', 'timeEntries.user', 'attachments.user']);

        return view('admin.tasks.show', compact('project', 'task'));
    }

    public function edit(Project $project, Task $task): View
    {
        $users = $project->members()->get()->push($project->manager)->filter()->unique('id');
        $tasks = $project->tasks()->whereNull('parent_id')->where('id', '!=', $task->id)->get(['id', 'title']);

        return view('admin.tasks.create', compact('project', 'task', 'users', 'tasks'));
    }

    public function update(Request $request, Project $project, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'assigned_to'     => ['nullable', 'exists:users,id'],
            'parent_id'       => ['nullable', 'exists:tasks,id'],
            'status'          => ['required', 'in:todo,in_progress,review,done,cancelled'],
            'priority'        => ['required', 'in:low,medium,high,urgent'],
            'category'        => ['nullable', 'string', 'max:100'],
            'due_date'        => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validated['status'] === 'done' && $task->status !== 'done') {
            $validated['completed_at'] = now()->toDateString();
        }

        $task->update($validated);

        $project->logActivity('updated', "Tâche « {$task->title} » mise à jour");

        return redirect()->route('admin.projects.tasks.show', [$project, $task])
            ->with('success', "Tâche mise à jour avec succès.");
    }

    public function approve(Request $request, Project $project, Task $task): RedirectResponse
    {
        $task->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'status'      => 'done',
            'completed_at' => now()->toDateString(),
        ]);

        $project->logActivity('approved', "Tâche « {$task->title} » approuvée");

        return back()->with('success', "Tâche approuvée avec succès.");
    }

    public function reject(Request $request, Project $project, Task $task): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'max:1000']]);

        $task->update([
            'is_approved'      => false,
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'status'           => 'in_progress',
            'rejection_reason' => $request->rejection_reason,
        ]);

        $project->logActivity('rejected', "Tâche « {$task->title} » rejetée");

        return back()->with('success', "Tâche rejetée, remise en cours.");
    }

    public function destroy(Project $project, Task $task): JsonResponse
    {
        $task->delete();

        $project->logActivity('deleted', "Tâche « {$task->title} » supprimée");

        return response()->json(['success' => true, 'message' => 'Tâche supprimée avec succès.']);
    }

    public function storeComment(Request $request, Project $project, Task $task): RedirectResponse
    {
        $request->validate([
            'content'     => ['required', 'string'],
            'is_internal' => ['boolean'],
        ]);

        TaskComment::create([
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'content'     => $request->content,
            'is_internal' => $request->boolean('is_internal'),
        ]);

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function storeTimeEntry(Request $request, Project $project, Task $task): RedirectResponse
    {
        $request->validate([
            'description' => ['nullable', 'string'],
            'date'        => ['required', 'date'],
            'minutes'     => ['required', 'integer', 'min:1'],
            'is_billable' => ['boolean'],
        ]);

        TimeEntry::create([
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'description' => $request->description,
            'date'        => $request->date,
            'minutes'     => $request->minutes,
            'is_billable' => $request->boolean('is_billable', true),
        ]);

        return back()->with('success', 'Temps enregistré.');
    }

    public function updateStatus(Request $request, Project $project, Task $task): JsonResponse
    {
        $request->validate(['status' => ['required', 'in:todo,in_progress,review,done,cancelled']]);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
}
