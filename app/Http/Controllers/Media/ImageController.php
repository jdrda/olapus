<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
        $cacheKey = 'image:' . $request->imageName . ':' . $request->imageExtension;
        
        /**
         * File cached
         */
        if (Cache::has($cacheKey)) {
            
            $imageMeta = Cache::get($cacheKey);
        }
        /**
         * File not cached
         */
        else{
            
            $imageMeta = @Image::where(['url' => $request->imageName, 'image_extension' => $request->imageExtension])->first(['image_mime_type', 'image_size', 'id']);
            
            /**
             * File does not exist
             */
            if(empty($imageMeta) == TRUE){
                App::abort(404);
            }
            
            /**
             * Save meta information to cache
             */
            Cache::forever($cacheKey, $imageMeta);
        }
        
        /**
         * Get filename
         */
        $filename = getStorageFilename(env('APP_IMAGE_STORAGE_DIRECTORY', 'images'), $imageMeta->id);
        
        /**
         * Prepare stream
         */
        $stream = Storage::readStream($filename);
        
        /**
         * File headers
         */
        $headers = array(
            'Content-Type' => $imageMeta->image_mime_type,
            'Content-Transfer-Encoding' => 'binary',
            'Content-Length' => $imageMeta->size,
        );
        
        /**
         * Stream to browser
         */
        return Response::stream(function() use ($stream, $imageMeta) {
                    fpassthru($stream);
                }, 200, $headers);
    }
}
