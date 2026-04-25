<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->can('projects.edit')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les membres du projet.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|max:100',
        ]);

        $memberId = (int) $request->user_id;

        if ($project->project_manager_id === $memberId) {
            return back()->with('error', 'Cet utilisateur est déjà chef de projet.')->withFragment('tab-team');
        }

        $project->members()->syncWithoutDetaching([
            $memberId => [
                'role' => $request->role,
                'added_by' => $user->id,
            ],
        ]);

        return back()->with('success', 'Membre ajouté au projet.')->withFragment('tab-team');
    }

    public function destroy(Project $project, User $member): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->can('projects.edit')) {
            abort(403, 'Vous n\'avez pas la permission de retirer des membres du projet.');
        }

        $project->members()->detach($member->id);

        return back()->with('success', 'Membre retiré du projet.')->withFragment('tab-team');
    }
}
