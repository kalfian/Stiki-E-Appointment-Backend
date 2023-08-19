<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AuthController as AdminAuthController,
    DashboardController as AdminDashboardController,
    LectureController as AdminLectureController,
    StudentController as AdminStudentController,
    ActivityController as AdminActivityController,
    SettingController as AdminSettingController,
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
    Route::get('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('auth.reset_password');
});

Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
    Route::get('/auth/signout', [AdminAuthController::class, 'signOut'])->name('auth.signout');

    Route::post('/auth/export/reset-password', [AdminAuthController::class, 'exportResetPassword'])->name('auth.export.reset_password');
    Route::post('/auth/import/user', [AdminAuthController::class, 'importUser'])->name('auth.import.user');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => '/lecture'], function () {
        Route::get('/', [AdminLectureController::class, 'index'])->name('admin.lectures.index');
        Route::get('/detail/{lecture}', [AdminLectureController::class, 'show'])->name('admin.lectures.show');
        Route::get('/create', [AdminLectureController::class, 'create'])->name('admin.lectures.create');
        Route::post('/create', [AdminLectureController::class, 'store'])->name('admin.lectures.store');
        Route::get('/data', [AdminLectureController::class, 'datatables'])->name('admin.lectures.datatables');
        Route::get('/edit/{lecture}', [AdminLectureController::class, 'edit'])->name('admin.lectures.edit');
        Route::post('/update', [AdminLectureController::class, 'update'])->name('admin.lectures.update');
    });

    Route::group(['prefix' => '/student'], function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('admin.students.index');
        Route::get('/detail/{student}', [AdminStudentController::class, 'show'])->name('admin.students.show');
        Route::get('/create', [AdminStudentController::class, 'create'])->name('admin.students.create');
        Route::post('/create', [AdminStudentController::class, 'store'])->name('admin.students.store');
        Route::get('/data', [AdminStudentController::class, 'datatables'])->name('admin.students.datatables');
        Route::get('/edit/{student}', [AdminStudentController::class, 'edit'])->name('admin.students.edit');
        Route::post('/update', [AdminStudentController::class, 'update'])->name('admin.students.update');
    });

    Route::group(['prefix' => '/setting'], function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/{setting}', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    });

    Route::group(['prefix' => '/activity'], function () {
        Route::get('/', [AdminActivityController::class, 'index'])->name('admin.activities.index');

    });

});
