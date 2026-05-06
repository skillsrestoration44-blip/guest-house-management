<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }
        /* Super admins (any role with name=super_admin) bypass all checks. */
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $next($request);
        }
        if (method_exists($user, 'hasPermission') && $user->hasPermission($permission)) {
            return $next($request);
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'forbidden', 'message' => 'You do not have permission to perform this action.'], 403);
        }
        abort(403);
    }
}
