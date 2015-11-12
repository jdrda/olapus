<?php

namespace App\Http\Controllers\Admin;

class ImageCategoryController extends AdminModuleController
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:imagecategory',
                    'description' => 'max:255',
                    'color' => 'max:255'];
    
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:imagecategory,name'];
 
}
