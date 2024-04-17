<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsVisibility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectPresentationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $id)
    {
        $project = Project::whereId($id)->first();
        $visibility = ProjectsVisibility::whereId($project->visibility)->first();

        if ($visibility->code === 'public') {
            return Inertia::render('App/ProjectPres', ['id' => $project->id]);
        }

        if ($visibility->code === 'private') {
            if (auth()->user() && auth()->user()->id === $project->user_id) {
                return Inertia::render('App/ProjectPres', ['id' => $project->id]);
            }
            return Inertia::render('App/ProjectUnauthorized', ['code' => $visibility->code]);
        }


        if ($visibility->code === 'only_members') {
            if (auth()->user()) {
                return Inertia::render('App/ProjectPres', ['id' => $project->id]);
            }
            return Inertia::render('App/ProjectUnauthorized', ['code' => $visibility->code]);
        }
    }
}
