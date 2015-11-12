<?php

namespace App\Http\Controllers\Admin;

class SettingsController extends AdminModuleController
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:slider',
                    'description' => 'max:255',
                    'cycle_interval' => 'required|integer|min:1'];
    
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:settings,name'];
}
