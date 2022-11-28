<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('sales_order_no')->nullable()->comment('Auto Generated');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable()->comment('Supplier/Company');
            $table->string('dealing_merchant')->nullable(); //new
            $table->string('team_leader')->nullable(); //new
            $table->unsignedInteger('season_id')->nullable();
            $table->string('location')->nullable()->comment('Factory Location');
            $table->unsignedInteger('currency_id');
            $table->string('ship_mode', 20)->nullable();
            $table->unsignedTinyInteger('ready_to_approve')->nullable();
            $table->unsignedTinyInteger('within_group')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('fabric_composition')->nullable();
            $table->text('unapproved_request')->nullable();//new
            $table->string('booking_no')->nullable();
            $table->string('booking_type')->nullable()->comment('Main,Short,Before,After');
            $table->date('receive_date')->nullable();
            $table->string('style_name');
            $table->string('attention')->nullable();
            $table->string('remarks')->nullable();
            //common column
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
        Schema::dropIfExists('fabric_sales_orders');
    }
}
