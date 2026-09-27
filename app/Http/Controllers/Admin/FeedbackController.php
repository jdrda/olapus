<?php
/**
 * Feedback module controller
 *
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

class FeedbackController extends AdminModuleController
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:feedback,name',
        'description' => 'max:255',
        'position' => 'required|integer',
    ];
}
