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
           
            $request->{$name.'_mime_type'} = $request->file('image')->getMimeType();
            $request->{$name.'_original_extension'} = $request->file('image')->getClientOriginalExtension();
            $request->{$name.'_original_name'} = $request->file('image')->getClientOriginalName();
            $request->{$name.'_extension'} = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_EXTENSION);
            $request->$name = file_get_contents($request->file('image')->getRealPath());
        }
        
        return $next($request);
    }
}
