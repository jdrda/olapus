<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Olapus URL format: relation=label:1,label:2,owner:7.
 * Repeated values of one key are OR; different keys are AND.
 * This class only handles URL values. It never resolves user-supplied model names.
 */
class RelationFilters
{
    public static function parse(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (!is_string($value) || strlen($value) > 8000) {
            throw new InvalidArgumentException('Invalid relation filter.');
        }

        $filters = [];
        foreach (explode(',', $value) as $pair) {
            $parts = explode(':', trim($pair), 2);
            if (count($parts) !== 2 || !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/D', $parts[0])) {
                throw new InvalidArgumentException('Invalid relation filter.');
            }
            $key = strtolower($parts[0]);
            $filters[$key][] = trim($parts[1]);
        }

        if (count($filters) > 20) {
            throw new InvalidArgumentException('Too many relation filters.');
        }

        foreach ($filters as $key => $ids) {
            $filters[$key] = self::ids($ids);
        }
        ksort($filters);

        return $filters;
    }

    public static function ids(mixed $values): array
    {
        if ($values === null || $values === '') {
            return [];
        }
        if (!is_array($values) || count($values) > 100) {
            throw new InvalidArgumentException('Select at most 100 values per filter.');
        }

        $ids = [];
        foreach ($values as $value) {
            if (!is_int($value) && !is_string($value)) {
                throw new InvalidArgumentException('Invalid filter value.');
            }
            $value = trim((string) $value);
            // Empty values allow an empty native multi-select to clear its filter.
            if ($value === '') {
                continue;
            }
            // Integer keys and UUID/ULID-style keys are supported, not SQL expressions.
            if (!preg_match('/^[a-zA-Z0-9_-]{1,100}$/D', $value)) {
                throw new InvalidArgumentException('Invalid filter value.');
            }
            if (ctype_digit($value)) {
                $value = ltrim($value, '0');
                $value = $value === '' ? '0' : $value;
            }
            $ids[] = $value;
        }
        $ids = array_values(array_unique($ids));
        sort($ids, SORT_NATURAL);

        return $ids;
    }

    public static function encode(array $filters): string
    {
        ksort($filters);
        $pairs = [];
        foreach ($filters as $key => $ids) {
            foreach (self::ids($ids) as $id) {
                $pairs[] = $key.':'.$id;
            }
        }

        return implode(',', $pairs);
    }

    public static function without(array $filters, string $key, ?string $id = null): array
    {
        if ($id === null) {
            unset($filters[$key]);
        } else {
            $filters[$key] = array_values(array_filter(
                $filters[$key] ?? [], fn ($value) => (string) $value !== $id
            ));
            if ($filters[$key] === []) {
                unset($filters[$key]);
            }
        }

        return $filters;
    }
}
