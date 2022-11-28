<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLunchPunchTimeColumnsInHrAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_attendance_summaries', function (Blueprint $table) {
            $table->time('lunch_in')->after('status')->nullable();
            $table->time('lunch_out')->after('lunch_in')->nullable();
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
            $table->dropColumn([
                'lunch_in',
                'lunch_out',
            ]);
        });
    }
}
