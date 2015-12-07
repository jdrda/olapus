<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleCategoryController extends AdminModuleController {

    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();

        /**
         * Other tables
         */
        $this->middleware('media.image.select:Image', ['only' => ['create', 'edit']]);
    }

    /**
     * Validation rules
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:articlecategory,name',
        'meta_title' => 'max:255',
        'meta_description' => 'max:255',
        'meta_keywords' => 'max:255',
        'url' => 'required|max:255|unique:articlecategory,url',
        'text' => 'max:1000000',
        'color' => 'required|max:255'];
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'image_id' => 'required|integer|min:1|exists:image,id',
        ]);

        if ($validator->fails()) {

            // nothing to do
        } else {


            $object->images()->associate($request->input('image_id'));
        }
    }

}
