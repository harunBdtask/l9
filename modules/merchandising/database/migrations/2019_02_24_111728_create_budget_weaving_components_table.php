<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetWeavingComponentsTable extends Migration
{
    public function up()
    {
        Schema::create('budget_weaving_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('woven_source');
            $table->unsignedInteger('woven_supplier_id')->nullable();
            $table->text('woven_fabric_description');
            $table->string('woven_fabric_composition');
            $table->unsignedInteger('woven_composition_fabric_id');
            $table->unsignedInteger('woven_fabric_type');
            $table->unsignedInteger('woven_gsm');
            $table->float('woven_required_dia');
            $table->float('woven_fabric_required_qty');
            $table->unsignedInteger('woven_uom');
            $table->float('woven_unit_price');
            $table->float('woven_total_amount');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_weaving_components');
    }
}
