<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotesType;
use App\Models\Project;
use App\Models\ProjectsNote;
use Illuminate\Http\Request;

class ProjectNoteController extends Controller
{
    public function index(string $projectId)
    {
        $project = Project::where('id', $projectId)->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        return ProjectsNote::addSelect(
            ['type' => NotesType::select('label')->whereColumn('id', 'projects_notes.note_type_id')->limit(1)]
        )->where('project_id', $projectId)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = ProjectsNote::whereId($id)->first();
        $project = Project::where('id', $note->project_id)->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $note->delete();
    }
}
