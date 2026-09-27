<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use SoftDeletes, AdminModelTrait;

    protected $table = 'project_tasks';
    protected $fillable = ['name', 'project_id', 'completed'];
    protected $casts = ['completed' => 'boolean'];
    protected $fulltextFields = ['name' => ['operator' => 'LIKE', 'prefix' => '%', 'sufix' => '%']];
    protected $defaultOrderBy = ['id' => 'desc'];
    protected $excludedFromIndex = [];
    protected $adminWith = ['project'];
    protected $adminFilters = [
        'project' => ['relation' => 'project', 'label' => 'admin_module_project.name'],
    ];

    protected $adminColumnFilters = [
        'completed' => ['type' => 'in', 'value_type' => 'boolean', 'label' => 'admin_filters.completed',
            'options' => ['0' => 'admin_filters.no', '1' => 'admin_filters.yes']],
        'created_from' => ['column' => 'created_at', 'type' => 'date_from', 'label' => 'admin_filters.created_from'],
        'created_to' => ['column' => 'created_at', 'type' => 'date_to', 'label' => 'admin_filters.created_to'],
    ];
    protected $adminFilterPresets = [
        ['label' => 'admin_filters.pending', 'filters' => ['completed' => '0']],
        ['label' => 'admin_filters.done', 'filters' => ['completed' => '1']],
        ['label' => 'admin_filters.any_state', 'filters' => ['completed' => '']],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
