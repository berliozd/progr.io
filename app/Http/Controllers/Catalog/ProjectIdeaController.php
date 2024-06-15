<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectsVisibility;
use App\Services\SeoService;
use Illuminate\Http\Request;

class ProjectIdeaController extends Controller
{
    public function __construct(private readonly SeoService $seoService)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function show(Request $request, string $category, string $title, int $id)
    {
        $project = Project::whereId($id)
            ->with('metaDescription')
            ->with('metaKeywords')
            ->first();
        $visibility = ProjectsVisibility::whereId($project->visibility)->first();
        if ($visibility->code === 'public') {
            return View('catalog.idea', ['project' => $project]);
        } else {
            return redirect()->route('home');
        }
    }

    public function index()
    {
        return View(
            'catalog.ideas',
            [
                'categories' => Category::orderBy('code')->get(),
                'projects' => $this->getProjects(),
                'keywords' => $this->seoService->getKeywords(),
                'description' => $this->seoService->getDescription(),
            ]
        );
    }

    public function category(Request $request, string $categoryCode)
    {
        return View(
            'catalog.ideas',
            [
                'categories' => Category::orderBy('code')->get(),
                'projects' =>$this->getProjects($categoryCode),
                'categoryCode' => $categoryCode,
                'keywords' => $this->seoService->getKeywords($categoryCode),
                'description' => $this->seoService->getDescription($categoryCode),
            ]
        );
    }

    private function getProjects(string $categoryCode = null)
    {
        \Log::info($categoryCode);
        $projects = Project::where('visibility', ProjectsVisibility::where('code', 'public')->first()->id)
            ->whereNotNull('category_id')
            ->with('category')
            ->orderBy('updated_at', 'desc');
        if ($categoryCode !== null) {
            \Log::info('Filtering projects by category ' . $categoryCode);;
            $projects->where('category_id', Category::where('code', $categoryCode)->first()->id);
        }

        return $projects->get();
    }
}
