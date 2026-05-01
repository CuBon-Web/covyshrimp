<?php

namespace App\Http\Middleware;

use Closure;

class CheckRbacPermission
{
    private function fallbackManagePermission($permission)
    {
        if (!is_string($permission) || strpos($permission, '.') === false) {
            return null;
        }
        $parts = explode('.', $permission);
        $action = array_pop($parts);
        if (!in_array($action, ['view', 'create', 'update', 'delete'], true)) {
            return null;
        }
        return implode('.', $parts) . '.manage';
    }

    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $isLegacyRoot = isset($user->type) && (int) $user->type === 1;
        if (($user->role ?? '') === 'super_admin' || (int) ($user->id ?? 0) === 1 || $isLegacyRoot) {
            return $next($request);
        }

        $user->loadMissing('roles.permissions');
        $permissionSlugs = $user->permissionSlugs();
        $manageFallback = $this->fallbackManagePermission($permission);
        if (!in_array($permission, $permissionSlugs, true) && (!$manageFallback || !in_array($manageFallback, $permissionSlugs, true))) {
            return response()->json(['message' => 'Bạn không có quyền truy cập chức năng này'], 403);
        }

        return $next($request);
    }
}
