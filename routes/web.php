<?php

use App\Http\Controllers\AdminSetUsers;
use App\Http\Controllers\Controller;
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
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
});
Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function() {
    Route::inertia('/','dashboard')->name('dashboard');
    Route::get('/user', [DashboardUserController::class, 'view'])->name('user.info');
    Route::get('/report', [DashboardUserController::class, 'report'])->name('user.report');
    Route::post('/user', [DashboardUserController::class, 'update'])->name('user.update');
    Route::get('/user/changePassword', function () {
        return redirect()->route('user.info');
    });
    Route::resource('/manager/subject', SubjectController::class);
    Route::get('/manager/subjects/sort', [Controller::class, 'sort'])->name('subject.sort');
    Route::post('/user/changePassword', [DashboardUserController::class, 'passwordReset'])->name('user.passwordReset');
    Route::get("/logout", [LogoutController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin', 'as' => 'admin'],function() {
        route::get('/controll', [AdminSetUsers::class, 'index']);
        route::get('/controll/{user}', [AdminSetUsers::class, 'edit'])->name('user.edit');
        route::put('/controll/{user}', [AdminSetUsers::class, 'update'])->name('user.update');
        route::delete('/controll/{user}', [AdminSetUsers::class, 'destroy'])->name('user.destroy');
        route::get('/controll/{user}/subjects', [AdminSetUsers::class, 'getUserSubjects'])->name('user.subjects');
        route::get('/controll/{user}/subject/create', [AdminSetUsers::class, 'createUserSubject'])->name('user.createSubject');
        route::post('/controll/{user}/subject/create', [AdminSetUsers::class, 'storeUserSubject'])->name('user.storeSubject');
    });
    //redirect
    //Route::redirect("dashboard/user/changePassword","/dashboard/user",301);
});


