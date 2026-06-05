<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $scope = 'any'): Response
    {
        // DB-based auth
        if ($request->user()?->is_admin) {
            if ($scope === 'super' && ! $request->user()->isSuperAdmin()) {
                abort(403, 'Super Admin access only.');
            }

            return $next($request);
        }

        // Session-based auth (existing system)
        if (session('auth_role') === 'admin') {
            if ($scope === 'super' && session('auth_admin_scope') !== 'super') {
                $branchId = session('auth_branch_id');
                if ($branchId) {
                    return redirect()->route('admin.store.dashboard', $branchId)
                        ->with('error', 'Branch admins can only view their own store dashboard.');
                }
                abort(403, 'Super Admin access only.');
            }

            return $next($request);
        }

        abort(403, 'Admin access only.');
    }
}
