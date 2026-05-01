<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoFieldsToProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (!Schema::hasColumn('products', 'seo_title')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('seo_title', 255)->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('products', 'meta_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('meta_description')->nullable()->after('description');
            });
        }

        if (!Schema::hasColumn('products', 'focus_keyword')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('focus_keyword', 255)->nullable()->after('meta_description');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (Schema::hasColumn('products', 'focus_keyword')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('focus_keyword');
            });
        }

        if (Schema::hasColumn('products', 'meta_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('meta_description');
            });
        }

        if (Schema::hasColumn('products', 'seo_title')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('seo_title');
            });
        }
    }
}

