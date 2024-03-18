<?php

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

Route::get('/terms', function () {
    return Inertia::render('Home/Terms');
})->name('terms');
Route::get('/privacy-policy', function () {
    return Inertia::render('Home/Privacy');
})->name('privacy-policy');

require __DIR__ . '/auth.php';
