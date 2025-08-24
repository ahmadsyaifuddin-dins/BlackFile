<?php

namespace App\Http\Controllers;

use App\Models\EncryptedContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; // [PENTING] Tambahkan ini

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
            $allContacts = EncryptedContact::with('user')->orderBy('codename', 'asc')->get();

            // Pisahkan koleksi menjadi dua: satu untuk Director, satu untuk yang lain
            list($directorContacts, $agentContacts) = $allContacts->partition(function ($contact) {
                return $contact->user_id === Auth::id();
            });

            // Kelompokkan kontak agen lain berdasarkan pemiliknya
            $agentContactsByUser = $agentContacts->groupBy('user_id');

            return view('encrypted_contacts.director_index', [
                'directorContacts' => $directorContacts,
                'agentContactsByUser' => $agentContactsByUser,
            ]);
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'payload' => 'nullable|array'
        ]);

        $dbPath = null;
        if ($request->hasFile('profile_photo')) {
            $webRoot = dirname(base_path());
            $destinationPath = $webRoot . '/uploads/contact_photos';
            $imageFile = $request->file('profile_photo');
            $imageName = uniqid() . '.' . $imageFile->extension();
            $imageFile->move($destinationPath, $imageName);
            $dbPath = 'uploads/contact_photos/' . $imageName;
        }

        EncryptedContact::create([
            'user_id' => $user->id,
            'codename' => $request->codename,
            'profile_photo_path' => $dbPath,
            'encrypted_payload' => $request->payload
        ]);

        return redirect()->route('encrypted-contacts.index')->with('success', 'Encrypted contact has been successfully added to the vault.');
    }

    /**
     * [UPDATED] Display the specified resource with new Director logic.
     */
    public function show(EncryptedContact $encryptedContact)
    {
        $currentUser = Auth::user();
        
        // Otorisasi awal (tidak berubah)
        if ($currentUser->id !== $encryptedContact->user_id && strtolower($currentUser->role->name) !== 'director') {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $isDecrypted = false;
        $encryptedSnippet = null;

        // --- LOGIKA BARU UNTUK DIRECTOR ---
        $isDirector = strtolower($currentUser->role->name) === 'director';
        $isOwnContact = $currentUser->id === $encryptedContact->user_id;

        // Kondisi untuk mendekripsi data:
        // 1. Jika pengguna adalah Director DAN melihat kontak agen lain.
        // 2. ATAU jika kontak (milik siapa pun, termasuk Director) sudah dibuka di session.
        if (($isDirector && !$isOwnContact) || Session::get('decrypted_contact_' . $encryptedContact->id) === true) {
            $isDecrypted = true;
        }

        if ($isDecrypted) {
            $rawEncryptedString = $encryptedContact->getRawOriginal('encrypted_payload');
            $encryptedSnippet = substr($rawEncryptedString, 20, 50) . '...';
        }

        return view('encrypted_contacts.show', [
            'contact' => $encryptedContact,
            'isDecrypted' => $isDecrypted,
            'encryptedSnippet' => $encryptedSnippet,
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

    /**
     * [UPDATED] Show the form for editing the specified resource with new Director logic.
     */
    public function edit(EncryptedContact $encryptedContact)
    {
        $currentUser = Auth::user();

        if ($currentUser->id !== $encryptedContact->user_id && strtolower($currentUser->role->name) !== 'director') {
            abort(403, 'UNAUTHORIZED ACCESS');
        }

        $isDecrypted = false;
        $isDirector = strtolower($currentUser->role->name) === 'director';
        $isOwnContact = $currentUser->id === $encryptedContact->user_id;

        if (($isDirector && !$isOwnContact) || Session::get('decrypted_contact_' . $encryptedContact->id) === true) {
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
        if (Auth::id() !== $encryptedContact->user_id) { abort(403); }

        $request->validate([
            'codename' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'payload' => 'nullable|array'
        ]);

        $dbPath = $encryptedContact->profile_photo_path;
        if ($request->hasFile('profile_photo')) {
            $webRoot = dirname(base_path());
            
            if ($dbPath && File::exists($webRoot . '/' . $dbPath)) {
                File::delete($webRoot . '/' . $dbPath);
            }

            $destinationPath = $webRoot . '/uploads/contact_photos';
            $imageFile = $request->file('profile_photo');
            $imageName = uniqid() . '.' . $imageFile->extension();
            $imageFile->move($destinationPath, $imageName);
            $dbPath = 'uploads/contact_photos/' . $imageName;
        }

        $payload = $request->payload;
        if (empty($payload['sia_password'])) {
            $oldPayload = $encryptedContact->encrypted_payload;
            $payload['sia_password'] = $oldPayload['sia_password'] ?? null;
        }

        $encryptedContact->update([
            'codename' => $request->codename,
            'profile_photo_path' => $dbPath,
            'encrypted_payload' => $payload
        ]);

        return redirect()->route('encrypted-contacts.show', $encryptedContact)->with('success', 'Encrypted contact has been updated.');
    }

    /**
     * [UPDATED] Remove the specified resource from storage.
     */
    public function destroy(EncryptedContact $encryptedContact)
    {
        if (Auth::id() !== $encryptedContact->user_id) { abort(403); }

        if ($encryptedContact->profile_photo_path) {
            $webRoot = dirname(base_path());
            $fullPath = $webRoot . '/' . $encryptedContact->profile_photo_path;
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $encryptedContact->delete();
        return redirect()->route('encrypted-contacts.index')->with('success', 'Encrypted contact has been terminated.');
    }
}
