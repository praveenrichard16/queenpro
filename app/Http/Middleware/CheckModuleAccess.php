<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleName): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admins and admins have access to all modules
        if ($user->is_super_admin || $user->is_admin) {
            return $next($request);
        }

        // Staff users need explicit module access
        if ($user->is_staff && !$user->hasModuleAccess($moduleName)) {
            abort(403, 'You do not have access to this module.');
        }

        // Customers don't have admin module access
        if (!$user->is_staff && !$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}

