<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ArchiveThumbnailTest extends TestCase
{
    use CreatesApplication;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::where('name', 'Director')->firstOrFail();

        $user = User::where('email', 'thumbtest@blackfile.local')->first();

        if (! $user) {
            $user = new User;
            $user->email = 'thumbtest@blackfile.local';
            $user->name = 'Thumb Test';
            $user->username = 'thumbtest';
            $user->codename = 'THUMB-TEST';
            $user->slug = 'thumb-test';
            $user->password = 'secret1234';
            $user->master_password = 'secret1234';
            $user->temp_password = 'temp1234';
        }

        $user->confirmed = true;
        $user->role_id = $role->id;
        $user->settings = ['per_page' => 20];
        $user->save();

        $this->user = $user;

        Archive::where('name', 'like', 'ZZTHUMB%')->forceDelete();

        // 1) dengan gambar, 2) tanpa gambar, 3) gambar rusak, 4) file, 5) image
        Archive::create([
            'user_id' => $user->id, 'name' => 'ZZTHUMB ada gambar',
            'description' => 'd', 'type' => 'url', 'category' => 'Link Game',
            'is_public' => true, 'links' => ['https://example.test'],
            'preview_image_url' => 'https://example.test/cover.png',
        ]);

        Archive::create([
            'user_id' => $user->id, 'name' => 'ZZTHUMB tanpa gambar',
            'description' => 'd', 'type' => 'url', 'category' => 'Link Game',
            'is_public' => true, 'links' => ['https://example.test'],
            'preview_image_url' => null,
        ]);

        Archive::create([
            'user_id' => $user->id, 'name' => 'ZZTHUMB gambar rusak',
            'description' => 'd', 'type' => 'url', 'category' => 'Link Game',
            'is_public' => true, 'links' => ['https://example.test'],
            'preview_image_url' => 'https://example.test/does-not-exist.png',
        ]);

        Archive::create([
            'user_id' => $user->id, 'name' => 'ZZTHUMB tipe file',
            'description' => 'd', 'type' => 'file', 'category' => 'Link Game',
            'is_public' => true,
            'preview_image_url' => null,
        ]);

        Archive::create([
            'user_id' => $user->id, 'name' => 'ZZTHUMB tipe image',
            'description' => 'd', 'type' => 'image', 'category' => 'Link Game',
            'is_public' => true,
            'preview_image_url' => null,
        ]);
    }

    protected function tearDown(): void
    {
        Archive::where('name', 'like', 'ZZTHUMB%')->forceDelete();
        User::where('email', 'thumbtest@blackfile.local')->delete();

        parent::tearDown();
    }

    public function test_index_renders_thumbnail_component_for_each_archive()
    {
        $idx = $this->actingAs($this->user)->get('/archives', ['HTTP_HOST' => '127.0.0.1:8000']);
        $idx->assertOk();

        $html = $idx->getContent();

        // Komponen Alpine thumbnail harus ter-render
        $this->assertStringContainsString('archiveThumb(', $html, 'komponen archiveThumb tidak ada');
        $this->assertStringContainsString('data-focus-row', $html, 'penanda fokus hilang');

        // Fallback icon harus ada di markup
        $this->assertStringContainsString('fallbackIcon', $html, 'fallbackIcon tidak di-bind');
        $this->assertStringContainsString('NO_PREVIEW', $html, 'label NO_PREVIEW tidak ada di card view');

        // Shimxer keyframes harus terdaftar
        $this->assertStringContainsString('@keyframes shimmer', $html, 'keyframe shimmer tidak ada');
        $this->assertStringContainsString('[x-cloak]', $html, 'aturan x-cloak tidak ada');

        // Setiap baris (test + data real) punya satu komponen thumbnail
        $this->assertGreaterThanOrEqual(5, substr_count($html, 'archiveThumb('), 'jumlah komponen thumbnail kurang');
    }

    public function test_thumbnail_src_passed_from_preview_image_url()
    {
        $idx = $this->actingAs($this->user)->get('/archives', ['HTTP_HOST' => '127.0.0.1:8000']);
        $html = $idx->getContent();

        // Arsip yang punya gambar: URL-nya diteruskan ke Alpine
        $this->assertStringContainsString('cover.png', $html, 'URL gambar tidak diteruskan');

        // src null harus jadi null, bukan string "null"
        $this->assertStringNotContainsString('src: "null"', $html, 'src null ter-render sebagai string');
    }

    public function test_type_passed_so_icon_matches_archive_type()
    {
        $idx = $this->actingAs($this->user)->get('/archives', ['HTTP_HOST' => '127.0.0.1:8000']);
        $html = $idx->getContent();

        // Tipe url, file, image harus ikut, supaya ikon fallback sesuai
        // @js() meng-emit single-quote, jadi dua format harus mungkin muncul
        foreach (['url', 'file', 'image'] as $type) {
            $found = str_contains($html, 'type: "'.$type.'"') || str_contains($html, 'type: \''.$type.'\'');

            $this->assertTrue($found, "tipe {$type} tidak diteruskan ke komponen");
        }
    }

    public function test_image_uses_object_cover_for_proper_ratio()
    {
        $idx = $this->actingAs($this->user)->get('/archives', ['HTTP_HOST' => '127.0.0.1:8000']);
        $html = $idx->getContent();

        // Card view: gambar menutup area dengan object-cover
        $this->assertStringContainsString(
            'object-cover',
            $html,
            'object-cover tidak dipakai, gambar bisa gepeng'
        );

        // onerror fallback supaya URL rusak tidak jadi broken image
        $this->assertStringContainsString(
            'onerror',
            $html,
            'onerror tidak ada, gambar rusak akan tampil ikon browser'
        );
    }
}