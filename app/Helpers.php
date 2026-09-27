<?php

/**
 * General application helpers.
 *
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use App\Support\RelationFilters;
use App\Support\ColumnFilters;
use App\Support\AdminSearch;
use InvalidArgumentException;

class Helpers
{
    /**
     * Search selected columns similarly to a simple fulltext search.
     */
    public static function virtualFulltextSearchColumns(
        Builder $query, ?string $word, array $fields, string $mode = 'phrase'
    ): Builder {
        return AdminSearch::apply($query, $word, $fields, $mode);
    }

    /**
     * Order query results using request parameters or model defaults.
     */
    public static function orderByColumns(Builder $query, array $orderBy): Builder
    {
        $column = Request::input('orderbycolumn');
        $direction = Request::input('orderbytype');
        $model = $query->getModel();
        $allowed = $model->getAdminSortableColumns();

        if (is_string($column) && in_array($column, $allowed, true)
            && is_string($direction) && in_array(strtolower($direction), ['asc', 'desc'], true)) {
            return $query->orderBy($model->qualifyColumn($column), strtolower($direction));
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    /**
     * Remember only allowed index parameters, never the entire request.
     * An empty supplied value clears that setting, while "0" remains a value.
     * Filter forms use filter=<key>&values[]=<id>; canonical URLs keep relation=.
     */
    public static function resetSaveIndexParameters(
        string $module,
        array $possibleParameters = ['search', 'search_mode', 'orderbycolumn', 'orderbytype', 'relation', 'filters'],
        ?array $allowedFilters = null,
        array $allowedColumnFilters = [],
        string $defaultSearchMode = 'phrase'
    ): string|false {
        $request = request();
        $userId = Auth::id();
        $cacheKey = 'olapus.index.v2.'.hash('sha256', $module.'|'.$userId);
        $saved = $userId === null ? [] : Cache::get($cacheKey, []);
        $saved = is_array($saved) ? array_intersect_key($saved, array_flip($possibleParameters)) : [];
        $state = $saved;

        if ($request->query('reset_filters') === '1') {
            $state = [];
        } else {
            foreach ($possibleParameters as $key) {
                if (!$request->query->has($key)) {
                    continue;
                }
                $value = $request->query($key);
                if ($key === 'filters') {
                    $state[$key] = $value; // Validated below; arrays are expected here.
                    continue;
                }
                abort_unless($value === null || is_string($value), 400, 'Invalid index parameter.');
                abort_if(is_string($value) && strlen($value) > 8000, 400, 'Index parameter is too long.');
                if ($value === null || $value === '') {
                    unset($state[$key]);
                } else {
                    $state[$key] = $value;
                }
            }

            try {
                $columns = ColumnFilters::normalize($state['filters'] ?? [], $allowedColumnFilters);
                if ($request->query->has('field_filter')) {
                    $key = $request->query('field_filter');
                    abort_unless(is_string($key) && isset($allowedColumnFilters[$key]), 400, 'Invalid column filter.');
                    $columns[$key] = $request->query('field_value', '');
                    $columns = ColumnFilters::normalize($columns, $allowedColumnFilters);
                }
                if ($columns === []) {
                    unset($state['filters']);
                } else {
                    $state['filters'] = $columns;
                }
                $mode = $state['search_mode'] ?? $defaultSearchMode;
                abort_unless(in_array($mode, ['phrase', 'all_words'], true), 400, 'Invalid search mode.');
                if ($mode === $defaultSearchMode) {
                    unset($state['search_mode']);
                }
                if ($mode === 'all_words' && isset($state['search'])) {
                    $terms = preg_split('/\\s+/u', trim($state['search']), -1, PREG_SPLIT_NO_EMPTY);
                    abort_unless(is_array($terms) && count($terms) <= 20, 400, 'Search supports at most 20 words.');
                }
                $filters = RelationFilters::parse($state['relation'] ?? null);
                if ($request->query->has('filter')) {
                    $key = $request->query('filter');
                    abort_unless(is_string($key) && preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/D', $key), 400, 'Invalid filter.');
                    $filters[strtolower($key)] = RelationFilters::ids($request->query('values', []));
                }
                if ($allowedFilters !== null) {
                    $filters = array_intersect_key($filters, $allowedFilters);
                }
                $relation = RelationFilters::encode($filters);
                if ($relation === '') {
                    unset($state['relation']);
                } else {
                    $state['relation'] = $relation;
                }
            } catch (InvalidArgumentException $exception) {
                abort(400, $exception->getMessage());
            }
        }

        ksort($state);
        if ($userId !== null) {
            Cache::forever($cacheKey, $state);
        }

        $parameters = $state;
        $page = $request->query('page');
        // Page links contain the full state. Changes to filters/search reset the page.
        if (!$request->query->has('filter') && !$request->query->has('field_filter') && !$request->query->has('reset_filters')
            && is_scalar($page) && ctype_digit((string) $page) && (int) $page > 1) {
            $parameters['page'] = (int) $page;
        }
        ksort($parameters);
        $newRoute = route($module.'.index', $parameters);

        return $newRoute === $request->fullUrl() ? false : $newRoute;
    }

    /**
     * Format a byte count for display.
     */
    public static function formatByteSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        if ($bytes > 0) {
            return $bytes.' b';
        }

        return '0 b';
    }
}
