<?php
/**
 * Media controller
 * 
 * Basic controller for media functions
 * 
 * @category Controller
 * @subpackage Media
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Media;

use App\Helpers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImageController extends Controller
{
   
   /**
    * Get image from storage
    * 
    * @param Request $request
    * @return file
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
            
            /**
             * Get META information
             */
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
        $filename = Helpers::getStorageFilename(env('APP_IMAGE_STORAGE_DIRECTORY', 'images'), $imageMeta->id);
        
        /**
         * Prepare stream
         */
        $stream = Storage::readStream($filename);
        
        /**
         * Cache expiration
         */
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
         * Response code cached
         */
        if(@$_SERVER['HTTP_IF_NONE_MATCH'] == $imageMeta->image_etag 
                || @$_SERVER['HTTP_IF_MODIFIED_SINCE'] == $imageMeta->updated_at->toRfc2822String()){
            
            $responseCode = 304;
        }
        
        /**
         * Response code not cached, but OK
         */
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