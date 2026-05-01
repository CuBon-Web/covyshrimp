<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixUsersAndRolesIdAutoincrement extends Migration
{
    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            $this->fixTableId('users');
            $this->fixTableId('roles');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        // Keep auto increment fix.
    }

    private function fixTableId($table)
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        $maxId = (int) DB::table($table)->max('id');
        $nextId = $maxId > 0 ? $maxId + 1 : 1;
        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$nextId}");
    }
}
