<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeeOfficialInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employee_official_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('designation_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('grade_id')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('punch_card_id')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('job_permanent_date')->nullable();
            $table->string('date_of_joining_bn')->nullable();
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
        Schema::dropIfExists('hr_employee_official_infos');
    }
}
