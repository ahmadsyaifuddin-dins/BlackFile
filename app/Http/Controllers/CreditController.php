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
        return view('credits.create');
    }

    public function store(Request $request)
    {
        // Karena store dan update sangat mirip, kita bisa panggil logic update
        return $this->update($request, Auth::user()->id);
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        // $this->authorize('update-credits', $user); // Anda perlu membuat policy ini

        $credits = $user->credits()->orderBy('created_at', 'asc')->get();

        return view('credits.edit', compact('user', 'credits'));
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        // $this->authorize('update-credits', $user);

        // Secara otomatis buat slug jika belum ada
        if (empty($user->slug)) {
            $user->slug = Str::slug($user->name);
            $user->save();
        }

        $validated = $request->validate([
            'credits' => 'present|array',
            'credits.*.id' => 'nullable|integer',
            'credits.*.role' => 'required|string|max:255',
            'credits.*.names' => 'required|array|min:1', // Memastikan array 'names' ada dan tidak kosong
            'credits.*.logos' => 'nullable|array',
            'credits.*.logos.*.type' => ['required', Rule::in(['file', 'url'])],
            'credits.*.logos.*.path' => 'nullable|string',
            'credits.*.logos.*.file' => 'nullable|image|max:2048',
            'music' => 'nullable|file|mimes:mp3,wav,ogg|max:4240',
        ]);

        $submittedCreditsData = $validated['credits'] ?? [];
        $existingCreditIds = $user->credits()->pluck('id')->toArray();
        $submittedCreditIds = [];
        $newMusicPath = null;

        DB::transaction(function () use ($request, $user, $submittedCreditsData, &$submittedCreditIds, &$newMusicPath, $existingCreditIds) {

            // PERBAIKAN: Logika musik hanya dijalankan jika ada file baru
            if ($request->hasFile('music')) {
                // 1. Hapus musik lama dari server
                $oldMusicCredit = $user->credits()->whereNotNull('music_path')->first();
                if ($oldMusicCredit) {
                    File::delete(public_path($oldMusicCredit->music_path));
                }

                // 2. Hapus path musik lama dari SEMUA entri credit di database
                $user->credits()->update(['music_path' => null]);

                // 3. Simpan musik baru
                $musicFile = $request->file('music');
                $fileName = Str::uuid() . '.' . $musicFile->getClientOriginalExtension();
                $musicFile->move(public_path('uploads/credits/music'), $fileName);
                $newMusicPath = 'uploads/credits/music/' . $fileName;
            }

            foreach ($submittedCreditsData as $index => $creditData) {
                $finalLogoPaths = [];
                if (!empty($creditData['logos'])) {
                    foreach ($creditData['logos'] as $logoIndex => $logoData) {
                        if ($logoData['type'] === 'url' && !empty($logoData['path'])) {
                            $finalLogoPaths[] = $logoData['path'];
                        } elseif ($logoData['type'] === 'file') {
                            if ($request->hasFile("credits.{$index}.logos.{$logoIndex}.file")) {
                                $file = $request->file("credits.{$index}.logos.{$logoIndex}.file");
                                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                                $file->move(public_path('uploads/credits/logos'), $fileName);
                                $finalLogoPaths[] = 'uploads/credits/logos/' . $fileName;
                                if (!empty($logoData['path'])) {
                                    File::delete(public_path($logoData['path']));
                                }
                            } elseif (!empty($logoData['path'])) {
                                $finalLogoPaths[] = $logoData['path'];
                            }
                        }
                    }
                }

                $payload = [
                    'role' => $creditData['role'],
                    'names' => array_filter($creditData['names']), // Gunakan 'names' (plural)
                    'logos' => $finalLogoPaths,
                    'user_id' => $user->id,
                ];

                if (!empty($creditData['id'])) {
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

            $idsToDelete = array_diff($existingCreditIds, $submittedCreditIds);
            if (!empty($idsToDelete)) {
                $creditsToDelete = Credit::whereIn('id', $idsToDelete)->get();
                foreach ($creditsToDelete as $creditToDelete) {
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

            if ($newMusicPath) {
                $firstCredit = $user->credits()->orderBy('id', 'asc')->first();
                if ($firstCredit) {
                    $firstCredit->music_path = $newMusicPath;
                    $firstCredit->save();
                }
            }
        });

        return redirect()->route('credits.index')->with('success', 'Credits have been successfully updated.');
    }

    /**
     * Display the public credits page for a user.
     *
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
