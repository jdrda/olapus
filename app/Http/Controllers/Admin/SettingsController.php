<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    use AdminModuleTrait;
    
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
