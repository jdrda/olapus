<?php

namespace App\Models;

use App\Helpers;
use App\Support\AdminFilters;
use App\Support\ColumnFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\ValidationException;
use LogicException;

/** Shared CRUD scopes and small relationship helpers for Olapus modules. */
trait AdminModelTrait
{
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function scopeFulltextAllColumns(Builder $query)
    {
        $search = request()->query('search');
        return Helpers::virtualFulltextSearchColumns(
            $query, is_string($search) ? $search : null, $this->fulltextFields ?? [],
            request()->query('search_mode', $this->getAdminSearchMode())
        );
    }

    public function scopeOrderByColumns(Builder $query)
    {
        return Helpers::orderByColumns($query, $this->defaultOrderBy ?? [$this->getKeyName() => 'desc']);
    }

    public function scopeExcludeFromIndex(Builder $query)
    {
        $columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        return $query->select(array_map($this->qualifyColumn(...), array_values(array_diff($columns, $this->excludedFromIndex ?? []))));
    }

    public function scopeExcludeFromFind(Builder $query)
    {
        if (empty($this->excludedFromFind)) {
            return $query;
        }
        $columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        return $query->select(array_map($this->qualifyColumn(...), array_values(array_diff($columns, $this->excludedFromFind))));
    }

    public function scopeRelationships(Builder $query)
    {
        return empty($this->adminWith) ? $query : $query->with($this->adminWith);
    }

    /** Explicit URL-key => Eloquent-relation allowlist; no names guessed from input. */
    public function getAdminFilters(): array
    {
        return $this->adminFilters ?? [];
    }

    /** Explicit field aliases; never expose every database column automatically. */
    public function getAdminColumnFilters(): array
    {
        return $this->adminColumnFilters ?? [];
    }

    public function getAdminFilterPresets(): array
    {
        return $this->adminFilterPresets ?? [];
    }

    public function getAdminSearchMode(): string
    {
        return $this->adminSearchMode ?? 'phrase';
    }

    public function scopeColumnFilters(Builder $query)
    {
        return ColumnFilters::apply($query, request()->query('filters', []) ?: [], $this->getAdminColumnFilters());
    }

    public function getAdminSortableColumns(): array
    {
        if (isset($this->sortableColumns)) {
            return $this->sortableColumns;
        }
        return array_values(array_diff(
            $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()),
            $this->excludedFromIndex ?? [], $this->getHidden()
        ));
    }

    public function scopeExternalTablesFilter(Builder $query)
    {
        return (new AdminFilters($this, '', request()->query()))->apply($query);
    }

    /** Associate one related record; null explicitly removes the relationship. */
    public function associateAdminRelation(string $relationship, mixed $id, ?string $field = null): void
    {
        $relation = $this->{$relationship}();
        if (!$relation instanceof BelongsTo) {
            throw new LogicException("{$relationship} must be a belongsTo relationship.");
        }
        $foreignKeys = $relation->getForeignKeyName();
        $ownerKeys = $relation->getOwnerKeyName();
        if ($id === null || $id === '') {
            if (is_array($foreignKeys)) {
                foreach ($foreignKeys as $key) { $this->setAttribute($key, null); }
                $this->setRelation($relationship, null);
            } else {
                $relation->dissociate();
            }
            return;
        }
        $field ??= is_array($foreignKeys) ? $relationship : $foreignKeys;
        if (!is_int($id) && !is_string($id)) {
            throw ValidationException::withMessages([$field => trans('validation.exists', ['attribute' => $field])]);
        }
        $lookup = $relation->getRelated()->newQuery();
        // A form never supplies half a composite key. Compoships copies the tuple from the selected model.
        $related = is_array($ownerKeys) ? $lookup->whereKey($id)->first() : $lookup->where($ownerKeys, $id)->first();
        if (!$related) {
            throw ValidationException::withMessages([$field => trans('validation.exists', ['attribute' => $field])]);
        }
        $relation->associate($related);
    }

    /** Sync only after saving the parent. Empty array detaches all; missing field is handled by the caller. */
    public function syncAdminRelation(string $relationship, mixed $ids, ?string $field = null): void
    {
        $relation = $this->{$relationship}();
        if (!$relation instanceof BelongsToMany) {
            throw new LogicException("{$relationship} must be a belongsToMany relationship.");
        }
        $field ??= $relationship;
        if (!is_array($ids) || count($ids) > 1000) {
            throw ValidationException::withMessages([$field => trans('validation.array', ['attribute' => $field])]);
        }
        foreach ($ids as $id) {
            if (!is_int($id) && !is_string($id)) {
                throw ValidationException::withMessages([$field => trans('validation.exists', ['attribute' => $field])]);
            }
        }
        $ids = array_values(array_unique(array_map('strval', array_filter($ids, fn ($id) => $id !== '' && $id !== null))));
        // Derive the table and key from the relationship, including singular/custom tables.
        $related = $relation->getRelated();
        $key = $relation->getRelatedKeyName();
        if (is_array($key)) {
            throw new LogicException('Composite-key pivot editing needs explicit tuple validation; it is not handled by this ID-only helper.');
        }
        $validIds = $related->newQuery()->whereIn($key, $ids)->pluck($key)->map('strval')->all();
        if (array_diff($ids, $validIds) !== []) {
            throw ValidationException::withMessages([$field => trans('validation.exists', ['attribute' => $field])]);
        }
        $relation->sync($validIds);
    }
}
