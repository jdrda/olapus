<?php
/**
 * Media add parameters
 * 
 * Adds variable parameters to media files
 * 
 * @category Middleware
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Middleware;

use Closure;

/**
 * Adding media parameters
 */
class MediaAddParameters {

    /**
     * Main function
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
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
