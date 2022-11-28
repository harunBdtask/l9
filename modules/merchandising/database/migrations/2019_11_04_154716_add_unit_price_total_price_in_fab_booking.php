<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitPriceTotalPriceInFabBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->double('unit_price', 15, 8)->nullable();
//            $table->double('total_price', 15, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->dropColumn('unit_price');
//            $table->dropColumn('total_price');
        });
    }
}
