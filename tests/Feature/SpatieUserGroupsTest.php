<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SpatieUserGroupsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Cache::flush();
        \Laravolt\Avatar\Facade::shouldReceive('create')->andReturnSelf();
        \Laravolt\Avatar\Facade::shouldReceive('toBase64')->andReturn('data:image/png;base64,');
    }

    public function test_user_can_have_multiple_spatie_roles(): void
    {
        $user = User::factory()->create();
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'description' => 'Administrators']);
        $editor = Role::create(['name' => 'editor', 'guard_name' => 'web', 'description' => 'Editors']);

        $user->syncRoles([$admin, $editor]);

        $this->assertTrue($user->fresh()->hasAllRoles(['admin', 'editor']));
        $this->assertCount(2, $user->fresh()->roles);
    }

    public function test_user_edit_form_syncs_all_selected_groups(): void
    {
        $operator = User::factory()->create();
        $user = User::factory()->create();
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $user->assignRole($admin);

        $this->actingAs($operator)->put(route('admin.user.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => [$editor->id],
        ])->assertRedirect(route('admin.user.index'));

        $this->assertFalse($user->fresh()->hasRole('admin'));
        $this->assertTrue($user->fresh()->hasRole('editor'));
    }

    public function test_user_group_module_creates_standard_spatie_role_with_description(): void
    {
        $operator = User::factory()->create();

        $this->actingAs($operator)->post(route('admin.usergroup.store'), [
            'name' => 'reviewer',
            'description' => 'Can review content',
        ])->assertRedirect(route('admin.usergroup.index'));

        $this->assertDatabaseHas('roles', [
            'name' => 'reviewer',
            'guard_name' => 'web',
            'description' => 'Can review content',
        ]);
    }

    public function test_user_list_can_filter_by_spatie_role(): void
    {
        $operator = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $matched = User::factory()->create();
        $other = User::factory()->create();
        $matched->assignRole($role);

        $this->actingAs($operator)
            ->followingRedirects()
            ->get(route('admin.user.index', ['relation' => 'role:'.$role->id]))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->pluck('id')->contains($matched->id)
                && ! $rows->pluck('id')->contains($other->id));
    }
}
