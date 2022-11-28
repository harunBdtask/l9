<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeeSalaryInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employee_salary_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->string('gross')->nullable();
            $table->string('basic')->nullable();
            $table->string('house_rent')->nullable();
            $table->string('transport')->nullable();
            $table->string('medical')->nullable();
            $table->string('food')->nullable();
            $table->string('out_of_city')->nullable();
            $table->string('mobile_allowence')->nullable();
            $table->string('attendance_bonus')->nullable();
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
        Schema::dropIfExists('hr_employee_salary_infos');
    }
}
