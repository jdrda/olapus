<?php

namespace App\Http\Controllers\Admin;

class AdvertLocationController extends AdminModuleController
{
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:advertlocation,name',
                    'description' => 'max:255',
                    'color' => 'max:255',
        ];
}
