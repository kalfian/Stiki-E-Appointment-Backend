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
        Route::get('/', [AdminLectureController::class, 'index'])->name('admin.lectures.index')->middleware(['permission:admin_lecture_read']);
        Route::get('/detail/{lecture}', [AdminLectureController::class, 'show'])->name('admin.lectures.show')->middleware(['permission:admin_lecture_read']);
        Route::get('/create', [AdminLectureController::class, 'create'])->name('admin.lectures.create')->middleware(['permission:admin_lecture_create|admin_lecture_read']);
        Route::post('/create', [AdminLectureController::class, 'store'])->name('admin.lectures.store')->middleware(['permission:admin_lecture_create']);
        Route::get('/data', [AdminLectureController::class, 'datatables'])->name('admin.lectures.datatables')->middleware(['permission:admin_lecture_read']);
        Route::get('/edit/{lecture}', [AdminLectureController::class, 'edit'])->name('admin.lectures.edit')->middleware(['permission:admin_lecture_update|admin_lecture_read']);
        Route::post('/update', [AdminLectureController::class, 'update'])->name('admin.lectures.update')->middleware(['permission:admin_lecture_update']);
        Route::get('/select2', [AdminLectureController::class, 'select2'])->name('admin.lectures.select2')->middleware(['permission:admin_lecture_read']);
    });

    Route::group(['prefix' => '/student'], function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('admin.students.index')->middleware(['permission:admin_student_read']);
        Route::get('/detail/{student}', [AdminStudentController::class, 'show'])->name('admin.students.show')->middleware(['permission:admin_student_read']);
        Route::get('/create', [AdminStudentController::class, 'create'])->name('admin.students.create')->middleware(['permission:admin_student_create|admin_student_read']);
        Route::post('/create', [AdminStudentController::class, 'store'])->name('admin.students.store')->middleware(['permission:admin_student_create']);
        Route::get('/data', [AdminStudentController::class, 'datatables'])->name('admin.students.datatables')->middleware(['permission:admin_student_read']);
        Route::get('/edit/{student}', [AdminStudentController::class, 'edit'])->name('admin.students.edit')->middleware(['permission:admin_student_update|admin_student_read']);
        Route::post('/update', [AdminStudentController::class, 'update'])->name('admin.students.update')->middleware(['permission:admin_student_update']);
        Route::get('/select2', [AdminStudentController::class, 'select2'])->name('admin.students.select2')->middleware(['permission:admin_student_read']);
    });

    Route::group(['prefix' => '/activity'], function () {
        Route::get('/', [AdminActivityController::class, 'index'])->name('admin.activities.index')->middleware(['permission:admin_activity_read']);
        Route::get('/detail/{activity}', [AdminActivityController::class, 'show'])->name('admin.activities.show')->middleware(['permission:admin_activity_read']);
        Route::get('/data', [AdminActivityController::class, 'datatables'])->name('admin.activities.datatables')->middleware(['permission:admin_activity_read']);
        Route::get('/create', [AdminActivityController::class, 'create'])->name('admin.activities.create')->middleware(['permission:admin_activity_create|admin_activity_read']);
        Route::post('/create', [AdminActivityController::class, 'store'])->name('admin.activities.store')->middleware(['permission:admin_activity_create']);
        Route::get('/edit/{activity}', [AdminActivityController::class, 'edit'])->name('admin.activities.edit')->middleware(['permission:admin_activity_update|admin_activity_read']);
        Route::post('/update/{activity}', [AdminActivityController::class, 'update'])->name('admin.activities.update')->middleware(['permission:admin_activity_update']);
        Route::post('/update/{activity}/add_participant', [AdminActivityController::class, 'addParticipant'])->name('admin.activities.add_participant')->middleware(['permission:admin_activity_update']);
        Route::post('/update/{activity}/remove_participant', [AdminActivityController::class, 'removeParticipant'])->name('admin.activities.remove_participant')->middleware(['permission:admin_activity_update']);
    });

    Route::group(['prefix' => '/setting'], function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/{setting}', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    });
});
