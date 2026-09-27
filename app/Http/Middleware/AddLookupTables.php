<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddLookupTables
{
    /**
     * Add lookup-table data to the request.
     */
    public function handle(Request $request, Closure $next)
    {
        $argumentCount = func_num_args();

        for ($i = 2; $i < $argumentCount; $i++) {
            $model = func_get_arg($i);
            $modelClass = 'App\\Models\\'.$model;

            $request->merge([
                $model => $modelClass::orderBy('name', 'asc')->get(),
            ]);
        }

        return $next($request);
    }
}
