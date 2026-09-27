<?php
/**
 * Article category module controller
 *
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

class ArticleCategoryController extends AdminModuleController
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:articlecategory,name',
        'meta_title' => 'max:255',
        'meta_description' => 'max:255',
        'meta_keywords' => 'max:255',
        'url' => 'required|max:255|unique:articlecategory,url',
        'text' => 'max:1000000',
        'color' => 'required|max:255',
    ];
}
