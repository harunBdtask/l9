<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFabricBookingDetailsBreakdownAddedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('remarks2')->comment('serve as remarks')->nullable();
            $table->string('pantone')->nullable();
            $table->string('yards')->nullable();
            $table->string('cuttable_dia')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->dropColumn('remarks2');
            $table->dropColumn('pantone');
            $table->dropColumn('yards');
            $table->dropColumn('cuttable_dia');
        });
    }
}
