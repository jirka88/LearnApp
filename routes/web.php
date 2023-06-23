<?php

use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
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

Route::group(['middleware' => ['guest']], function() {
    Route::get('/login', [LoginController::class, 'edit'])->name('login.edit');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
});
Route::group(['middleware' => ['auth']], function() {
    Route::inertia('/dashboard', 'dashboard');
    Route::get('/dashboard/user', [DashboardUserController::class, 'view'])->name('user.info');
    Route::post('/dashboard/user/changePassword', [DashboardUserController::class, 'passwordReset'])->name('user.passwordReset');
    Route::post("logout", [LogoutController::class, 'logout'])->name('logout');
});


