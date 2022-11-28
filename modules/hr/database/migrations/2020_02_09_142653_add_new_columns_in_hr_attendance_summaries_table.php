<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInHrAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_attendance_summaries', function (Blueprint $table) {
            $table->time('approved_ot_hour')->nullable()->after('total_work_hour');
            $table->time('unapproved_ot_hour')->nullable()->after('extra_ot_hour_same_day');

            $table->double('approved_ot_minute')->nullable()->after('total_work_minute');
            $table->double('unapproved_ot_minute')->nullable()->after('extra_ot_minute_same_day');

            $table->tinyInteger('present_status')->default(1)->after('extra_ot_hour_next_day')->comment="1=present,0=not";
            $table->tinyInteger('working_day_type')->default(1)->after('present_status')->comment="1=regular,2=weekend";
            $table->tinyInteger('ot_eligible_status')->nullable()->after('working_day_type')->comment="1=yes,0=no";
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
                'approved_ot_hour',
                'unapproved_ot_hour',
                'approved_ot_minute',
                'unapproved_ot_minute',
                'present_status',
                'working_day_type',
            ]);
        });
    }
}
