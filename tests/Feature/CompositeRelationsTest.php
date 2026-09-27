<?php

namespace Tests\Feature;

use App\Models\ExternalRecord;
use App\Models\ExternalRow;
use App\Models\User;
use Database\Seeders\ExternalRelationsDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/** Real Eloquent/Compoships integration tests; run after composer require awobaz/compoships. */
class CompositeRelationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Cache::flush();
        \Laravolt\Avatar\Facade::shouldReceive('create')->andReturnSelf();
        \Laravolt\Avatar\Facade::shouldReceive('toBase64')->andReturn('data:image/png;base64,');
        $this->seed(ExternalRelationsDemoSeeder::class);
    }

    public function test_lazy_and_eager_loading_do_not_mix_sources(): void
    {
        $records = ExternalRecord::with('rows')->orderBy('source_id')->get();
        $this->assertCount(2, $records);
        foreach ($records as $record) {
            $this->assertCount(2, $record->rows);
            $this->assertSame([$record->source_id], $record->rows->pluck('source_id')->unique()->values()->all());
            $this->assertSame(2, $record->rows()->count());
        }
        foreach (ExternalRow::with('record')->get() as $row) {
            $this->assertSame($row->source_id, $row->record->source_id);
            $this->assertSame($row->source_id, $row->record()->firstOrFail()->source_id);
        }
    }

    public function test_where_has_correlates_both_keys(): void
    {
        $row = ExternalRow::where('source_id', 1)->firstOrFail();
        $records = ExternalRecord::whereHas('rows', fn ($query) => $query->whereKey($row->id))->get();
        $this->assertCount(1, $records);
        $this->assertSame(1, $records->first()->source_id);
    }

    public function test_relation_filter_uses_related_primary_id_without_losing_composite_match(): void
    {
        $this->actingAs(User::factory()->create());
        $record = ExternalRecord::where('source_id', 2)->firstOrFail();
        $this->followingRedirects()->get(route('admin.externalRow.index', ['relation' => 'record:'.$record->id]))
            ->assertOk()->assertViewHas('results', fn ($rows) => $rows->total() === 2
                && $rows->pluck('source_id')->unique()->values()->all() === [2]);
    }

    public function test_form_selection_copies_both_owner_keys_and_ignores_forged_columns(): void
    {
        $this->actingAs(User::factory()->create());
        $record = ExternalRecord::where('source_id', 2)->firstOrFail();
        $this->post(route('admin.externalRow.store'), [
            'name' => 'New child', 'external_record_id' => $record->id,
            'source_id' => 1, 'record_external_id' => 999,
        ])->assertRedirect();
        $this->assertDatabaseHas('external_rows', ['name' => 'New child', 'source_id' => 2, 'record_external_id' => 100]);
    }

    public function test_reassigning_to_same_external_id_in_different_source_works(): void
    {
        $this->actingAs(User::factory()->create());
        $row = ExternalRow::where('source_id', 1)->firstOrFail();
        $target = ExternalRecord::where('source_id', 2)->firstOrFail();
        $this->put(route('admin.externalRow.update', $row->id), [
            'name' => 'Moved', 'external_record_id' => $target->id,
        ])->assertRedirect();
        $this->assertSame(2, $row->fresh()->source_id);
        $this->assertSame($target->id, $row->fresh()->record->id);
    }

    public function test_unique_external_identity_is_validated_within_source(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.externalRecord.store'), ['name' => 'Duplicate', 'source_id' => 1, 'external_id' => 100])
            ->assertSessionHasErrors('external_id');
        $this->post(route('admin.externalRecord.store'), ['name' => 'Third source', 'source_id' => 3, 'external_id' => 100])
            ->assertRedirect();
        $this->assertDatabaseHas('external_records', ['source_id' => 3, 'external_id' => 100]);
    }

    public function test_demo_seeder_is_repeatable_without_creating_users(): void
    {
        $users = User::count();
        $this->seed(ExternalRelationsDemoSeeder::class);
        $this->assertDatabaseCount('external_records', 2);
        $this->assertDatabaseCount('external_rows', 4);
        $this->assertSame($users, User::count());
    }

    public function test_example_requires_login(): void
    {
        $this->get(route('admin.externalRecord.index'))->assertRedirect(route('login'));
    }

    public function test_invalid_owner_id_does_not_insert_a_row(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.externalRow.store'), ['name' => 'Invalid', 'external_record_id' => 999999])
            ->assertSessionHasErrors('external_record_id');
        $this->assertDatabaseMissing('external_rows', ['name' => 'Invalid']);
    }
}
