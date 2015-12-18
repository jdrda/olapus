<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends AdminModuleController
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
                    'name' => 'required|max:255|unique:feedback,name',
                    'description' => 'max:255',
                    'position' => 'required|integer',
        
    ];
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|integer|min:1|exists:image,id',
        ]);

        if ($validator->fails()) {

            // nothing to do
        }
        else{

            $object->images()->associate($request->input('image_id'));

        }
        
    }
    
}