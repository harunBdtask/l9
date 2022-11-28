<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrHolidayAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_holiday_attendance_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('userid');
            $table->date('date');
            $table->time('att_in')->nullable();
            $table->time('att_out')->nullable();
            $table->time('total_work_hour')->nullable();
            $table->double('total_work_minute')->nullable();
            $table->time('approved_start')->nullable();
            $table->time('approved_end')->nullable();
            $table->time('approved_hour')->nullable();
            $table->double('approved_minute')->nullable();
            $table->time('total_approved_work_hour')->nullable();
            $table->double('total_approved_work_minute')->nullable();
            $table->time('total_unapproved_work_hour')->nullable();
            $table->double('total_unapproved_work_minute')->nullable();
            $table->tinyInteger('ot_eligible_status')->default(0)->comment="1=yes,0=no";
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_holiday_attendance_summaries');
    }
}
