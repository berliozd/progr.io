<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competitor;
use App\Models\Project;
use App\Models\ProjectsNote;

class CompetitorController extends Controller
{
    public function destroy(string $id)
    {
        $competitor = Competitor::whereId($id)->first();
        $project = $competitor->project()->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $competitor->delete();
    }
}
