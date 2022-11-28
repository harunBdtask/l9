<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnPurchaseRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_purchase_requisition_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requisition_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('yarn_type')->nullable();
            $table->unsignedInteger('uom')->nullable();
            $table->string('requisition_no', 30)->nullable();
            $table->string('unique_id', 30)->nullable();
            $table->string('style_name', 30)->nullable();
            $table->unsignedInteger('yarn_count')->nullable();
            $table->string('yarn_color', 30)->nullable();
            $table->string('yarn_composition')->nullable();
            $table->float('percentage')->nullable();
            $table->float('requisition_qty')->nullable();
            $table->float('rate')->nullable();
            $table->float('amount')->nullable();
            $table->date('yarn_in_house_date')->format('d.m.Y')->nullable();
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
        Schema::dropIfExists('yarn_purchase_requisition_details');
    }
}
