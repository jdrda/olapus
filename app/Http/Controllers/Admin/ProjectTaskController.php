<?php

namespace App\Http\Controllers\Admin;

class ProjectTaskController extends AdminModuleController
{
    protected $arValidationArray = [
        'name' => 'required|string|max:255',
        'project_id' => 'required|integer',
        'completed' => 'required|boolean',
    ];
    protected $relationFields = ['project_id' => 'project'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('add.lookup.tables:Project', ['only' => ['create', 'edit']]);
    }
}
