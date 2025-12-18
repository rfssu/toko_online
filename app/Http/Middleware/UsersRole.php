<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UsersRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->status === 'off') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        if ($user->role === 'buyer') {
            return redirect()->route('home');
        }
        // Admin/seller fallback - redirect to admin products page
        return redirect()->route('barangs.index');
    }
}
