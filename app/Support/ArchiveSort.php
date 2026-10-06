<?php

namespace App\Support;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Pengurutan (sorting) data arsip.
 *
 * Tiga kelompok urutan:
 * 1. Tanggal  -> terbaru / terlama (created_at)
 * 2. Abjad    -> A-Z / Z-A (name)
 * 3. Tag      -> berdasarkan era cerita pada tag
 *
 * Untuk urutan tag, setiap tag dinormalkan lebih dulu oleh TagEra menjadi satu
 * angka tahun: "1868" -> 1868, "ABAD KE 19" -> 1850, "49 SM" -> -49,
 * "199X" -> 1995, "MASA DEPAN" -> era paling akhir. Lihat TagEra.
 *
 * Kalau satu arsip punya BEBERAPA tag era, yang dipakai adalah tag yang PALING
 * PRESISI (dunia nyata: tahun pasti menang atas abad, abad menang atas "MASA
 * DEPAN"). Contoh: Hitman ber-tag "AWAL ABAD KE 21" + "2012" -> diurutkan
 * sebagai 2012, BUKAN 2001. London (tag "199X" + "AKHIR ABAD KE 20") -> 1995.
 *
 * Dulu memakai MIN/MAX atas SEMUA tag era, dan itu salah: "AWAL ABAD KE 21"
 * (2001) menyeret "2012" turun ke 2001 sehingga arsip ber-tahun pasti jadi
 * satu kelompok raksasa di 2001 dan akhirnya diurutkan alfabetis.
 *
 * Arsip tanpa tag era selalu ditaruh paling akhir di kedua arah, supaya tidak
 * dianggap "tahun 0" dan tidak mengacaukan urutan.
 */
class ArchiveSort
{
    /** Semua pilihan urutan yang valid: value => label. */
    public const OPTIONS = [
        'newest' => 'Newest First',
        'oldest' => 'Oldest First',
        'name_asc' => 'Name (A-Z)',
        'name_desc' => 'Name (Z-A)',
        'tag_asc' => 'Tag: Lowest to Highest',
        'tag_desc' => 'Tag: Highest to Lowest',
    ];

    /**
     * Normalisasi nilai `sort` dari request.
     * Nilai tak dikenal / kosong -> default 'newest' (perilaku lama).
     */
    public static function normalize(?string $sort): string
    {
        return isset(self::OPTIONS[$sort]) ? $sort : 'newest';
    }

    public static function isValid(?string $sort): bool
    {
        return $sort !== null && isset(self::OPTIONS[$sort]);
    }

    /**
     * Terapkan pengurutan ke query arsip.
     */
    public static function apply(Builder $query, ?string $sort): Builder
    {
        $sort = self::normalize($sort);

        return match ($sort) {
            'oldest' => $query->orderBy('archives.created_at')->orderBy('archives.id'),

            'name_asc' => $query
                ->orderByRaw('LOWER(archives.name) ASC')
                ->orderBy('archives.id'),

            'name_desc' => $query
                ->orderByRaw('LOWER(archives.name) DESC')
                ->orderBy('archives.id'),

            'tag_asc' => self::applyTagSort($query, 'asc'),

            'tag_desc' => self::applyTagSort($query, 'desc'),

            // default: 'newest'
            default => $query
                ->orderBy('archives.created_at', 'desc')
                ->orderByDesc('archives.id'),
        };
    }

    /**
     * Urutkan berdasarkan era pada tag.
     *
     * Nilai era tiap tag sudah dihitung di PHP oleh TagEra, lalu disuntikkan
     * ke SQL sebagai CASE tag_id => nilai. Dengan begitu query tidak perlu
     * regex SQL yang rumit.
     *
     * Karena satu arsip bisa punya beberapa tag era, CASE juga dibuat untuk
     * tingkat presisi (TagEra::specificityMap), lalu baris yang dipilih adalah
     * yang presisinya PALING TINGGI; antar tag se-presisi, ikut arah urutan
     * (ASC -> tahun terkecil, DESC -> tahun terbesar).
     */
    private static function applyTagSort(Builder $query, string $direction): Builder
    {
        $ascending = strtoupper($direction) === 'ASC';

        $valueCase = TagEra::caseExpression(TagEra::map());
        $specCase = TagEra::caseExpression(TagEra::specificityMap());

        // Untuk tiap arsip, ambil SATU tag pemenang: presisi tertinggi dulu,
        // lalu (jika se-presisi) urutkan nilainya sesuai arah. Arsip tanpa tag
        // era menghasilkan NULL -> selalu paling akhir.
        $eraValue = DB::table('archive_tag')
            ->whereColumn('archive_tag.archive_id', 'archives.id')
            ->orderByRaw("({$specCase}) DESC")
            ->orderByRaw("({$valueCase}) ".($ascending ? 'ASC' : 'DESC'))
            ->limit(1)
            ->selectRaw("({$valueCase}) AS sort_tag_era");

        $query->addSelect(['sort_tag_era' => $eraValue]);

        return $query
            // Tanpa tag era -> selalu paling akhir, di kedua arah.
            ->orderByRaw('CASE WHEN sort_tag_era IS NULL THEN 1 ELSE 0 END ASC')
            ->orderByRaw('sort_tag_era '.($ascending ? 'ASC' : 'DESC'))
            // Sama-sama satu era: urutkan huruf biar stabil.
            ->orderBy('archives.name');
    }

    /**
     * Label tag yang dipakai untuk ditampilkan di UI.
     */
    public static function label(?string $sort): string
    {
        return self::OPTIONS[self::normalize($sort)];
    }

    /**
     * Semua tag yang punya nilai era, untuk keperluan debug/diagnostics.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    public static function numericTagNames()
    {
        return Tag::all()
            ->filter(fn ($tag) => TagEra::value($tag->name) !== null)
            ->pluck('name')
            ->values();
    }
}