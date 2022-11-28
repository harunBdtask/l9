<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetGrayFabricComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_gray_fabric_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('gray_fabric_source');
            $table->text('gray_fabric_description');
            $table->string('gray_fabric_composition');
            $table->unsignedInteger('gray_composition_fabric_id');
            $table->unsignedInteger('gray_fabric_type');
            $table->float('gray_gsm');
            $table->float('gray_required_dia');
            $table->float('gray_fabric_required_qty');
            $table->unsignedInteger('supplier_id');
            $table->float('gray_unit_price');
            $table->float('gray_total_amount');
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('budget_gray_fabric_components');
    }
}
