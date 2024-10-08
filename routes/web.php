<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PasswordReset;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SharingController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
    Route::get('/forgot-password', [ForgotPasswordController::class, 'passwordResetShow'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'passwordResetStore'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', function (string $token) {
        return Inertia::render('Authentication/ForgotPassword', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password',[ForgotPasswordController::class, 'passwordReset'])->name('reset');
    Route::get('/404', function (Request $request) {
        return Inertia::render('errors/404')->toResponse($request)->setStatusCode(404);
    });
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/email/verify', [VerifyEmailController::class, 'show'])->middleware('unverified')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/request-verification', [VerifyEmailController::class, 'requestVerification'])->middleware('throttle:6,1')->name('verification.email');
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'dashboard'], function () {
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
    Route::get('/manager/Subjects/sort', [Controller::class, 'sort'])->name('subject.sort');
    Route::get('/manager/subject/{slug}/select', [ChapterController::class, 'selectChapter'])->name('chapter.select');
    Route::resource('/manager/subject/{slug}/chapter', ChapterController::class);
    Route::post('/manager/subject/{slug}/chapter/{chapterSlug}/export', [ChapterController::class, 'exportFile']);
    Route::get('/manager/subject/{slug}/sharing/users', [Controller::class, 'showUsersForSharing'])->name('sharing');
    Route::post('/sharing/users', [SharingController::class, 'store'])->name('share');
    Route::get('/sharing/Subjects', [SharingController::class, 'showOfferShare'])->name('share.view');
    Route::post('/sharing/Subjects', [SharingController::class, 'acceptShare'])->name('share.accept');
    Route::get('/sharing/show', [SharingController::class, 'index'])->name('share.show');
    Route::put('/sharing/edit', [SharingController::class, 'update'])->name('share.edit');
    Route::delete('/sharing/Subjects/{slug}/user/{user}', [SharingController::class, 'delete'])->name('sharing.delete');
    Route::delete('/sharing/Subjects/{slug}', [SharingController::class, 'refuseShare'])->name('share.delete');
    Route::post('/user/changeProfilePicture', [DashboardUserController::class, 'changeProfilePicture'])->name('user.profilePicture');
    Route::delete('/user/deleteProfilePicture/{user}', [DashboardUserController::class, 'deleteProfilePicture'])->name('user.deleteProfilePicture');
    Route::get('/search/user', [Controller::class, 'searchUser'])->name('user.search');
    Route::get('/subject/search', [SubjectController::class, 'searchSubject'])->name('subject.search');
    Route::get('/404', function (Request $request) {
        return Inertia::render('errors/auth/404')->toResponse($request)->setStatusCode(404);
    });
    Route::get('/403', function (Request $request) {
        return Inertia::render('errors/auth/403')->toResponse($request)->setStatusCode(403);
    });
    Route::get('/500', function (Request $request) {
        return Inertia::render('errors/auth/500')->toResponse($request)->setStatusCode(500);
    });
    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin', 'as' => 'admin'], function () {
        route::get('/controll', [Admin::class, 'index'])->name('users');
        route::get('/controll/log', [LogController::class, 'index'])->name('log');
        route::get('/controll/log/{activity}', [LogController::class, 'show'])->name('log.show');
        route::delete('/controll/log/{activity}', [LogController::class, 'destroy'])->name('log.destroy');
        Route::post('/controll/log/export', [LogController::class, 'exportFile']);
        route::get('/controll/sort', [Admin::class, 'sortIndex'])->name('users.sort');
        Route::post('/controll/users', [Admin::class, 'userExportPDF'])->name('users.exportPDF');
        route::get('/controll/{user}', [Admin::class, 'edit'])->name('user.edit');
        route::put('/controll/{user}', [Admin::class, 'update'])->name('user.update');
        route::delete('/controll/{user}', [Admin::class, 'destroy'])->name('user.destroy');
        route::get('/controll/{slug}/Subjects', [Admin::class, 'getUserSubjects'])->name('user.Subjects');
        route::get('/controll/{slug}/subject/create', [Admin::class, 'createUserSubject'])->name('user.createSubject');
        route::post('/controll/{slug}/subject/create', [Admin::class, 'storeUserSubject'])->name('user.storeSubject');
        route::get('controll/user/create', [Admin::class, 'create'])->name('user.create');
        route::post('controll/user/create', [Admin::class, 'store'])->name('user.store');
        route::put('/controll/registration/{register}', [Admin::class, 'changeRestriction'])->name('register.restriction');
        route::put('/controll/theme/{color}', [Admin::class, 'changeTheme'])->name('theme.color');
    });
});
