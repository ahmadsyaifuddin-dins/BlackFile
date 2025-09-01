<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\CreditView;
use App\Models\DefaultMusic;

class CreditController extends Controller
{
    /**
     * Display the management view for credits.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role->name === 'Director') {
            // PERUBAHAN: Gunakan withCount untuk mengambil jumlah view secara efisien
            $usersWithCredits = User::has('credits')
                ->with('credits')
                ->withCount('creditViews') // Menghitung relasi creditViews
                ->get();
            $directorHasCredits = $user->credits()->exists();
            return view('credits.index', compact('usersWithCredits', 'directorHasCredits'));
        }

        $hasCredits = $user->credits()->exists();
        return view('credits.index', compact('hasCredits'));
    }

    /**
     * MENAMPILKAN LOG PENAYANGAN HALAMAN CREDIT PUBLIK.
     * HANYA BISA DIAKSES OLEH DIRECTOR.
     */
    public function viewLog()
    {
        // Fase 1: Otorisasi - Pastikan hanya Director yang bisa mengakses
        if (Auth::user()->role->name !== 'Director') {
            abort(403, 'ACCESS DENIED. THIS AREA IS RESTRICTED TO DIRECTOR-LEVEL PERSONNEL.');
        }

        // Fase 2: Pengambilan Data
        // Ambil semua data dari credit_views, urutkan dari yang terbaru.
        // Gunakan eager loading ('owner', 'visitor') untuk efisiensi query database.
        $views = CreditView::with(['owner', 'visitor'])
            ->latest('viewed_at')
            ->paginate(20); // Gunakan paginasi agar tidak berat

        // Fase 3: Tampilkan View
        return view('credits.view_log', compact('views'));
    }

