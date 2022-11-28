<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSewingStartingHourInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('sewing_starting_hour')->default(8)->after('line_wise_hour_show');
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
            $table->dropColumn('sewing_starting_hour');
        });
    }
}
