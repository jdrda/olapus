<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertController extends AdminModuleController {

    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();

        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:AdvertLocation', ['only' => ['create', 'edit']]);

        /**
         * Other tables
         */
        $this->middleware('media.image.select:Image', ['only' => ['create', 'edit']]);
    }

    /**
     * Validation rules
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:article,name',
        'caption' => 'max:255',
        'text' => 'max:1000000',
        'link_url' => 'max:255',
        'link_title' => 'max:255',
        'position' => 'required:integer'
    ];

    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'image_id' => 'integer|min:1|exists:image,id',
        ]);

        if ($validator->fails()) {

            // nothing to do
        } else {

            $object->images()->associate($request->input('image_id'));
        }
    }
    
    /**
     * Associate relationships to other table with ID
     */
    public function associateRelationshipsWithID($object, Request $request) {
        
        /**
         * Validate advert location ID, if failed set to default
         */
        if($request->has('advertlocation_id')){
            
            $validIDs = [];
            
            
            foreach ($request->input('advertlocation_id') as $advertlocation_id) {

                $arrayForValidator = ['advertlocation_id' =>  $advertlocation_id];

                $validator = Validator::make($arrayForValidator, [
                            'advertlocation_id' => 'required|integer|min:1|exists:advertlocation,id',
                ]);

                if ($validator->fails()) {

                    /**
                     * Nothing to do
                     */
                } else {

                    $validIDs[] = $advertlocation_id;
                }
            }
            
            /**
             * Sync all to pivot
             */
            $object->advertlocations()->sync($validIDs);
        }
    }
    

}
