<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsInKnitCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->string('sales_order_no')->after('knit_card_no')->nullable();
            $table->date('booking_date')->after('sales_order_no')->nullable();
            $table->string('fabric_type')->after('booking_date')->nullable();
            $table->string('season_id')->after('fabric_type')->nullable();
            $table->string('color')->after('season_id')->nullable();
            $table->string('color_id')->after('color')->nullable();
            $table->string('gsm')->after('color_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_cards', function (Blueprint $table) {
            $table->dropColumn('sales_order_no');
            $table->dropColumn('booking_date');
            $table->dropColumn('fabric_type');
            $table->dropColumn('season_id');
            $table->dropColumn('color');
            $table->dropColumn('color_id');
            $table->dropColumn('gsm');
        });
    }
}
