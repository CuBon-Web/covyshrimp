<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\RbacPermissions;

class ExpandRbacPermissionsCrud extends Migration
{
    public function up()
    {
        $now = now();
        foreach (RbacPermissions::all() as $slug => $name) {
            $exists = DB::table('permissions')->where('slug', $slug)->first();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        // Keep RBAC baseline data.
    }
}
