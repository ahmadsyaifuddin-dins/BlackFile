<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ArchiveCreateRedirectTest extends TestCase
{
    use CreatesApplication;

    private const CATEGORY = 'Link Game';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::where('name', 'Director')->firstOrFail();

        $user = User::where('email', 'createredir@blackfile.local')->first();

        if (! $user) {
            $user = new User;
            $user->email = 'createredir@blackfile.local';
            $user->name = 'Create Redirect Test';
            $user->username = 'createredir';
            $user->codename = 'CREATEREDIR';
            $user->slug = 'create-redirect-test';
            $user->password = 'secret1234';
            $user->master_password = 'secret1234';
            $user->temp_password = 'temp1234';
        }

        $user->confirmed = true;
        $user->role_id = $role->id;
        $user->save();

        $this->user = $user;

        $this->cleanup();

        $this->setLock(false);
    }

    protected function tearDown(): void
    {
        $this->cleanup();

        User::where('email', 'createredir@blackfile.local')->delete();

        $this->setLock(false);

        parent::tearDown();
    }

    private function setLock(bool $locked): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'archive_edit_redirect_locked'],
            ['value' => $locked ? '1' : '0']
        );
    }

    private function cleanup(): void
    {
        Archive::where('name', 'like', 'ZZNEW%')->forceDelete();
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function setSettings(array $settings): void
    {
        $this->user->settings = $settings;
        $this->user->save();
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(array $extra = []): array
    {
        return array_merge([
            'name' => 'ZZNEW entry',
            'description' => 'probe',
            'category' => self::CATEGORY,
            'type' => 'url',
            'links' => 'https://example.test',
            'tags' => '',
            'is_public' => '1',
        ], $extra);
    }

    private function returnUrl(): string
    {
        return '/archives?'.http_build_query([
            'category' => self::CATEGORY,
            'sort' => 'tag_desc',
            'page' => 1,
        ]);
    }

    private function host(): array
    {
        return ['HTTP_HOST' => '127.0.0.1:8000', 'HTTP_ACCEPT' => 'application/json'];
    }

    public function test_index_position_preference_returns_to_filtered_index_with_focus()
    {
        $this->setSettings(['archive_create_redirect' => 'index_position']);

        $res = $this->actingAs($this->user)
            ->post('/archives', $this->payload(['return_url' => $this->returnUrl()]), $this->host());

        $res->assertCreated();

        $redirect = $res->json('redirect_url');
        $archive = Archive::where('name', 'ZZNEW entry')->firstOrFail();

        $q = [];
        parse_str((string) parse_url($redirect, PHP_URL_QUERY), $q);

        $this->assertSame(self::CATEGORY, $q['category'] ?? null, 'filter hilang');
        $this->assertSame('tag_desc', $q['sort'] ?? null, 'urutan hilang');
        $this->assertSame((string) $archive->id, (string) ($q['focus'] ?? null), 'focus hilang');
    }

    public function test_show_preference_goes_to_archive_detail()
    {
        $this->setSettings(['archive_create_redirect' => 'show']);

        $res = $this->actingAs($this->user)
            ->post('/archives', $this->payload(['return_url' => $this->returnUrl()]), $this->host());

        $res->assertCreated();

        $redirect = $res->json('redirect_url');
        $archive = Archive::where('name', 'ZZNEW entry')->firstOrFail();

        $this->assertStringContainsString('/archives/'.$archive->id, $redirect, 'bukan ke detail');

        // return_url tetap dibawa supaya tombol BACK kembali ke posisi semula
        $q = [];
        parse_str((string) parse_url($redirect, PHP_URL_QUERY), $q);

        $this->assertArrayHasKey('return_url', $q, 'return_url tidak ikut ke detail');

        $inner = [];
        parse_str((string) parse_url($q['return_url'], PHP_URL_QUERY), $inner);

        $this->assertSame(self::CATEGORY, $inner['category'] ?? null);
        $this->assertSame((string) $archive->id, (string) ($inner['focus'] ?? null));
    }

    public function test_focus_miss_added_when_new_archive_does_not_match_filter()
    {
        $this->setSettings(['archive_create_redirect' => 'index_position']);

        // Filter aktif category=Link Game, tapi entri baru dipasang ke kategori lain
        $res = $this->actingAs($this->user)
            ->post('/archives', $this->payload([
                'category' => 'Video Langka',
                'return_url' => $this->returnUrl(),
            ]), $this->host());

        $res->assertCreated();

        $redirect = $res->json('redirect_url');

        $this->assertStringContainsString('focus_miss=1', $redirect, 'focus_miss tidak ditandai');
    }

    public function test_create_and_edit_preferences_are_independent()
    {
        $this->setSettings([
            'archive_create_redirect' => 'show',
            'archive_edit_redirect' => 'index_position',
        ]);

        $create = $this->actingAs($this->user)
            ->post('/archives', $this->payload(['return_url' => $this->returnUrl()]), $this->host());

        $archive = Archive::where('name', 'ZZNEW entry')->firstOrFail();

        $this->assertStringContainsString('/archives/'.$archive->id, $create->json('redirect_url'));

        // Edit harus tetap pakai setelan edit (index_position), bukan ikut "show"
        $edit = $this->actingAs($this->user)
            ->put('/archives/'.$archive->id, $this->payload([
                'name' => 'ZZNEW edited',
                'return_url' => $this->returnUrl(),
            ]), $this->host());

        $edit->assertOk();

        $this->assertStringNotContainsString('/archives/'.$archive->id.'?', $edit->json('redirect_url'));
        $this->assertStringContainsString('/archives?', $edit->json('redirect_url'));
    }

    public function test_global_lock_overrides_create_preference()
    {
        $this->setSettings(['archive_create_redirect' => 'show']);

        $this->setLock(true);

        $res = $this->actingAs($this->user)
            ->post('/archives', $this->payload(['return_url' => $this->returnUrl()]), $this->host());

        $res->assertCreated();

        $redirect = $res->json('redirect_url');
        $archive = Archive::where('name', 'ZZNEW entry')->firstOrFail();

        $this->assertStringNotContainsString(
            '/archives/'.$archive->id,
            $redirect,
            'global lock harus memaksa balik ke index'
        );
    }

    public function test_create_form_carries_return_url_from_index()
    {
        $this->actingAs($this->user)
            ->get('/archives?'.http_build_query(['category' => self::CATEGORY, 'sort' => 'tag_desc']), $this->host())
            ->assertOk()
            ->assertSee('return_url', false);
    }

    /**
     * Ambil nilai string dari atribut x-data, lalu buang escape yang dipakai @js().
     *
     * @js() menulis JSON.parse('...') atau string ter-quote, dan meng-escape
     * "/" jadi "\/" serta "&" jadi "". Setelah escape itu dibuang,
     * nilainya harus identik dengan string aslinya.
     */
    private function jsValue(string $html, string $key): ?string
    {
        preg_match('/'.$key.':\s*(.+?),\r?\n/', $html, $m);

        $raw = trim($m[1] ?? '');

        if ($raw === '') {
            return null;
        }

        $raw = preg_replace('/^JSON\.parse\((.*)\)$/s', '$1', $raw);
        $raw = trim($raw, "'\"");

        return str_replace(
            ['\\/', '\\u0026', '\\u0020', '\\u003D', '\\u002B', '\\\\'],
            ['/', '&', ' ', '=', '+', '\\'],
            $raw
        );
    }

    public function test_create_page_keeps_return_url_when_opened_directly()
    {
        $return = $this->returnUrl();

        $html = $this->actingAs($this->user)
            ->get('/archives/create?return_url='.urlencode($return), ['HTTP_HOST' => '127.0.0.1:8000'])
            ->assertOk()
            ->getContent();

        $decoded = $this->jsValue($html, 'returnUrl');

        $this->assertSame($return, $decoded, 'return_url berubah saat sampai di form tambah');
    }

    public function test_settings_accepts_archive_create_redirect()
    {
        $this->actingAs($this->user)
            ->post('/settings', [
                'locale' => 'en',
                'per_page' => 9,
                'theme' => 'default',
                'alert_position' => 'bottom-right',
                'archive_edit_redirect' => 'index_position',
                'archive_create_redirect' => 'show',
            ])
            ->assertRedirect();

        $this->assertSame('show', $this->user->fresh()->settings['archive_create_redirect']);
    }

    public function test_settings_rejects_invalid_archive_create_redirect()
    {
        $this->actingAs($this->user)
            ->post('/settings', [
                'locale' => 'en',
                'per_page' => 9,
                'theme' => 'default',
                'archive_edit_redirect' => 'index_position',
                'archive_create_redirect' => '<script>',
            ])
            ->assertSessionHasErrors('archive_create_redirect');
    }

    public function test_success_message_differs_between_create_and_edit()
    {
        $this->setSettings(['archive_create_redirect' => 'index_position']);

        $create = $this->actingAs($this->user)
            ->get('/archives/create?return_url='.urlencode($this->returnUrl()), ['HTTP_HOST' => '127.0.0.1:8000']);

        $create->assertSee('ARCHIVE ADDED', false);
        $create->assertDontSee('ARCHIVE UPDATED', false);

        $archive = Archive::where('name', 'ZZNEW entry')->first()
            ?? Archive::create([
                'user_id' => $this->user->id,
                'name' => 'ZZNEW entry',
                'description' => 'd',
                'type' => 'url',
                'category' => self::CATEGORY,
                'is_public' => true,
                'links' => ['https://example.test'],
            ]);

        $edit = $this->actingAs($this->user)
            ->get('/archives/'.$archive->id.'/edit?return_url='.urlencode($this->returnUrl()),
                ['HTTP_HOST' => '127.0.0.1:8000']);

        $edit->assertOk();
        $edit->assertSee('ARCHIVE UPDATED', false);
        $edit->assertDontSee('ARCHIVE ADDED', false);
    }
}