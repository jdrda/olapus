<?php
/**
 * Advert module controller
 * 
 * Controller for module Advert
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertController extends AdminModuleController {

    /**
     * Constructor
     * 
     * @param Request $request
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
     * 
     * @var array
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
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'image_id' => 'integer|min:1|exists:image,id',
        ]);
        
        /**
         * Validator fails - nothing to do
         */
        if ($validator->fails()) {

            
        }
        
        /**
         * Validator OK - save it
         */
        else {

            $object->images()->associate($request->input('image_id'));
        }
    }
    
    /**
     * Associate relationships to other table, where ID if object must be present
     * 
     * @param object $object
     * @param Request $request
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
                
                /**
                 * Validator fails - nothing to do
                 */
                if ($validator->fails()) {

                }
                
                /**
                 * Validator OK - save it
                 */
                else {

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
