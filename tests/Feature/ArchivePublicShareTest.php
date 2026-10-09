<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ArchivePublicShareTest extends TestCase
{
    use CreatesApplication;

    private const CATEGORY = 'ZZPUBLICA';

    private User $director;

    private User $agent;

    private User $otherAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $directorRole = Role::where('name', 'Director')->firstOrFail();
        $agentRole = Role::where('name', 'Agent')->first()
            ?? Role::where('name', '!=', 'Director')->firstOrFail();

        $this->director = $this->makeUser('dirshare@blackfile.local', 'Director Share', $directorRole->id);
        $this->agent = $this->makeUser('agentshare@blackfile.local', 'Agent Share', $agentRole->id);
        $this->otherAgent = $this->makeUser('agent2share@blackfile.local', 'Agent Two Share', $agentRole->id);

        $this->cleanup();
    }

    protected function tearDown(): void
    {
        $this->cleanup();

        User::whereIn('email', [
            'dirshare@blackfile.local',
            'agentshare@blackfile.local',
            'agent2share@blackfile.local',
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

    private function makeArchive(
        string $name,
        User $owner,
        bool $isPublic = false,
        bool $hasAd = false,
        bool $isShared = false
    ): Archive {
        return Archive::create([
            'user_id' => $owner->id,
            'name' => $name,
            'description' => 'probe public share',
            'type' => 'url',
            'category' => self::CATEGORY,
            'is_public' => $isPublic,
            'has_ad' => $hasAd,
            'is_shared' => $isShared,
            'links' => ['https://example.test'],
        ]);
    }

    public function test_public_landing_without_ad_redirects_to_data()
    {
        $archive = $this->makeArchive('ZZPUB direct link', $this->agent, isPublic: true, hasAd: false, isShared: true);
        $archive->ensurePublicToken();

        $this->get('/s/'.$archive->public_token)
            ->assertRedirect(route('archives.public.open', $archive->public_token));
    }

    public function test_public_landing_with_ad_shows_gate_page()
    {
        $archive = $this->makeArchive('ZZPUB gate', $this->agent, isPublic: true, hasAd: true, isShared: true);
        $archive->ensurePublicToken();

        $html = $this->get('/s/'.$archive->public_token)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('MENUJU TAUTAN', $html, 'tombol lanjut manual tidak tampil');
        $this->assertStringContainsString('/open', $html, 'target data hilang');
    }

    public function test_public_open_renders_public_page_without_auth()
    {
        $archive = $this->makeArchive('ZZPUB tampil publik', $this->agent, isPublic: true, hasAd: false, isShared: true);
        $archive->ensurePublicToken();

        $html = $this->get('/s/'.$archive->public_token.'/open')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString($archive->name, $html, 'nama arsip tidak tampil');
        $this->assertStringNotContainsString('DELETE', $html, 'public page bocor tombol delete');
    }

    public function test_private_archive_link_returns_404()
    {
        $archive = $this->makeArchive('ZZPUB privat', $this->agent, isPublic: false, hasAd: false);

        $this->get('/s/'.$archive->public_token)->assertNotFound();
    }

    public function test_share_link_returns_404_when_internal_is_public_but_share_off()
    {
        // Visibilitas internal PUBLIC, tapi link eksternal sengaja dimatikan.
        $archive = $this->makeArchive('ZZPUB non-eksternal', $this->agent, isPublic: true, hasAd: false);
        $archive->public_token = 'probe-token-non-eksternal';
        $archive->save();

        $this->get('/s/'.$archive->public_token)->assertNotFound();
        $this->get('/s/'.$archive->public_token.'/open')->assertNotFound();
    }

    public function test_agent_can_toggle_share_and_ad_on_own_internal_public_archive()
    {
        $archive = $this->makeArchive('ZZPUB toggle sendiri', $this->agent, isPublic: true, hasAd: false);

        $response = $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertOk()
            ->json();

        $this->assertTrue($response['is_shared']);
        $this->assertNotNull($response['public_url']);
        $this->assertNotNull($archive->fresh()->public_token, 'token tidak dibuat');
        $this->assertTrue($archive->fresh()->is_shared);

        // Matikan lagi -> link eksternal hilang
        $response = $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertOk()
            ->json();

        $this->assertFalse($response['is_shared']);
        $this->assertNull($response['public_url']);

        // Nyalakan ad mode
        $response = $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-ad')
            ->assertOk()
            ->json();

        $this->assertTrue($response['has_ad']);
        $this->assertTrue($archive->fresh()->has_ad);
    }

    public function test_share_cannot_be_enabled_while_internal_visibility_is_private()
    {
        $archive = $this->makeArchive('ZZPUB private intern', $this->agent, isPublic: false, hasAd: false);

        $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertStatus(409);

        $this->assertFalse($archive->fresh()->is_shared, 'link eksternal bocor walau internal private');
    }

    public function test_director_also_cannot_enable_share_while_internal_is_private()
    {
        $archive = $this->makeArchive('ZZPUB private director', $this->otherAgent, isPublic: false, hasAd: false);

        $this->actingAs($this->director)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertStatus(409);

        $this->assertFalse($archive->fresh()->is_shared);
    }

    public function test_agent_cannot_toggle_another_agents_archive()
    {
        $archive = $this->makeArchive('ZZPUB toggle lain', $this->otherAgent, isPublic: true, hasAd: false);

        $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertForbidden();

        $this->actingAs($this->agent)
            ->post('/archives/'.$archive->id.'/toggle-ad')
            ->assertForbidden();
    }

    public function test_director_can_toggle_share_on_another_agents_internal_public_archive()
    {
        $archive = $this->makeArchive('ZZPUB toggle director', $this->otherAgent, isPublic: true, hasAd: false);

        $this->actingAs($this->director)
            ->post('/archives/'.$archive->id.'/toggle-share')
            ->assertOk();

        $this->assertTrue($archive->fresh()->is_shared);
    }

    public function test_index_shows_share_controls_for_owner()
    {
        $archive = $this->makeArchive('ZZPUB kontrol', $this->agent, isPublic: true, hasAd: false);

        $html = $this->actingAs($this->agent)
            ->get('/archives?category='.self::CATEGORY)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('/toggle-share', $html, 'tombol share tidak tampil');
        $this->assertStringContainsString('/toggle-ad', $html, 'tombol ad tidak tampil');
    }

    public function test_show_page_renders_share_panel_for_owner()
    {
        $archive = $this->makeArchive('ZZPUB panel', $this->agent, isPublic: true, hasAd: false, isShared: true);
        $archive->ensurePublicToken();

        $html = $this->actingAs($this->agent)
            ->get('/archives/'.$archive->id)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('> PUBLIC_SHARE', $html, 'panel share tidak tampil');
        $this->assertStringContainsString('/toggle-share', $html, 'tombol share tidak tampil');
        $this->assertStringContainsString($archive->public_token, $html, 'link publik tidak tampil');
    }
}