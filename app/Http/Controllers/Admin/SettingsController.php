<?php

namespace App\Http\Controllers\Admin;

class SettingsController extends AdminModuleController
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:settings',
                    'value' => 'max:255',
                    'description' => 'max:255'];
    
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:settings,name'];
}
