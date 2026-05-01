<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\RbacPermissions;

class SyncFixedPermissionsCatalog extends Migration
{
    public function up()
    {
        $catalog = RbacPermissions::all();
        $now = now();

        foreach ($catalog as $slug => $name) {
            $exists = DB::table('permissions')->where('slug', $slug)->first();
            if ($exists) {
                DB::table('permissions')->where('id', $exists->id)->update([
                    'name' => $name,
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $validPermissionIds = DB::table('permissions')
            ->whereIn('slug', array_keys($catalog))
            ->pluck('id')
            ->toArray();

        DB::table('role_permission')
            ->whereNotIn('permission_id', $validPermissionIds)
            ->delete();

        DB::table('permissions')
            ->whereNotIn('slug', array_keys($catalog))
            ->delete();

        $superRole = DB::table('roles')->where('slug', 'super-admin')->first();
        if ($superRole) {
            foreach ($validPermissionIds as $permissionId) {
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
        }
    }

    public function down()
    {
        // Keep fixed permissions.
    }
}
