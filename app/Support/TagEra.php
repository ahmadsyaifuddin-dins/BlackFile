<?php

namespace App\Support;

/**
 * Mengubah nama tag menjadi "nilai era" numerik supaya arsip bisa diurutkan
 * berdasarkan waktu ceritanya.
 *
 * Bentuk tag di sistem ini beragam, jadi semuanya dinormalkan ke satu angka:
 *
 *   "2022"                    => 2022            tahun biasa
 *   "432 AC"                  => ERA_AC + 432    era "After Calamity" (fiksi)
 *   "49 SM"                   => -49             SM = Sebelum Masehi
 *   "ABAD KE 19"              => 1850            tengah abad ke-19
 *   "awal abad ke-18"         => 1701            awal abad ke-18
 *   "AKHIR ABAD KE 20"        => 2000            akhir abad ke-20
 *   "PERTENGAHAN ABAD KE 18"  => 1750            pertengahan abad ke-18
 *   "ABAD KE 5 SM"            => -450            abad ke-5 sebelum Masehi
 *   "199X"                    => 1995            rentang 1990-1999, dipakai tengahnya
 *   "MASA DEPAN"              => ERA_FUTURE      belum ada tahun, selalu paling akhir
 *
 * Tanggal sebelum Masehi bernilai negatif supaya urutannya benar: masa kuno
 * selalu muncul di depan masa modern.
 *
 * Soal sufiks "AC": di arsip ini "AC" berarti After CALAMITY, yaitu timeline
 * fiksi pascapaclisis yang belum pernah terjadi (mis. Project Wingman di 432 AC).
 * Angka "432" dipakai hanya sebagai pembanding di dalam kelompok era AC
 * (mis. 100 AC sebelum 432 AC), bukan sebagai posisi terhadap tahun Masehi.
 * Jadi "N AC" diperlakukan sebagai era MASA DEPAN dengan hitungan tahun, bukan
 * sebagai tahun 432 Masehi. Kalau tidak, Wingman akan tersisip di antara arsip
 * abad ke-15 dan ke-16, padahal secara cerita jauh setelah masa sekarang.
 *
 * Kalau suatu saat ada arsip yang benar-benar memakai "AC" untuk After Christ
 * (kalender historis), tag itu perlu diubah ke "M" atau "AD" supaya tidak ikut
 * terhitung sebagai masa depan.
 *
 * Yang TIDAK dianggap era (bernilai null) termasuk tag biasa seperti
 * "BAJAK LAUT", dan tag teknis seperti
 * "56.80 GB / Split 11 parts 4.95 GB Compressed" yang kebetulan punya angka.
 * Pola tahun menuntut 4 digit utuh (1000-2999) yang berdiri sendiri, sehingga
 * "56.80" atau "4.95" tidak akan salah terbaca.
 */
class TagEra
{
/**
     * Era masa depan yang belum ada tahun pastinya, mis. "MASA DEPAN".
     * Dipakai supaya entri ini selalu berada di posisi paling akhir saat
     * diurutkan dari yang terbaru, dan tidak dianggap "tahun 0".
     */
    public const ERA_FUTURE = 999999;

    /**
     * Titik awal era "After Calamity". Semua "N AC" diletakkan di atas semua
     * tahun Masehi (maks 2999) supaya timeline fiksi pascapaclisis selalu
     * berada di masa depan. Angkanya ditambahkan supaya "432 AC" tetap bisa
     * dibandingkan dengan "100 AC" di dalam kelompoknya sendiri.
     */
    public const ERA_AC_BASE = 100000;

    /** Sufiks yang menandai tahun sebelum Masehi. */
    private const BC_SUFFIXES = ['SM', 'BC', 'BCE'];

