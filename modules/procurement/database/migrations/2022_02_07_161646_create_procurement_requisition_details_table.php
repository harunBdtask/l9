<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('procurement_requisition_id');
            $table->date('date')->nullable();
            $table->string('item_type', 30)->nullable();
            $table->unsignedInteger('item_category_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->text('item_description')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
            $table->string('origin', 50)->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('qty')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('procurement_requisition_details');
    }
}
