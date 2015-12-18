<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

/**
 * Adding media parameters
 */
class MediaAddParameters {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        foreach ($request->file() as $name => $value) {

            /**
             * All media
             */
            $request->merge(
                    [
                        $name . '_mime_type' => $request->file($name)->getMimeType(),
                        $name . '_original_extension' => $request->file($name)->getClientOriginalExtension(),
                        $name . '_original_name' => $request->file($name)->getClientOriginalName(),
                        $name . '_extension' => pathinfo($request->file($name)->getClientOriginalName(), PATHINFO_EXTENSION),
                        $name . '_size' => filesize($request->file($name)->getRealPath()),
                        $name . '_etag' => sha1_file($request->file($name)->getRealPath()),
            ]);



            /**
             * Images
             */
            if (str_contains($name . '_mime_type', 'image')) {
                list($width, $height) = (getimagesize($request->file($name)->getRealPath()));
                $request->merge(
                        [
                            $name . '_width' => $width,
                            $name . '_height' => $height,
                ]);

                /**
                 * Save to DB or storage?
                 */
                /* if(env('APP_IMAGE_LOCATION', 'storage') == 'database'){
                  $request->merge(
                  [
                  $name => file_get_contents($request->file($name)->getRealPath())
                  ]);
                  }
                  else{ */
                $request->merge(
                        [
                            $name => $request->file($name)->getRealPath()
                ]);
                /* } */
            }

            
        }

        return $next($request);
    }

}
