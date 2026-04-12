<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini digunakan untuk membatasi akses berdasarkan role pengguna.
     * Penggunaan: ->middleware('role:admin') atau ->middleware('role:user,admin')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Jika tidak terautentikasi atau role tidak sesuai
        if (! $user || ! in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Required role: ' . implode(' or ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
