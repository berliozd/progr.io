<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsVisibility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectIdeaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function show(Request $request, string $category, string $title, int $id)
    {
        $project = Project::whereId($id)->first();
        $visibility = ProjectsVisibility::whereId($project->visibility)->first();

        if ($visibility->code === 'public') {
            return Inertia::render('Catalog/ProjectIdea', ['id' => $project->id]);
        } else {
            return redirect()->route('home');
        }
    }

    public function index(Request $request)
    {
        return Inertia::render('Catalog/ProjectIdeas');
    }

    public function category(Request $request, string $categoryCode)
    {
        return Inertia::render('Catalog/ProjectIdeas', ['categoryCode' => $categoryCode]);
    }
}
