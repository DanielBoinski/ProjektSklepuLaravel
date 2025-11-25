<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Obsługa żądania.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jeśli użytkownik nie jest zalogowany -> na stronę logowania
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Jeśli rola użytkownika NIE jest jedną z dozwolonych
        if (!in_array($userRole, $roles)) {
            abort(403, 'Brak uprawnień');
        }

        return $next($request);
    }
}
