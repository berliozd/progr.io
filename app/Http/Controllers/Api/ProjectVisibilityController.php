<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectsVisibility;
use Illuminate\Database\Eloquent\Collection;

class ProjectVisibilityController extends Controller
{
    public function index()
    {
        return $this->getVisibilities();
    }

    private function getVisibilities(): Collection
    {
        $visibilities = ProjectsVisibility::all();
        foreach ($visibilities as $visibility) {
            $visibility->label = trans('app.project.visibilities.' . $visibility->code);
        }
        return $visibilities;
    }
}
