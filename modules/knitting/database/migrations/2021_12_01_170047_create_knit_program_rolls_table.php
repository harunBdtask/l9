<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnitProgramRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knit_program_rolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_info_id');
            $table->unsignedBigInteger('knitting_program_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->string('roll_weight')->nullable();
            $table->dateTime('production_datetime')->nullable();
            $table->string('production_pcs_total', 35)->nullable();
            $table->dateTime('qc_datetime')->nullable();
            $table->string('qc_roll_weight')->nullable();
            $table->unsignedBigInteger('qc_shift_id')->nullable();
            $table->unsignedBigInteger('qc_operator_id')->nullable();
            $table->string('qc_fabric_dia', 35)->nullable();
            $table->string('qc_length_in_yards', 35)->nullable();
            $table->string('qc_fabric_gsm', 35)->nullable();
            $table->json('qc_fault_details')->nullable();
            $table->string('qc_total_point', 35)->nullable();
            $table->string('qc_grade_point', 35)->nullable();
            $table->string('qc_fabric_grade', 35)->nullable();
            $table->tinyInteger('qc_status')->nullable()->comment("1=Pass,2=Fail,3=Hold");
            $table->string('reject_roll_weight')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knit_program_rolls');
    }
}
