<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('permissions')) {
            return;
        }
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }
        Schema::dropIfExists('permissions');
    }
}
