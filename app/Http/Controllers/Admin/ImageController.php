<?php
/**
 * Image module controller
 * 
 * Controller for module Image
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends AdminModuleController
{
   
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->middleware('add.lookup.tables:ImageCategory', ['only' => ['index','create','edit']]);
    }
   
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'description' => 'max:255',
                    'alt' => 'max:255',
                    'url' => 'max:255|unique:image,url'
    ];
   
    /**
     * Binary fields
     * 
     * @var array 
     */
    protected $binaryFields = [];
            
    /**
     * Get number of pagination rows
     * 
     * @return integer
     */
    public function getRowsToPaginate(){
        
        return env('ADMIN_MEDIA_PAGINATE', 12);
    }
    
    /**
     * Associate relationships to other table
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate category ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'imagecategory_id' => 'required|integer|min:1|exists:imagecategory,id',
        ]);
        
        /**
         * Validator fails - set category to default
         */
        if ($validator->fails()) {

            $object->imagecategories()->associate(1);
        }
        
        /**
         * Validator OK - save it
         */
        else{

            $object->imagecategories()->associate($request->input('imagecategory_id'));
        } 
    }

    /**
     * Save media to storage
     *
     * @param object $object
     * @param Request $request
     * @param boolean $update
     * @return boolean
     */
    public function saveMediaToStorage($object, $request, $update = FALSE) {

        /**
         * Check if requested and then save
         */
        if ($update == FALSE || ($update == TRUE && $request->has('image'))) {

            /**
             * Image has an url
             */
            if(strlen($request->input('url') > 5)){
                $object->addMedia($request->file('image'))->toMediaCollection('images')
                    ->usingName($request->input('url'));
            }
            else{
                $object->addMedia($request->file('image'))->toMediaCollection('images');
            }

            /**
             * Update meta information
             */
            return TRUE;
        }

        return FALSE;
    }

}
