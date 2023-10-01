<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\{
    AuthController as V1AuthController
};

use App\Http\Controllers\V1\{
    ActivityController as V1ActivityController,
    AppointmentController as V1AppointmentController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/', [V1AuthController::class, 'login'])->name('auth.login');
    });

    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::group(['prefix' => 'auth'], function() {
            Route::post('/logout', [V1AuthController::class, 'logout'])->name('auth.logout');
        });

        Route::group(['middleware' => ['role:student']], function() {
            Route::group(['prefix' => 'student', 'as' => 'student.'], function() {
                Route::group(['prefix' => 'activity'], function() {
                    Route::get('/', [V1ActivityController::class, 'index'])->name('activity.index');
                    Route::get('/{activity}', [V1ActivityController::class, 'show'])->name('activity.show');

                    Route::group(['prefix' => '{activity}/appointment'], function() {
                        Route::post('/', [V1AppointmentController::class, 'store'])->name('appointment.store');
                        Route::get('/{appointment}', [V1AppointmentController::class, 'show'])->name('appointment.show');
                    });
                });

                Route::group(["prefix" => "appointment"], function() {
                    Route::get('/', [V1AppointmentController::class, 'index'])->name('appointment.index');
                    Route::get('/{appointment}', [V1AppointmentController::class, 'show'])->name('appointment.show');
                });
            });
        });

    });
});
