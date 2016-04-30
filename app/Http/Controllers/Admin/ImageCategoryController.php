<?php
/**
 * Image category module controller
 * 
 * Controller for module Image category
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

class ImageCategoryController extends AdminModuleController
{
    
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:imagecategory,name',
                    'description' => 'max:255',
                    'color' => 'max:255'];
}
