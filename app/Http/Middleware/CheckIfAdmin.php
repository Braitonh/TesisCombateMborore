<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Si el usuario NO es admin Y no está ya en pedidos.detalle, redirige
        if ($user && $user->rol !== 'admin' && !$request->routeIs('pedidos.detalle')) {
            return redirect()->route('pedidos.detalle');
        }
    
        return $next($request);
    
    }
}
