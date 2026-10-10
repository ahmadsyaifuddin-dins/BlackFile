<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua agen (direktori).
     */
    public function index(Request $request)
    {
        $onlineThreshold = now()->subMinutes(2);

        $query = User::query()
            ->where('id', '!=', Auth::id())
            ->with('role');

        // Pencarian bebas: codename, nama asli, username, spesialisasi
        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($sub) use ($term) {
                $sub->where('name', 'like', $term)
                    ->orWhere('codename', 'like', $term)
                    ->orWhere('username', 'like', $term)
                    ->orWhere('specialization', 'like', $term);
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('role', fn ($role) => $role->where('name', $request->role));
        }

        // Filter berdasarkan status kehadiran
        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->where('last_active_at', '>=', $onlineThreshold);
            } elseif ($request->status === 'offline') {
                $query->where(function ($sub) use ($onlineThreshold) {
                    $sub->whereNull('last_active_at')
                        ->orWhere('last_active_at', '<', $onlineThreshold);
                });
            } elseif ($request->status === 'pending') {
                $query->where('confirmed', false);
            }
        }

        // Urutan data
        match ($request->get('sort')) {
            'name' => $query->orderBy('name'),
            'recent' => $query->orderByDesc('created_at'),
            'activity' => $query->orderByDesc('last_active_at'),
            default => $query->orderBy('codename'),
        };

        $perPage = Auth::user()->settings['per_page'] ?? 12;
        $users = $query->paginate($perPage)->appends($request->query());

        // Statistik ringkas untuk panel atas
        $stats = [
            'total' => User::where('id', '!=', Auth::id())->count(),
            'online' => User::where('id', '!=', Auth::id())->where('last_active_at', '>=', $onlineThreshold)->count(),
            'directors' => User::whereHas('role', fn ($role) => $role->where('name', 'Director'))->count(),
            'pending' => User::where('confirmed', false)->count(),
        ];

        $roles = Role::orderBy('name')->pluck('alias', 'name');
        $statuses = [
            'online' => __('Online'),
            'offline' => __('Offline'),
            'pending' => __('Pending'),
        ];
        $sortOptions = [
            'codename' => __('Codename (A-Z)'),
            'name' => __('Real Name (A-Z)'),
            'recent' => __('Newest Agent'),
            'activity' => __('Recent Activity'),
        ];

        return view('users.index', compact('users', 'roles', 'statuses', 'sortOptions', 'stats', 'onlineThreshold'));
    }

    /**
     * REALTIME PRESENCE — Heartbeat.
     * Dipanggil setiap beberapa detik oleh halaman yang terbuka agar pengguna
     * terlihat "online" melalui last_active_at. Tidak menyentuh updated_at.
     */
    public function heartbeat(Request $request)
    {
        if (! Auth::id()) {
            return response()->json(['ok' => false], 401);
        }

        $this->touchPresence();

        return response()->json(['ok' => true], 200);
    }

    /**
     * REALTIME PRESENCE — Mark offline.
     * Dipanggil via sendBeacon saat tab/browser ditutup atau saat logout.
     */
    public function offline(Request $request)
    {
        if (! Auth::id()) {
            return response()->json(['ok' => false], 401);
        }

        User::query()
            ->where('id', Auth::id())
            ->update(['last_active_at' => null]);

        return response()->json(['ok' => true], 200);
    }

    /**
     * REALTIME PRESENCE — Snapshot status untuk polling /agents.
     * Mengembalikan peta id -> last_active_at (ISO), daftar id online, dan jumlahnya.
     */
    public function presence(Request $request)
    {
        $threshold = now()->subMinutes(2);

        $rows = User::query()
            ->where('id', '!=', Auth::id())
            ->orderByDesc('last_active_at')
            ->get(['id', 'last_active_at']);

        $agents = [];
        $onlineIds = [];

        foreach ($rows as $user) {
            $agents[$user->id] = $user->last_active_at ? $user->last_active_at->toIso8601String() : null;

            if ($user->last_active_at && $user->last_active_at->gte($threshold)) {
                $onlineIds[] = $user->id;
            }
        }

        return response()->json([
            'agents' => $agents,
            'online_ids' => $onlineIds,
            'online_count' => count($onlineIds),
        ]);
    }

    /**
     * Menulis heartbeat last_active_at tanpa menyentuh kolom updated_at.
     */
    private function touchPresence(): void
    {
        if (! Auth::id()) {
            return;
        }

        User::query()
            ->where('id', Auth::id())
            ->update(['last_active_at' => now()]);
    }

    /**
     * Menampilkan profil spesifik seorang agen (read-only).
     */
    public function show(User $user)
    {
        // Atur nilai default terlebih dahulu
        $localeName = 'N/A';
        $perPageName = 'N/A';
        $themeName = 'N/A';

        // Timpa nilai default HANYA JIKA user->settings ada
        if ($user->settings) {
            $locale = $user->settings['locale'] ?? 'N/A';
            $per_page = $user->settings['per_page'] ?? 'N/A';
            $theme = $user->settings['theme'] ?? 'N/A';

            // Format Bahasa
            if ($locale === 'id') {
                $localeName = 'Indonesian (id)';
            } elseif ($locale === 'en') {
                $localeName = 'English (en)';
            } else {
                $localeName = $locale;
            }

            // Format Per Page
            $perPageName = ($per_page !== 'N/A') ? $per_page . ' entries' : 'N/A';

            // Format Theme
            $themeName = ucfirst($theme);
        }

        return view('users.show', compact('user', 'localeName', 'perPageName', 'themeName'));
    }

     /**
     * Menampilkan form untuk mengedit data user lain.
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'Director')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Memperbarui data user lain.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6|confirmed',
            'specialization' => 'nullable|string|max:255',
            'quotes' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        // Update data utama, kecuali password
        $user->update($request->except('password'));

        // Normalisasi kolom opsional: kosong => null
        $user->gender = $request->input('gender') ?: null;
        $user->date_of_birth = $request->input('date_of_birth') ?: null;
        $user->save();

        // Hanya update password jika field-nya diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->temp_password = $request->password;
            $user->save();
        }

        return redirect()->route('agents.index')->with('success', "Agent {$user->codename}'s Agent has been updated.");
    }

    /**
     * [DIRECTOR ONLY] Reset password agen sekali klik.
     * Password baru = "password" + tanggal hari ini (format ddmmyyyy).
     */
    public function resetPassword(User $user)
    {
        $plainPassword = 'password' . now()->format('dmY');

        // Cast model ('hashed' + 'encrypted') menangani enkripsi masing-masing
        $user->password = $plainPassword;
        $user->temp_password = $plainPassword;
        $user->save();

        return redirect()->route('agents.show', $user)
            ->with('auto_reveal', true)
            ->with('success', __('CREDENTIAL OVERRIDE EXECUTED // :codename\'s password is now: :password', [
                'codename' => $user->codename,
                'password' => $plainPassword,
            ]));
    }

    /**
     * Menghapus user.
     */
    public function destroy(User $user)
    {
        // Pengaman: Director tidak bisa menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->withErrors(['msg' => 'A Director cannot terminate their own Agent.']);
        }
        
        $codename = $user->codename;
        $user->delete();

        return redirect()->route('agents.index')->with('success', "Agent {$codename} has been successfully terminated.");
    }
}