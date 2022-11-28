<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSampleBookingConfirmOrdersTableAddFabricNatureAndStyleName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_booking_confirm_orders', function (Blueprint $table) {
            $table->unsignedInteger('fabric_nature_id')->nullable();
            $table->string('style_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_booking_confirm_orders', function (Blueprint $table) {
            $table->dropColumn(['fabric_nature_id', 'style_name']);
        });
    }
}
