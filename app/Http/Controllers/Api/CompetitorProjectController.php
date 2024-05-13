<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompetitorProject;

class CompetitorProjectController extends Controller
{
    public function destroy(string $id)
    {
        $competitorProject = CompetitorProject::whereId($id)->first();
        $project = $competitorProject->project()->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $competitorProject->project()->detach($id);
    }
}
