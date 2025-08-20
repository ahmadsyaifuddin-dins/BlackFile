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
*/

// Rute ini tetap bisa diakses oleh semua orang, baik tamu maupun yang sudah login.
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Grup rute yang HANYA bisa diakses oleh "tamu" (pengguna yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Grup rute yang HANYA bisa diakses oleh pengguna yang SUDAH LOGIN
Route::middleware('auth')->group(function () {
    // Rute dasar setelah login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Semua rute aplikasi Anda yang lain
    Route::resource('entities', EntityController::class);
    Route::resource('prototypes', PrototypeController::class);
    Route::get('/codex', [CodexController::class, 'index'])->name('codex.index');
    
    // Rute Friends Network
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/create', [FriendController::class, 'create'])->name('friends.create');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::get('/friends/{friend}/edit', [FriendController::class, 'edit'])->name('friends.edit');
    Route::put('/friends/{friend}', [FriendController::class, 'update'])->name('friends.update');
    Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');
    Route::get('/friends/{id}/tree', [FriendController::class, 'showTree'])->name('friends.tree');
    Route::get('/central-tree', [FriendController::class, 'centralTreeGraph'])->name('central-tree');
    Route::post('/connections/sub-asset', [FriendController::class, 'storeSubAsset'])->name('connections.store_sub_asset');

    // Rute Profil & Agen
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/agents', [UserController::class, 'index'])->name('agents.index');
    Route::get('/agents/{user}', [UserController::class, 'show'])->name('agents.show');

    // Grup rute yang dilindungi oleh role tertentu (Director atau Technician)
    // Menggunakan 'role' middleware kustom Anda
    Route::middleware('role:Director,Technician')->group(function () {
        Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);

        Route::get('/agents/{user}/edit', [UserController::class, 'edit'])->name('agents.edit');
        Route::patch('/agents/{user}', [UserController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{user}', [UserController::class, 'destroy'])->name('agents.destroy');
    });
});

// Rute untuk proxy avatar (tetap di luar middleware)
Route::get('/avatar-proxy/{name}', function (string $name) {
    $response = Http::get('https://ui-avatars.com/api/', [
        'name' => $name, 'background' => '0d1117', 'color' => '2ea043', 'bold' => 'true',
    ]);
    if ($response->successful()) {
        return response($response->body())->header('Content-Type', $response->header('Content-Type'));
    }
    abort(404, 'Avatar not found.');
})->name('avatar.proxy');
