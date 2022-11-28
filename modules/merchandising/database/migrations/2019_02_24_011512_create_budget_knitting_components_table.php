<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetKnittingComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_knitting_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('knitting_source');
            $table->unsignedInteger('knitting_supplier_id')->nullable();
            $table->text('knitting_fabric_description');
            $table->string('knitting_composition');
            $table->unsignedInteger('knitting_composition_fabric_id');
            $table->unsignedInteger('knitting_yarn_count');
            $table->float('knitting_fabric_req_qty');
            $table->float('knitting_fabric_price_per_kg');
            $table->float('knitting_total');
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
        Schema::dropIfExists('budget_knitting_components');
    }
}
