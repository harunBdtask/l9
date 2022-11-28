<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForFabricBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->after('id');

            $table->unsignedInteger('color_type_id')->nullable()->after('uom_value');
            $table->string('color_type_value')->nullable()->after('color_type_id');

            $table->unsignedInteger('dia_type')->nullable()->after('color_type_value');
            $table->string('dia_type_value')->nullable()->after('dia_type');
//
//            $table->unsignedInteger('color_type_id')->nullable();
//            $table->string('color_type_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_booking_details', function (Blueprint $table) {
            $table->dropColumn('booking_id');
            $table->dropColumn('color_type_id');
            $table->dropColumn('color_type_value');
            $table->dropColumn('dia_type');
            $table->dropColumn('dia_type_value');
//            $table->dropColumn('color_type_id');
//            $table->dropColumn('color_type_value');
        });
    }
}
