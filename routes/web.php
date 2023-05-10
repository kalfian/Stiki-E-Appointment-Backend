<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AuthController as AdminAuthController,
    DashboardController as AdminDashboardController,
    LectureController as AdminLectureController,
    StudentController as AdminStudentController,
    ActivityController as AdminActivityController,
};

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
    return redirect()->route('auth.login');
});

Route::prefix('/auth')->middleware(['guest'])->group(function () {
    Route::get('/', [AdminAuthController::class, 'login'])->name('auth.login');
    Route::post('/', [AdminAuthController::class, 'signIn'])->name('auth.login.signin');
});

Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
    Route::get('/auth/signout', [AdminAuthController::class, 'signOut'])->name('auth.signout');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => '/lectures'], function () {
        Route::get('/', [AdminLectureController::class, 'index'])->name('admin.lectures.index');
    });

    Route::group(['prefix' => '/students'], function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('admin.students.index');
    });

    Route::group(['prefix' => '/activities'], function () {
        Route::get('/', [AdminActivityController::class, 'index'])->name('admin.activities.index');
    });

});
