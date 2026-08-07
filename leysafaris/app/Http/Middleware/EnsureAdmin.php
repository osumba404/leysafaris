<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the admin area.');
        }

        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