    /**
     * Show the form for creating the entire credit list.
     */
    public function create()
    {
        // PERBAIKAN: Definisikan variabel $user di sini
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->credits()->exists()) {
            return redirect()->route('credits.edit', $user->id);
        }
        $defaultMusics = DefaultMusic::all();
        return view('credits.create', compact('defaultMusics'));
    }

    public function store(Request $request)
    {
        // Karena store dan update sangat mirip, kita bisa panggil logic update
        return $this->update($request, Auth::user()->id);
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $credits = $user->credits()->orderBy('created_at', 'asc')->get();

        // TAMBAHKAN INI
        $defaultMusics = DefaultMusic::all();

        // Cari path musik yang ada
        $musicCredit = $credits->whereNotNull('music_path')->first();
        $musicPath = $musicCredit->music_path ?? null;

        return view('credits.edit', compact('user', 'credits', 'musicPath', 'defaultMusics'));
    }


    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Secara otomatis buat slug jika belum ada
        if (empty($user->slug)) {
            $user->slug = Str::slug($user->name);
            $user->save();
        }

        $validated = $request->validate([
            'credits'              => 'present|array',
            'credits.*.id'         => 'nullable|integer',
            'credits.*.role'       => 'required|string|max:255',
            'credits.*.names'      => 'required|array|min:1',
            'credits.*.names.*'    => 'required|string|max:255',
            'credits.*.logos'      => 'nullable|array',
            'credits.*.logos.*.type' => ['required_with:credits.*.logos', \Illuminate\Validation\Rule::in(['file', 'url'])],
            'credits.*.logos.*.path' => 'nullable|string',
            'credits.*.logos.*.file' => 'nullable|image|max:2048', // 2MB
            'music_source'         => 'required|in:default,custom',
            'default_music_path'   => 'nullable|string',
            'music'                => 'nullable|file|mimes:mp3,wav,ogg|max:10240', // 10MB
        ]);

        $submittedCreditsData = $validated['credits'] ?? [];
        $existingCreditIds = $user->credits()->pluck('id')->toArray();

        DB::transaction(function () use ($request, $user, $submittedCreditsData, $existingCreditIds, $validated) {

            $submittedCreditIds = [];

            // --- FASE 1: SINKRONISASI DATA CREDIT (SECTIONS, NAMES, LOGOS) ---
            foreach ($submittedCreditsData as $index => $creditData) {

                // Logika untuk memproses dan menyimpan logo
                $finalLogoPaths = [];
                if (!empty($creditData['logos'])) {
                    foreach ($creditData['logos'] as $logoIndex => $logoData) {
                        if ($logoData['type'] === 'url' && !empty($logoData['path'])) {
                            $finalLogoPaths[] = $logoData['path'];
                        } elseif ($logoData['type'] === 'file') {
                            if ($request->hasFile("credits.{$index}.logos.{$logoIndex}.file")) {
                                $file = $request->file("credits.{$index}.logos.{$logoIndex}.file");
                                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                                // Gunakan public_path() untuk penyimpanan lokal
                                $file->move(public_path('uploads/credits/logos'), $fileName);
                                $finalLogoPaths[] = 'uploads/credits/logos/' . $fileName;

                                // Hapus file lama jika ada yang diganti
                                if (!empty($logoData['path']) && !Str::startsWith($logoData['path'], 'http')) {
                                    File::delete(public_path($logoData['path']));
                                }
                            } elseif (!empty($logoData['path'])) {
                                // Pertahankan path file lama jika tidak ada file baru yang diunggah
                                $finalLogoPaths[] = $logoData['path'];
                            }
                        }
                    }
                }

                $payload = [
                    'user_id' => $user->id,
                    'role'    => $creditData['role'],
                    'names'   => array_filter($creditData['names']),
                    'logos'   => $finalLogoPaths,
                ];

                if (!empty($creditData['id']) && is_numeric($creditData['id'])) {
                    $credit = Credit::find($creditData['id']);
                    if ($credit) {
                        $credit->update($payload);
                        $submittedCreditIds[] = $credit->id;
                    }
                } else {
                    $newCredit = Credit::create($payload);
                    $submittedCreditIds[] = $newCredit->id;
                }
            }

            // --- FASE 2: HAPUS DATA CREDIT LAMA ---
            $idsToDelete = array_diff($existingCreditIds, $submittedCreditIds);
            if (!empty($idsToDelete)) {
                $creditsToDelete = Credit::whereIn('id', $idsToDelete)->get();
                foreach ($creditsToDelete as $creditToDelete) {
                    // Hapus file logo terkait sebelum menghapus record dari DB
                    if ($creditToDelete->logos) {
                        foreach ($creditToDelete->logos as $logo) {
                            if (!Str::startsWith($logo, 'http')) {
                                File::delete(public_path($logo));
                            }
                        }
                    }
                }
                Credit::destroy($idsToDelete);
            }

            // --- FASE 3: PROSES DAN SIMPAN MUSIK ---
            $newMusicPath = null;
            $currentMusicCredit = $user->credits()->whereNotNull('music_path')->first();

            if ($validated['music_source'] === 'default') {
                if ($currentMusicCredit && Str::startsWith($currentMusicCredit->music_path, 'uploads/credits/music')) {
                    File::delete(public_path($currentMusicCredit->music_path));
                }
                $newMusicPath = $validated['default_music_path'] ?? null;
            } elseif ($validated['music_source'] === 'custom' && $request->hasFile('music')) {
                if ($currentMusicCredit && Str::startsWith($currentMusicCredit->music_path, 'uploads/credits/music')) {
                    File::delete(public_path($currentMusicCredit->music_path));
                }
                $file = $request->file('music');
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/credits/music'), $fileName);
                $newMusicPath = 'uploads/credits/music/' . $fileName;
            }
            // Jika user memilih custom tapi tidak upload, pertahankan yang lama
            elseif ($validated['music_source'] === 'custom' && $currentMusicCredit) {
                $newMusicPath = $currentMusicCredit->music_path;
            }

            // Update path musik di database SETELAH semua credit disinkronisasi
            $user->credits()->update(['music_path' => null]);
            if ($newMusicPath) {
                // Ambil credit pertama yang ada SETELAH proses sinkronisasi
                $firstCredit = $user->credits()->orderBy('created_at', 'asc')->first();
                if ($firstCredit) {
                    $firstCredit->update(['music_path' => $newMusicPath]);
                }
            }
        });

        return redirect()->route('credits.index')->with('success', 'Credits have been successfully updated.');
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function publicShow(Request $request, User $user)
    {
        // --- LOGIKA PELACAKAN BARU ---
        $isDirector = false;
        if (Auth::check()) {
            /** @var \App\Models\User $visitor */
            $visitor = Auth::user();
            if ($visitor->role->name === 'Director') {
                $isDirector = true;
            }
        }

        // Jangan catat jika pengunjung adalah Director
        if (!$isDirector) {
            CreditView::create([
                'user_id' => $user->id, // ID pemilik halaman
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'visitor_id' => Auth::id(), // Akan null jika pengunjung adalah guest
            ]);
        }

        $credits = $user->credits()->orderBy('created_at', 'asc')->get();

        if ($credits->isEmpty()) {
            abort(404, 'No credits found for this operative.');
        }

        // Ambil credit pertama yang memiliki path musik
        $musicCredit = $credits->whereNotNull('music_path')->first();

        return view('credits.public', compact('user', 'credits', 'musicCredit'));
    }

    /**
     * Remove all credits for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($userId)
    {
        // Otorisasi: Hanya Director yang bisa menghapus
        if (Auth::user()->role->name !== 'Director') {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $user = User::findOrFail($userId);
        $credits = $user->credits;

        // Hapus semua file terkait
        foreach ($credits as $credit) {
            // Hapus logos
            if ($credit->logos) {
                foreach ($credit->logos as $logoPath) {
                    if (!Str::startsWith($logoPath, 'http')) {
                        File::delete(public_path($logoPath));
                    }
                }
            }
            // Hapus music
            if ($credit->music_path) {
                File::delete(public_path($credit->music_path));
            }
        }

        // Hapus semua record credit dari database
        $user->credits()->delete();

        return redirect()->route('credits.index')->with('success', "All credits for {$user->name} have been deleted.");
    }
}
