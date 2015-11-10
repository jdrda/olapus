<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Adding media parameters
 */
class MediaAddParameters
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach ($request->file() as $name => $value){
           
            /**
             * All media
             */
            $request->merge(
                    [
                        $name.'_mime_type' => $request->file($name)->getMimeType(),
                        $name.'_original_extension' => $request->file($name)->getClientOriginalExtension(),
                        $name.'_original_name' => $request->file($name)->getClientOriginalName(),
                        $name.'_extension' => pathinfo($request->file($name)->getClientOriginalName(), PATHINFO_EXTENSION),                        
                        $name => file_get_contents($request->file($name)->getRealPath()),
                        $name.'_size' => filesize($request->file($name)->getRealPath()),
            ]);
            
            /**
             * Images
             */
            if(str_contains($name.'_mime_type', 'image')){
                list($width, $height) = (getimagesize($request->file($name)->getRealPath()));
                $request->merge(
                    [
                        $name.'_width' => $width,
                        $name.'_height' => $height,
                ]);
            }
        }

        return $next($request);
    }
}
