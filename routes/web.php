<?php

use Illuminate\Support\Facades\Route;

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

Route::inertia('/', 'app');
Route::get('/register', function() {
    return \Inertia\Inertia::render('register', ['value' => 0]);
});
Route::get('/login', function() {
    return \Inertia\Inertia::render('register', ['value' => 1]);
});
