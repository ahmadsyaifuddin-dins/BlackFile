<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesApplication;

class AgentPasswordResetTest extends TestCase
{
    use CreatesApplication;

    private User $director;

    private User $agent;

    protected function setUp(): void
    {
        parent::setUp();

        $directorRole = Role::where('name', 'Director')->firstOrFail();
        $agentRole = Role::where('name', 'Agent')->first()
            ?? Role::where('name', '!=', 'Director')->firstOrFail();

        $this->director = $this->makeUser('dircpt@blackfile.local', 'Director Cpt', $directorRole->id);
        $this->agent = $this->makeUser('agentcpt@blackfile.local', 'Agent Cpt', $agentRole->id);
    }

    protected function tearDown(): void
    {
        User::whereIn('email', ['dircpt@blackfile.local', 'agentcpt@blackfile.local'])->delete();

        parent::tearDown();
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
            $user->master_password = 'secret1234';
        }

        $user->name = $name;
        $user->password = 'secret1234';
        $user->temp_password = 'secret1234';
        $user->confirmed = true;
        $user->role_id = $roleId;
        $user->save();

        return $user;
    }

    public function test_director_can_reset_agent_password_in_one_click()
    {
        $expected = 'password'.now()->format('dmY');

        $this->actingAs($this->director)
            ->post('/agents/'.$this->agent->id.'/reset-password')
            ->assertRedirect('/agents/'.$this->agent->id);

        $agent = User::findOrFail($this->agent->id);

        $this->assertTrue(Hash::check($expected, $agent->password));
        $this->assertSame($expected, $agent->temp_password);
    }

    public function test_reset_password_is_forbidden_for_non_director()
    {
        $this->actingAs($this->agent)
            ->post('/agents/'.$this->director->id.'/reset-password')
            ->assertForbidden();

        $director = User::findOrFail($this->director->id);

        $this->assertTrue(Hash::check('secret1234', $director->password));
    }

    public function test_director_sees_credential_panel_on_agent_detail()
    {
        $html = $this->actingAs($this->director)
            ->get('/agents/'.$this->agent->id)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('CREDENTIAL OVERRIDE', $html);
        $this->assertStringContainsString('secret1234', $html, 'password director tidak muncul');
        $this->assertStringContainsString('ONE-CLICK PASSWORD RESET', $html);
    }

    public function test_non_director_never_sees_credential_panel()
    {
        $html = $this->actingAs($this->agent)
            ->get('/agents/'.$this->director->id)
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('CREDENTIAL OVERRIDE', $html);
        $this->assertStringNotContainsString('secret1234', $html, 'password bocor ke non-director');
        $this->assertStringNotContainsString('/reset-password', $html);
    }

    public function test_reset_redirects_director_back_with_revealable_new_password()
    {
        $expected = 'password'.now()->format('dmY');

        $this->actingAs($this->director)
            ->post('/agents/'.$this->agent->id.'/reset-password')
            ->assertRedirect('/agents/'.$this->agent->id)
            ->assertSessionHas('auto_reveal', true);

        $html = $this->actingAs($this->director)
            ->get('/agents/'.$this->agent->id)
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString($expected, $html, 'password hasil reset tidak bisa dilihat lagi');
    }

    public function test_successful_login_records_password_copy_for_director()
    {
        // Akun lama: belum pernah punya salinan password
        $this->agent->temp_password = null;
        $this->agent->save();

        $this->post('/login', [
            'username' => $this->agent->username,
            'password' => 'secret1234',
        ])->assertOk()->assertJson(['status' => 'success']);

        $this->assertSame('secret1234', $this->agent->fresh()->temp_password);
    }

    public function test_failed_login_does_not_touch_password_copy()
    {
        $this->agent->temp_password = 'keepme123';
        $this->agent->save();

        $this->post('/login', [
            'username' => $this->agent->username,
            'password' => 'wrong-password',
        ])->assertStatus(422);

        $this->assertSame('keepme123', $this->agent->fresh()->temp_password);
    }
}
