<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Prototype;
use App\Models\User;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// Tambahan untuk memindai direktori
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

class DashboardController extends Controller
{
    /**
     * Helper function to recursively get directory size.
     *
     * @param string $path
     * @return int
     */
    private function getDirectorySize(string $path): int
    {
        $bytesTotal = 0;
        
        // Pastikan path ada sebelum mencoba memindai
        if (!file_exists($path) || !is_dir($path)) {
            return 0;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_FILEINFO),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            // Hanya tambahkan ukuran jika itu adalah file
            if ($file->isFile()) {
                $bytesTotal += $file->getSize();
            }
        }
        return $bytesTotal;
    }

    /**
     * Helper function to format bytes into human-readable string.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes === 0) {
            return '0 Bytes';
        }

        $base = log($bytes, 1024);
        $suffixes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        $floor = floor($base);
        $suffix = $suffixes[$floor] ?? 'TB'; // Default ke TB jika sangat besar

        return round(pow(1024, $base - $floor), $precision) . ' ' . $suffix;
    }


    /**
     * Display the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung metrik utama (tidak berubah)
        $totalAgents = User::count();
        $totalEntities = Entity::count();
        $totalPrototypes = Prototype::count();

        // Mengambil 5 entitas yang terakhir diperbarui (tidak berubah)
        $recentActivities = Entity::orderBy('updated_at', 'desc')->take(12)->get();

        // Mengambil satu entitas berbahaya secara acak (tidak berubah)
        $highThreatRanks = ['Keter', 'Apollyon', 'Omega (Global Threat)', 'Cosmic (Existential Threat)', 'Outer God'];
        $systemAlert = Entity::whereIn('rank', $highThreatRanks)->inRandomOrder()->first();

        // Mengambil status jaringan agen (tidak berubah)
        $activeAgents = User::whereNotNull('last_active_at')
            ->where('id', '!=', Auth::id())
            ->orderBy('last_active_at', 'desc')
            ->take(4)
            ->get();

        // Menghitung status proyek (tidak berubah)
        $projectStatuses = Prototype::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 1. Total Public Archives (File + URL, is_public = 1)
        $totalPublicArchives = Archive::where('is_public', 1)->count();

        // 2. Total URL Archives (Semua status)
        $totalUrlArchives = Archive::where('type', 'url')->count();

        // 3. Total File Archives (Semua status)
        $totalFileArchives = Archive::where('type', 'file')->count();

        // 4. Physical Storage Size (Memindai disk)
        $storagePath = public_path('uploads/archives');

        // Menggunakan @ untuk menekan peringatan jika path di luar open_basedir
        if (!@is_dir($storagePath) || !@file_exists($storagePath)) {
            
            // Coba path alternatif (untuk struktur hosting /htdocs/uploads/archives)
            // Menggunakan $_SERVER['DOCUMENT_ROOT'] seperti yang ditunjukkan oleh debug
            // Tambahkan ?? '' untuk fallback jika var server tidak diset
            $alternatePath = ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/uploads/archives';
            
            // Jika path alternatif ada, gunakan itu
            if (@is_dir($alternatePath) && @file_exists($alternatePath)) {
                $storagePath = $alternatePath;
            }
        }


        $totalPhysicalBytes = $this->getDirectorySize($storagePath);
        $totalPhysicalStorage = $this->formatBytes($totalPhysicalBytes);

        // 5. Menghitung Distribusi Peringkat Entitas (Tidak berubah)
        $entityRankDistribution = Entity::query()
            ->select('rank', DB::raw('count(*) as total'))
            ->groupBy('rank')
            ->orderBy('total', 'desc')
            ->get();

        // Mengirim semua data ke view, termasuk data baru
        return view('dashboard', [
            'totalAgents' => $totalAgents,
            'totalEntities' => $totalEntities,
            'totalPrototypes' => $totalPrototypes,
            'recentActivities' => $recentActivities,
            'systemAlert' => $systemAlert,
            'activeAgents' => $activeAgents,
            'projectStatuses' => $projectStatuses,
            'entityRankDistribution' => $entityRankDistribution,
            
            // Variabel Metrik Arsip yang Baru
            'totalPublicArchives' => $totalPublicArchives,
            'totalUrlArchives' => $totalUrlArchives,
            'totalFileArchives' => $totalFileArchives,
            'totalPhysicalStorage' => $totalPhysicalStorage,
        ]);
    }
}