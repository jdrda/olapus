<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Generic relationship example: one owner, many labels, many child tasks. */
class Project extends Model
{
    use SoftDeletes, AdminModelTrait;

    protected $table = 'projects';
    protected $fillable = ['name', 'description', 'owner_id'];
    protected $fulltextFields = [
        'name' => ['operator' => 'LIKE', 'prefix' => '%', 'suffix' => '%'],
        'description' => ['operator' => 'LIKE', 'prefix' => '%', 'suffix' => '%'],
    ];
    protected $defaultOrderBy = ['id' => 'desc'];
    protected $excludedFromIndex = [];
    protected $adminWith = ['owner', 'labels', 'tasks'];
    protected $adminFilters = [
        'owner' => ['relation' => 'owner', 'label' => 'admin_module_project.fields.owner'],
        'label' => ['relation' => 'labels', 'label' => 'admin_module_project.fields.labels'],
        'task' => ['relation' => 'tasks', 'label' => 'admin_module_project.fields.tasks'],
    ];

    // Two different filter aliases may target the same column (an inclusive date range).
    protected $adminColumnFilters = [
        'name' => ['type' => 'like', 'label' => 'admin_module_project.fields.name'],
        'created_from' => ['column' => 'created_at', 'type' => 'date_from', 'label' => 'admin_filters.created_from'],
        'created_to' => ['column' => 'created_at', 'type' => 'date_to', 'label' => 'admin_filters.created_to'],
    ];

    public function getAdminFilterPresets(): array
    {
        return [
            ['label' => 'admin_filters.this_month', 'filters' => [
                'created_from' => now()->startOfMonth()->toDateString(),
                'created_to' => now()->endOfMonth()->toDateString(),
            ]],
            ['label' => 'admin_filters.any_period', 'filters' => ['created_from' => '', 'created_to' => '']],
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(ProjectLabel::class, 'project_label_project', 'project_id', 'project_label_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'project_id');
    }
}
