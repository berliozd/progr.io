<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competitor;
use Illuminate\Http\Request;


class CompetitorController extends Controller
{
    public function index($projectId)
    {
        return Competitor::where('project_id', $projectId)->get();
    }


    public function store($projectId, Request $request)
    {
        $rawData = $request->toArray();
        $data = [
            'project_id' => $projectId,
            'name' => $rawData['name'],
            'description' => $rawData['description'],
            'url' => $rawData['url']
        ];
        return Competitor::create($data);
    }
}
