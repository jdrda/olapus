<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

/** Autotech's word search, with an explicit phrase/all_words mode and safe AND/OR grouping. */
class AdminSearch
{
    public static function apply(Builder $query, ?string $search, array $fields, string $mode = 'phrase'): Builder
    {
        if ($search === null || trim($search) === '' || $fields === []) {
            return $query;
        }
        if (!in_array($mode, ['phrase', 'all_words'], true)) {
            throw new InvalidArgumentException('Invalid search mode.');
        }
        if (strlen($search) > 8000) {
            throw new InvalidArgumentException('Search is too long.');
        }
        $terms = $mode === 'all_words'
            ? preg_split('/\s+/u', trim($search), -1, PREG_SPLIT_NO_EMPTY)
            : [$search];
        if ($terms === false || count($terms) > 20) {
            throw new InvalidArgumentException('Search supports at most 20 words.');
        }

        // Every term must match at least one configured column. Never leak OR outside the group.
        foreach ($terms as $term) {
            $query->where(function (Builder $group) use ($fields, $term) {
                $used = false;
                foreach ($fields as $column => $attributes) {
                    if (is_int($column)) {
                        $column = $attributes;
                        $attributes = [];
                    }
                    if (($attributes['type'] ?? 'text') === 'date') {
                        if (ColumnFilters::isDate($term)) {
                            $group->orWhereDate($column, '=', $term);
                            $used = true;
                        }
                        continue; // Do not feed arbitrary text into a database date comparison.
                    }
                    $group->orWhere(
                        $column,
                        $attributes['operator'] ?? '=',
                        ($attributes['prefix'] ?? '').$term.($attributes['suffix'] ?? $attributes['sufix'] ?? '')
                    );
                    $used = true;
                }
                if (!$used) {
                    $group->whereRaw('1 = 0'); // No date column can match an invalid date.
                }
            });
        }
        return $query;
    }
}
