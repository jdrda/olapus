<?php

/**
 * Slide module controller
 * 
 * Controller for module Slide
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

class SlideController extends AdminModuleController {

    /**
     * Constructor
     * 
     * @var Request $request
     */
    public function __construct(Request $request) {
        parent::__construct();

        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:Slider', ['only' => ['index', 'create', 'edit']]);

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
        'name' => 'required|max:255|unique:slide,name',
        'description' => 'max:255',
        'caption' => 'max:255',
        'text' => 'max:1000000',
        'position' => 'required|integer',
    ];

    /**
     * Associate relationships to other table, where ID if object must be present
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate slider ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'slider_id' => 'required|integer|min:1|exists:slider,id',
        ]);
        
        /**
         * Validator fails - associate default slider
         */
        if ($validator->fails()) {

            $object->sliders()->associate(1);
        } 
        
        /**
         * Validator OK - save it
         */
        else {

            $object->sliders()->associate($request->input('slider_id'));
        }

        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'image_id' => 'required|integer|min:1|exists:image,id',
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
}