<?php

namespace App\Support;

/**
 * Utilitas untuk membawa "posisi user" (filter + halaman paginasi) saat
 * berpindah halaman: index -> show -> edit -> simpan -> index.
 *
 * Masalah yang diselesaikan:
 * `return_url` Originally dibuat dari `request()->fullUrl()`, yaitu string URL
 * MENTAJA. Saat string itu ditulis ke `href` dengan `{{ }}`, Blade mengubah
 * `&` menjadi `&amp;`. Kalau string itu lalu di-`urlencode()` lagi dan
 * diputar ke halaman berikutnya, karakternya berantai:
 *
 *     ?category=Link%20Game&page=2
 *   -> ?category=Link%20Game&amp;page=2      (& jadi &amp; di href)
 *   -> ?category=Link%20Game&amp;amp;page=2  (|urlencode| lagi)
 *   -> ...&amp;amp;amp;page=2...&page=2      (bertambah terus)
 *
 * Akibatnya `page` hilang (dikirim sebagai `amp;page`), sehingga user selalu
 * mendarat di halaman 1.
 *
 * Solusi: JANGAN mempercayai string URL yang berputar. Di setiap hop, parse
 * query-nya, buang kunci sampah yang diawali `amp;`, buang `focus`/`focus_miss`,
 * lalu bangun ulang URL dengan `route()` supaya `&`-nya benar-benar `&`.
 */
class ArchiveReturnUrl
{
    /** Parameter yang menandai posisi baris, bukan filter. */
    private const POINTER_KEYS = ['focus', 'focus_miss'];

    /** Kunci yang hanya perlu dihapus dari URL. */
    private const DROP_KEYS = ['return_url'];

    /**
     * Bersihkan `return_url` menjadi array parameter yang aman dipakai `route()`.
     *
     * @param  string|null  $return  URL return (boleh absolut, relatif, atau rusak)
     * @param  bool  $keepPointers  true bila ingin mempertahankan focus/focus_miss
     * @return array<string, mixed>
     */
    public static function params(?string $return, bool $keepPointers = false): array
    {
        $decoded = static::flatten($return);

        if ($decoded === null) {
            return [];
        }

        $parts = parse_url($decoded);

        $query = [];
        parse_str($parts['query'] ?? '', $query);

        $clean = [];

        foreach ($query as $key => $value) {
            $key = (string) $key;

            // 1. Perbaiki kunci sampah hasil rantai "&amp;" -> "amp;page",
            //    "amp;amp;page", dll. Ambil nama aslinya (page) lagi supaya
            //    posisi paginasi tidak hilang. Kalau versi bersihnya juga ada,
            //    pakai yang bersih.
            if (preg_match('/^(?:amp;)+/i', $key, $m)) {
                $repaired = substr($key, strlen($m[0]));

                if ($repaired === '' || array_key_exists($repaired, $query)) {
                    continue;
                }

                $key = $repaired;
            }

            // 2. Buang return_url agar tidak bersarang bertingkat
            if (in_array($key, static::DROP_KEYS, true)) {
                continue;
            }

            // 3. Buang penanda posisi kecuali memang diminta
            if (! $keepPointers && in_array($key, static::POINTER_KEYS, true)) {
                continue;
            }

            // 4. Buang nilai kosong supaya URL tetap rapi
            if ($value === '' || $value === null || $value === []) {
                continue;
            }

            $clean[$key] = $value;
        }

        return $clean;
    }

    /**
     * URL index Archives yang bersih, dibangun via route() supaya `&`-nya asli.
     * Sumbernya adalah URL halaman yang sedang aktif.
     *
     * @param  array<string, mixed>  $extra
     */
    public static function index(array $extra = []): string
    {
        $params = static::params(request()->fullUrl());

        return route('archives.index', array_merge($params, $extra));
    }

    /**
     * Ambil `return_url` milik user saat ini, lalu bangun ulang jadi URL index bersih.
     * Dipakai tombol BACK di halaman detail.
     *
     * @param  array<string, mixed>  $extra
     */
    public static function back(array $extra = [], bool $keepPointers = true): string
    {
        $params = static::params(static::rawInput(), $keepPointers);

        // Kalau tidak ada return_url, pakai filter yang sedang aktif (mis. saat
        // user membuka detail lewat bookmark lalu menekan BACK).
        if ($params === []) {
            $params = static::params(request()->fullUrl(), $keepPointers);
        }

        return route('archives.index', array_merge($params, $extra));
    }

    /**
     * Nilai return_url dari request, sudah di-flatten dari rantai encoding.
     */
    public static function rawInput(): ?string
    {
        $value = request()->input('return_url');

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * Buka rantai URL encoding sampai stabil supaya `&amp;amp;` jadi `&`.
     * Batasi jumlah putaran supaya tidak-loop.
     */
    private static function flatten(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $current = $url;

        for ($i = 0; $i < 5; $i++) {
            $next = rawurldecode(html_entity_decode($current, ENT_QUOTES, 'UTF-8'));

            if ($next === $current) {
                break;
            }

            $current = $next;
        }

        return $current;
    }

    /**
     * Nilai return_url mentah milik user saat ini (untuk controller).
     *
     * @param  array<string, mixed>  $extra
     */
    public static function forController(?string $input, array $extra = []): string
    {
        $params = static::params($input);

        return route('archives.index', array_merge($params, $extra));
    }
}