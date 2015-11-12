<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ImageController extends Controller
{
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getImage(Request $request)
    {  
        /**
         * Check the cache
         */
        $cacheKey = $request->imageName.":".$request->imageExtension;
        
        /**
         * File cached
         */
        if (Cache::has($cacheKey)) {
            $image = Cache::get($cacheKey);
        }
        /**
         * File not cached
         */
        else{

            $image = Image::where(['url' => $request->imageName, 'image_extension' => $request->imageExtension])->first();
            Cache::forever($cacheKey, $image);
        }
        
        if(!$image){
            App::abort(404);
        }
        
        $response = Response::make($image->image, 200);
        $response->header('Content-Type', $image->image_mime_type);
        return $response;
    }
}
