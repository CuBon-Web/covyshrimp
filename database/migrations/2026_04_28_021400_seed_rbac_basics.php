<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\RbacPermissions;

class SeedRbacBasics extends Migration
{
    public function up()
    {
        $now = now();

        $permissions = [];
        foreach (RbacPermissions::all() as $slug => $name) {
            $permissions[] = ['name' => $name, 'slug' => $slug];
        }
        foreach ($permissions as $permission) {
            $exists = DB::table('permissions')->where('slug', $permission['slug'])->first();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Editor', 'slug' => 'editor'],
            ['name' => 'Viewer', 'slug' => 'viewer'],
        ];
        $nextRoleId = (int) DB::table('roles')->max('id');
        foreach ($roles as $role) {
            $exists = DB::table('roles')->where('slug', $role['slug'])->first();
            if (!$exists) {
                $nextRoleId++;
                DB::table('roles')->insert([
                    'id' => $nextRoleId,
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $superRole = DB::table('roles')->where('slug', 'super-admin')->first();
        if ($superRole) {
            $permissionIds = DB::table('permissions')->pluck('id')->toArray();
            foreach ($permissionIds as $permissionId) {
                $exists = DB::table('role_permission')
                    ->where('role_id', $superRole->id)
                    ->where('permission_id', $permissionId)
                    ->first();
                if (!$exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $superRole->id,
                        'permission_id' => $permissionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $rootUser = DB::table('users')->where('id', 1)->first();
            if ($rootUser) {
                $exists = DB::table('user_role')->where('user_id', 1)->where('role_id', $superRole->id)->first();
                if (!$exists) {
                    DB::table('user_role')->insert([
                        'user_id' => 1,
                        'role_id' => $superRole->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        // Keep RBAC baseline data.
    }
}
