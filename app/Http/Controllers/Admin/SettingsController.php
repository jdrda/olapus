<?php

namespace App\Http\Controllers\Admin;

class SettingsController extends AdminModuleController
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:settings,name',
                    'value' => 'max:255',
                    'description' => 'max:255'];
    
}
