<?php

use App\Http\Controllers\App\ProjectPresentationController;
use App\Http\Controllers\Catalog\ProjectIdeaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render(
        'Home/Welcome',
        ['canLogin' => Route::has('login'), 'canRegister' => Route::has('register')]
    );
})->name('home');

Route::inertia('/terms', 'Home/Terms')->name('terms');
Route::inertia('/privacy-policy', 'Home/Privacy')->name('privacy-policy');
Route::get('/project-presentation/{id}', ProjectPresentationController::class)->name('app.projects.presentation');
Route::get('/project-ideas/{category}/{title}/{id}', [ProjectIdeaController::class, 'show'])->name('app.projects.idea');
Route::get('/project-ideas', [ProjectIdeaController::class, 'index'])->name('app.projects.ideas');
Route::get('/project-ideas/{category}', [ProjectIdeaController::class, 'category'])
    ->name('app.projects.ideas.category');


require __DIR__ . '/auth.php';
