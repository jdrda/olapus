<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use LogicException;

/**
 * Eloquent queries and prepared view data for the model's explicit adminFilters map.
 * No database queries or model-name guessing are needed inside Blade.
 */
class AdminFilters
{
    private array $selected;
    private array $definitions;
    private array $options = [];
    private array $columns;
    private array $columnDefinitions;

    public function __construct(private Model $model, private string $route, private array $query = [])
    {
        $this->definitions = $model->getAdminFilters();
        $this->columnDefinitions = $model->getAdminColumnFilters();
        $this->columns = ColumnFilters::normalize($query['filters'] ?? [], $this->columnDefinitions);
        $this->selected = array_intersect_key(
            RelationFilters::parse($query['relation'] ?? null), $this->definitions
        );
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->selected as $key => $ids) {
            $definition = $this->definitions[$key];
            $relationship = $definition['relation'];
            $relation = $this->relation($relationship);
            $column = $relation->getRelated()->getQualifiedKeyName();

            // An explicit configuration can require ALL selected related records.
            if (($definition['match'] ?? 'any') === 'all') {
                foreach ($ids as $id) {
                    $query->whereHas($relationship, function (Builder $related) use ($definition, $column, $id) {
                        if (isset($definition['scope'])) { $related->{$definition['scope']}(); }
                        $related->where($column, $id);
                    });
                }
            } else {
                $query->whereHas($relationship, function (Builder $related) use ($definition, $column, $ids) {
                    if (isset($definition['scope'])) { $related->{$definition['scope']}(); }
                    $related->whereIn($column, $ids);
                });
            }
        }

