<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDisciplineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_discipline_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('employee_id');
            $table->float('amount')->nullable();
            $table->date('deduction_month')->nullable();
            $table->date('suspended_from')->nullable();
            $table->date('suspended_to')->nullable();
            $table->date('termination_date')->nullable();
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
        Schema::dropIfExists('hr_discipline_details');
    }
}
