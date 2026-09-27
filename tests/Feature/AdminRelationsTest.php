<?php

namespace Tests\Feature;

use App\Models\Advert;
use App\Models\AdvertLocation;
use App\Models\Page;
use App\Models\PageCategory;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRelationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['honeypot.enabled' => false]);
        Cache::flush();
        // These tests exercise tables/forms, not the independent avatar image library.
        \Laravolt\Avatar\Facade::shouldReceive('create')->andReturnSelf();
        \Laravolt\Avatar\Facade::shouldReceive('toBase64')->andReturn('data:image/png;base64,');
    }

    private function fixtures(): array
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $a = ProjectLabel::create(['name' => 'Label A']);
        $b = ProjectLabel::create(['name' => 'Label B']);
        $first = Project::create(['name' => 'First', 'owner_id' => $owner->id]);
        $second = Project::create(['name' => 'Second', 'owner_id' => $owner->id]);
        $third = Project::create(['name' => 'Third', 'owner_id' => $other->id]);
        $first->labels()->attach([$a->id, $b->id]);
        $second->labels()->attach($b->id);
        $third->labels()->attach($a->id);
        $this->actingAs($owner);
        return compact('owner', 'other', 'a', 'b', 'first', 'second', 'third');
    }

    public function test_multiple_values_are_or_and_different_relations_are_and_without_duplicates(): void
    {
        $f = $this->fixtures();
        $relation = 'label:'.$f['a']->id.',label:'.$f['b']->id.',owner:'.$f['owner']->id;
        $this->followingRedirects()->get(route('admin.project.index', ['relation' => $relation]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2
                && $rows->pluck('id')->sort()->values()->all() === [$f['first']->id, $f['second']->id]);
    }

    public function test_has_many_filter_uses_the_relationship(): void
    {
        $f = $this->fixtures();
        $task = $f['first']->tasks()->create(['name' => 'Child task']);
        $this->followingRedirects()->get(route('admin.project.index', ['relation' => 'task:'.$task->id]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->pluck('id')->all() === [$f['first']->id]);
    }

    public function test_filter_forms_add_and_clear_a_group_without_losing_the_other_filters(): void
    {
        $f = $this->fixtures();
        $this->followingRedirects()->get(route('admin.project.index', [
            'relation' => 'owner:'.$f['owner']->id,
            'filter' => 'label', 'values' => [$f['a']->id, $f['b']->id],
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2);
        $this->followingRedirects()->get(route('admin.project.index', ['filter' => 'label']))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2);
        $this->followingRedirects()->get(route('admin.project.index', ['reset_filters' => 1]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 3);
    }

    public function test_search_zero_is_preserved_and_invalid_sort_is_not_sql(): void
    {
        $this->fixtures();
        Project::create(['name' => 'Project 0']);
        $this->followingRedirects()->get(route('admin.project.index', [
            'search' => '0', 'orderbycolumn' => 'name; DROP TABLE projects', 'orderbytype' => 'bad',
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 1);
        $this->assertDatabaseCount('projects', 4);
    }

    public function test_saving_many_to_many_and_clearing_all_works(): void
    {
        $f = $this->fixtures();
        $this->put(route('admin.project.update', $f['first']->id), [
            'name' => 'Edited', 'owner_id' => $f['owner']->id,
            'label_ids' => [$f['b']->id],
        ])->assertRedirect();
        $this->assertSame([$f['b']->id], $f['first']->fresh()->labels->modelKeys());
        $this->put(route('admin.project.update', $f['first']->id), [
            'name' => 'Edited', 'owner_id' => $f['owner']->id,
            '_relations_present' => ['label_ids' => '1'],
        ])->assertRedirect();
        $this->assertSame([], $f['first']->fresh()->labels->modelKeys());
    }

    public function test_missing_multi_select_field_preserves_existing_relations(): void
    {
        $f = $this->fixtures();
        $this->put(route('admin.project.update', $f['first']->id), ['name' => 'Renamed'])
            ->assertRedirect();
        $this->assertCount(2, $f['first']->fresh()->labels);
        $this->assertSame($f['owner']->id, $f['first']->fresh()->owner_id);
    }

    public function test_invalid_related_id_rolls_back_main_record_and_pivots(): void
    {
        $f = $this->fixtures();
        $this->post(route('admin.project.store'), [
            'name' => 'Must not survive', 'owner_id' => $f['owner']->id,
            'label_ids' => [$f['a']->id, 999999],
        ])->assertSessionHasErrors('label_ids');
        $this->assertDatabaseMissing('projects', ['name' => 'Must not survive']);
    }

    public function test_nested_child_ids_cannot_edit_another_parent_and_changes_are_atomic(): void
    {
        $f = $this->fixtures();
        $foreign = $f['third']->tasks()->create(['name' => 'Owned elsewhere']);
        $this->put(route('admin.project.update', $f['first']->id), [
            'name' => 'Must roll back', '_tasks_present' => '1',
            'tasks' => [['id' => $foreign->id, 'name' => 'Stolen', 'completed' => '1']],
        ])->assertSessionHasErrors('tasks');
        $this->assertSame('First', $f['first']->fresh()->name);
        $this->assertSame('Owned elsewhere', $foreign->fresh()->name);
    }

    public function test_nested_child_rows_can_be_created_updated_and_removed(): void
    {
        $f = $this->fixtures();
        $task = $f['first']->tasks()->create(['name' => 'Old']);
        $removed = $f['first']->tasks()->create(['name' => 'Remove']);
        $this->put(route('admin.project.update', $f['first']->id), [
            'name' => 'First', '_tasks_present' => '1', 'tasks' => [
                ['id' => $task->id, 'name' => 'Updated', 'completed' => '1'],
                ['id' => null, 'name' => 'New task', 'completed' => '0'],
            ],
        ])->assertRedirect();
        $this->assertSame('Updated', $task->fresh()->name);
        $this->assertTrue($task->fresh()->completed);
        $this->assertSoftDeleted('project_tasks', ['id' => $removed->id]);
        $this->assertSame(2, $f['first']->tasks()->count());
    }

    public function test_existing_nonstandard_pivot_keys_work_in_both_directions(): void
    {
        $user = User::factory()->create();
        $location = AdvertLocation::create(['name' => 'Placement', 'color' => 'primary']);
        $advert = Advert::create(['name' => 'Ad', 'position' => 1]);
        $advert->advertlocations()->attach($location->id);
        $this->assertSame([$location->id], $advert->fresh()->advertlocations->modelKeys());
        $this->assertSame([$advert->id], $location->fresh()->adverts->modelKeys());
        $category = PageCategory::create(['name' => 'Category']);
        $page = Page::create(['name' => 'Page', 'url' => 'example', 'user_id' => $user->id]);
        $page->pagecategories()->attach($category->id);
        $this->assertSame([$page->id], $category->fresh()->pages->modelKeys());
    }

    public function test_generated_command_is_registered_without_writing_files(): void
    {
        $this->artisan('make:admin-module', ['module' => 'PortSmokeExample', '--dry-run' => true])
            ->assertExitCode(0);
        $this->assertFileDoesNotExist(app_path('Models/PortSmokeExample.php'));
    }

    public function test_authentication_still_uses_laravel_ui(): void
    {
        $user = User::factory()->create(['password' => Hash::make('A-testing-password-42')]);
        $this->post(route('authPostLogin'), ['email' => $user->email, 'password' => 'A-testing-password-42'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_example_routes_require_login(): void
    {
        $this->get(route('admin.project.index'))->assertRedirect(route('login'));
    }
}
