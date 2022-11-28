<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnPurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_purchase_order_id');
            $table->unsignedInteger('requisition_id')->nullable();
            $table->unsignedInteger('requisition_details_id')->nullable();
            $table->unsignedInteger('budget_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('yarn_type_id')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('wo_no')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('style_name')->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->string('yarn_color')->nullable();
            $table->unsignedInteger('yarn_composition_id')->nullable();
            $table->string('percentage', 10)->nullable();
            $table->integer('wo_qty')->nullable();
            $table->string('rate', 40)->nullable();
            $table->string('amount', 40)->nullable();
            $table->date('delivery_start_date')->nullable();
            $table->date('delivery_end_date')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('yarn_purchase_order_details');
    }
}
