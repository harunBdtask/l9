<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanningInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */

    public function up()
    {
        Schema::create('planning_infos', function (Blueprint $table) {
            $table->id();
            $table->string('plan_info_uid')->nullable()->comment('Auto Generate');
            $table->unsignedInteger('programmable_id');
            $table->string('programmable_type');
            $table->json('details_ids');
            $table->text('details');
            $table->string('total_qty');
            $table->json('knitting_program_ids')->nullable();
            $table->date('program_date')->nullable();
            $table->string('booking_no')->nullable(); //, , , , , ,
            $table->string('buyer_name')->nullable();
            $table->string('style_name')->nullable();
            $table->string('unique_id')->nullable()->comment('order job_no');
            $table->string('body_part')->nullable();
            $table->string('color_type')->nullable();
            $table->string('fabric_description')->nullable();
            $table->string('fabric_gsm', 30)->nullable();
            $table->string('fabric_dia', 30)->nullable();
            $table->string('dia_type', 30)->nullable();
            $table->string('booking_qty', 30)->nullable();
            $table->string('program_qty', 30)->nullable();
            $table->string('production_qty', 30)->nullable();
            // common column
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('planning_infos');
    }
}
