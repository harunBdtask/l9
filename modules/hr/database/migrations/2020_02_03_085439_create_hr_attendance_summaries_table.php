<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_attendance_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('userid');
            $table->date('date');
            $table->time('att_in');
            $table->string('status')->nullable();
            $table->time('att_out')->nullable();
            $table->time('total_work_hour')->nullable();
            $table->time('total_in_day_ot_hour')->nullable();
            $table->time('regular_ot_hour')->nullable();
            $table->time('extra_ot_hour_same_day')->nullable();
            $table->time('extra_ot_hour_next_day')->nullable();

            $table->double('total_work_minute')->nullable();
            $table->double('total_in_day_ot_minute')->nullable();
            $table->double('regular_ot_minute')->nullable();
            $table->double('extra_ot_minute_same_day')->nullable();
            $table->double('extra_ot_minute_next_day')->nullable();

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
        Schema::dropIfExists('hr_attendance_summaries');
    }
}
