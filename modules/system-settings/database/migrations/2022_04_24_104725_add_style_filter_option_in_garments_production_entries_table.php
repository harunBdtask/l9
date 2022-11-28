<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleFilterOptionInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('style_filter_option')->nullable()->comment("1=Style Name,2=Booking No,2=Reference No")->after('entry_type');
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
            $table->dropColumn('style_filter_option');
        });
    }
}
