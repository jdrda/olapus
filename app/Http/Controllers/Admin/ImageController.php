<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;

class ImageController extends Controller
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'description' => 'max:255',
                    'alt' => 'max:255',
                    'url' => 'max:255|unique:image',
                    'image' => 'image|max:2000000',
        ];
    
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * Return page
         */
        return view('admin.modules.image.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validate input
         */
        $this->validate($request, $this->arValidationArray);
        
        /**
         * Create row
         */
        Settings::create([
            'name' => $request['name'],
            'alt' => $request['alt'],
            'description' => $request['description'],
            'image' => $request['image'],
        ]);
        
        /**
         * Redirect to index
         */
        return redirect(route('admin.settings.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
