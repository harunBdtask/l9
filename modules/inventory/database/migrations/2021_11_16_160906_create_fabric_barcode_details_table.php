<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricBarcodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_barcode_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 30);
            $table->unsignedBigInteger('fabric_receive_id');
            $table->unsignedBigInteger('fabric_receive_detail_id');
            $table->string('receivable_type', 30)->nullable();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('style_id')->nullable();
            $table->string('style_name', 60)->nullable();
            $table->text('po_no')->nullable();
            $table->string('batch_no', 60)->nullable();
            $table->unsignedInteger('gmts_item_id');
            $table->unsignedBigInteger('body_part_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->string('construction');
            $table->string('fabric_description');
            $table->string('dia', 10);
            $table->string('gsm', 10);
            $table->tinyInteger('dia_type')->nullable();
            $table->integer('color_id')->nullable();
            $table->json('contrast_color_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->string('qty', 20);
            $table->string('rate', 20);
            $table->string('amount', 20);
            $table->string('fabric_shade', 5)->nullable();
            $table->string('grey_used')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('rack_id')->nullable();
            $table->integer('shelf_id')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('color_type_id')->nullable();
            $table->string('machine_name')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1 = Used, 0 = Not use');
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
        Schema::dropIfExists('fabric_barcode_details');
    }
}
