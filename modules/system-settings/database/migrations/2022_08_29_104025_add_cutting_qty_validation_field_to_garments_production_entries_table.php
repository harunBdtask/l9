<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCuttingQtyValidationFieldToGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('cutting_qty_validation')->default(0)
                ->after('scan_data_caching_time')
                ->comment('1:YES, 0:NO')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->dropColumn(['cutting_qty_validation']);
        });
    }
}
