<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsNote;
use Illuminate\Support\Facades\Log;

class ProjectNoteController extends Controller
{
    public function destroy(string $id)
    {
        Log::debug(sprintf('trying to delete project note %s', $id));
        $note = ProjectsNote::whereId($id)->first();
        Log::debug($note->toArray());
        $project = Project::where('id', $note->project_id)->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $note->delete();
        Log::debug('deleted');
    }
}
