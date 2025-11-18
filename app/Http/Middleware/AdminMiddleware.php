<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //usuario no logueado
        if(!Auth::check()) {
            return redirect()->route('login');
        }
        //usuario logueado
        if(Auth::user()->rol !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso de administrador');
        }
        //admin
        return $next($request);
    }
}
