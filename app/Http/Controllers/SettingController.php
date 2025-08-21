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
}
