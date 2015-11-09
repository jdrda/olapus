<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;

class ImageController extends Controller
{
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getImage($strURL)
    {
        
        $objArticle = \App\ArticlesPublic::where('url', $strURL)->first();
        
        if(!$objArticle){
            App::abort(404);
        }
        
        $response = Response::make($objArticle->image, 200);
        $response->header('Content-Type', 'image/jpeg');
        return $response;
    }
}
