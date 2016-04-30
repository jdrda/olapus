<?php
/**
 * Settings module controller
 * 
 * Controller for module Settings, universal module to hold any values
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace App\Http\Controllers\Admin;

class SettingsController extends AdminModuleController
{
    
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:settings,name',
                    'value' => 'max:255',
                    'description' => 'max:255'];
}