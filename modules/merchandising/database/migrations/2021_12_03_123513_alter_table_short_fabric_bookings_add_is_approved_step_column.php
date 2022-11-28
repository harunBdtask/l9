<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableShortFabricBookingsAddIsApprovedStepColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_fabric_bookings', function (Blueprint $table) {
            $table->tinyInteger('is_approved')->nullable();
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
        Schema::table('short_fabric_bookings', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('step');
        });
    }
}
