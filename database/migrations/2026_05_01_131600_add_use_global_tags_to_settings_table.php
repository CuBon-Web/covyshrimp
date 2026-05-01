<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseGlobalTagsToSettingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'use_global_tags')) {
                $table->tinyInteger('use_global_tags')->default(1)->after('linkImgHeader');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'use_global_tags')) {
                $table->dropColumn('use_global_tags');
            }
        });
    }
}
