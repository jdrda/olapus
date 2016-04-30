<?php
/**
 * Page category module controller
 * 
 * Controller for module Page category
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

class PageCategoryController extends AdminModuleController
{
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:pagecategory,name',
                    'description' => 'max:255',
                    'class' => 'max:255',
        ];
}
