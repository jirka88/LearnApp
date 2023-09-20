<?php

use App\Http\Controllers\AdminSetUsers;
use App\Http\Controllers\ChapterController;
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
    Route::put('/user', [DashboardUserController::class, 'update'])->name('user.update');
    Route::get('/user/changePassword', function () {
        return to_route('user.info');
    });
    Route::put('/user/changePassword', [DashboardUserController::class, 'passwordReset'])->name('user.passwordReset');
    Route::put('/user/changeShare', [DashboardUserController::class, 'changeShare'])->name('user.share');
    Route::resource('/manager/subject', SubjectController::class);
    Route::get('/manager/subjects/sort', [Controller::class, 'sort'])->name('subject.sort');
    Route::get("/logout", [LogoutController::class, 'logout'])->name('logout');
    Route::get("/manager/subject/{slug}/select", [ChapterController::class, 'selectChapter'])->name('chapter.select');
    Route::resource("/manager/subject/{slug}/chapter", ChapterController::class);
    Route::get("/manager/subject/{slug}/sharing/users", [Controller::class, 'showUsersForSharing'])->name('sharing');
    Route::post("/sharing/users", [Controller::class, 'share'])->name('share');
    Route::get("/sharing/subjects", [Controller::class, 'showShare'])->name('share.view');
    Route::post("/sharing/subjects", [Controller::class, 'acceptShare'])->name('share.accept');
    Route::delete("/sharing/subjects/{slug}", [Controller::class, 'deleteShare'])->name('share.delete');

    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin', 'as' => 'admin'],function() {
        route::get('/controll', [AdminSetUsers::class, 'index']);
        route::get('/controll/{slug}', [AdminSetUsers::class, 'edit'])->name('user.edit');
        route::put('/controll/{user}', [AdminSetUsers::class, 'update'])->name('user.update');
        route::delete('/controll/{user}', [AdminSetUsers::class, 'destroy'])->name('user.destroy');
        route::get('/controll/{slug}/subjects', [AdminSetUsers::class, 'getUserSubjects'])->name('user.subjects');
        route::get('/controll/{slug}/subject/create', [AdminSetUsers::class, 'createUserSubject'])->name('user.createSubject');
        route::post('/controll/{slug}/subject/create', [AdminSetUsers::class, 'storeUserSubject'])->name('user.storeSubject');
        route::get('controll/user/create', [AdminSetUsers::class, 'create'])->name('user.create');
        route::post('controll/user/create', [AdminSetUsers::class, 'store'])->name('user.store');
    });
});


