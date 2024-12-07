<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ((Auth::check() && Auth::user()->role == 'admin')) { // Cambia 1 por el ID del usuario permitido
            return $next($request);
        }

        // Redirige o muestra un error si no tiene acceso
        return redirect('/')->with('error', 'No tienes acceso a esta sección.');

        //return $next($request);
    }
}
