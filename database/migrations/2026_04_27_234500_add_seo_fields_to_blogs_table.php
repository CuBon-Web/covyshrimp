<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoFieldsToBlogsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        if (!Schema::hasColumn('blogs', 'seo_title')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('seo_title', 255)->nullable()->after('title');
            });
        }

        if (!Schema::hasColumn('blogs', 'meta_description')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->text('meta_description')->nullable()->after('description');
            });
        }

        if (!Schema::hasColumn('blogs', 'focus_keyword')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('focus_keyword', 255)->nullable()->after('meta_description');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        if (Schema::hasColumn('blogs', 'focus_keyword')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('focus_keyword');
            });
        }

        if (Schema::hasColumn('blogs', 'meta_description')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('meta_description');
            });
        }

        if (Schema::hasColumn('blogs', 'seo_title')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('seo_title');
            });
        }
    }
}

