<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;


class DashboardController extends Controller
{
    const RECENT_PROJECTS_LIMIT = 5;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render(
            'App/Dashboard',
            $this->getData()
        );
    }

    public function getData(): array
    {
        $projects = Project::addSelect(
            ['status_label' => ProjectsStatus::select('label')->whereColumn('id', 'projects.status')->limit(1)]
        )
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('updated_at')
            ->get();

        return [
            'invoices' => auth()->user()?->invoices(),
            'projects' => $projects->take(self::RECENT_PROJECTS_LIMIT)->values(),
            'projectsCount' => $projects->count(),
        ];
    }
}
