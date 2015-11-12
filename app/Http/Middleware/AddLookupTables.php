<?php

namespace App\Http\Middleware;

use Closure;

class AddLookupTables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $models)
    {
        /**
         * Workaround instead of variadic function for PHP < 5.6
         */
       
        for($a = 2; $a < func_num_args(); $a++){
            
            $model = func_get_arg($a);
            $modelWithNamespace = 'App\\'.$model;
            
            $request->merge([$model => $modelWithNamespace::orderBy('name', 'asc')->get()]);
        }
  
        
        return $next($request);
       
    }
}
