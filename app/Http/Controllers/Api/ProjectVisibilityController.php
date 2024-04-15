<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectsVisibility;

class ProjectVisibilityController extends Controller
{
    public function index()
    {
        return ProjectsVisibility::all();
    }
}
