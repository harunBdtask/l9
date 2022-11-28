<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOtApprovalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_ot_approval_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ot_approval_id');
            $table->date('ot_date')->nullable();
            $table->time('ot_start_time')->nullable();
            $table->time('ot_end_time')->nullable();
            $table->unsignedInteger('ot_for')->nullable()->comment = '1 = general, 2 = night';
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->text('approved_by')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('hr_ot_approval_details');
    }
}
