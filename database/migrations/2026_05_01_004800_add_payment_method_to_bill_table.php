<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill', function (Blueprint $table) {
            if (!Schema::hasColumn('bill', 'payment_method')) {
                $table->string('payment_method', 20)->default('cod')->after('transport_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill', function (Blueprint $table) {
            if (Schema::hasColumn('bill', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
}
