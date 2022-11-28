<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActualStartDateAndActualEndDateToTrimsInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_inventories', function (Blueprint $table) {
            $table->date('actual_start_date')->nullable()->after('tna_end_date');
            $table->date('actual_end_date')->nullable()->after('actual_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_inventories', function (Blueprint $table) {
            $table->dropColumn('actual_start_date');
            $table->dropColumn('actual_end_date');
        });
    }
}
