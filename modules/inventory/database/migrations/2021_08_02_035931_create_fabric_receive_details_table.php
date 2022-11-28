<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_receive_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 30);
            $table->unsignedBigInteger('receive_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('style_id')->nullable();
            $table->string('style_name', 60)->nullable();
            $table->string('batch_no', 60)->nullable();
            $table->unsignedInteger('gmts_item_id');
            $table->unsignedBigInteger('body_part_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->string('construction')->nullable();
            $table->string('fabric_description')->nullable();
            $table->string('dia', 10)->nullable();
            $table->string('gsm', 10)->nullable();
            $table->unsignedTinyInteger('dia_type')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->json('contrast_color_id')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('receive_qty', 20);
            $table->string('rate', 20);
            $table->string('amount', 20);
            $table->string('reject_qty', 20)->nullable();
            $table->string('fabric_shade', 5)->nullable();
            $table->string('no_of_roll', 5)->nullable();
            $table->string('grey_used')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('rack_id')->nullable();
            $table->unsignedInteger('shelf_id')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedTinyInteger('created_by')->nullable();
            $table->unsignedTinyInteger('updated_by')->nullable();
            $table->unsignedTinyInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('fabric_receive_details');
    }
}