        return $query;
    }

    public function controls(): array
    {
        $controls = [];
        foreach ($this->definitions as $key => $definition) {
            $options = [];
            $selected = $this->selected[$key] ?? [];
            foreach ($this->options($key) as $id => $label) {
                $options[] = ['id' => (string) $id, 'label' => $label, 'selected' => in_array((string) $id, $selected, true)];
            }
            // A deleted/unavailable relation still has a visible, removable filter.
            foreach ($selected as $id) {
                if (!array_key_exists($id, $this->options($key))) {
                    $options[] = ['id' => $id, 'label' => '#'.$id, 'selected' => true];
                }
            }
            $controls[$key] = [
                'key' => $key,
                'label' => trans($definition['label']),
                'options' => $options,
                'hidden' => $this->hiddenFields(),
                'action' => route($this->route.'.index'),
                'clear_url' => $this->url(['relation' => RelationFilters::encode(RelationFilters::without($this->selected, $key))]),
                'selected' => $selected !== [],
            ];
        }

        return $controls;
    }

    public function badges(): array
    {
        $badges = [];
        if (isset($this->query['search']) && $this->query['search'] !== '') {
            $badges[] = [
                'label' => trans('admin.search').': '.$this->query['search'],
                'url' => $this->url(['search' => '']),
            ];
        }
        foreach ($this->selected as $key => $ids) {
            foreach ($ids as $id) {
                $badges[] = [
                    'label' => trans($this->definitions[$key]['label']).': '.($this->options($key)[$id] ?? '#'.$id),
                    'url' => $this->url(['relation' => RelationFilters::encode(RelationFilters::without($this->selected, $key, $id))]),
                ];
            }
        }

        foreach ($this->columns as $key => $values) {
            $definition = $this->columnDefinitions[$key];
            foreach ((array) $values as $value) {
                $remaining = is_array($values)
                    ? array_values(array_filter($values, fn ($item) => $item !== $value)) : '';
                $option = $definition['options'][$value] ?? null;
                $badges[] = [
                    'label' => trans($definition['label'] ?? $key).': '.($option !== null ? trans($option) : $value),
                    'url' => $this->columnUrl([$key => $remaining]),
                ];
            }
        }

        return $badges;
    }

    public function addUrl(string $key, int|string $id): string
    {
        if (!isset($this->definitions[$key])) {
            throw new LogicException("Unknown admin filter: {$key}");
        }
        $filters = $this->selected;
        $filters[$key][] = (string) $id;

        return $this->url(['relation' => RelationFilters::encode($filters)]);
    }

    public function clearUrl(): string
    {
        return route($this->route.'.index', ['reset_filters' => 1]);
    }

    public function url(array $changes = []): string
    {
        // Keep search, sorting and the other filters; deliberately reset the page.
        return route($this->route.'.index', array_merge($this->parameters(), $changes));
    }

    public function parameters(): array
    {
        $parameters = array_intersect_key($this->query, array_flip(['search', 'search_mode', 'orderbycolumn', 'orderbytype']));
        // An explicit empty value prevents a cleared filter from being restored from cache.
        $parameters['relation'] = RelationFilters::encode($this->selected);
        $parameters['filters'] = $this->columns ?: '';
        $parameters['search_mode'] = $this->searchMode();

        return $parameters;
    }

    /** Form fields use bracket notation for nested filters, not Array-to-string conversion. */
    public function hiddenFields(array $except = []): array
    {
        return self::flatten(array_diff_key($this->parameters(), array_flip($except)));
    }

    private static function flatten(array $values, string $prefix = ''): array
    {
        $fields = [];
        foreach ($values as $key => $value) {
            $name = $prefix === '' ? (string) $key : $prefix.'['.$key.']';
            if (is_array($value)) {
                $fields += self::flatten($value, $name);
            } else {
                $fields[$name] = $value;
            }
        }
        return $fields;
    }

    public function searchMode(): string
    {
        return $this->query['search_mode'] ?? $this->model->getAdminSearchMode();
    }

    /** Merge a preset/one changed field with current fields; keep relations, search and sorting. */
    public function columnUrl(array $changes): string
    {
        $values = ColumnFilters::normalize(array_replace($this->columns, $changes), $this->columnDefinitions);
        return $this->url(['filters' => $values ?: '']);
    }

    public function columnControls(): array
    {
        $controls = [];
        foreach ($this->columnDefinitions as $key => $definition) {
            $type = $definition['type'] ?? 'exact';
            $value = $this->columns[$key] ?? '';
            $options = [];
            foreach ($definition['options'] ?? [] as $id => $label) {
                $options[] = [
                    'id' => (string) $id, 'label' => trans($label),
                    'selected' => in_array((string) $id, (array) $value, true),
                ];
            }
            $controls[$key] = [
                'key' => $key, 'label' => trans($definition['label'] ?? $key),
                'value' => is_array($value) ? implode(', ', $value) : $value,
                'options' => $options,
                'select' => isset($definition['options']),
                'multiple' => $type === 'in' || ($definition['multiple'] ?? is_array($value)),
                'input_type' => str_starts_with($type, 'date') ? 'date'
                    : (in_array($definition['value_type'] ?? '', ['integer', 'number'], true) ? 'number' : 'text'),
                'step' => ($definition['value_type'] ?? '') === 'integer' ? '1' : 'any',
                'min' => $definition['min'] ?? null, 'max' => $definition['max'] ?? null,
                'hidden' => $this->hiddenFields(), 'action' => route($this->route.'.index'),
                'selected' => array_key_exists($key, $this->columns),
                'clear_url' => $this->columnUrl([$key => '']),
            ];
        }
        return $controls;
    }

    /** Developer-defined shortcuts; these are not end-user saved views. */
    public function presets(): array
    {
        $presets = [];
        foreach ($this->model->getAdminFilterPresets() as $preset) {
            $presets[] = [
                'label' => trans($preset['label']),
                'url' => $this->columnUrl($preset['filters']),
            ];
        }
        return $presets;
    }

    private function options(string $key): array
    {
        if (!array_key_exists($key, $this->options)) {
            $definition = $this->definitions[$key];
            $related = $this->relation($definition['relation'])->getRelated();
            $label = $definition['display'] ?? 'name';
            $query = $related->newQuery();
            // Optional named scope for application-specific visibility rules.
            if (isset($definition['scope'])) {
                $query->{$definition['scope']}();
            }
            $this->options[$key] = $query->orderBy($related->qualifyColumn($label))
                ->pluck($related->qualifyColumn($label), $related->getQualifiedKeyName())->all();
        }

        return $this->options[$key];
    }

    private function relation(string $name): Relation
    {
        $relation = $this->model->{$name}();
        if (!$relation instanceof Relation) {
            throw new LogicException("{$name} is not an Eloquent relationship.");
        }

        return $relation;
    }
}
