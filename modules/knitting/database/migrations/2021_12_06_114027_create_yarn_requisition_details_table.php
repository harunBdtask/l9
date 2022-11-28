<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_requisition_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('yarn_type_id')->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->unsignedInteger('yarn_composition_id')->nullable();

            $table->string('yarn_lot');
            $table->string('requisition_qty');
            $table->string('requisition_date');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('yarn_requisition_details');
    }
}
