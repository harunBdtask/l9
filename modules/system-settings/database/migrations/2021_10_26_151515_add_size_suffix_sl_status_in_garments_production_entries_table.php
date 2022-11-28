<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeSuffixSlStatusInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('size_suffix_sl_status')->after('bundle_card_serial')->default(0)->comment("0=No,1=Yes");
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
            $table->dropColumn('size_suffix_sl_status');
        });
    }
}
