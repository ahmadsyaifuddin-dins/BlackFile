<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Credit;
use App\Models\DarkArchive;
use App\Models\EncryptedContact;
use App\Models\Entity;
use App\Models\Friend;
use App\Models\Prototype;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class SystemInfoController extends Controller
{
    /**
     * Menampilkan halaman informasi sistem BlackFile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $project = config('blackfile.project', []);
        $timezone = config('app.timezone');

        // Tanggal proyek dibuat, diterjemahkan ke Bahasa Indonesia.
        $createdAt = Carbon::parse($project['created_at'] ?? now(), $timezone)->locale('id');
        $releasedAt = Carbon::parse($project['released_at'] ?? now(), $timezone)->locale('id');

        $projectOrigin = [
            'day' => $createdAt->translatedFormat('l'),
            'date' => $createdAt->translatedFormat('d'),
            'month' => $createdAt->translatedFormat('F'),
            'year' => $createdAt->translatedFormat('Y'),
            'time' => $createdAt->format('H:i'),
            'seconds' => $createdAt->format('s'),
            'full' => $createdAt->translatedFormat('l, d F Y'),
            'timestamp' => $createdAt->timestamp,
        ];

        // Lama waktu berjalan sejak proyek diinisialisasi.
        $uptime = $createdAt->diffForHumans(now(), [
            'syntax' => Carbon::DIFF_ABSOLUTE,
            'parts' => 3,
            'join' => true,
        ]);

        // Selisih versi terakhir dirilis dari sekarang.
        $lastRelease = $releasedAt->diffForHumans(now(), [
            'syntax' => Carbon::DIFF_ABSOLUTE,
            'parts' => 2,
            'join' => true,
        ]);

        $environment = [
            'app_name' => config('app.name'),
            'laravel' => app()->version(),
            'php' => PHP_VERSION,
            'environment' => app()->environment(),
            'debug' => config('app.debug') ? 'ENABLED' : 'DISABLED',
            'timezone' => $timezone,
            'locale' => config('app.locale'),
            'database' => strtoupper(config('database.default')),
            'database_name' => $this->databaseName(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? PHP_OS,
            'os' => PHP_OS,
        ];

        $stats = [
            ['label' => 'Agents', 'value' => User::count(), 'icon' => 'fa-user-secret'],
            ['label' => 'Entities', 'value' => Entity::count(), 'icon' => 'fa-dna'],
            ['label' => 'Prototypes', 'value' => Prototype::count(), 'icon' => 'fa-flask'],
            ['label' => 'Archives', 'value' => Archive::count(), 'icon' => 'fa-box-archive'],
            ['label' => 'Dark Archives', 'value' => DarkArchive::count(), 'icon' => 'fa-folder-closed'],
            ['label' => 'Friends Network', 'value' => Friend::count(), 'icon' => 'fa-diagram-project'],
            ['label' => 'Credits', 'value' => Credit::count(), 'icon' => 'fa-scroll'],
            ['label' => 'Encrypted Contacts', 'value' => EncryptedContact::count(), 'icon' => 'fa-lock'],
        ];

        return view('system-info.index', compact(
            'project',
            'projectOrigin',
            'releasedAt',
            'lastRelease',
            'uptime',
            'environment',
            'stats',
        ));
    }

    /**
     * Mengambil nama database aktif dengan aman.
     */
    private function databaseName(): string
    {
        try {
            return DB::connection()->getDatabaseName() ?: 'Not Available';
        } catch (Throwable $e) {
            return 'Not Available';
        }
    }
}
