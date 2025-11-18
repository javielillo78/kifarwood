<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'es'));

        app()->setLocale($locale);
        if (class_exists(Carbon::class)) {
            Carbon::setLocale($locale);
        }
        return $next($request);
    }
}
