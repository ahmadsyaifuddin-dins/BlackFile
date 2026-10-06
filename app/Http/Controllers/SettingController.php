<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * Memperbarui pengaturan paginasi dan menyimpannya di session.
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
     * Memperbarui semua pengaturan pengguna.
     */
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,id',
            'per_page' => 'required|integer|in:6,9,12,15,18,27,54',
            'theme' => 'required|string|in:default,amber,arctic,red',
            'alert_position' => 'nullable|string',
            'archive_edit_redirect' => 'nullable|string|in:index_position,show',
            'archive_create_redirect' => 'nullable|string|in:index_position,show',
        ]);

        $user = Auth::user();

        // Ambil pengaturan yang ada, atau buat array kosong jika belum ada
        $settings = $user->settings ?? [];

        // Gabungkan pengaturan lama dengan yang baru dari form
        $settings['locale'] = $request->locale;
        $settings['per_page'] = $request->per_page;
        $settings['theme'] = $request->theme;
        $settings['alert_position'] = $request->alert_position;
        $settings['archive_edit_redirect'] = $request->input('archive_edit_redirect', 'index_position');
        // Setelan terpisah untuk alur "tambah arsip baru".
        $settings['archive_create_redirect'] = $request->input('archive_create_redirect', 'index_position');
        // Simpan kembali ke database
        $user->settings = $settings;

        /** @var \App\Models\User $user */
        $user->save();

        return back()->with('success', __('Settings have been saved to your profile.'));
    }
}
