<?php
/**
 * Slider module controller
 * 
 * Controller for module Slider
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

class SliderController extends AdminModuleController
{
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:slider,name',
                    'description' => 'max:255',
                    'cycle_interval' => 'required|integer|min:1'];
}
