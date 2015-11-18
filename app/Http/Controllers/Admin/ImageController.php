<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\ImageCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ImageController extends AdminModuleController
{
   
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->middleware('media.add.parameters', ['only' => ['store','update']]);
        $this->middleware('add.lookup.tables:ImageCategory', ['only' => ['create','edit']]);
    }
   
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'description' => 'max:255',
                    'alt' => 'max:255',
                    'url' => 'max:255|unique:image',
                    'image_mime_type' => 'max:255',
                    'image_extension' => 'max:255',
                    'image_original_name' => 'max:255',
                    'image_size' => 'integer',
                    'image_width' => 'integer',
                    'image_height' => 'integer',
                    'image' => 'required|max:4000000',
        
    ];
    protected $arValidationArrayUpdateChange = [
                    'url' => 'required|max:255|unique:image,url',
                    'image' => 'max:4000000',
    ];
    
    /**
     * Get pagination rows
     */
    public function getRowsToPaginate(){
        
        return env('ADMIN_MEDIA_PAGINATE', 12);
    }
    
    /**
     * Reset cache
     */
    public function resetCache($object) {
        
        $cacheKey = $object->url.":".$object->image_extension;
        
        /**
         * File cached
         */
        if (Cache::has($cacheKey)) {

            $image = Cache::forget($cacheKey);
        }
    }
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate category ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'imagecategory_id' => 'required|integer|min:1|exists:imagecategory,id',
        ]);

        if ($validator->fails()) {

            $object->imagecategories()->associate(1);
        }
        else{

            $object->imagecategories()->associate($request->input('imagecategory_id'));

        }
        
    }
    
}
