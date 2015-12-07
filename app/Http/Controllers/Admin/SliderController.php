<?php

namespace App\Http\Controllers\Admin;

class SliderController extends AdminModuleController
{
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:slider,name',
                    'description' => 'max:255',
                    'cycle_interval' => 'required|integer|min:1'];
}
