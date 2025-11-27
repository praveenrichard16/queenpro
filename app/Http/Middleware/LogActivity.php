<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and admin routes
        if (auth()->check() && $request->is('admin/*')) {
            $user = auth()->user();
            $route = $request->route()?->getName();
            $method = $request->method();

            // Skip logging for certain routes
            $skipRoutes = ['admin.dashboard', 'notifications.*'];
            if (in_array($route, $skipRoutes) || $request->is('admin/api/*')) {
                return $response;
            }

            // Determine action type based on method
            $actionType = match($method) {
                'POST' => 'create',
                'PUT', 'PATCH' => 'update',
                'DELETE' => 'delete',
                'GET' => 'view',
                default => 'action',
            };

            // Create description
            $description = $this->generateDescription($route, $method, $request);

            ActivityLog::create([
                'user_id' => $user->id,
                'action_type' => $actionType,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $route,
                'method' => $method,
            ]);
        }

        return $response;
    }

    private function generateDescription(string $route, string $method, Request $request): string
    {
        if (str_contains($route, '.store')) {
            return 'Created new record';
        } elseif (str_contains($route, '.update')) {
            return 'Updated record';
        } elseif (str_contains($route, '.destroy')) {
            return 'Deleted record';
        } elseif (str_contains($route, '.index')) {
            return 'Viewed list';
        } elseif (str_contains($route, '.show') || str_contains($route, '.edit')) {
            return 'Viewed details';
        }

        return ucfirst($method) . ' request to ' . $route;
    }
}
