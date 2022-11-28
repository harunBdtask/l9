<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingGoodsDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_goods_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('goods_delivery_uid')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->enum('entry_basis', [1, 2])->comment('1=Batch,2=Order');
            $table->unsignedInteger('batch_id')->nullable();
            $table->string('batch_no', 40)->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->string('order_no', 40)->nullable();
            $table->date('delivery_date')->nullable();
            $table->unsignedBigInteger('dyeing_unit_id')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->string('driver_name')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_dyeing_goods_deliveries');
    }
}
