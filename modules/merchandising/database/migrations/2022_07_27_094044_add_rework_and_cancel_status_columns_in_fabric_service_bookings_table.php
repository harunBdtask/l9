<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReworkAndCancelStatusColumnsInFabricServiceBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_service_bookings', function (Blueprint $table) {
            $table->tinyInteger('rework_status')->default(0)->after('is_approved')->comment('1 = rework enable, 0 = rework disable');
            $table->tinyInteger('cancel_status')->default(0)->after('rework_status')->comment('1 = cancel enable, 0 = cancel disable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_service_bookings', function (Blueprint $table) {
            $table->dropColumn(['rework_status', 'cancel_status']);
        });
    }
}
