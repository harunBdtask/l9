<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedInteger('department');
            $table->unsignedInteger('section')->nullable();
            $table->unsignedInteger('designation');
            $table->string('code');
            $table->string('type');
            $table->string('nid');
            $table->date('date_of_birth');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->longText('present_address')->nullable();
            $table->longText('permanent_address')->nullable();
            $table->string('physical_appearance')->nullable();
            $table->string('acne')->nullable();
            $table->string('beard')->nullable();
            $table->string('mustache')->nullable();
            $table->string('bank_info')->nullable();
            $table->string('branch')->nullable();
            $table->string('account')->nullable();
            $table->string('tin')->nullable();
            $table->string('basic_salary')->nullable();
            $table->string('transport_allowance')->nullable();
            $table->string('house_rent')->nullable();
            $table->string('medical_allowance')->nullable();
            $table->string('food_allowance')->nullable();
            $table->string('sex')->nullable();
            $table->text('photo')->nullable();
            $table->string('emergency_contact_no_bn')->nullable();
            $table->string('mobile_no')->nullable();
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
        Schema::dropIfExists('hr_employees');
    }
}
