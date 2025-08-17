<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManual\LoginController;
use App\Http\Controllers\AuthManual\RegisterController;
use App\Http\Controllers\AuthManual\LogoutController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

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
})->name('welcome');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->middleware('role:director')->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('role:director');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/create', [FriendController::class, 'create'])->name('friends.create');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::get('/friends/{id}/tree', [FriendController::class, 'showTree'])->name('friends.tree');
    Route::get('/friends/central-tree', [FriendController::class, 'centralTreeGraph'])->name('friends.central-tree');

     // [BARU] Tambahkan rute untuk edit dan hapus
     Route::get('/friends/{friend}/edit', [FriendController::class, 'edit'])->name('friends.edit');
     Route::put('/friends/{friend}', [FriendController::class, 'update'])->name('friends.update');
     Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');

     // [BARU] Tambahkan rute untuk store sub-asset
     Route::post('/connections/sub-asset', [FriendController::class, 'storeSubAsset'])->name('connections.store_sub_asset');

      // --- Rute untuk Profil Pribadi (di-handle oleh ProfileController) ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // --- Rute untuk Manajemen Agen ---
    Route::get('/agents', [UserController::class, 'index'])->name('agents.index');
    Route::get('/agents/{user}', [UserController::class, 'show'])->name('agents.show');

    // [BARU] Rute untuk aksi Director, dilindungi oleh middleware role
    Route::middleware('role:Director')->group(function () {
        Route::get('/agents/{user}/edit', [UserController::class, 'edit'])->name('agents.edit');
        Route::patch('/agents/{user}', [UserController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{user}', [UserController::class, 'destroy'])->name('agents.destroy');
    });
});
