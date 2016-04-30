<?php
/**
 * Add loopkup tables
 * 
 * Adds lookup tables in controller
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

class AddLookupTables
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $models)
    {
        /**
         * Get lookup table data and insert into request
         */
        for($a = 2; $a < func_num_args(); $a++){
            
            $model = func_get_arg($a);
            $modelWithNamespace = 'App\\'.$model;
            
            $request->merge([$model => $modelWithNamespace::orderBy('name', 'asc')->get()]);
        }
  
        return $next($request);     
    }
}
