<?php

namespace Tests\Unit;

use App\Support\RelationFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RelationFiltersTest extends TestCase
{
    public function test_repeated_keys_keep_multiple_values_and_roundtrip(): void
    {
        $filters = RelationFilters::parse('label:2,owner:7,label:1,label:2');
        $this->assertSame(['label' => ['1', '2'], 'owner' => ['7']], $filters);
        $this->assertSame('label:1,label:2,owner:7', RelationFilters::encode($filters));
    }

    public function test_removal_preserves_the_other_values_and_groups(): void
    {
        $filters = RelationFilters::parse('label:1,label:2,owner:7');
        $this->assertSame('label:2,owner:7', RelationFilters::encode(RelationFilters::without($filters, 'label', '1')));
        $this->assertSame('owner:7', RelationFilters::encode(RelationFilters::without($filters, 'label')));
    }

    public function test_zero_is_not_treated_as_an_empty_value(): void
    {
        $this->assertSame(['0', '1'], RelationFilters::ids(['0', '001', '1', '']));
    }

    public function test_nested_input_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RelationFilters::ids([['1']]);
    }

    public function test_unbounded_filter_lists_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RelationFilters::ids(range(1, 101));
    }

    public function test_sql_expressions_are_not_valid_filter_keys_or_ids(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RelationFilters::parse('label:1 OR 1=1');
    }
}
