<?php

namespace App\Http\Controllers;

use App\Models\EncryptedContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class EncryptedContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // [PERUBAHAN] Logika untuk Director
        if (strtolower($user->role->name) === 'director') {
            // Ambil semua kontak dan kelompokkan berdasarkan user_id (pemiliknya)
            $contactsByUser = EncryptedContact::with('user')->get()->groupBy('user_id');

            // Kirim data ke view baru yang dirancang untuk Director
            return view('encrypted_contacts.director_index', compact('contactsByUser'));
        }

        // Logika untuk agen biasa (tidak berubah)
        if (is_null($user->master_password)) {
            return redirect()->route('master-password.setup')->with('warning', 'You must set up your Master Password to access the encrypted vault.');
        }

        $contacts = EncryptedContact::where('user_id', $user->id)->orderBy('codename', 'asc')->get();
        return view('encrypted_contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('encrypted_contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'codename' => 'required|string|max:255',
            'payload' => 'nullable|array'
        ]);

        EncryptedContact::create([
            'user_id' => $user->id,
            'codename' => $request->codename,
            'encrypted_payload' => $request->payload
        ]);

        return redirect()->route('encrypted-contacts.index')->with('success', 'Encrypted contact has been successfully added to the vault.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EncryptedContact $encryptedContact)
    {
        // Otorisasi: Pastikan pengguna adalah pemilik atau seorang Director.
        if (Auth::id() !== $encryptedContact->user_id && strtolower(Auth::user()->role->name) !== 'director') {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $isDecrypted = false;
        // Jika Director, data selalu terdekripsi.
        // Jika agen, cek apakah sesi untuk kontak ini sudah "terbuka".
        if (strtolower(Auth::user()->role->name) === 'director' || Session::get('decrypted_contact_' . $encryptedContact->id) === true) {
            $isDecrypted = true;
        }

        return view('encrypted_contacts.show', [
            'contact' => $encryptedContact,
            'isDecrypted' => $isDecrypted,
        ]);
    }

    /**
     * Handle Master Password verification to unlock a contact.
     */
    public function unlock(Request $request, EncryptedContact $encryptedContact)
    {
        // Otorisasi: Pastikan hanya pemilik yang bisa mencoba membuka.
        if (Auth::id() !== $encryptedContact->user_id) {
            abort(403);
        }

        $request->validate(['master_password' => 'required']);

        // Verifikasi Master Password dengan hash yang ada di database.
        if (Hash::check($request->master_password, Auth::user()->master_password)) {
            // Jika benar, simpan status "terbuka" di session untuk kontak ini.
            Session::put('decrypted_contact_' . $encryptedContact->id, true);
            return redirect()->route('encrypted-contacts.show', $encryptedContact);
        }

        // Jika salah, kembali dengan pesan error.
        return back()->with('error', 'Invalid Master Password. Access denied.');
    }

    public function edit(EncryptedContact $encryptedContact)
    {
        if (Auth::id() !== $encryptedContact->user_id && strtolower(Auth::user()->role->name) !== 'director') {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $isDecrypted = false;
        if (strtolower(Auth::user()->role->name) === 'director' || Session::get('decrypted_contact_' . $encryptedContact->id) === true) {
            $isDecrypted = true;
        }

        return view('encrypted_contacts.edit', [
            'contact' => $encryptedContact,
            'isDecrypted' => $isDecrypted,
        ]);
    }

    /**
     * [UPDATED] Update the specified resource in storage.
     */
    public function update(Request $request, EncryptedContact $encryptedContact)
    {
        // Otorisasi: Hanya pemilik yang bisa mengupdate. Director hanya bisa melihat.
        if (Auth::id() !== $encryptedContact->user_id) {
            abort(403);
        }

        $request->validate([
            'codename' => 'required|string|max:255',
            'payload' => 'nullable|array'
        ]);

        $payload = $request->payload;

        // Logika khusus untuk password: jangan simpan jika kosong
        if (empty($payload['sia_password'])) {
            // Ambil password lama dari data yang sudah ada
            $oldPayload = $encryptedContact->encrypted_payload;
            $payload['sia_password'] = $oldPayload['sia_password'] ?? null;
        }

        $encryptedContact->update([
            'codename' => $request->codename,
            'encrypted_payload' => $payload // Otomatis dienkripsi ulang oleh model
        ]);

        return redirect()->route('encrypted-contacts.show', $encryptedContact)->with('success', 'Encrypted contact has been updated.');
    }

    /**
     * [UPDATED] Remove the specified resource from storage.
     */
    public function destroy(EncryptedContact $encryptedContact)
    {
        // Otorisasi: Hanya pemilik yang bisa menghapus.
        if (Auth::id() !== $encryptedContact->user_id) {
            abort(403);
        }

        $encryptedContact->delete();

        return redirect()->route('encrypted-contacts.index')->with('success', 'Encrypted contact has been terminated.');
    }
}
