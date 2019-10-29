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
         * Get META information
         */
        $imageMeta = Image::where(['url' => $request->imageName])->first();

        /**
         * File does not exist
         */
        if(empty($imageMeta) == TRUE){
            App::abort(404);
        }

        $mediaItems = $imageMeta->getMedia();
        $fullPathOnDisk = $mediaItems[0]->getPath();


        /**
         * Prepare stream
         */
        $stream = Storage::readStream($fullPathOnDisk);

      
        /**
         * File headers
         */
        $headers = array(
            'Content-Description'       => 'File Transfer',
            'Content-Transfer-Encoding' => 'binary',
            'Pragma'                    => 'public',
            'Expires'                   => Carbon::createFromTimestamp(time()+3600)->toRfc2822String(),
            'Last-Modified'             => $imageMeta->updated_at->toRfc2822String()
        );
        
        /**
         * Response code cached
         */
        if( (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $imageMeta->image_etag)
                || (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $imageMeta->updated_at->toRfc2822String()) ){
            
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
        return Response::stream(function() use ($stream) {
                    fpassthru($stream);
                }, $responseCode, $headers);
    }
}
