<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Validator;

class ArticleCategoryController extends AdminModuleController
{
    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();
        
        /**
         * Other tables
         */
        $this->middleware('media.image.select:Image', ['only' => ['create','edit']]);

    }
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:articlecategory',
                    'meta_title' => 'max:255',
                    'meta_description' => 'max:255',
                    'meta_keywords' => 'max:255',
                    'url' => 'required|max:255|unique:articlecategory',
                    'text' => 'max:1000000',
                    'color' => 'required|max:255'];
    
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:articlecategory,name',
                    'url' => 'required|max:255|unique:articlecategory,url',
        ];
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {

            // nothing to do
        }
        else{
        
            /**
             * Find category or save default
             */
            try{
                
                $image = Image::findOrFail($request->input('image_id'));
                $object->images()->associate($image);

            } catch (Exception $ex) {

                // nothing to do
            }
        }
        
    }
}
