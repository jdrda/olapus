<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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
            
            $imageMeta = @Image::where(['url' => $request->imageName, 'image_extension' => $request->imageExtension])->first(['image_mime_type', 'image_size', 'id', 'updated_at', 'image_etag']);
            
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
        
        $expires = Carbon::createFromTimestamp(time()+3600)->toDateTimeString();
      
        /**
         * File headers
         */
        $headers = array(
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $imageMeta->image_mime_type,
            'Content-Transfer-Encoding' => 'binary',
            //'Content-Length'            => File::size(storage_path()."/app/".$filename),
            'Pragma'                    => 'public',
            'Expires'                   => Carbon::createFromTimestamp(time()+3600)->toRfc2822String(),
            'Last-Modified'             => $imageMeta->updated_at->toRfc2822String(),
            'Etag'                      => $imageMeta->image_etag,
        );
        
        /**
         * Response code
         */
        if(@$_SERVER['HTTP_IF_NONE_MATCH'] == $imageMeta->image_etag 
                || @$_SERVER['HTTP_IF_MODIFIED_SINCE'] == $imageMeta->updated_at->toRfc2822String()){
            
            $responseCode = 304;
        }
        else{
            
            $responseCode = 200;
        }

        /**
         * Stream to browser
         */
        return Response::stream(function() use ($stream, $imageMeta) {
                    fpassthru($stream);
                }, $responseCode, $headers);
    }
}