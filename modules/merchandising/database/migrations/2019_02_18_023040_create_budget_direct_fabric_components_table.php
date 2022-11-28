<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetDirectFabricComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_direct_fabric_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->text('fabric_description');
            $table->string('fabric_composition');
            $table->unsignedInteger('composition_fabric_id');
            $table->unsignedInteger('fabric_type');
            $table->unsignedInteger('gsm');
            $table->unsignedInteger('required_dia');
            $table->float('fabric_required_qty');
            $table->unsignedInteger('supplier_id');
            $table->float('unit_price');
            $table->float('total_amount');
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
        Schema::dropIfExists('budget_direct_fabric_components');
    }
}
