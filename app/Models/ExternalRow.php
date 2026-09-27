<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalRow extends Model
{
    use SoftDeletes, AdminModelTrait, Compoships;

    protected $table = 'external_rows';
    protected $fillable = ['name', 'source_id', 'record_external_id'];
    protected $casts = ['source_id' => 'integer', 'record_external_id' => 'integer'];
    protected $defaultOrderBy = ['id' => 'desc'];
    protected $excludedFromIndex = [];
    protected $fulltextFields = ['name' => ['operator' => 'LIKE', 'prefix' => '%', 'suffix' => '%']];
    protected $adminWith = ['record'];
    protected $adminFilters = [
        'record' => ['relation' => 'record', 'label' => 'admin_module_externalrecord.name'],
    ];
    protected $adminColumnFilters = [
        'name' => ['type' => 'like', 'label' => 'admin_module_externalrow.fields.name'],
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(ExternalRecord::class, ['source_id', 'record_external_id'], ['source_id', 'external_id']);
    }
}
