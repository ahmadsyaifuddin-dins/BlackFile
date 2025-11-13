<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Memperbarui bahasa aplikasi dan menyimpannya di session.
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,id', // Hanya izinkan 'en' atau 'id'
        ]);

        Session::put('locale', $request->locale);

        return back()->with('success', 'Language has been updated.');
    }

    /**
     * [BARU] Memperbarui pengaturan paginasi dan menyimpannya di session.
     */
    public function updatePagination(Request $request)
    {
        $request->validate([
            'per_page' => 'required|integer|in:6,9,12,15,18,27,54', // Hanya izinkan nilai ini
        ]);

        Session::put('per_page', $request->per_page);

        return back()->with('success', 'Pagination setting has been updated.');
    }

    /**
     * [BARU & DISATUKAN] Memperbarui semua pengaturan pengguna.
     */
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,id',
            'per_page' => 'required|integer|in:6,9,12,15,18,27,54',
            'theme' => 'required|string|in:default,amber,arctic,red',
        ]);

        $user = Auth::user();
        
        // Ambil pengaturan yang ada, atau buat array kosong jika belum ada
        $settings = $user->settings ?? [];

        // Gabungkan pengaturan lama dengan yang baru dari form
        $settings['locale'] = $request->locale;
        $settings['per_page'] = $request->per_page;
        $settings['theme'] = $request->theme;
        
        // Simpan kembali ke database
        $user->settings = $settings;
                
        /** @var \App\Models\User $user */
        $user->save();

        return back()->with('success', 'Settings have been saved to your profile.');
    }
}
