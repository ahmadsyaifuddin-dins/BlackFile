<?php

namespace Tests\Feature;

use App\Support\TagEra;
use PHPUnit\Framework\TestCase;

class TagEraTest extends TestCase
{
    public static function eraProvider(): array
    {
        return [
            // Tahun Masehi, bentuk polos.
            'tahun 4 digit' => ['1868', 1868],
            'tahun 2xxx' => ['2022', 2022],
            'tahun 1xxx' => ['1400', 1400],

            // Sebelum Masehi -> negatif.
            'sebelum masehi SM' => ['49 SM', -49],
            'sebelum masehi SM besar' => ['1250 SM', -1250],
            'sebelum masehi BC' => ['300 BC', -300],
            'sebelum masehi BCE' => ['300 BCE', -300],

            // Setelah Masehi, mengikuti-meaning BC/AD.
            'AC = After Christ' => ['432 AC', 432],
            'AD' => ['432 AD', 432],
            'M = Masehi' => ['1900 M', 1900],

            // Abad, dengan penanda posisi.
            'abad polos = tengah' => ['ABAD KE 19', 1850],
            'abad dengan spasi' => ['ABAD KE 16', 1550],
            'abad dengan strip' => ['ABAD KE-18', 1750],
            'awal abad' => ['awal abad ke-18', 1701],
            'akhir abad' => ['AKHIR ABAD KE 19', 1900],
            'pertengahan abad' => ['PERTENGAHAN ABAD KE 18', 1750],
            'pertengahan abad 21' => ['PERTENGAHAN ABAD KE 21', 2050],
            'abad sebelum masehi' => ['ABAD KE 5 SM', -450],
            'akhir abad 20' => ['AKHIR ABAD KE 20', 2000],

            // Rentang decade: "199X" -> 1990-1999, dipakai tengahnya.
            'decade 199X' => ['199X', 1995],
            'decade lowercase' => ['199x', 1995],
            'decade 189X' => ['189X', 1895],

            // Masa depan: nilai khusus, selalu paling akhir.
            'masa depan' => ['MASA DEPAN', TagEra::ERA_FUTURE],

            // Tahun yang tersembunyi di dalam tanggal.
            'tanggal amerika' => ['Jan 20 2021', 2021],
        ];
    }

    /**
     * @dataProvider eraProvider
     */
    public function test_it_normalizes_era_tags_to_a_single_year(string $tag, int $expected)
    {
        $this->assertSame($expected, TagEra::value($tag), "tag: {$tag}");
    }

    public static function ignoredProvider(): array
    {
        return [
            'tag biasa' => ['BAJAK LAUT'],
            'tag franchise' => ['WORLD OF ASSASSINATION'],
            'nama karakter' => ['SHAY PATRICK CORMAC'],
            'kode AC saja tanpa angka' => ['AC'],
            'ukuran file dengan angka' => ['56.80 GB / Split 11 parts 4.95 GB Compressed'],
            'ukuran file 2 bagian' => ['6.84 GB / Split 2 parts 4.95 GB Compressed'],
            'kota' => ['New York'],
            'sebelum masehi tanpa angka' => ['SEBELUM MASEHI'],
            'abad tanpa nomor' => ['ABAD KE'],
            'abad di luar rentang' => ['ABAD KE 0'],
            'abad melebihi 99' => ['ABAD KE 100'],
            'string kosong' => ['   '],
            'hanya tanda baca' => ['-'],
        ];
    }

    /**
     * @dataProvider ignoredProvider
     */
    public function test_it_ignores_tags_that_are_not_era_markers(string $tag)
    {
        $this->assertNull(TagEra::value($tag), "tag: {$tag}");
    }

    public function test_null_and_empty_input_return_null()
    {
        $this->assertNull(TagEra::value(null));
        $this->assertNull(TagEra::value(''));
    }

    public function test_before_common_era_sorts_before_ad()
    {
        // "49 SM" harus selalu mendahului "432 AC".
        $this->assertLessThan(TagEra::value('432 AC'), TagEra::value('49 SM'));
    }

    public function test_future_era_is_above_every_real_year()
    {
        $this->assertGreaterThan(TagEra::value('2999'), TagEra::value('MASA DEPAN'));
        $this->assertGreaterThan(TagEra::value('AKHIR ABAD KE 20'), TagEra::value('MASA DEPAN'));
    }

    public function test_century_and_year_land_in_the_same_scale()
    {
        // "ABAD KE 19" (1850) harus di antara 1752 dan 2022, bukan di angka 19.
        $this->assertGreaterThan(TagEra::value('1752'), TagEra::value('ABAD KE 19'));
        $this->assertLessThan(TagEra::value('2022'), TagEra::value('ABAD KE 19'));
    }

    public function test_case_expression_maps_tag_ids_and_defaults_to_null()
    {
        $sql = TagEra::caseExpression([7 => 1850, 9 => -49], 'archive_tag.tag_id');

        $this->assertStringContainsString('WHEN 7 THEN 1850', $sql);
        $this->assertStringContainsString('WHEN 9 THEN -49', $sql);
        $this->assertStringEndsWith('ELSE NULL END', $sql);
    }

    public function test_case_expression_is_null_when_there_are_no_era_tags()
    {
        $this->assertSame('NULL', TagEra::caseExpression([]));
    }
}