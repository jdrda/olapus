<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use App\ImageCategory;

class ImageController extends Controller
{
    use AdminModuleTrait {
        AdminModuleTrait::__construct as private __traitConstruct;
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
        
        return env('ADMIN_MEDIA_PAGINATE', 10);
    }
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
        /**
         * Validate category ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'imagecategory_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            
            $object->imagecategories()->associate(1);
        }
        else{
        
            /**
             * Find category or save default
             */
            try{
                $imageCategory = ImageCategory::findOrFail($request->input('imagecategory_id'));
                $object->imagecategories()->associate($imageCategory);

            } catch (Exception $ex) {

                $object->imagecategories()->associate(1);
            }
        }
        
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->__traitConstruct();
        
        $this->middleware('media.add.parameters', ['only' => ['store','update']]);
        $this->middleware('add.lookup.tables:ImageCategory', ['only' => ['create','edit']]);
        
    }
}
