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
 * 3. Tag      -> berdasarkan angka pada tag (mis. "ABAD KE 19" atau "1868")
 *
 * Untuk urutan tag, angka diambil dari tag dengan prioritas:
 *   1. Tag TAHUN  (4 digit, mis. "1868")  -> dipakai
 *   2. Tag ABAD   (mis. "ABAD KE 19")     -> dipakai kalau tidak ada tag tahun
 *   3. Tag lain   ("Game", "AC")          -> NULL, diabaikan
 *
 * Prioritas ini penting: kalau langsung ambil MIN/MAX dari semua angka, maka
 * "ABAD KE 19" (nilai 19) akan tercampur dengan "1868" (nilai 1868) dan
 * hampir selalu kalah, padahal yang jelas lebih spesifik adalah tahun.
 *
 * Arsip tanpa tag bernumber dianggap paling "lama" untuk ASC dan paling
 * "baru"/terakhir untuk DESC, jadi tetap tampil tapi tidak mengacaukan urutan.
 */
class ArchiveSort
{
    /** Nilai tag yang dianggap "tidak ada angka" (diabaikan). */
    private const NULL_RANK_ASC = 1;

    private const NULL_RANK_DESC = 0;

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
     * Regex tag tahun: 4 digit mulai 1xxx/2xxx, mis. "1868", "1500".
     * Angka harus berdiri sendiri agar "ABAD KE 19" tidak ikut.
     */
    private const YEAR_PATTERN = '(^|[^0-9])[12][0-9]{3}([^0-9]|$)';

    /**
     * Urutkan berdasarkan angka pada tag (tahun, atau abad sebagai cadangan).
     */
    private static function applyTagSort(Builder $query, string $direction): Builder
    {
        $aggregate = $direction === 'asc' ? 'MIN' : 'MAX';
        $nullRank = $direction === 'asc' ? self::NULL_RANK_ASC : self::NULL_RANK_DESC;

        // Subquery 1: angka TAHUN milik arsip ini (paling spesifik, prioritas)
        $yearTag = static::numericTagSubQuery($query, self::YEAR_PATTERN, $aggregate);

        // Subquery 2: angka dari tag ABAD / token bernomor lain (cadangan)
        $fallbackTag = static::numericTagSubQuery($query, '[0-9]', $aggregate);

        $query->addSelect([
            'sort_tag_year' => $yearTag,
            'sort_tag_any' => $fallbackTag,
        ]);

        return $query
            // Arsip tanpa tag bern selalu ditaruh paling akhir, supaya tidak
            // dianggap sebagai "tahun 0".
            ->orderByRaw('CASE WHEN COALESCE(sort_tag_year, sort_tag_any) IS NULL THEN '.$nullRank.' ELSE 0 END ASC')
            // Tahun menang kalau ada; kalau tidak, pakai angka tag lain.
            ->orderByRaw('COALESCE(sort_tag_year, sort_tag_any) '.(strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC'))
            ->orderBy('archives.name');
    }

    /**
     * Subquery: nilai numerik terbaik dari tag milik tiap arsip.
     *
     * @param  string  $condition  syarat WHERE tambahan untuk memilih tag
     */
    private static function numericTagSubQuery(Builder $query, string $condition, string $aggregate): \Illuminate\Database\Query\Builder
    {
        return DB::table('tags')
            ->join('archive_tag', 'tags.id', '=', 'archive_tag.tag_id')
            ->whereColumn('archive_tag.archive_id', 'archives.id')
            ->whereRaw('tags.name REGEXP ?', [$condition])
            ->selectRaw("{$aggregate}(CAST(SUBSTRING_INDEX(tags.name, ' ', -1) AS UNSIGNED))");
    }

    /**
     * Label tag yang dipakai untuk ditampilkan di UI.
     */
    public static function label(?string $sort): string
    {
        return self::OPTIONS[self::normalize($sort)];
    }

    /**
     * Semua nilai tag bern yang ada, untuk keperluan debug/diagnostics.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    public static function numericTagNames()
    {
        return Tag::whereRaw('name REGEXP ?', ['[0-9]'])->pluck('name');
    }
}