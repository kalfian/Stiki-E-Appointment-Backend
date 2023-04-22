<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AuthController as AdminAuthController
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

Route::prefix('/auth')->group(function () {
    Route::get('/', [AdminAuthController::class, 'login'])->name('auth.login');
    Route::post('/', [AdminAuthController::class, 'signIn'])->name('auth.login.signin');
});
