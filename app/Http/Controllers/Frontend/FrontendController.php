<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use App\Slider;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewValues = [];
        
        /**
         * Upper sponsor images
         */
        $viewValues['upper_sponsor'] = Image::where('imagecategory_id', 2)
                ->orderBy('id', 'asc')->get(['description', 'url', 'alt', 'image_extension']);
        
        /**
         * Upper slider
         */
        $viewValues['upper_slider'] = Slider::where('id', 1)->relationships()->
                get()->first();
        
        /**
         * Main partners
         */
        $viewValues['main_partners'] = Image::where('imagecategory_id', 4)
                ->orderBy('id', 'asc')->get(['description', 'url', 'alt', 'image_extension']);
        
        /**
         * Main media partners
         */
        $viewValues['main_media_partners'] = Image::where('imagecategory_id', 5)
                ->orderBy('id', 'asc')->get(['description', 'url', 'alt', 'image_extension']);
       
        return view('frontend.index', $viewValues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
