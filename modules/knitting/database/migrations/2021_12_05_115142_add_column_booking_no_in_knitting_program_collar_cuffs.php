<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBookingNoInKnittingProgramCollarCuffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_program_collar_cuffs', function (Blueprint $table) {
            $table->string('booking_no')->after('knitting_program_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_program_collar_cuffs', function (Blueprint $table) {
            $table->dropColumn('booking_no');
        });
    }
}
