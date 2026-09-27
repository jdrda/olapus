<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Tests\TestCase;

class FortifyAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_user_can_log_in_with_existing_password_hash(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('testing-password-42'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'testing-password-42',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_fortify_action_creates_user_with_hashed_password(): void
    {
        /** @var User $user */
        $user = app(CreatesNewUsers::class)->create([
            'name' => 'Fortify User',
            'email' => 'fortify-user@example.test',
            'password' => 'testing-password-42',
            'password_confirmation' => 'testing-password-42',
        ]);

        $this->assertTrue(Hash::check('testing-password-42', $user->password));
    }

    public function test_fortify_reset_action_can_reset_user_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-testing-password-42'),
        ]);

        app(ResetsUserPasswords::class)->reset($user, [
            'password' => 'new-testing-password-42',
            'password_confirmation' => 'new-testing-password-42',
        ]);

        $this->assertTrue(
            Hash::check('new-testing-password-42', $user->fresh()->password)
        );
    }

    public function test_forgotten_password_can_be_reset(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $token = null;

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])->assertSessionHasNoErrors();

        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function (ResetPassword $notification) use (&$token): bool {
                $token = $notification->token;
                return true;
            }
        );

        $this->assertNotNull($token);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-testing-password-42',
            'password_confirmation' => 'new-testing-password-42',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(
            Hash::check('new-testing-password-42', $user->fresh()->password)
        );
    }

    public function test_admin_edit_without_password_keeps_existing_password(): void
    {
        $operator = User::factory()->create();
        $user = User::factory()->create([
            'password' => Hash::make('original-testing-password-42'),
        ]);
        $originalHash = $user->password;

        $this->actingAs($operator)->put(route('admin.user.update', $user->id), [
            'name' => 'Updated User',
            'email' => $user->email,
            'roles' => [],
        ])->assertRedirect(route('admin.user.index'));

        $user->refresh();

        $this->assertSame($originalHash, $user->password);
        $this->assertTrue(Hash::check('original-testing-password-42', $user->password));
    }

    public function test_admin_can_change_user_password_through_fortify_reset_action(): void
    {
        $operator = User::factory()->create();
        $user = User::factory()->create([
            'password' => Hash::make('old-admin-password-42'),
        ]);

        $this->actingAs($operator)->put(route('admin.user.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'new-admin-password-42',
            'password_confirmation' => 'new-admin-password-42',
            'roles' => [],
        ])->assertRedirect(route('admin.user.index'));

        $user->refresh();

        $this->assertTrue(Hash::check('new-admin-password-42', $user->password));
        $this->assertFalse(Hash::check('old-admin-password-42', $user->password));
    }

    public function test_admin_password_change_uses_fortify_password_validation(): void
    {
        $operator = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($operator)->put(route('admin.user.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'short',
            'password_confirmation' => 'short',
            'roles' => [],
        ])->assertSessionHasErrors('password');
    }

}
