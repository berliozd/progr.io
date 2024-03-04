<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectsStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::debug(__CLASS__ . '/' . __FUNCTION__ . ' user : ' . auth()->user()->id);
        return Project::addSelect(
            ['status_label' => ProjectsStatus::select('label')->whereColumn('id', 'projects.status')->limit(1)]
        )
            ->where('user_id', auth()->user()->id)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Log::debug(__CLASS__ . '/' . __FUNCTION__);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Log::debug(__CLASS__ . '/' . __FUNCTION__ . '/' . $id);
        return Project::where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::debug(__CLASS__ . '/' . __FUNCTION__);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::debug(__CLASS__ . '/' . __FUNCTION__);
    }
}
