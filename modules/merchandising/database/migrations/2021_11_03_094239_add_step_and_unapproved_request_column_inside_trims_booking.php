<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepAndUnapprovedRequestColumnInsideTrimsBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_bookings', function (Blueprint $table) {
            $table->string('un_approve_request')->nullable();
            $table->integer('step')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_bookings', function (Blueprint $table) {
            $table->dropColumn('un_approve_request');
            $table->dropColumn('step');
        });
    }
}
