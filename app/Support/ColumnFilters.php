<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use LogicException;

/** Filters over explicitly allowed columns; complements, never replaces, relation filters. */
class ColumnFilters
{
    public const TYPES = ['exact', 'in', 'like', 'starts_with', 'date', 'date_from', 'date_to'];

    /** Request/cache data only. Unknown keys are ignored; malformed known values are rejected. */
    public static function normalize(mixed $input, array $definitions): array
    {
        if ($input === null || $input === '') {
            return [];
        }
        if (!is_array($input) || count($input) > 50) {
            throw new InvalidArgumentException('Invalid column filters.');
        }

        $filters = [];
        foreach (array_intersect_key($input, $definitions) as $key => $value) {
            $definition = $definitions[$key];
            self::checkDefinition($key, $definition);
            $type = $definition['type'] ?? 'exact';
            if ($value === null || $value === '' || $value === []) {
                continue;
            }
            if (is_array($value)) {
                if (!in_array($type, ['exact', 'in'], true) || count($value) > 100) {
                    throw new InvalidArgumentException("Invalid values for filter {$key}.");
                }
                $values = [];
                foreach ($value as $item) {
                    if ($item !== null && $item !== '') {
                        $values[] = self::value($key, $item, $definition);
                    }
                }
                $value = array_values(array_unique($values));
                sort($value, SORT_STRING);
                if ($value === []) {
                    continue;
                }
            } else {
                $value = self::value($key, $value, $definition);
                if ($type === 'in') {
                    $value = [$value];
                }
            }
            $filters[$key] = $value;
        }
        ksort($filters);
        return $filters;
    }

    public static function apply(Builder $query, array $filters, array $definitions): Builder
    {
        $model = $query->getModel();
        foreach (self::normalize($filters, $definitions) as $key => $value) {
            $definition = $definitions[$key];
            // Qualify through the real model (including custom table/connection names).
            $column = $model->qualifyColumn($definition['column'] ?? $key);
            switch ($definition['type'] ?? 'exact') {
                case 'like':
                    $query->where($column, 'LIKE', '%'.$value.'%');
                    break;
                case 'starts_with':
                    $query->where($column, 'LIKE', $value.'%');
                    break;
                case 'date':
                    $query->whereDate($column, '=', $value);
                    break;
                case 'date_from':
                    $query->whereDate($column, '>=', $value);
                    break;
                case 'date_to':
                    $query->whereDate($column, '<=', $value);
                    break;
                default:
                    is_array($value) ? $query->whereIn($column, $value) : $query->where($column, '=', $value);
            }
        }
        return $query;
    }

    public static function isDate(string $value): bool
    {
        return (bool) preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $value, $parts)
            && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }

    private static function value(string $key, mixed $value, array $definition): string
    {
        if (!is_string($value) && !is_int($value) && !is_float($value) && !is_bool($value)) {
            throw new InvalidArgumentException("Invalid value for filter {$key}.");
        }
        $value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        if (strlen($value) > 500 || str_contains($value, "\0")) {
            throw new InvalidArgumentException("Value is too long or invalid for filter {$key}.");
        }
        if (str_starts_with($definition['type'] ?? '', 'date') && !self::isDate($value)) {
            throw new InvalidArgumentException("Use a valid YYYY-MM-DD date for filter {$key}.");
        }
        $valueType = $definition['value_type'] ?? 'string';
        if ($valueType === 'boolean' && !in_array($value, ['0', '1'], true)) {
            throw new InvalidArgumentException("Use 0 or 1 for filter {$key}.");
        }
        if ($valueType === 'integer' && !preg_match('/^-?\d{1,18}$/D', $value)) {
            throw new InvalidArgumentException("Use an integer for filter {$key}.");
        }
        if ($valueType === 'number' && !preg_match('/^-?\d{1,18}(\.\d{1,10})?$/D', $value)) {
            throw new InvalidArgumentException("Use a number for filter {$key}.");
        }
        if (in_array($valueType, ['integer', 'number'], true)
            && ((isset($definition['min']) && (float) $value < $definition['min'])
                || (isset($definition['max']) && (float) $value > $definition['max']))) {
            throw new InvalidArgumentException("Value is out of range for filter {$key}.");
        }
        if (isset($definition['options']) && !array_key_exists($value, $definition['options'])) {
            throw new InvalidArgumentException("Unknown option for filter {$key}.");
        }
        return $value;
    }

    private static function checkDefinition(string $key, array $definition): void
    {
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/D', $key)
            || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/D', $definition['column'] ?? $key)
            || !in_array($definition['type'] ?? 'exact', self::TYPES, true)
            || !in_array($definition['value_type'] ?? 'string', ['string', 'integer', 'number', 'boolean'], true)) {
            throw new LogicException("Invalid column filter definition: {$key}");
        }
    }
}
