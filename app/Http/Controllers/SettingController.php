<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            'per_page' => 'required|integer|in:6,9,18,27', // Hanya izinkan nilai ini
        ]);

        Session::put('per_page', $request->per_page);

        return back()->with('success', 'Pagination setting has been updated.');
    }

     /**
     * [BARU] Memperbarui tema UI aplikasi.
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:default,amber,arctic,red',
        ]);

        Session::put('theme', $request->theme);

        return back()->with('success', 'UI theme has been updated.');
    }
}
