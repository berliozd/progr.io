<?php

use App\Http\Controllers\Api\AiAssistantController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Api\UserSettingsController;
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


Route::middleware('auth:sanctum')->group(function () {

    // user_settings
    Route::patch('/user_settings/{id}', [UserSettingsController::class, 'update']);
    Route::get('/user_settings/{id}', [UserSettingsController::class, 'show']);

    // projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::patch('/projects/{id}', [ProjectController::class, 'update']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // project_status
    Route::apiResource('projects_status', ProjectStatusController::class);

    Route::post('/ai', [AiAssistantController::class, 'ask']);
});
