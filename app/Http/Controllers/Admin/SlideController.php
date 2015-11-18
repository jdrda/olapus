<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SlideController extends AdminModuleController
{
    
    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();
        
        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:Slider', ['only' => ['index','create','edit']]);
        
        /**
         * Other tables
         */
        $this->middleware('media.image.select:Image', ['only' => ['create','edit']]);

    }
   
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255|unique:slide',
                    'description' => 'max:255',
                    'caption' => 'max:255',
                    'text' => 'max:1000000',
                    'position' => 'required|integer',
        
    ];
    protected $arValidationArrayUpdateChange = [
                    'name' => 'required|max:255|unique:slide,name',
    ];
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate slider ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'slider_id' => 'required|integer|min:1|exists:slider,id',
        ]);

        if ($validator->fails()) {

            $object->sliders()->associate(1);
        }
        else{

            $object->sliders()->associate($request->input('slider_id'));
        }
        
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
