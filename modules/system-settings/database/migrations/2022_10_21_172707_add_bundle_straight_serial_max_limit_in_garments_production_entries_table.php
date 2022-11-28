<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBundleStraightSerialMaxLimitInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->integer('bundle_straight_serial_max_limit')->nullable()->after('customized_sticker_serials');
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
            $table->dropColumn('bundle_straight_serial_max_limit');
        });
    }
}
