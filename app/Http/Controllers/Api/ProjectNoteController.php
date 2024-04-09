<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsNote;

class ProjectNoteController extends Controller
{
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
