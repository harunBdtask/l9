<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_inventory_detail_id');
            $table->unsignedBigInteger('trims_store_receive_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('store_id');
            $table->date('current_date')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->text('item_description')->nullable();
            $table->string('color_id')->nullable();
            $table->string('size_id')->nullable();
            $table->string('planned_garments_qty')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('receive_qty')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('receive_return_qty')->nullable();
            $table->date('receive_return_date')->nullable();
            $table->string('excess_qty')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->string('rate')->nullable();
            $table->string('total_receive_amount')->nullable();
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
        Schema::dropIfExists('trims_store_receive_details');
    }
}
