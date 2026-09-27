<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Integration example: the external identity is (source_id, external_id), NOT external_id alone. */
class ExternalRecord extends Model
{
    use SoftDeletes, AdminModelTrait, Compoships;

    protected $table = 'external_records';
    protected $fillable = ['name', 'source_id', 'external_id'];
    protected $casts = ['source_id' => 'integer', 'external_id' => 'integer'];
    protected $defaultOrderBy = ['source_id' => 'asc', 'external_id' => 'asc'];
    protected $excludedFromIndex = [];
    protected $fulltextFields = ['name' => ['operator' => 'LIKE', 'prefix' => '%', 'suffix' => '%']];
    protected $adminWith = ['rows'];
    protected $adminFilters = [
        'row' => ['relation' => 'rows', 'label' => 'admin_module_externalrow.name'],
    ];
    protected $adminColumnFilters = [
        'source_id' => ['type' => 'exact', 'value_type' => 'integer', 'min' => 1, 'label' => 'admin_composite.source'],
        'external_id' => ['type' => 'exact', 'value_type' => 'integer', 'min' => 1, 'label' => 'admin_composite.external_id'],
        'name_prefix' => ['column' => 'name', 'type' => 'starts_with', 'label' => 'admin_filters.name_prefix'],
        'created' => ['column' => 'created_at', 'type' => 'date', 'label' => 'admin_filters.created_on'],
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(ExternalRow::class, ['source_id', 'record_external_id'], ['source_id', 'external_id']);
    }
}
