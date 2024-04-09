<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competitor;

class CompetitorController extends Controller
{
    public function index($projectId)
    {
        return Competitor::where('project_id', $projectId)->get();
    }
}
