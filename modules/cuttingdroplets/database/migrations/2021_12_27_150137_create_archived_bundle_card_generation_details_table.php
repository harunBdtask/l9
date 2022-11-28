<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedBundleCardGenerationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_bundle_card_generation_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sid')->nullable();
            $table->boolean('is_regenerated')->default(0);
            $table->integer('max_quantity')->nullable();
            $table->float('booking_consumption')->nullable();
            $table->float('booking_dia')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('garments_item_id')->nullable();
            $table->json('colors')->nullable();
            $table->string('cutting_no');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('cutting_table_id');
            $table->boolean('is_tube')->default(0);
            $table->unsignedInteger('part_id');
            $table->unsignedInteger('type_id');
            $table->json('lot_ranges')->nullable();
            $table->json('rolls')->nullable();
            $table->json('ratios')->nullable();
            $table->boolean('is_manual')->default(0);
            $table->json('po_details')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id');
            $table->tinyInteger('size_suffix_sl_status')->default(0)->comment("0=No,1=Yes");
            $table->softDeletes();
            $table->timestamps();

            $table->index('sid');
            $table->index('buyer_id');
            $table->index('order_id');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('cutting_table_id')->references('id')->on('cutting_tables')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archived_bundle_card_generation_details');
    }
}
