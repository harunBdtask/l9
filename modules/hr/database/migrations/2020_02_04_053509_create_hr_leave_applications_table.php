<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_leave_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('designation_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('employee_id');
            $table->string('applicant_name')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedTinyInteger('duration')->nullable();
            $table->date('application_date')->nullable()->comment("The date application was submitted");
            $table->date('leave_date')->nullable();
            $table->date('rejoin_date')->nullable();
            $table->string('contact_details')->nullable();
            $table->string('application_for', 20)->nullable()->comment("'in_advance', 'leave_of_absence'");
            $table->string('is_approved', 10)->nullable()->comment("'yes', 'no'");
            $table->string('code', 20)->nullable();


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
        Schema::dropIfExists('hr_leave_applications');
    }
}
