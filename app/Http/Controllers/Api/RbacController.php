<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\Support\RbacPermissions;
use App\User;
use Illuminate\Http\Request;

class RbacController extends Controller
{
    private $fixedSuperAdminUserId = 1;
    private $superAdminRoleSlug = 'super-admin';
    public function permissions()
    {
        $this->authorizeSuperAdmin();
        $slugs = array_keys(RbacPermissions::all());
        $data = Permission::whereIn('slug', $slugs)->orderBy('slug', 'asc')->get();
        return response()->json(['data' => $data, 'message' => 'success']);
    }

    public function createPermission(Request $request)
    {
        return response()->json([
            'message' => 'Permission được cố định theo hệ thống, bạn chỉ tạo Role và gán Permission.'
        ], 422);
    }

    public function roles()
    {
        $this->authorizeSuperAdmin();
        $data = Role::with('permissions:id,name,slug')->orderBy('id', 'desc')->get();
        return response()->json(['data' => $data, 'message' => 'success']);
    }

    public function createRole(Request $request)
    {
        $this->authorizeSuperAdmin();
        $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:120|unique:roles,slug',
            'permission_ids' => 'array'
        ]);

        $role = Role::create([
            'name' => trim($request->name),
            'slug' => trim($request->slug),
        ]);
        $role->permissions()->sync($request->permission_ids ?: []);

        return response()->json([
            'data' => $role->load('permissions:id,name,slug'),
            'message' => 'Tạo role thành công'
        ]);
    }

    public function updateRolePermissions(Request $request, $id)
    {
        $this->authorizeSuperAdmin();
        $request->validate([
            'permission_ids' => 'array'
        ]);

        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->permission_ids ?: []);

        return response()->json([
            'data' => $role->load('permissions:id,name,slug'),
            'message' => 'Cập nhật permission cho role thành công'
        ]);
    }

    public function adminUsers()
    {
        $this->authorizeSuperAdmin();
        $data = User::with('roles:id,name,slug')
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'email', 'created_at']);
        return response()->json(['data' => $data, 'message' => 'success']);
    }

    public function assignRolesToUser(Request $request, $userId)
    {
        $this->authorizeSuperAdmin();
        $request->validate([
            'role_ids' => 'required|array'
        ]);

        $superRole = Role::where('slug', $this->superAdminRoleSlug)->first();
        $superRoleId = $superRole ? (int) $superRole->id : 0;
        $requestRoleIds = collect($request->role_ids)->map(function ($id) {
            return (int) $id;
        })->values()->all();

        if ((int) $userId === (int) $this->fixedSuperAdminUserId) {
            return response()->json([
                'message' => 'Tài khoản Super Admin cố định, không thể thay đổi role'
            ], 422);
        }

        if ($superRoleId > 0 && in_array($superRoleId, $requestRoleIds, true)) {
            return response()->json([
                'message' => 'Chỉ tài khoản Super Admin cố định mới được giữ role Super Admin'
            ], 422);
        }

        $user = User::findOrFail($userId);
        $user->roles()->sync($requestRoleIds);

        return response()->json([
            'data' => $user->load('roles:id,name,slug'),
            'message' => 'Gán role cho tài khoản thành công'
        ]);
    }

    private function authorizeSuperAdmin()
    {
        $user = auth()->user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        $isLegacyRoot = isset($user->type) && (int) $user->type === 1;
        if (($user->role ?? '') !== 'super_admin' && (int) ($user->id ?? 0) !== 1 && !$isLegacyRoot) {
            abort(403, 'Bạn không có quyền truy cập');
        }
    }
}
