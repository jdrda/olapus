<?php
/**
 * Media image select
 * 
 * Adds images to request for selection
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
use Illuminate\Http\Request;

class MediaImageSelect
{

    /**
     * Main function
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param array $models
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $intArgs = func_num_args();
        for($a = 2; $a < $intArgs; $a++){
            
            $model = func_get_arg($a);
            $modelWithNamespace = 'App\\'.$model;
            
            
            $array = ['other_tables' =>
            [
                $model => $modelWithNamespace::orderBy('id', 'desc')->get(['id', 'name', 'url', 'image_extension'])
            ]
                ];
            
            $request->merge($array);
        }

        
        return $next($request);
    }
}
