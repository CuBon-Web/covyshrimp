<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function list()
    {
        $this->authorizeSuperAdmin();

        $data = User::query()
            ->with('roles:id,name,slug')
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->role = 'editor';
        $user->save();
        $defaultRole = Role::where('slug', 'editor')->first();
        if ($defaultRole) {
            $user->roles()->sync([$defaultRole->id]);
        }

        return response()->json([
            'data' => $user->load('roles:id,name,slug'),
            'message' => 'Tạo tài khoản quản trị thành công'
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'role' => 'required|in:super_admin,editor,viewer'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'data' => $user->only(['id', 'name', 'email', 'role']),
            'message' => 'Cập nhật phân quyền thành công'
        ]);
    }
    public function resetPassword(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu cho tài khoản thành công'
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
