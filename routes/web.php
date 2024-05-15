<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SharingController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
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

Route::inertia('/', 'app');
Route::post('/language/{language}', [Controller::class, 'changeLanguage'])->name('language');

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [LoginController::class, 'edit'])->name('login.edit');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::get('/passwordreset', [LoginController::class, 'passwordReset'])->name('reset');
    Route::get('/404', function (Request $request) {
        return Inertia::render('errors/404')->toResponse($request)->setStatusCode(404);
    });
});
Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {
    Route::get('/', [DashboardUserController::class, 'getUserStats'])->name('dashboard');
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
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::get('/manager/subject/{slug}/select', [ChapterController::class, 'selectChapter'])->name('chapter.select');
    Route::resource('/manager/subject/{slug}/chapter', ChapterController::class);
    Route::get('/manager/subject/{slug}/sharing/users', [Controller::class, 'showUsersForSharing'])->name('sharing');
    Route::post('/sharing/users', [SharingController::class, 'store'])->name('share');
    Route::get('/sharing/subjects', [SharingController::class, 'showOfferShare'])->name('share.view');
    Route::post('/sharing/subjects', [SharingController::class, 'acceptShare'])->name('share.accept');
    Route::get('/sharing/show', [SharingController::class, 'index'])->name('share.show');
    Route::put('/sharing/edit', [SharingController::class, 'update'])->name('share.edit');
    Route::delete('/sharing/subjects/{slug}/user/{user}', [SharingController::class, 'delete'])->name('sharing.delete');
    Route::delete('/sharing/subjects/{slug}', [SharingController::class, 'refuseShare'])->name('share.delete');
    Route::post('/user/changeProfilePicture', [DashboardUserController::class, 'changeProfilePicture'])->name('user.profilePicture');
    Route::delete('/user/deleteProfilePicture/{user}', [DashboardUserController::class, 'deleteProfilePicture'])->name('user.deleteProfilePicture');
    Route::get('/search/user', [Controller::class, 'searchUser'])->name('user.search');
    Route::get('/404', function (Request $request) {
        return Inertia::render('errors/auth/404')->toResponse($request)->setStatusCode(404);
    });
    Route::get('/403', function (Request $request) {
        return Inertia::render('errors/auth/403')->toResponse($request)->setStatusCode(403);
    });
    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin', 'as' => 'admin'], function () {
        route::get('/controll', [Admin::class, 'index']);
        route::get('/controll/{user}', [Admin::class, 'edit'])->name('user.edit');
        route::put('/controll/{user}', [Admin::class, 'update'])->name('user.update');
        route::delete('/controll/{user}', [Admin::class, 'destroy'])->name('user.destroy');
        route::get('/controll/{slug}/subjects', [Admin::class, 'getUserSubjects'])->name('user.subjects');
        route::get('/controll/{slug}/subject/create', [Admin::class, 'createUserSubject'])->name('user.createSubject');
        route::post('/controll/{slug}/subject/create', [Admin::class, 'storeUserSubject'])->name('user.storeSubject');
        route::get('controll/user/create', [Admin::class, 'create'])->name('user.create');
        route::post('controll/user/create', [Admin::class, 'store'])->name('user.store');
        route::put('/controll/registration/{register}', [Admin::class, 'changeRestriction'])->name('register.restriction');
        route::put('/controll/theme/{color}', [Admin::class, 'changeTheme'])->name('theme.color');
    });
});
