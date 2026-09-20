<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akaunti yako imezimwa.']);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Huna ruhusa ya kufikia ukurasa huu.');
        }

        return $next($request);
    }
}