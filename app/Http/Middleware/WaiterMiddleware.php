<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class WaiterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            Log::info('WaiterMiddleware: User not authenticated');
            return redirect()->route('login');
        }

        $user = auth()->user();
        Log::info('WaiterMiddleware: User role = ' . $user->role);

        // Check if user has waiter or admin role
        if (!in_array($user->role, ['waiter', 'admin'])) {
            Log::warning('WaiterMiddleware: Unauthorized access attempt by user ' . $user->id . ' with role ' . $user->role);
            abort(403, 'Unauthorized access. Waiter privileges required.');
        }

        Log::info('WaiterMiddleware: Access granted for user ' . $user->id);
        return $next($request);
    }
}
