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
use Illuminate\Http\Request;

class AddLookupTables
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Get lookup table data and insert into request
         */
        $intArgs = func_num_args();
        for($a = 2; $a < $intArgs; $a++){
            
            $model = func_get_arg($a);
            $modelWithNamespace = 'App\\'.$model;
            
            $request->merge([$model => $modelWithNamespace::orderBy('name', 'asc')->get()]);
        }
  
        return $next($request);     
    }
}
