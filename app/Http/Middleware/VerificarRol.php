<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $usuario = $request->user();

        if (!$usuario || !$usuario->activo) {
            abort(403, 'Acceso no autorizado.');
        }

        $rol = $usuario->rol?->nombre;

        if (!$rol || !in_array($rol, $roles, true)) {
            abort(403, 'No tienes permisos para acceder a esta función.');
        }

        return $next($request);
    }
}