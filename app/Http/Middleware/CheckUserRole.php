<?php

namespace App\Http\Middleware;

use App\Helpers\PermissionUserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $checkPermission = PermissionUserRole::checkUserRole($roles);
        if ($checkPermission) {
            return $next($request);
        } else {
            return redirect()->to('/');
        }
    }
}
