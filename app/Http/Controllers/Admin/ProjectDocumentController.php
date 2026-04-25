<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectDocumentController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->can('projects.edit') && ! $project->isMember($user->id)) {
            abort(403, 'Vous n\'avez pas la permission d\'ajouter des documents à ce projet.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:51200', // 50 MB max
        ]);

        $file = $request->file('file');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("project-documents/{$project->id}", $filename, 'local');

        $project->documents()->create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return back()->with('success', 'Document uploadé avec succès.')->withFragment('tab-documents');
    }

    public function download(Project $project, ProjectDocument $document): StreamedResponse
    {
        $user = auth()->user();

        if (! $user->can('projects.view.all') && ! $project->isMember($user->id)) {
            abort(403, 'Vous n\'avez pas accès aux documents de ce projet.');
        }

        abort_if($document->project_id !== $project->id, 404);

        if (! Storage::disk('local')->exists($document->path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function destroy(Project $project, ProjectDocument $document): RedirectResponse
    {
        $user = auth()->user();

        $canDelete = $user->can('projects.edit')
            || $document->user_id === $user->id
            || $project->project_manager_id === $user->id;

        if (! $canDelete) {
            abort(403, 'Vous n\'avez pas la permission de supprimer ce document.');
        }

        abort_if($document->project_id !== $project->id, 404);

        Storage::disk('local')->delete($document->path);
        $document->delete();

        return back()->with('success', 'Document supprimé.')->withFragment('tab-documents');
    }
}
