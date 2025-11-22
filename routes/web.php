<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthManual\LoginController;
use App\Http\Controllers\AuthManual\LogoutController;
use App\Http\Controllers\AuthManual\RegisterAgentController;
use App\Http\Controllers\AuthManual\RegisterController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\CodexController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefaultMusicController;
use App\Http\Controllers\EncryptedContactController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MasterPasswordController;
use App\Http\Controllers\OsintToolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrototypeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Tools\ExifIntelController;
use App\Http\Controllers\Tools\UsernameTrackerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute ini tetap bisa diakses oleh semua orang, baik tamu maupun yang sudah login.
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Using route-model binding on the 'slug' column of the User model
Route::get('/public-credits/{user:slug}', [CreditController::class, 'publicShow'])->name('credits.public');

// Grup rute yang HANYA bisa diakses oleh "tamu" (pengguna yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::get('/default-music-file/{path}', [DefaultMusicController::class, 'serveMusicFile'])
    ->where('path', '.*')
    ->name('default-music.serve');

// Grup rute yang HANYA bisa diakses oleh pengguna yang SUDAH LOGIN
Route::middleware('auth')->group(function () {
    // Rute dasar setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::get('/credits/access-log', [CreditController::class, 'viewLog'])->name('credits.viewLog');

    Route::get('/credits/default-music', [DefaultMusicController::class, 'index'])->name('credits.default-music.index');
    Route::post('/credits/default-music', [DefaultMusicController::class, 'store'])->name('credits.default-music.store');
    Route::delete('/credits/default-music/{defaultMusic}', [DefaultMusicController::class, 'destroy'])->name('credits.default-music.destroy');

    // Semua rute aplikasi Anda yang lain
    Route::resource('entities', EntityController::class);

    Route::post('/entities/{entity}/generate-ai', [EntityController::class, 'generateAiAssessment'])
        ->name('entities.generate_ai');

    Route::get('/simulation', [BattleController::class, 'index'])->name('battle.index');
    Route::post('/simulation/run', [BattleController::class, 'simulate'])->name('battle.run');

    Route::get('/simulation/{id}', [BattleController::class, 'show'])->name('battle.show');

    Route::resource('prototypes', PrototypeController::class);
    Route::get('/codex', [CodexController::class, 'index'])->name('codex.index');

    Route::prefix('entities')->name('entities.')->group(function () {
        // Route Menuju Halaman Edit Stats
        Route::get('/{entity}/assessment', [EntityController::class, 'assessment'])->name('assessment');
        // Route Proses Simpan
        Route::put('/{entity}/assessment', [EntityController::class, 'updateAssessment'])->name('update_assessment');
    });

    // --- OSINT TOOLS ARSENAL ---
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [OsintToolController::class, 'index'])->name('index');

        // Rute placeholder untuk fitur yang akan kita bangun nanti
        Route::get('/identity-seeker', [UsernameTrackerController::class, 'index'])->name('username');
        // API Helper untuk mengecek URL tanpa kena CORS Browser
        Route::post('/check-availability', [UsernameTrackerController::class, 'check'])->name('check');

        // === TOOL 2: EXIF INTEL ===
        Route::get('/exif-intel', [ExifIntelController::class, 'index'])->name('exif');
        Route::post('/exif-intel/analyze', [ExifIntelController::class, 'analyze'])->name('exif.analyze');

        Route::get('/narrative-radar', [OsintToolController::class, 'commentAnalyzer'])->name('comment');
    });

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

    // --- RUTE UNTUK SETUP MASTER PASSWORD ---
    Route::get('/master-password/setup', [MasterPasswordController::class, 'create'])->name('master-password.setup');
    Route::post('/master-password/setup', [MasterPasswordController::class, 'store'])->name('master-password.store');

    // --- RUTE UNTUK BRANKAS KONTAK TERENKRIPSI ---
    Route::resource('encrypted-contacts', EncryptedContactController::class);
    Route::post('/encrypted-contacts/{encryptedContact}/unlock', [EncryptedContactController::class, 'unlock'])->name('encrypted-contacts.unlock');

    Route::get('favorites/archives', [ArchiveController::class, 'favorites'])->name('favorites.archives');
    Route::post('archives/{archive}/favorite', [ArchiveController::class, 'toggleFavorite'])->name('archives.favorite.toggle');

    // Pastikan ini di dalam middleware auth agar tidak sembarang orang bisa pakai kuota AI kamu
    Route::post('/archives/generate-ai-desc', [ArchiveController::class, 'generateAiDescription'])
        ->name('archives.generate-ai-desc');

    Route::resource('archives', ArchiveController::class);

    Route::resource('credits', CreditController::class);

    // Grup rute yang dilindungi oleh role tertentu (Director atau Technician)
    // Menggunakan 'role' middleware kustom Anda
    Route::middleware('role:Director,Technician')->group(function () {

        Route::post('/admin/system-setting/toggle', [AdminController::class, 'toggleSetting'])->name('admin.setting.toggle');

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::patch('/admin/users/{user}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
        Route::delete('/admin/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
        Route::post('/admin/invites/generate', [AdminController::class, 'generateInvite'])->name('admin.invites.generate');

        Route::post('/command/notify', [AdminController::class, 'sendNotification'])->name('admin.command.notify');

        Route::get('/admin/applicants/{user}', [AdminController::class, 'getApplicantDetails'])->name('admin.applicants.show');

        Route::get('/register/agent', [RegisterAgentController::class, 'showRegisterForm'])->name('register.agent');
        Route::post('/register/agent', [RegisterAgentController::class, 'registerAgent'])->name('register.agent');

        Route::get('/agents/{user}/edit', [UserController::class, 'edit'])->name('agents.edit');
        Route::patch('/agents/{user}', [UserController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{user}', [UserController::class, 'destroy'])->name('agents.destroy');
    });
});

// Rute untuk proxy avatar dengan Caching
Route::get('/avatar-proxy/{name}', function (string $name) {
    // Kunci unik untuk cache
    $cacheKey = 'avatar.proxy.'.Str::slug($name);

    // Coba ambil dari cache, jika tidak ada, jalankan fungsi untuk mengambilnya
    $imageData = Cache::remember($cacheKey, now()->addHours(24), function () use ($name) {
        $response = Http::get('https://blackfile.xo.je/agent-default.jpg', [
            'name' => $name,
            'background' => '0d1117',
            'color' => '2ea043',
            'bold' => 'true',
        ]);

        if ($response->successful()) {
            // Simpan body dan header ke dalam cache
            return [
                'body' => $response->body(),
                'content_type' => $response->header('Content-Type'),
            ];
        }

        return null; // Jika gagal, cache akan menyimpan null
    });

    // Jika data tidak ada (baik di cache maupun dari request baru)
    if (! $imageData) {
        abort(404, 'Avatar not found.');
    }

    // Kirimkan gambar dari data yang sudah di-cache
    return response($imageData['body'])->header('Content-Type', $imageData['content_type']);
})->name('avatar.proxy');

Route::get('/cek-gemini', function () {
    $apiKey = env('GEMINI_API_KEY_BATTLE');
    // Request ke endpoint 'list models'
    $response = Http::withoutVerifying()
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

    return $response->json();
});
