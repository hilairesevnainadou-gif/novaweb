<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function globalIndex(Request $request): View
    {
        $query = Meeting::with(['project', 'organizer']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if (! auth()->user()->can('meetings.view.all')) {
            $userId = auth()->id();
            $query->where(function ($q) use ($userId) {
                $q->where('organizer_id', $userId)
                    ->orWhereHas('participants', fn ($p) => $p->where('user_id', $userId));
            });
        }

        $meetings = $query->orderBy('scheduled_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Meeting::count(),
            'upcoming'  => Meeting::upcoming()->count(),
            'completed' => Meeting::completed()->count(),
            'today'     => Meeting::whereDate('scheduled_at', today())->count(),
        ];

        $projects = Project::orderBy('name')->get(['id', 'name']);

        return view('admin.meetings.global-index', compact('meetings', 'stats', 'projects'));
    }

    public function index(Project $project, Request $request): View
    {
        $query = $project->meetings()->with('organizer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $meetings = $query->orderBy('scheduled_at', 'desc')->paginate(10);

        $stats = [
            'total'    => $project->meetings()->count(),
            'upcoming' => $project->meetings()->upcoming()->count(),
            'done'     => $project->meetings()->completed()->count(),
        ];

        return view('admin.meetings.index', compact('project', 'meetings', 'stats'));
    }

    public function create(Project $project): View
    {
        $users = $project->members()->get()->push($project->manager)->filter()->unique('id');

        return view('admin.meetings.create', compact('project', 'users'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'type'             => ['required', 'in:kickoff,weekly,review,demo,retrospective,emergency,other'],
            'scheduled_at'     => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:5'],
            'location'         => ['nullable', 'string', 'max:255'],
            'meeting_url'      => ['nullable', 'url', 'max:500'],
            'meeting_mode'     => ['required', 'in:in_person,online,hybrid'],
            'agenda'           => ['nullable', 'string'],
            'participants'     => ['nullable', 'array'],
            'participants.*'   => ['exists:users,id'],
        ]);

        $validated['project_id']   = $project->id;
        $validated['organizer_id'] = auth()->id();
        $validated['status']       = 'scheduled';

        $meeting = Meeting::create($validated);

        if (! empty($validated['participants'])) {
            $meeting->participants()->sync($validated['participants']);
        }

        $project->logActivity('created', "Réunion « {$meeting->title} » planifiée");

        return redirect()->route('admin.projects.meetings.show', [$project, $meeting])
            ->with('success', "Réunion planifiée avec succès.");
    }

    public function show(Project $project, Meeting $meeting): View
    {
        $meeting->load(['organizer', 'participants']);

        return view('admin.meetings.show', compact('project', 'meeting'));
    }

    public function edit(Project $project, Meeting $meeting): View
    {
        $users = $project->members()->get()->push($project->manager)->filter()->unique('id');
        $meeting->load('participants');

        return view('admin.meetings.edit', compact('project', 'meeting', 'users'));
    }

    public function update(Request $request, Project $project, Meeting $meeting): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'type'             => ['required', 'in:kickoff,weekly,review,demo,retrospective,emergency,other'],
            'status'           => ['required', 'in:scheduled,in_progress,completed,cancelled,postponed'],
            'scheduled_at'     => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:5'],
            'location'         => ['nullable', 'string', 'max:255'],
            'meeting_url'      => ['nullable', 'url', 'max:500'],
            'meeting_mode'     => ['required', 'in:in_person,online,hybrid'],
            'agenda'           => ['nullable', 'string'],
            'minutes'          => ['nullable', 'string'],
            'action_items'     => ['nullable', 'string'],
            'participants'     => ['nullable', 'array'],
            'participants.*'   => ['exists:users,id'],
        ]);

        $meeting->update($validated);

        if (array_key_exists('participants', $validated)) {
            $meeting->participants()->sync($validated['participants'] ?? []);
        }

        $project->logActivity('updated', "Réunion « {$meeting->title} » mise à jour");

        return redirect()->route('admin.projects.meetings.show', [$project, $meeting])
            ->with('success', "Réunion mise à jour avec succès.");
    }

    public function destroy(Project $project, Meeting $meeting): JsonResponse
    {
        $meeting->delete();

        $project->logActivity('deleted', "Réunion « {$meeting->title} » supprimée");

        return response()->json(['success' => true, 'message' => 'Réunion supprimée avec succès.']);
    }
}
