<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHrShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_shifts', function (Blueprint $table) {
            $table->renameColumn('time', 'start_time');
            $table->time('end_time')->after('time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_shifts', function (Blueprint $table) {
            $table->renameColumn('start_time', 'time');
            $table->dropColumn('end_time');
        });
    }
}
