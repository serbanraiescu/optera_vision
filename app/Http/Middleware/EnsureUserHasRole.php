<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Ensure user is authenticated
        if (!$request->user()) {
            return redirect()->route('admin.login');
        }

        // 2. Ensure user possesses at least one of the specified roles
        if (!$request->user()->hasRole($roles)) {
            abort(403, 'Acces neautorizat. Nu aveți permisiunile necesare pentru a accesa această pagină.');
        }

        return $next($request);
    }
}
