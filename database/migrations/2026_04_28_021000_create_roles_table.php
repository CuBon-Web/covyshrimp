<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('roles')) {
            return;
        }
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('roles')) {
            return;
        }
        Schema::dropIfExists('roles');
    }
}
