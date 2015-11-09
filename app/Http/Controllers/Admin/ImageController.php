<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;

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
     * Constructor
     */
    public function __construct() {
        $this->__traitConstruct();
        
        $this->middleware('media.addparameters', ['except' => ['index','create','show','edit','destroy']]);
    }
}
