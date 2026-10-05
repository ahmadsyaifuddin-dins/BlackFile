<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ArchiveReturnUrlTest extends TestCase
{
    use CreatesApplication;

    private User $user;

    /** @var array<int, Archive> */
    private array $made = [];

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::where('name', 'Director')->firstOrFail();

        $user = User::where('email', 'returl@blackfile.local')->first();

        if (! $user) {
            $user = new User;
            $user->email = 'returl@blackfile.local';
            $user->name = 'Return Url Test';
            $user->username = 'returltest';
            $user->codename = 'RETURL-TEST';
            $user->slug = 'return-url-test';
            $user->password = 'secret1234';
            $user->master_password = 'secret1234';
            $user->temp_password = 'temp1234';
        }

        $user->confirmed = true;
        $user->role_id = $role->id;
        $user->settings = ['archive_edit_redirect' => 'index_position', 'per_page' => 6];
        $user->save();

        $this->user = $user;

        Archive::where('name', 'like', 'ZZRET%')->forceDelete();

        // per_page 6, terurut terbaru -> halaman 2 berisi arsip #8..#3
        for ($i = 1; $i <= 14; $i++) {
            $a = Archive::create([
                'user_id' => $user->id,
                'name' => 'ZZRET '.$i,
                'description' => 'd'.$i,
                'type' => 'url',
                'category' => 'Link Game',
                'is_public' => true,
                'links' => ['https://example.test'],
            ]);

            $a->created_at = now()->subDays(20 - $i);
            $a->updated_at = $a->created_at;
            $a->save();

            $this->made[$i] = $a;
        }
    }

    protected function tearDown(): void
    {
        Archive::where('name', 'like', 'ZZRET%')->forceDelete();
        User::where('email', 'returl@blackfile.local')->delete();

        parent::tearDown();
    }

    private function host(): array
    {
        return ['HTTP_HOST' => '127.0.0.1:8000'];
    }

    /** Ambil href pertama yang cocok pola, sudah di-decode entity HTML-nya. */
    private function href(string $html, string $pattern): string
    {
        preg_match($pattern, $html, $m);

        return html_entity_decode($m[1] ?? '', ENT_QUOTES, 'UTF-8');
    }

    /** Nilai JS di dalam atribut x-data, di-JSON-decode. */
    private function jsValue(string $html, string $key): ?string
    {
        preg_match('/'.$key.':\s*(.+?),\r?\n/', $html, $m);

        $raw = trim($m[1] ?? '');

        if ($raw === '') {
            return null;
        }

        // Buang pembungkus: JSON.parse('...') atau kutip tunggal/ganda
        $raw = preg_replace('/^JSON\.parse\((.*)\)$/s', '$1', $raw);
        $raw = trim($raw, "'\"");

        // Urai escape yang dipakai @js()
        return str_replace(
            ['\\/', '\\u0026', '\\u0020', '\\u003D', '\\u002B', '\\\\'],
            ['/', '&', ' ', '=', '+', '\\'],
            $raw
        );
    }

    public function test_index_page2_show_back_and_edit_chain_stays_clean()
    {
        // Halaman 2 dengan filter category Link Game
        $page2 = '/archives?category=Link%20Game&page=2';
        $target = $this->made[8]; // arsip yang benar-benar ada di halaman 2

        $idx = $this->actingAs($this->user)->get($page2, $this->host());
        $idx->assertOk();
        $idx->assertSee('ZZRET 8', false);

        // -- 1. Link "show" harus membawa page=2, tanpa '&amp;'
        $showHref = $this->href(
            $idx->getContent(),
            '/href="([^"]*\/archives\/'.$target->id.'[^"]*return_url=[^"]+)"/'
        );

        $this->assertNotSame('', $showHref, 'link show tidak ditemukan');

        $q = [];
        parse_str((string) parse_url($showHref, PHP_URL_QUERY), $q);

        $this->assertArrayNotHasKey('amp;page', $q, 'ada param amp;page di link show');
        $this->assertStringNotContainsString('amp%3B', $showHref, 'ada "amp;" ter-encode di link show');

        // -- 2. Buka detail, tombol BACK harus kembali ke page 2 (bukan page 1)
        $show = $this->actingAs($this->user)->get($showHref, $this->host());
        $show->assertOk();

        $backHref = $this->href($show->getContent(), '/href="([^"]*\/archives\?[^"]*)"[^>]*>\s*\[ ESC \]/');

        $this->assertNotSame('', $backHref, 'tombol BACK tidak ditemukan');

        $this->assertStringNotContainsString('amp;', $backHref, 'BACK mengandung "amp;"');
        $this->assertStringNotContainsString('&amp;', $backHref, 'BACK mengandung "&amp;"');

        $bq = [];
        parse_str((string) parse_url($backHref, PHP_URL_QUERY), $bq);
        $this->assertSame('Link Game', $bq['category'] ?? null, 'filter category hilang di BACK');
        $this->assertSame('2', (string) ($bq['page'] ?? null), 'page hilang di BACK');

        // -- 3. Klik BACK harus benar-benar tampilkan isi halaman 2
        $back = $this->actingAs($this->user)->get($backHref, $this->host());
        $back->assertOk();
        $back->assertSee('ZZRET 8', false);
        $back->assertDontSee('ZZRET 14', false);

        // -- 4. Detail -> EDIT harus meneruskan posisi yang sama
        $editHref = $this->href(
            $show->getContent(),
            '/href="([^"]*\/archives\/'.$target->id.'\/edit\?return_url=[^"]+)"/'
        );

        $this->assertNotSame('', $editHref, 'link EDIT di detail tidak ditemukan');
        $this->assertStringNotContainsString('amp', $editHref, 'link EDIT mengandung "amp"');

        $eq = [];
        parse_str((string) parse_url($editHref, PHP_URL_QUERY), $eq);
        $inner = [];
        parse_str((string) parse_url($eq['return_url'], PHP_URL_QUERY), $inner);

        $this->assertSame('Link Game', $inner['category'] ?? null, 'category hilang di return_url EDIT');
        $this->assertSame('2', (string) ($inner['page'] ?? null), 'page hilang di return_url EDIT');

        // -- 5. Halaman EDIT: targetUrl & returnUrl harus bersih
        $edit = $this->actingAs($this->user)->get($editHref, $this->host());
        $edit->assertOk();
        $html = $edit->getContent();

        $targetUrl = $this->jsValue($html, 'targetUrl');
        $returnUrl = $this->jsValue($html, 'returnUrl');

        $this->assertNotNull($targetUrl);
        $this->assertStringNotContainsString('amp;', $targetUrl, 'targetUrl mengandung "amp;"');

        $tq = [];
        parse_str((string) parse_url($targetUrl, PHP_URL_QUERY), $tq);

        $this->assertSame('Link Game', $tq['category'] ?? null, 'category hilang di targetUrl');
        $this->assertSame('2', (string) ($tq['page'] ?? null), 'page hilang di targetUrl');
        $this->assertSame((string) $target->id, (string) ($tq['focus'] ?? null), 'focus hilang di targetUrl');

        // -- 6. Simpan (payload axios) harus balik ke page 2 + focus, BERSIH
        $ajax = $this->actingAs($this->user)->put('/archives/'.$target->id, [
            'name' => 'ZZRET 8 diedit',
            'description' => 'baru',
            'category' => 'Link Game',
            'type' => 'url',
            'links' => 'https://example.test',
            'tags' => '',
            'is_public' => '1',
            'return_url' => $returnUrl,
        ], $this->host() + ['HTTP_ACCEPT' => 'application/json']);

        $ajax->assertOk();
        $redirect = $ajax->json('redirect_url');

        $this->assertIsString($redirect);
        $this->assertStringNotContainsString('amp;', $redirect, 'redirect_url mengandung "amp;"');

        $rq = [];
        parse_str((string) parse_url($redirect, PHP_URL_QUERY), $rq);

        $this->assertSame('Link Game', $rq['category'] ?? null, 'category hilang setelah simpan');
        $this->assertSame('2', (string) ($rq['page'] ?? null), 'page hilang setelah simpan');
        $this->assertSame((string) $target->id, (string) ($rq['focus'] ?? null), 'focus hilang setelah simpan');

        // -- 7. Buka hasil redirect: harus halaman 2, dengan baris yang diedit
        $after = $this->actingAs($this->user)->get($redirect, $this->host());
        $after->assertOk();
        $after->assertSee('ZZRET 8 diedit', false);
    }

    public function test_corrupted_amp_url_is_repaired()
    {
        $target = $this->made[5];

        // URL rusak seperti yang pernah muncul di address bar:
        // `page` ikut jadi `amp;page` sehingga user mendarat di halaman 1.
        $broken = '/archives?category=Link%20Game&amp;page=2';

        $idx = $this->actingAs($this->user)->get($broken, $this->host());
        $idx->assertOk();

        $showHref = $this->href(
            $idx->getContent(),
            '/href="([^"]*\/archives\/'.$target->id.'[^"]*return_url=[^"]+)"/'
        );

        // 尽管 address bar rusak, link yang dihasilkan harus bersih
        $this->assertStringNotContainsString('amp', $showHref, 'link masih membawa "amp"');

        $q = [];
        parse_str((string) parse_url($showHref, PHP_URL_QUERY), $q);
        $inner = [];
        parse_str((string) parse_url($q['return_url'], PHP_URL_QUERY), $inner);

        $this->assertSame('Link Game', $inner['category'] ?? null);
        $this->assertSame('2', (string) ($inner['page'] ?? null), 'page tidak dipulihkan');
    }

    public function test_deeply_corrupted_url_is_repaired()
    {
        $target = $this->made[5];

        // Stirisan bertingkat: amp%3Bamp%3Bpage, amp%3Bpage, amp%3Bfocus ...
        $broken = '/archives?category=Link%20Game&amp%3Bamp%3Bpage=2&amp;page=2&amp;focus=60&amp;page=2&amp;focus='.$target->id;

        $idx = $this->actingAs($this->user)->get($broken, $this->host());
        $idx->assertOk();

        $showHref = $this->href(
            $idx->getContent(),
            '/href="([^"]*\/archives\/'.$target->id.'[^"]*return_url=[^"]+)"/'
        );

        $this->assertStringNotContainsString('amp', $showHref, 'link masih membawa "amp"');

        $q = [];
        parse_str((string) parse_url($showHref, PHP_URL_QUERY), $q);
        $inner = [];
        parse_str((string) parse_url($q['return_url'], PHP_URL_QUERY), $inner);

        $this->assertSame('Link Game', $inner['category'] ?? null);
        $this->assertSame('2', (string) ($inner['page'] ?? null), 'page tidak dipulihkan dari URL rusak bertingkat');
        $this->assertArrayNotHasKey('focus', $inner, 'focus lama masih nyangkut');
    }

    public function test_show_preference_keeps_return_url_for_back_button()
    {
        // Preferensi user: "Masuk ke Detail Arsip"
        $this->user->settings = ['archive_edit_redirect' => 'show', 'per_page' => 6];
        $this->user->save();

        $target = $this->made[8]; // ada di halaman 2

        $indexUrl = 'http://127.0.0.1:8000/archives?category=Link%20Game&page=2';

        $ajax = $this->actingAs($this->user)->put('/archives/'.$target->id, [
            'name' => 'ZZRET 8 diedit',
            'description' => 'baru',
            'category' => 'Link Game',
            'type' => 'url',
            'links' => 'https://example.test',
            'tags' => '',
            'is_public' => '1',
            'return_url' => $indexUrl,
        ], $this->host() + ['HTTP_ACCEPT' => 'application/json']);

        $ajax->assertOk();
        $redirect = $ajax->json('redirect_url');

        $this->assertIsString($redirect);

        // Harus ke halaman DETAIL, bukan index
        $this->assertStringContainsString('/archives/'.$target->id, $redirect, 'preferensi show harus ke detail');
        $this->assertStringNotContainsString('amp', $redirect, 'URL masih membawa "amp"');

        // return_url harus ikut tersembunyi di query detail
        $q = [];
        parse_str((string) parse_url($redirect, PHP_URL_QUERY), $q);

        $this->assertArrayHasKey('return_url', $q, 'detail tidak membawa return_url');

        $inner = [];
        parse_str((string) parse_url($q['return_url'], PHP_URL_QUERY), $inner);

        $this->assertSame('Link Game', $inner['category'] ?? null, 'filter hilang dari return_url');
        $this->assertSame('2', (string) ($inner['page'] ?? null), 'page hilang dari return_url');

        // Buka detailnya, tombol BACK harus ke index berfilter halaman 2
        $show = $this->actingAs($this->user)->get($redirect, $this->host());
        $show->assertOk();

        $backHref = $this->href($show->getContent(), '/href="([^"]*\/archives\?[^"]*)"[^>]*>\s*\[ ESC \]/');

        $this->assertNotSame('', $backHref, 'tombol BACK tidak ditemukan');
        $this->assertStringNotContainsString('amp;', $backHref, 'BACK mengandung "amp;"');

        $bq = [];
        parse_str((string) parse_url($backHref, PHP_URL_QUERY), $bq);

        $this->assertSame('Link Game', $bq['category'] ?? null, 'category hilang di BACK');
        $this->assertSame('2', (string) ($bq['page'] ?? null), 'page hilang di BACK');

        // Penanda focus harus ikut, supaya baris yang diedit tersorot
        $this->assertSame((string) $target->id, (string) ($bq['focus'] ?? null), 'focus hilang di BACK');

        // Klik BACK harus benar-benar isi halaman 2
        $back = $this->actingAs($this->user)->get($backHref, $this->host());
        $back->assertOk();
        $back->assertSee('ZZRET 8 diedit', false);

        // Dan baris itu harus ditandai untuk di-highlight + auto-scroll
        $this->assertStringContainsString(
            'data-focus-row',
            $back->getContent(),
            'baris hasil edit tidak ditandai data-focus-row'
        );
    }

    public function test_show_preference_survives_category_change()
    {
        // User menyaring kategori Link Game, lalu mengedit arsipnya jadi kategori lain.
        // Setelah simpan -> detail, tombol BACK harus tetap ke filter aslinya.
        $this->user->settings = ['archive_edit_redirect' => 'show', 'per_page' => 6];
        $this->user->save();

        $target = $this->made[8];

        $indexUrl = 'http://127.0.0.1:8000/archives?category=Link%20Game&page=2';

        $ajax = $this->actingAs($this->user)->put('/archives/'.$target->id, [
            'name' => 'ZZRET 8 dipindah',
            'description' => 'baru',
            'category' => 'Video Langka',
            'type' => 'url',
            'links' => 'https://example.test',
            'tags' => '',
            'is_public' => '1',
            'return_url' => $indexUrl,
        ], $this->host() + ['HTTP_ACCEPT' => 'application/json']);

        $redirect = $ajax->json('redirect_url');

        $show = $this->actingAs($this->user)->get($redirect, $this->host());
        $show->assertOk();

        $backHref = $this->href($show->getContent(), '/href="([^"]*\/archives\?[^"]*)"[^>]*>\s*\[ ESC \]/');

        $bq = [];
        parse_str((string) parse_url($backHref, PHP_URL_QUERY), $bq);

        // Tetap kembali ke filter yang diklik user, walau arsipnya sudah pindah kategori
        $this->assertSame('Link Game', $bq['category'] ?? null, 'filter asli hilang');
        $this->assertSame('2', (string) ($bq['page'] ?? null), 'page asli hilang');
    }

    public function test_external_host_still_blocked()
    {
        $target = $this->made[5];

        $ajax = $this->actingAs($this->user)->put('/archives/'.$target->id, [
            'name' => 'ZZRET 5 diedit',
            'description' => 'baru',
            'category' => 'Link Game',
            'type' => 'url',
            'links' => 'https://example.test',
            'tags' => '',
            'is_public' => '1',
            'return_url' => 'https://evil.test/archives?category=Link%20Game&page=2',
        ], $this->host() + ['HTTP_ACCEPT' => 'application/json']);

        $redirect = $ajax->json('redirect_url');

        $this->assertStringNotContainsString('evil.test', $redirect);
    }
}