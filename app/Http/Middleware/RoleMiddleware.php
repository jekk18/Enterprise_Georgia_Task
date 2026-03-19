<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next, string $role): Response
{
    if (!auth()->check() || auth()->user()->role->name !== $role) {
         
        abort(403, 'თქვენ არ გაქვთ ამ გვერდზე წვდომის უფლება! შესაძლოა თქვენი როლი შეიცვალა.');
    }

    return $next($request);
}
}
