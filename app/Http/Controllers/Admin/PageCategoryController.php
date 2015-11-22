<?php

namespace App\Http\Controllers\Admin;

class PageCategoryController extends AdminModuleController
{
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:pagecategory',
                    'description' => 'max:255',
                    'class' => 'max:255',
        ];
    
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:pagecategory,name'];
}