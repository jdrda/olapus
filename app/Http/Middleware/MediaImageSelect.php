<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Adding media parameters
 */
class MediaImageSelect
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $models = ['Image'])
    {
        
        for($a = 2; $a < func_num_args(); $a++){
            
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
