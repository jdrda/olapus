<?php

namespace Tests\Unit;

use App\Support\ColumnFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ColumnFiltersTest extends TestCase
{
    public function test_zero_and_multiple_exact_values_survive_normalization(): void
    {
        $this->assertSame(['status' => ['0', '1']], ColumnFilters::normalize(
            ['status' => ['1', '0', '', '1'], 'password' => 'hidden'],
            ['status' => ['type' => 'in', 'value_type' => 'boolean']]
        ));
    }

    public function test_real_dates_are_required(): void
    {
        $this->assertTrue(ColumnFilters::isDate('2024-02-29'));
        $this->assertFalse(ColumnFilters::isDate('2026-02-29'));
        $this->expectException(InvalidArgumentException::class);
        ColumnFilters::normalize(['from' => '2026-02-30'], ['from' => ['column' => 'created_at', 'type' => 'date_from']]);
    }

    public function test_nested_inputs_are_not_valid_sql_values(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ColumnFilters::normalize(['name' => ['x']], ['name' => ['type' => 'like']]);
    }

    public function test_unknown_columns_are_not_allowed_implicitly(): void
    {
        $this->assertSame([], ColumnFilters::normalize(['password' => 'a', 'internal_key' => 'b'], []));
    }
}
