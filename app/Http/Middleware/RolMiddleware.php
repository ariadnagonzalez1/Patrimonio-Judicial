<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        // 🚫 Si no hay usuario logueado
        if (!$user) {
            abort(403, 'Debes iniciar sesión.');
        }

        // 🚫 Si el usuario está inactivo (si tenés campo activo en la tabla)
        if (property_exists($user, 'activo') && (int) $user->activo !== 1) {
            abort(403, 'Usuario inactivo.');
        }

        // ✅ ADMIN: acceso total
        if (isset($user->rol) && strtolower($user->rol->nombre) === 'administrador') {
            return $next($request);
        }

        // ✅ Si el rol del usuario coincide con alguno de los roles permitidos
        if (isset($user->rol) && in_array(strtolower($user->rol->nombre), array_map('strtolower', $roles))) {
            return $next($request);
        }

        // 🚫 Caso contrario, acceso denegado
        abort(403, 'Acceso denegado. No tienes permisos para esta sección.');
    }
}
