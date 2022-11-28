<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMachineAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_machine_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idattendance_sheet');
            $table->string('userid', 30)->nullable();
            $table->date('date')->nullable();
            $table->string('daytype', 45)->nullable();
            $table->integer('leavetype')->default(0);
            $table->integer('sche1')->default(0);
            $table->integer('shiftNo')->default(1);
            $table->string('att_in', 5)->nullable();
            $table->string('att_break', 5)->nullable();
            $table->string('att_resume', 5)->nullable();
            $table->string('att_out', 5)->nullable();
            $table->string('att_ot', 5)->nullable();
            $table->string('att_done', 5)->nullable();
            $table->decimal('workhour', 4,2)->default(0.00);
            $table->decimal('othour', 4,2)->default(0.00);
            $table->decimal('shorthour', 4,2)->default(0.00);
            $table->decimal('in_s', 4,2)->default(0.00);
            $table->decimal('break_s', 4,2)->default(0.00);
            $table->decimal('resume_s', 4,2)->default(0.00);
            $table->decimal('out_s', 4,2)->default(0.00);
            $table->decimal('ot_s', 4,2)->default(0.00);
            $table->decimal('done_s', 4,2)->default(0.00);
            $table->integer('daytype_c')->default(0);
            $table->integer('leavetype_c')->default(0);
            $table->integer('remark_c')->default(0);
            $table->integer('sche1_c')->default(0);
            $table->integer('in_c')->default(0);
            $table->integer('break_c')->default(0);
            $table->integer('resume_c')->default(0);
            $table->integer('out_c')->default(0);
            $table->integer('ot_c')->default(0);
            $table->integer('done_c')->default(0);
            $table->integer('workhour_c')->default(0);
            $table->integer('othour_c')->default(0);
            $table->integer('shorthour_c')->default(0);
            $table->integer('in_x')->default(0);
            $table->integer('break_x')->default(0);
            $table->integer('resume_x')->default(0);
            $table->integer('out_x')->default(0);
            $table->integer('ot_x')->default(0);
            $table->integer('done_x')->default(0);
            $table->integer('remark')->default(0);
            $table->tinyInteger('hasmisspunch')->default(0);
            $table->dateTime('createdate')->default('2012-01-01 00:00:00');
            $table->timestamp('lastupdate');
            $table->decimal('diffothour', 4,2)->default(0.00);
            $table->integer('diffothour_c')->default(0);
            $table->string('in_o', 5)->nullable();
            $table->string('break_o', 5)->nullable();
            $table->string('resume_o', 5)->nullable();
            $table->string('out_o', 5)->nullable();
            $table->string('ot_o', 5)->nullable();
            $table->string('done_o', 5)->nullable();
            $table->decimal('SumWork', 7,3)->default(0.000);
            $table->decimal('SumOT', 7,3)->default(0.000);
            $table->decimal('SumDiffOT', 7,3)->default(0.000);
            $table->decimal('SumShort', 7,3)->default(0.000);
            $table->decimal('TotHr', 7,3)->default(0.000);
            $table->decimal('TotWork', 7,3)->default(0.000);
            $table->decimal('TotOT', 7,3)->default(0.000);
            $table->decimal('TotDiff', 7,3)->default(0.000);
            $table->decimal('TotDiffOT', 7,3)->default(0.000);
            $table->decimal('BankHour', 7,3)->default(0.000);
            $table->decimal('LeaveHour', 7,3)->default(0.000);
            $table->integer('SumWork_c')->default(0);
            $table->integer('SumOT_c')->default(0);
            $table->integer('SumDiffOT_c')->default(0);
            $table->integer('SumShort_c')->default(0);
            $table->integer('TotHr_c')->default(0);
            $table->integer('TotWork_c')->default(0);
            $table->integer('TotOT_c')->default(0);
            $table->integer('TotDiff_c')->default(0);
            $table->integer('TotDiffOT_c')->default(0);
            $table->integer('BankHour_c')->default(0);
            $table->integer('LeaveHour_c')->default(0);
            $table->string('desc', 200)->nullable();
            $table->integer('desc_c')->default(0);
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
        Schema::dropIfExists('hr_machine_attendances');
    }
}
