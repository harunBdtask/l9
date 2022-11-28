<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiftStatusColumnInHrAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_attendance_summaries', function (Blueprint $table) {
            $table->tinyInteger('shift_status')->default(0)->after('ot_eligible_status')->comment="1=shift enabled, 0=no";
            $table->time('att_in')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_attendance_summaries', function (Blueprint $table) {
            $table->dropColumn('shift_status');
        });
    }
}