    /**
     * Nilai era dari nama tag, atau null kalau tag itu bukan penanda waktu.
     */
    public static function value(?string $name): ?int
    {
        if ($name === null) {
            return null;
        }

        // Normalisasi: huruf besar, spasi rapat, buang tanda baca di tepi.
        $n = strtoupper(trim(preg_replace('/\s+/', ' ', $name) ?? ''));
        $n = trim($n, " \t\n\r\0\x0B.,;:-");

        if ($n === '') {
            return null;
        }

        // 1. Masa depan: belum ada tahun, jadi selalu paling akhir.
        if (preg_match('/MASA\s+DEPAN|\bFUTURE\b|\bDEPAN\b/', $n)) {
            return self::ERA_FUTURE;
        }

        // 2. Penanda abad. Dicek sebelum pola tahun biasa supaya "ABAD KE 5 SM"
        //    terbaca sebagai abad, bukan tahun "-5".
        //    AWAL / AKHIR / PERTENGAHAN menentukan posisi di dalam abad itu.
        if (preg_match('/(AWAL|AKHIR|PERTENGAHAN|TENGAH)?\s*ABAD\s*KE\s*-?\s*(\d{1,2})\s*(SM|BC|BCE|AC)?\b/u', $n, $m)) {
            $century = (int) $m[2];

            if ($century < 1 || $century > 99) {
                return null;
            }

            $position = ($m[1] ?? '') !== '' ? strtoupper($m[1]) : 'PERTENGAHAN';
            $suffix = $m[3] ?? null;

            // Abad ke-N secara formal berjalan 100*(N-1)+1 .. 100*N.
            $value = match ($position) {
                'AWAL' => 100 * ($century - 1) + 1,
                'AKHIR' => 100 * $century,
                default => 100 * ($century - 1) + 50,
            };

            // "ABAD KE 7 AC" -> era After Calamity, bukan tahun Masehi.
            if ($suffix !== null && strtoupper($suffix) === 'AC') {
                return self::ERA_AC_BASE + $value;
            }

            return self::isBeforeCommonEra($suffix) ? -$value : $value;
        }

        // 3. Tahun berlabel era: "432 AC", "49 SM", "1200 M".
        //    "AC" ditangani terpisah karena maknanya bukan tahun Masehi.
        if (preg_match('/(\d{1,4})\s*AC\b/u', $n, $m)) {
            return self::ERA_AC_BASE + (int) $m[1];
        }

        if (preg_match('/(\d{1,4})\s*(SM|BCE|BC|MASEHI|AD|CE|M)\b/u', $n, $m)) {
            $year = (int) $m[1];

            return self::isBeforeCommonEra($m[2]) ? -$year : $year;
        }

        // 4. Rentang decade: "199X" berarti 1990-1999, dipakai angka tengahnya.
        if (preg_match('/(?<!\d)(\d{3})X(?!\d)/i', $n, $m)) {
            return (int) $m[1] * 10 + 5;
        }

        // 5. Tahun polos 4 digit (1000-2999) yang berdiri sendiri, mis. "2022"
        //    atau "Jan 20 2021".
        if (preg_match('/(?<![\d.,])((?:1[0-9]|2[0-9])\d{2})(?![\d.,])/', $n, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    /**
     * Peta tag_id => nilai era, hanya untuk tag yang punya nilai.
     *
     * Dipakai ArchiveSort untuk menyusun ekspresi CASE di SQL. Logika parsing
     * tetap di PHP (bukan regex SQL yang sulit dirawat), sementara pengurutan
     * tetap dikerjakan database.
     *
     * @param  iterable<int, \App\Models\Tag>|null  $tags
     * @return array<int, int>
     */
    public static function map(?iterable $tags = null): array
    {
        $tags ??= \App\Models\Tag::all();

        $map = [];

        foreach ($tags as $tag) {
            $value = self::value($tag->name);

            if ($value !== null) {
                $map[(int) $tag->id] = $value;
            }
        }

        return $map;
    }

    /**
     * Ekspresi SQL yang mengubah tag_id menjadi nilai era.
     * Tag yang tidak ada di peta -> NULL.
     */
    public static function caseExpression(array $map, string $column = 'archive_tag.tag_id'): string
    {
        if ($map === []) {
            return 'NULL';
        }

        $sql = 'CASE '.$column;

        foreach ($map as $tagId => $value) {
            $sql .= ' WHEN '.(int) $tagId.' THEN '.(int) $value;
        }

        return $sql.' ELSE NULL END';
    }

    private static function isBeforeCommonEra(?string $suffix): bool
    {
        return $suffix !== null && in_array(strtoupper($suffix), self::BC_SUFFIXES, true);
    }
}