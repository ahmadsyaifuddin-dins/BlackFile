<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManual\LoginController;
use App\Http\Controllers\AuthManual\RegisterController;
use App\Http\Controllers\AuthManual\LogoutController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->middleware('role:director');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('role:director');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});
