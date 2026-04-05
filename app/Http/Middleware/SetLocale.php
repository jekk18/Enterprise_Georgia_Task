<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // ვიღებთ ენას URL-ის პირველი სეგმენტიდან (მაგ: /ka/...)
        $locale = $request->segment(1);

        if (in_array($locale, ['ka', 'en'])) {
            app()->setLocale($locale);
        } else {
            // თუ ენა არ არის მითითებული, დავაყენოთ ქართული
            app()->setLocale('ka');
        }

        return $next($request);
    }
}
