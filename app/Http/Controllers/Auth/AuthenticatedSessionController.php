<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Maneja la autenticación del usuario.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();
    $rol = $user->rol?->nombre;

    // 🔹 Redirección según el rol del usuario
    switch ($rol) {
        case 'administrador':
            return redirect()->route('admin.panel');

        case 'recepcionista':
            return redirect()->route('receptor.panel');

        case 'gestorInventario':
            return redirect()->route('gestor.panel');

        case 'dataEntry':
            return redirect()->route('dataentry.panel');

        case 'consultor':
            return redirect()->route('consultor.panel');

        default:
            // Si no tiene rol válido, lo mandamos al login con error
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tu rol no tiene acceso asignado al sistema.',
            ]);
    }
}

    /**
     * Cierra la sesión de usuario.
     */
public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}
}