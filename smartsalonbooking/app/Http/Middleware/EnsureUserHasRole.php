<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route to one or more user roles.
 *
 * Usage in routes/web.php:
 *   Route::middleware(['auth', 'role:admin'])->group(...)
 *   Route::middleware(['auth', 'role:admin,receptionist'])->group(...)
 *
 * Roles are stored on the users.role column - see database/migrations for
 * the enum values ('admin', 'receptionist', 'customer').
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
