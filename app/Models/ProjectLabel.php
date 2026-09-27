<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLabel extends Model
{
    use SoftDeletes, AdminModelTrait;

    protected $table = 'project_labels';
    protected $fillable = ['name'];
    protected $fulltextFields = ['name' => ['operator' => 'LIKE', 'prefix' => '%', 'sufix' => '%']];
    protected $defaultOrderBy = ['name' => 'asc'];
    protected $excludedFromIndex = [];
    protected $adminWith = ['projects'];
    protected $adminFilters = [
        'project' => ['relation' => 'projects', 'label' => 'admin_module_project.name'],
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_label_project', 'project_label_id', 'project_id');
    }
}
