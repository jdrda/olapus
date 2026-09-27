<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\ProjectTask;
use App\Models\User;
use App\Support\AdminFilters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminAutotechFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['honeypot.enabled' => false]);
        Cache::flush();
        \Laravolt\Avatar\Facade::shouldReceive('create')->andReturnSelf();
        \Laravolt\Avatar\Facade::shouldReceive('toBase64')->andReturn('data:image/png;base64,');
        $this->actingAs(User::factory()->create());
    }

    public function test_column_and_relation_filters_are_combined_without_duplicate_rows(): void
    {
        $p = Project::create(['name' => 'First']);
        $other = Project::create(['name' => 'Second']);
        $a = $p->tasks()->create(['name' => 'Open', 'completed' => false]);
        $p->tasks()->create(['name' => 'Done', 'completed' => true]);
        $other->tasks()->create(['name' => 'Other open', 'completed' => false]);
        $this->followingRedirects()->get(route('admin.projectTask.index', [
            'relation' => 'project:'.$p->id, 'filters' => ['completed' => ['0']],
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->pluck('id')->all() === [$a->id]);
    }

    public function test_multiple_column_values_and_zero_are_kept(): void
    {
        $p = Project::create(['name' => 'Project']);
        $p->tasks()->create(['name' => 'Open', 'completed' => false]);
        $p->tasks()->create(['name' => 'Done', 'completed' => true]);
        $this->followingRedirects()->get(route('admin.projectTask.index', ['filters' => ['completed' => ['0', '1']]]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2)
            ->assertViewHas('filterBadges', fn ($badges) => count($badges) === 2);
    }

    public function test_removing_last_column_value_does_not_restore_it_from_cache(): void
    {
        $p = Project::create(['name' => 'Project']);
        $p->tasks()->create(['name' => 'Open', 'completed' => false]);
        $p->tasks()->create(['name' => 'Done', 'completed' => true]);
        $this->followingRedirects()->get(route('admin.projectTask.index', ['filters' => ['completed' => ['0']]]))->assertOk();
        $this->followingRedirects()->get(route('admin.projectTask.index', ['field_filter' => 'completed']))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2);
        $this->followingRedirects()->get(route('admin.projectTask.index'))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2);
    }

    public function test_adding_relation_retains_columns_and_sorting(): void
    {
        $p = Project::create(['name' => 'Project']);
        $p->tasks()->create(['name' => 'Open', 'completed' => false]);
        $p->tasks()->create(['name' => 'Done', 'completed' => true]);
        $this->followingRedirects()->get(route('admin.projectTask.index', [
            'filters' => ['completed' => ['0']], 'orderbycolumn' => 'name', 'orderbytype' => 'asc',
            'filter' => 'project', 'values' => [$p->id],
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 1)
            ->assertViewHas('indexFilters', fn ($filters) => $filters->parameters()['filters']['completed'] === ['0']);
    }

    public function test_inclusive_date_bounds_on_one_column_work_together(): void
    {
        $inside = Project::create(['name' => 'Inside']);
        $outside = Project::create(['name' => 'Outside']);
        $inside->forceFill(['created_at' => '2026-09-30 23:59:59'])->save();
        $outside->forceFill(['created_at' => '2026-10-01 00:00:00'])->save();
        $this->followingRedirects()->get(route('admin.project.index', [
            'filters' => ['created_from' => '2026-09-01', 'created_to' => '2026-09-30'],
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->pluck('id')->all() === [$inside->id]);
    }

    public function test_invalid_date_is_a_bad_request_not_a_database_error(): void
    {
        $this->get(route('admin.project.index', ['filters' => ['created_from' => '2026-02-30']]))->assertStatus(400);
    }

    public function test_unknown_column_is_never_used_as_sql(): void
    {
        Project::create(['name' => 'Safe']);
        $this->followingRedirects()->get(route('admin.project.index', ['filters' => ['password' => 'secret']]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 1);
    }

    public function test_nested_scalar_filter_input_is_rejected(): void
    {
        $this->get(route('admin.project.index', ['filters' => ['name' => ['invalid']]]))->assertStatus(400);
    }

    public function test_phrase_and_all_words_have_explicit_different_meanings(): void
    {
        $split = Project::create(['name' => 'Jan', 'description' => 'Praha']);
        $phrase = Project::create(['name' => 'Jan Praha']);
        $this->followingRedirects()->get(route('admin.project.index', ['search' => 'Jan Praha', 'search_mode' => 'phrase']))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->pluck('id')->all() === [$phrase->id]);
        $this->followingRedirects()->get(route('admin.project.index', ['search' => 'Jan Praha', 'search_mode' => 'all_words']))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2);
    }

    public function test_word_search_does_not_escape_a_relation_constraint(): void
    {
        $label = ProjectLabel::create(['name' => 'Chosen']);
        $inside = Project::create(['name' => 'Jan', 'description' => 'Praha']);
        $outside = Project::create(['name' => 'Jan Praha']);
        $inside->labels()->attach($label->id);
        $this->followingRedirects()->get(route('admin.project.index', [
            'search' => 'Jan Praha', 'search_mode' => 'all_words', 'relation' => 'label:'.$label->id,
        ]))->assertOk()->assertViewHas('results', fn ($rows) => $rows->pluck('id')->all() === [$inside->id]);
    }

    public function test_preset_url_keeps_other_filters_but_resets_page(): void
    {
        $filters = new AdminFilters(new ProjectTask, 'admin.projectTask', [
            'relation' => 'project:7', 'search' => 'Alpha', 'page' => 9,
            'filters' => ['created_from' => '2026-09-01'],
        ]);
        parse_str(parse_url($filters->presets()[0]['url'], PHP_URL_QUERY), $query);
        $this->assertSame('project:7', $query['relation']);
        $this->assertSame(['0'], $query['filters']['completed']);
        $this->assertSame('2026-09-01', $query['filters']['created_from']);
        $this->assertArrayNotHasKey('page', $query);
    }

    public function test_last_filter_badge_is_visible_when_no_row_matches(): void
    {
        Project::create(['name' => 'Alpha']);
        $this->followingRedirects()->get(route('admin.project.index', ['filters' => ['name' => 'Not here']]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 0)
            ->assertViewHas('filterBadges', fn ($badges) => count($badges) === 1);
    }
}
