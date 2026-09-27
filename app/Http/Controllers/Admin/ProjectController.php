<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ProjectController extends AdminModuleController
{
    protected $arValidationArray = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000000',
        'owner_id' => 'nullable|integer|min:1',
        'label_ids' => 'sometimes|array|max:1000',
        'label_ids.*' => 'integer|min:1|distinct',
    ];

    protected $relationFields = ['owner_id' => 'owner', 'label_ids' => 'labels'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('add.lookup.tables:User,ProjectLabel', ['only' => ['create', 'edit']]);
    }

    // The nested-child example is deliberately explicit, rather than a hidden generic walker.
    protected function afterStore($request, $model)
    {
        $this->saveTasks($request, $model);
    }

    protected function afterUpdate($request, $model)
    {
        $this->saveTasks($request, $model);
    }

    private function saveTasks(Request $request, $project): void
    {
        if ($request->input('_tasks_present') !== '1') {
            return; // Other clients updating a project do not implicitly delete its tasks.
        }
        $data = $request->validate([
            'tasks' => 'sometimes|array|max:100',
            'tasks.*.id' => 'nullable|integer|min:1|distinct',
            'tasks.*.name' => 'required|string|max:255',
            'tasks.*.completed' => 'required|boolean',
        ]);
        $rows = $data['tasks'] ?? [];
        $ids = array_values(array_filter(array_column($rows, 'id')));
        $owned = $project->tasks()->whereKey($ids)->pluck('id')->map('strval')->all();
        if (array_diff(array_map('strval', $ids), $owned) !== []) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tasks' => trans('admin_examples.task_not_owned'),
            ]);
        }

        // Scope both deletes and updates to THIS project (never a global Task::find()).
        $project->tasks()->whereNotIn('id', $ids)->delete();
        foreach ($rows as $row) {
            $values = ['name' => $row['name'], 'completed' => (bool) $row['completed']];
            if (!empty($row['id'])) {
                $project->tasks()->whereKey($row['id'])->firstOrFail()->update($values);
            } else {
                $project->tasks()->create($values);
            }
        }
    }
}
