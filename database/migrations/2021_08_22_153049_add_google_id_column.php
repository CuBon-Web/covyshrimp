<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer', 'google_id')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->string('google_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('customer', 'google_id')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->dropColumn('google_id');
            });
        }
    }
}
