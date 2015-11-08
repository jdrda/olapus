<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;

class ImageController extends Controller
{
    use AdminModuleTrait;
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'description' => 'max:255',
                    'alt' => 'max:255',
                    'url' => 'max:255|unique:image',
                    'image' => 'image|max:4000000',
    ];
    protected $arValidationArrayUpdateChange = [
                    'url' => 'required|max:255|unique:image,url'];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        /**
         * Handle saved settings
         */
        if(($redirectRoute = resetSaveIndexParameters('admin.image')) !== FALSE){
            
            return redirect($redirectRoute);
        }
        
        /**
         * Get the rows
         */
        $arResults = Image::allColumns(@$request->search)->orderByColumns()->paginate(env('ADMIN_MEDIA_PAGINATE', 10));
        
        
        /**
         * Return page
         */
        return view('admin.modules.image.index', ['results' => $arResults]);
    }
}
