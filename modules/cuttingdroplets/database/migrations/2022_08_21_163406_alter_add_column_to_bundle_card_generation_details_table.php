<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnToBundleCardGenerationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->string('booking_gsm')->nullable()
                ->after('booking_dia');

            $table->tinyInteger('cons_validation')->default('1')
                ->comment('1: Default, 2: Costing')
                ->after('booking_gsm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->dropColumn(['booking_gsm', 'cons_validation']);
        });
    }
}
