<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddPublishedAt
{
    /**
     * Set published_at according to the presence of the published field.
     */
    public function handle(Request $request, Closure $next)
    {
        $request->merge([
            'published_at' => $request->has('published') ? now() : null,
        ]);

        return $next($request);
    }
}
