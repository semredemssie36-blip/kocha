<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is deactivated. Contact Administrator.'], 403);
        }

        // super_admin always has full access
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        if (!empty($roles) && !in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Access Denied: You do not have the required permissions (' . implode(' or ', $roles) . ').',
                'user_role' => $user->role
            ], 403);
        }

        return $next($request);
    }
}
