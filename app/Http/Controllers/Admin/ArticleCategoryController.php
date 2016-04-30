<?php
/**
 * Article category module controller
 * 
 * Controller for module Article category
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

class ArticleCategoryController extends AdminModuleController {

    /**
     * Constructor
     * 
     * @param Request $request
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
     * 
     * @var array
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
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request) {

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

            // nothing to do
        } 
        
        /**
         * Validator OK - save it
         */
        else {

            $object->images()->associate($request->input('image_id'));
        }
    }

}
