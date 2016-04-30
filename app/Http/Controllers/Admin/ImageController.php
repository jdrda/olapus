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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ImageController extends AdminModuleController
{
   
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->middleware('media.add.parameters', ['only' => ['store','update']]);
        $this->middleware('add.lookup.tables:ImageCategory', ['only' => ['index','create','edit']]);
    }
   
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'description' => 'max:255',
                    'alt' => 'max:255',
                    'url' => 'max:255|unique:image,url',
                    'image_mime_type' => 'max:255',
                    'image_extension' => 'max:255',
                    'image_original_name' => 'max:255',
                    'image_size' => 'integer',
                    'image_width' => 'integer',
                    'image_height' => 'integer',
                    'image_etag' => 'max:255',
                    'image' => 'max:255',
        
    ];
   
    /**
     * Binary fields
     * 
     * @var array 
     */
    protected $binaryFields = ['image', 'image_mime_type', 'image_extension', 
        'image_original_name', 'image_size', 'image_width', 'image_height'];
            
    /**
     * Get number of pagination rows
     * 
     * @return integer
     */
    public function getRowsToPaginate(){
        
        return env('ADMIN_MEDIA_PAGINATE', 12);
    }
    
    /**
     * Reset cache 
     * 
     * @param object $object
     */
    public function resetCache($object) {
        
        $cacheKey = 'image:' . $object->url . ':' . $object->image_extension;
        
        /**
         * File cached
         */
        if (Cache::has($cacheKey)) {

           Cache::forget($cacheKey);
        }
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
         * Get filename
         */
        $filename = getStorageFilename(env('APP_IMAGE_STORAGE_DIRECTORY', 'images'), $object->id);
        
        /*
         * Check if requested and then save
         */
        if ($update == FALSE || ($update == TRUE && $request->has('image'))) {
            
            $resource = fopen($request->image, 'r');

            Storage::put($filename, $resource);
            
            fclose($resource);
            
            /**
             * Update meta information
             */
            return TRUE;
        }
        
        return FALSE;
    }
}
