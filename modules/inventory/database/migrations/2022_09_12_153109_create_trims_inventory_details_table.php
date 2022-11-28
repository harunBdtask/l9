<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_inventory_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('store_id');
            $table->date('receive_date');
            $table->unsignedBigInteger('item_id');
            $table->text('item_description')->nullable();
            $table->string('color_id')->nullable();
            $table->string('size_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->string('approval_shade_code');
            $table->string('delivery_swatch');
            $table->string('is_color')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('excess_qty')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('is_qty')->nullable();
            $table->string('quality')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('cf_to_wah')->nullable();
            $table->string('inventory_by')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('trims_inventory_details');
    }
}
