<?php

use App\Http\Controllers\Api\AiAssistantController;
use App\Http\Controllers\Api\AutoPopulationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompetitorController;
use App\Http\Controllers\Api\CompetitorNoteController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectNoteController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Api\ProjectVisibilityController;
use App\Http\Controllers\Api\SendMailController;
use App\Http\Controllers\Api\UserController;
use App\Models\CompetitorProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/projects/public/list', [ProjectController::class, 'list']);

Route::middleware('auth:sanctum')->group(function () {
    // projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::patch('/projects/{id}', [ProjectController::class, 'update']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // project_status
    Route::apiResource('projects_status', ProjectStatusController::class);
    // project_visibilities
    Route::apiResource('projects_visibilities', ProjectVisibilityController::class);
    // auto populations
    Route::apiResource('auto_populations', AutoPopulationController::class);

    // projects_notes
    Route::delete('/projects_notes/{id}', [ProjectNoteController::class, 'destroy']);

    // competitors_notes
    Route::delete('/competitors_notes/{id}', [CompetitorNoteController::class, 'destroy']);

    // competitors
    Route::delete('/competitors/{id}', [CompetitorController::class, 'destroy']);
    Route::delete('/competitor_project/{id}', [CompetitorProject::class, 'destroy']);

    // AI
    Route::post('/ai', [AiAssistantController::class, 'ask']);
    Route::post('/ai/ideas', [AiAssistantController::class, 'askIdeas']);
    Route::post('/ai/note', [AiAssistantController::class, 'askNote']);
    Route::post('/ai/competitors', [AiAssistantController::class, 'askCompetitors']);

    // Mail send
    Route::post('/mail', [SendMailController::class, 'send']);

    // users
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::get('/user/{id}', [UserController::class, 'show']);
});
