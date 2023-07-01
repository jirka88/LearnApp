<?php

use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
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
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('register', [RegisterController::class, 'store'])->name('register');
});
Route::group(['middleware' => ['auth']], function() {
    Route::inertia('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard/user', [DashboardUserController::class, 'view'])->name('user.info');
    Route::post('/dashboard/user', [DashboardUserController::class, 'update'])->name('user.update');
    Route::get('/dashboard/user/changePassword', function () {
        return redirect()->route('user.info');
    });
    Route::resource('/dashboard/manager/subject', SubjectController::class);
    Route::post('/dashboard/user/changePassword', [DashboardUserController::class, 'passwordReset'])->name('user.passwordReset');
    Route::get("/logout", [LogoutController::class, 'logout'])->name('logout');
    //redirect
    //Route::redirect("dashboard/user/changePassword","/dashboard/user",301);
});


