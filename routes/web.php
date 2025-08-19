<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManual\LoginController;
use App\Http\Controllers\AuthManual\RegisterController;
use App\Http\Controllers\AuthManual\LogoutController;
use App\Http\Controllers\CodexController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrototypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;

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

    // --- Rute untuk Manajemen Prototype/Projects ---
    Route::resource('prototypes', PrototypeController::class);

    // --- Rute untuk Manajemen Entity ---
    Route::resource('entities', EntityController::class);

    // Rute untuk Codex
    Route::get('/codex', [CodexController::class, 'index'])->name('codex.index');


    // [BARU] Rute untuk aksi Director, dilindungi oleh middleware role
    Route::middleware('role:Director')->group(function () {

        Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);

        Route::get('/agents/{user}/edit', [UserController::class, 'edit'])->name('agents.edit');
        Route::patch('/agents/{user}', [UserController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{user}', [UserController::class, 'destroy'])->name('agents.destroy');
    });
});







// [BARU] Rute untuk proxy avatar
Route::get('/avatar-proxy/{name}', function (string $name) {
    // Ambil gambar dari ui-avatars
    $response = Http::get('https://ui-avatars.com/api/', [
        'name' => $name,
        'background' => '0d1117',
        'color' => '2ea043',
        'bold' => 'true',
    ]);

    // Jika berhasil, kirimkan gambar kembali ke browser dengan header yang benar
    if ($response->successful()) {
        return response($response->body())
            ->header('Content-Type', $response->header('Content-Type'));
    }

    // Jika gagal, bisa kembalikan gambar default atau error
    abort(404, 'Avatar not found.');
})->name('avatar.proxy');
