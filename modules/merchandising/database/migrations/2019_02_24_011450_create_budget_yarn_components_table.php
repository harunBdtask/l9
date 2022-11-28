<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetYarnComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_yarn_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('yarn_source');
            $table->unsignedInteger('yarn_supplier_id')->nullable();
            $table->text('yarn_description');
            $table->string('yarn_composition');
            $table->unsignedInteger('yarn_composition_fabric_id');
            $table->unsignedInteger('yarn_count');
            $table->string('yarn_consumption');
            $table->float('fabric_req_qty');
            $table->float('process_loss');
            $table->float('yarn_req_qty');
            $table->float('price_per_kg');
            $table->float('total');
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
        Schema::dropIfExists('budget_yarn_components');
    }
}
