<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_info_id');
            $table->string('booking_no')->nullable();
            $table->string('booking_type')->nullable()->comment("Main,Short,Before,After");
            $table->string('program_no')->nullable();
            $table->tinyInteger('knitting_source_id')->nullable()->comment("1= In House, 2= Subcontract");
            $table->unsignedBigInteger('color_range_id')->nullable();
            $table->tinyInteger('feeder_id')->nullable()->comment("1= Half, 2= Full");
            $table->unsignedBigInteger('knitting_party_id')->nullable();
            $table->string('knitting_party_type')->nullable()->comment("Factory / Supplier");
            $table->text('machine_nos')->nullable();
            $table->string('fabric_description')->nullable();
            $table->json('fabric_colors')->nullable();
            $table->string('finish_fabric_dia')->nullable();
            $table->string('machines_capacity')->nullable();
            $table->string('stitch_length')->nullable();
            $table->string('machine_dia')->nullable();
            $table->string('machine_gg')->nullable();
            $table->text('remarks')->nullable();
            $table->date('program_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('program_qty')->nullable();
            $table->string('production_qty')->nullable();
            $table->string('qc_pass_qty')->nullable();
            $table->tinyInteger('production_pending_status')->default(0)->comment("0=Pending,1=Done");
            $table->tinyInteger('qc_pending_status')->default(0)->comment("0=Pending,1=Done");
            $table->string('status')->nulable()->comment("Waiting, Running, Stop/ Close");
            $table->json('fleece_info')->nullable();
            $table->text('requisition_no')->nullable();
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
        Schema::dropIfExists('knitting_programs');
    }
}
