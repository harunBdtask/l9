<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseCartonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_cartons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode_no');
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('purchase_order_id')->nullable()->index();
            $table->string('garments_qty');
            $table->tinyInteger('created_status')->default(1)->comment="0=not created, 1=created";
            $table->tinyInteger('rack_allocation_status')->default(0)->comment="0=not allocated, 1=allocated";
            $table->unsignedInteger('warehouse_floor_id')->nullable()->index();
            $table->unsignedInteger('warehouse_rack_id')->nullable()->index();
            $table->tinyInteger('shipment_status')->default(0)->comment="0=not shipped, 1=shipped";
            $table->unsignedInteger('factory_id')->index();
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
        Schema::dropIfExists('warehouse_cartons');
    }
}
