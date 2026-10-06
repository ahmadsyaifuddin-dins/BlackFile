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
 * Kalau satu arsip punya beberapa tag era, urutannya memakai rentang yang
 * paling logis per arah: ASC memakai tahun TERAWAL (MIN), DESC memakai tahun
 * TERAKHIR (MAX). Jadi arsip Black Mesa (tag "199X" + "AKHIR ABAD KE 20")
 * akan muncul sebagai 1995 saat naik dan 2000 saat turun.
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
     * regex SQL yang rumit, tapi agregasi MIN/MAX tetap jalan di database.
     */
    private static function applyTagSort(Builder $query, string $direction): Builder
    {
        $ascending = strtoupper($direction) === 'ASC';

        // ASC -> tahun paling awal, DESC -> tahun paling akhir.
        $aggregate = $ascending ? 'MIN' : 'MAX';

        $case = TagEra::caseExpression(TagEra::map());

        $eraValue = DB::table('archive_tag')
            ->whereColumn('archive_tag.archive_id', 'archives.id')
            ->selectRaw("{$aggregate}({$case})");

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