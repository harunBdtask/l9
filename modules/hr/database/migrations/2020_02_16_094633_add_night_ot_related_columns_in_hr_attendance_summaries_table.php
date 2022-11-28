<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNightOtRelatedColumnsInHrAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_attendance_summaries', function (Blueprint $table) {
            $table->time('night_start')->nullable()->after('extra_ot_minute_next_day');
            $table->time('night_end')->nullable()->after('night_start');
            $table->time('total_night_hour')->nullable()->after('night_end');
            $table->time('approved_night_start')->nullable()->after('total_night_hour');
            $table->time('approved_night_end')->nullable()->after('approved_night_start');
            $table->time('total_approved_ot_hour')->nullable()->after('approved_night_end');
            $table->time('total_night_ot_hour')->nullable()->after('total_approved_ot_hour');
            $table->time('unapproved_night_ot_hour')->nullable()->after('total_night_ot_hour');
            $table->double('total_night_minute')->nullable()->after('unapproved_night_ot_hour');
            $table->double('total_approved_ot_minute')->nullable()->after('total_night_minute');
            $table->double('total_night_ot_minute')->nullable()->after('total_approved_ot_minute');
            $table->double('unapproved_night_ot_minute')->nullable()->after('total_night_ot_minute');
            $table->tinyInteger('night_ot_eligible_status')->nullable()->after('unapproved_night_ot_minute')->comment="1=yes,0=no";

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
                'night_start',
                'night_end',
                'total_night_hour',
                'approved_night_start',
                'approved_night_end',
                'total_approved_ot_hour',
                'total_night_ot_hour',
                'unapproved_night_ot_hour',
                'total_night_minute',
                'total_approved_ot_minute',
                'total_night_ot_minute',
                'unapproved_night_ot_minute',
                'night_ot_eligible_status',
            ]);
        });
    }
}
