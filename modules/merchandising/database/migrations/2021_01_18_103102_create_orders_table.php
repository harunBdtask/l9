<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->string('job_no')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('price_quotation_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('style_description')->nullable();
            $table->unsignedInteger('product_category_id')->nullable();
            $table->unsignedInteger('product_dept_id')->nullable();
            $table->string('sub_dept')->nullable();
            $table->unsignedInteger('order_uom_id')->nullable();
            $table->string('smv')->nullable();
            $table->text('item_details')->nullable();
            $table->string('region')->nullable();
            $table->unsignedInteger('team_leader_id')->nullable();
            $table->unsignedInteger('dealing_merchant_id')->nullable();
            $table->unsignedInteger('factory_merchant_id')->nullable();
            $table->unsignedInteger('season_id')->nullable();
            $table->string('ship_mode')->nullable();
            $table->string('packing_ratio')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->string('repeat_no')->nullable();
            $table->unsignedInteger('buying_agent_id')->nullable();
            $table->string('quality_label')->nullable();
            $table->string('style_owner')->nullable();
            $table->string('client')->nullable();
            $table->string('remarks')->nullable();
            $table->string('images')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
