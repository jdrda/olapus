<?php
/**
 * Add puplished at
 * 
 * Adds published_at datetime
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

class AddPublishedAt
{
    /**
     * Add published at for request where the field exists with NOW datetime
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
 
        if($request->has('published')){
            
            $request->merge(['published_at' => \Carbon\Carbon::now()]);
        }
        else{

            $request->merge(['published_at' => NULL]);
        }
        
        return $next($request);
       
    }
}
