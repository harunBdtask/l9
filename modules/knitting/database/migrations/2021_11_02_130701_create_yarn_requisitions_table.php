<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_no')->comment('Auto Generated Id')->nullable();
            $table->unsignedInteger('program_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('yarn_type_id')->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->unsignedInteger('yarn_composition_id')->nullable();

            $table->string('yarn_lot');
            $table->string('requisition_qty');
            $table->string('requisition_date');
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('yarn_requisitions');
    }
}
