<?php

namespace App\Http\Controllers\Admin;

class ProjectLabelController extends AdminModuleController
{
    protected $arValidationArray = ['name' => 'required|string|max:255'];
}
