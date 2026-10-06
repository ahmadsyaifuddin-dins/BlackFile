<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Support\ArchiveReturnUrl;
use App\Support\ArchiveSort;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ArchiveSortTest extends TestCase
{
    use CreatesApplication;

    // Tanpa spasi supaya tidak ikut tertangkap regex nama arsip saat parsing HTML.
    private const CATEGORY = 'ZZSORTGAME';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::where('name', 'Director')->firstOrFail();

        $user = User::where('email', 'sorttest@blackfile.local')->first();

        if (! $user) {
            $user = new User;
            $user->email = 'sorttest@blackfile.local';
            $user->name = 'Sort Test';
            $user->username = 'sorttest';
            $user->codename = 'SORT-TEST';
            $user->slug = 'sort-test';
            $user->password = 'secret1234';
            $user->master_password = 'secret1234';
            $user->temp_password = 'temp1234';
        }

        $user->confirmed = true;
        $user->role_id = $role->id;
        $user->settings = ['per_page' => 50];
        $user->save();

        $this->user = $user;

        $this->cleanup();

        // Skenario dari kasus nyata: game dengan tag abad + tag tahun.
        $this->makeArchive('ZZSORT Syndicate', '2024-01-05 10:00:00', ['AC', 'ABAD KE 19', '1868']);
        $this->makeArchive('ZZSORT Brotherhood', '2024-01-04 10:00:00', ['AC', 'ABAD KE 16', '1500']);
        $this->makeArchive('ZZSORT Origins', '2024-01-06 10:00:00', ['AC', 'ABAD KE 15', '1400']);
        $this->makeArchive('ZZSORT Unity', '2024-01-07 10:00:00', ['AC', 'ABAD KE 19']);
        $this->makeArchive('ZZSORT Tanpa Angka', '2024-01-08 10:00:00', ['AC']);
        $this->makeArchive('ZZSORT Omega', '2024-01-09 10:00:00', ['AC', 'ABAD KE 18']);
    }

    protected function tearDown(): void
    {
        $this->cleanup();

        User::where('email', 'sorttest@blackfile.local')->delete();

        parent::tearDown();
    }

    private function cleanup(): void
    {
        Archive::where('category', self::CATEGORY)->forceDelete();
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function makeArchive(string $name, string $createdAt, array $tags): Archive
    {
        $archive = Archive::create([
            'user_id' => $this->user->id,
            'name' => $name,
            'description' => 'probe sorting',
            'type' => 'url',
            'category' => self::CATEGORY,
            'is_public' => true,
            'links' => ['https://example.test'],
        ]);

        $archive->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->save();

        $tagIds = [];
        foreach ($tags as $tag) {
            $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
        }

        $archive->tags()->sync($tagIds);

        return $archive;
    }

    /**
     * Ambil urutan nama arsip hasil sorting dari endpoint index.
     *
     * @return array<int, string>
     */
    private function names(string $sort): array
    {
        $response = $this->actingAs($this->user)->get('/archives?'.http_build_query([
            'category' => self::CATEGORY,
            'sort' => $sort,
            'per_page' => 50,
        ]), ['HTTP_HOST' => '127.0.0.1:8000']);

        $response->assertOk();

        $html = $response->getContent();

        preg_match_all('/ZZSORT ([A-Za-z]+)/', $html, $m);

        // Nama unik, urutan kemunculan pertama di HTML = urutan tampil
        return array_values(array_unique($m[1]));
    }

    public function test_newest_puts_most_recent_first()
    {
        $this->assertSame(
            ['Omega', 'Tanpa', 'Unity', 'Origins', 'Syndicate', 'Brotherhood'],
            $this->names('newest')
        );
    }

    public function test_oldest_puts_least_recent_first()
    {
        $this->assertSame(
            ['Brotherhood', 'Syndicate', 'Origins', 'Unity', 'Tanpa', 'Omega'],
            $this->names('oldest')
        );
    }

    public function test_name_ascending_is_alphabetical_and_case_insensitive()
    {
        $this->assertSame(
            ['Brotherhood', 'Omega', 'Origins', 'Syndicate', 'Tanpa', 'Unity'],
            $this->names('name_asc')
        );
    }

    public function test_name_descending_is_reversed_alphabetical()
    {
        $this->assertSame(
            ['Unity', 'Tanpa', 'Syndicate', 'Origins', 'Omega', 'Brotherhood'],
            $this->names('name_desc')
        );
    }

    public function test_tag_ascending_sorts_by_smallest_numeric_tag()
    {
        // ASC: century-only (18, 19) lebih kecil dari tahun (1400, 1500, 1868)
        $this->assertSame(
            ['Omega', 'Unity', 'Origins', 'Brotherhood', 'Syndicate', 'Tanpa'],
            $this->names('tag_asc')
        );
    }

    public function test_tag_descending_sorts_by_largest_numeric_tag()
    {
        $this->assertSame(
            ['Syndicate', 'Brotherhood', 'Origins', 'Unity', 'Omega', 'Tanpa'],
            $this->names('tag_desc')
        );
    }

    public function test_year_tag_takes_priority_over_century_tag()
    {
        // "ABAD KE 19" bernilai 19, "1868" bernilai 1868. Kalau century ikut
        // dihitung sebagai angka biasa, 19 akan selalu kalah dan urutannya salah.
        $withBoth = Archive::where('category', self::CATEGORY)
            ->where('name', 'ZZSORT Syndicate')
            ->firstOrFail();

        $years = $withBoth->tags->filter(fn ($t) => (int) $t->name === 1868)->count();
        $centuries = $withBoth->tags->filter(fn ($t) => $t->name === 'ABAD KE 19')->count();

        $this->assertSame(1, $years);
        $this->assertSame(1, $centuries);

        // Urutannya harus berdasarkan 1868, bukan 19.
        $this->assertSame(
            ['Syndicate', 'Brotherhood', 'Origins', 'Unity', 'Omega', 'Tanpa'],
            $this->names('tag_desc')
        );
    }

    public function test_unknown_sort_value_falls_back_to_newest()
    {
        $this->assertSame(
            ['Omega', 'Tanpa', 'Unity', 'Origins', 'Syndicate', 'Brotherhood'],
            $this->names('drop table')
        );

        $this->assertSame('newest', ArchiveSort::normalize('drop table'));
        $this->assertSame('newest', ArchiveSort::normalize(null));
    }

    public function test_filter_form_offers_all_six_sort_options()
    {
        $response = $this->actingAs($this->user)->get('/archives', ['HTTP_HOST' => '127.0.0.1:8000']);
        $response->assertOk();

        $html = $response->getContent();

        $this->assertStringContainsString('name="sort"', $html, 'kontrol sort tidak ada di filter form');

        // Opsi select dirender sebagai JSON ke dalam Alpine state (bukan <option>),
        // dan Blade meng-escape quote-nya jadi &quot;.
        foreach (ArchiveSort::OPTIONS as $value => $label) {
            $this->assertStringContainsString(
                htmlspecialchars(json_encode($value).':'.json_encode($label), ENT_QUOTES),
                $html,
                "opsi sort {$value} tidak dirender"
            );
        }
    }

    public function test_sort_survives_redirect_around_edit()
    {
        // Urutan yang aktif harus ikut dibawa ke halaman detail & form edit,
        // lalu kembali ke index dengan urutan yang sama.
        $indexUrl = '/archives?'.http_build_query([
            'category' => self::CATEGORY,
            'sort' => 'tag_desc',
            'page' => 1,
        ]);

        $this->actingAs($this->user)->get($indexUrl, ['HTTP_HOST' => '127.0.0.1:8000'])->assertOk();

        $return = urlencode($indexUrl);

        $this->actingAs($this->user)
            ->get('/archives?'.http_build_query(['category' => self::CATEGORY, 'return_url' => $return]),
                ['HTTP_HOST' => '127.0.0.1:8000'])
            ->assertOk();

        $back = ArchiveReturnUrl::forController($indexUrl);

        $this->assertStringContainsString('sort=tag_desc', $back, 'sort tidak ikut dibawa ke return_url');
    }

    public function test_invalid_sort_value_is_stripped_from_return_url()
    {
        $dirty = '/archives?category='.rawurlencode(self::CATEGORY).'&sort='.rawurlencode('<script>');

        $clean = ArchiveReturnUrl::forController($dirty);

        $this->assertStringNotContainsString('sort=', $clean, 'sort ngawur tidak boleh ikut');
        $this->assertStringContainsString('category=', $clean, 'filter sah ikut terbuang');
    }
}