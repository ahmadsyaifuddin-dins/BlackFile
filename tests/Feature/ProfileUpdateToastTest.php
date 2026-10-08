<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ProfileUpdateToastTest extends TestCase
{
    use CreatesApplication;

    private User $agent;

    protected function setUp(): void
    {
        parent::setUp();

        $agentRole = Role::where('name', 'Agent')->first()
            ?? Role::where('name', '!=', 'Director')->firstOrFail();

        $this->agent = $this->makeUser('toastagent@blackfile.local', 'Toast Agent', $agentRole->id);
    }

    protected function tearDown(): void
    {
        User::where('email', 'toastagent@blackfile.local')->delete();

        parent::tearDown();
    }

    private function makeUser(string $email, string $name, int $roleId): User
    {
        $user = User::where('email', $email)->first() ?? new User;
        $user->email = $email;
        $user->username = 'toastagent';
        $user->codename = 'TOASTAGENT';
        $user->slug = str_replace(' ', '-', strtolower($name));
        $user->name = $name;
        $user->password = 'secret1234';
        $user->temp_password = 'secret1234';
        $user->master_password = 'secret1234';
        $user->confirmed = true;
        $user->role_id = $roleId;
        $user->save();

        return $user;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => $this->agent->name,
            'codename' => $this->agent->codename,
            'username' => $this->agent->username,
            'email' => $this->agent->email,
            'quotes' => 'Fortune favors the bold.',
        ], $overrides);
    }

    public function test_profile_page_fires_success_toast_after_update()
    {
        $this->actingAs($this->agent)
            ->patch('/profile', $this->payload(['name' => 'Toast Agent Updated']))
            ->assertRedirect('/profile');

        $html = $this->actingAs($this->agent)
            ->get('/profile')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString(
            "agentAlert('success', 'PROFILE UPDATED'",
            $html,
            'toast sukses update profile tidak muncul'
        );
    }

    public function test_profile_page_without_update_has_no_toast()
    {
        $html = $this->actingAs($this->agent)
            ->get('/profile')
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('PROFILE UPDATED', $html);
    }

    public function test_edit_page_fires_warning_toast_on_validation_error()
    {
        $this->actingAs($this->agent)
            ->from('/profile/edit')
            ->patch('/profile', $this->payload(['email' => 'bukan-email']))
            ->assertRedirect('/profile/edit')
            ->assertSessionHasErrors('email');

        $html = $this->actingAs($this->agent)
            ->get('/profile/edit')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString(
            "agentAlert('warning', 'DATA INPUT ANOMALY'",
            $html,
            'toast error validasi tidak muncul'
        );
    }
}
