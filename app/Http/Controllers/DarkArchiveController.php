<?php

namespace App\Http\Controllers;

use App\Models\DarkArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DarkArchiveController extends Controller
{
    // ==========================
    // AREA PUBLIK & DASHBOARD
    // ==========================

    public function index()
    {
        // 1. LOGIKA UNTUK USER LOGIN (DASHBOARD VIEW - ADMIN PANEL)
        if (Auth::check()) {
            $user = Auth::user();
            // Ambil role dengan aman (safe navigation)
            $role = strtolower($user->role->name ?? '');

            // Daftar role internal yang boleh melihat DRAFT
            $internalRoles = ['director', 'agent', 'analyst', 'technician'];

            if (in_array($role, $internalRoles)) {
                // Internal: Lihat SEMUA (Draft & Declassified) urut dari input terbaru
                $archives = DarkArchive::orderBy('created_at', 'desc')->get();
            } else {
                // User login biasa: Hanya Declassified
                $archives = DarkArchive::where('status', 'declassified')
                    ->orderBy('incident_date', 'asc')
                    ->get();
            }

            // Return ke View Dashboard (Admin Panel)
            // Perbaikan nama folder: dark-archives
            return view('dark-archives.index', compact('archives'));
        }

        // 2. LOGIKA UNTUK GUEST / PUBLIK (IMMERSIVE VIEW)
        $archives = DarkArchive::where('status', 'declassified')
            ->orderBy('incident_date', 'asc')
            ->get();

        // Return ke View Publik Khusus (Tanpa Sidebar)
        // Perbaikan nama folder: dark-archives
        return view('dark-archives.public_index', compact('archives'));
    }

    public function show($slug)
    {
        // Public Show
        $archive = DarkArchive::where('slug', $slug)
            ->where('status', 'declassified')
            ->firstOrFail();

        $sessionKey = 'viewed_archive_'.$archive->id;
        if (! Session::has($sessionKey)) {
            $archive->increment('views');
            Session::put($sessionKey, true);
        }

        // Perbaikan nama folder: dark-archives
        return view('dark-archives.show', compact('archive'));
    }

    public function payRespect($id)
    {
        $archive = DarkArchive::findOrFail($id);

        $sessionKey = 'respected_archive_'.$id;
        if (! Session::has($sessionKey)) {
            $archive->increment('respects');
            Session::put($sessionKey, true);

            return response()->json([
                'success' => true,
                'total' => $archive->respects,
                'message' => 'Penghormatan tersampaikan.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Anda sudah memberikan penghormatan.',
        ]);
    }

    // ==========================
    // AREA AGENT (Input & Edit Data)
    // ==========================

    public function create()
    {
        // Kita kirim variabel $archive sebagai null untuk menandakan ini mode CREATE
        return view('dark-archives.create', ['archive' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'case_code' => 'required|unique:dark_archives,case_code',
            'content' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title.'-'.Str::random(5));
        $data['user_id'] = Auth::id();

        // === LOGIKA UPLOAD FIX ===
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'evidence_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
            $file->storeAs('evidence_files', $filename, 'main_uploads');
            $data['thumbnail'] = 'uploads/evidence_files/'.$filename;
        }

        DarkArchive::create($data);

        return redirect()->route('dark-archives.index')->with('success', 'Arsip berhasil dibuat.');
    }

    public function edit($slug)
    {
        // Cari berdasarkan slug atau ID, sesuaikan dengan route Anda
        // Jika route pakai {id}, ganti jadi findOrFail($id)
        $archive = DarkArchive::where('slug', $slug)->firstOrFail();

        // Reuse view create, tapi kali ini variabel $archive ada isinya
        return view('dark-archives.create', compact('archive'));
    }

    public function update(Request $request, $id)
    {
        $archive = DarkArchive::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            // Ignore unique rule untuk ID yang sedang diedit
            'case_code' => 'required|unique:dark_archives,case_code,'.$archive->id,
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Update slug jika judul berubah (opsional, tapi disarankan agar SEO friendly)
        if ($request->title !== $archive->title) {
            $data['slug'] = Str::slug($request->title.'-'.Str::random(5));
        }

        // === LOGIKA UPDATE GAMBAR ===
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'evidence_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();

            // 1. Hapus gambar lama jika ada
            if ($archive->thumbnail) {
                // Path di DB: uploads/evidence_files/xxx.jpg
                // Path di Disk: evidence_files/xxx.jpg
                // Kita perlu hilangkan prefix 'uploads/' agar sesuai root disk 'main_uploads'
                $oldPath = str_replace('uploads/', '', $archive->thumbnail);

                if (Storage::disk('main_uploads')->exists($oldPath)) {
                    Storage::disk('main_uploads')->delete($oldPath);
                }
            }

            // 2. Upload gambar baru
            $file->storeAs('evidence_files', $filename, 'main_uploads');
            $data['thumbnail'] = 'uploads/evidence_files/'.$filename;
        }

        $archive->update($data);

        return redirect()->route('dark-archives.index')->with('success', 'Arsip berhasil diperbarui.');
    }
}
