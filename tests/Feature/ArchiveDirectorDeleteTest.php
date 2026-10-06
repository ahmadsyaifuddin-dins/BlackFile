<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesApplication;

class ArchiveDirectorDeleteTest extends TestCase
{
    use CreatesApplication;

    private const CATEGORY = 'ZZDELAGENT';

    private User $director;

    private User $agent;

    private User $otherAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $directorRole = Role::where('name', 'Director')->firstOrFail();
        $agentRole = Role::where('name', 'Agent')->first()
            ?? Role::where('name', '!=', 'Director')->firstOrFail();

        $this->director = $this->makeUser('dirdel@blackfile.local', 'Director Del', $directorRole->id);
        $this->agent = $this->makeUser('agentdel@blackfile.local', 'Agent Del', $agentRole->id);
        $this->otherAgent = $this->makeUser('agent2del@blackfile.local', 'Agent Two', $agentRole->id);

        $this->cleanup();
    }

    protected function tearDown(): void
    {
        $this->cleanup();

        User::whereIn('email', [
            'dirdel@blackfile.local',
            'agentdel@blackfile.local',
            'agent2del@blackfile.local',
        ])->delete();

        parent::tearDown();
    }

    private function cleanup(): void
    {
        Archive::where('category', self::CATEGORY)->forceDelete();
    }

    private function makeUser(string $email, string $name, int $roleId): User
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User;
            $user->email = $email;
            $user->username = strtolower(str_replace('@blackfile.local', '', $email));
            $user->codename = strtoupper($user->username);
            $user->slug = str_replace(' ', '-', strtolower($name));
            $user->password = 'secret1234';
            $user->master_password = 'secret1234';
            $user->temp_password = 'temp1234';
        }

        $user->name = $name;
        $user->confirmed = true;
        $user->role_id = $roleId;
        $user->settings = ['per_page' => 20];
        $user->save();

        return $user;
    }

    private function makeArchive(string $name, User $owner): Archive
    {
        return Archive::create([
            'user_id' => $owner->id,
            'name' => $name,
            'description' => 'probe delete',
            'type' => 'url',
            'category' => self::CATEGORY,
            'is_public' => true,
            'links' => ['https://example.test'],
        ]);
    }

    public function test_director_can_delete_another_agents_archive()
    {
        $archive = $this->makeArchive('ZZDEL milik agent', $this->agent);

        $this->actingAs($this->director)
            ->delete('/archives/'.$archive->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('archives', ['id' => $archive->id]);
    }

    public function test_agent_cannot_delete_another_agents_archive()
    {
        $archive = $this->makeArchive('ZZDEL agent lain', $this->otherAgent);

        $this->actingAs($this->agent)
            ->delete('/archives/'.$archive->id)
            ->assertForbidden();

        $this->assertDatabaseHas('archives', ['id' => $archive->id]);
    }

    public function test_agent_can_delete_own_archive()
    {
        $archive = $this->makeArchive('ZZDEL milik sendiri', $this->agent);

        $this->actingAs($this->agent)
            ->delete('/archives/'.$archive->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('archives', ['id' => $archive->id]);
    }

    public function test_index_shows_delete_button_for_directors_archive()
    {
        $archive = $this->makeArchive('ZZDEL tampil tombol', $this->agent);

        $html = $this->actingAs($this->director)
            ->get('/archives?category='.self::CATEGORY)
            ->assertOk()
            ->getContent();

        // Director boleh, jadi policy harus mengizinkan
        $this->assertTrue($this->director->can('delete', $archive));

        // Konfirmasi delete harus membawa return_url supaya user kembali ke
        // posisi list semula, bukan halaman 1.
        $this->assertStringContainsString(
            htmlspecialchars(urlencode('/archives?category='.self::CATEGORY), ENT_QUOTES),
            $html,
            'tombol delete tidak membawa return_url'
        );
    }

    public function test_index_hides_delete_button_for_other_agents_archive()
    {
        $archive = $this->makeArchive('ZZDEL sembunyi', $this->otherAgent);

        // Agent biasa tidak boleh, jadi policy menolak (tombol disembunyikan @can)
        $this->assertFalse($this->agent->can('delete', $archive));

        $html = $this->actingAs($this->agent)
            ->get('/archives?category='.self::CATEGORY)
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString(
            htmlspecialchars(urlencode('/archives/'.$archive->id), ENT_QUOTES),
            $html,
            'endpoint delete arsip milik agent lain ikut terkirim ke browser'
        );
    }

    public function test_delete_returns_user_to_previous_filter_and_page()
    {
        $archive = $this->makeArchive('ZZDEL posisi', $this->agent);

        $returnUrl = '/archives?category='.self::CATEGORY.'&sort=tag_desc&page=1';

        $response = $this->actingAs($this->director)
            ->delete('/archives/'.$archive->id.'?return_url='.urlencode($returnUrl));

        $response->assertRedirect();

        $location = $response->headers->get('Location');

        $this->assertStringContainsString('category='.self::CATEGORY, $location, 'filter hilang');
        $this->assertStringContainsString('sort=tag_desc', $location, 'urutan hilang');
    }

    /**
     * Bikin arsip yatim: user_id menunjuk user yang tidak ada lagi.
     *
     * Kolomnya punya FK onDelete cascade, jadi tidak bisa lewat delete User
     * (arsipnya ikut terhapus). Foreign key dimatikan sementara supaya bisa
     * dibuat kondisiExactly seperti kasus error yang dilaporkan.
     */
    private function makeOrphanArchive(string $name): Archive
    {
        Schema::disableForeignKeyConstraints();

        try {
            DB::table('archives')->insert([
                'user_id' => 999999,
                'name' => $name,
                'description' => 'orphan',
                'type' => 'url',
                'category' => self::CATEGORY,
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        return Archive::where('name', $name)->firstOrFail();
    }

    public function test_index_renders_deleted_user_badge_without_error()
    {
        // Ini regresi untuk error: Attempt to read property "name" on null
        $this->makeOrphanArchive('ZZDEL yatim');

        $html = $this->actingAs($this->director)
            ->get('/archives?category='.self::CATEGORY)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('ZZDEL yatim', $html, 'arsip yatim hilang dari daftar');
        $this->assertStringContainsString('[DELETED USER]', $html, 'penanda owner hilang tidak tampil');
    }

    public function test_card_view_renders_deleted_user_badge_without_error()
    {
        $this->makeOrphanArchive('ZZDEL yatim card');

        $html = $this->actingAs($this->director)
            ->get('/archives?category='.self::CATEGORY.'&view=card')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('[DELETED USER]', $html, 'card view tidak menampilkan penanda');
    }

    public function test_show_page_renders_deleted_user_badge_without_error()
    {
        $archive = $this->makeOrphanArchive('ZZDEL yatim show');

        $this->withoutExceptionHandling();

        // Fixture-nya sengaja tanpa `links` (null) untuk sekaligus menguji
        // bahwa halaman detail tahan data yang tidak lengkap.
        $this->actingAs($this->director)
            ->get('/archives/'.$archive->id)
            ->assertOk()
            ->assertSee('[DELETED USER]', false);
    }

    public function test_director_can_delete_orphan_archive()
    {
        $archive = $this->makeOrphanArchive('ZZDEL yatim hapus');

        $this->assertTrue($this->director->can('delete', $archive));

        $this->actingAs($this->director)
            ->delete('/archives/'.$archive->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('archives', ['id' => $archive->id]);
    }
}