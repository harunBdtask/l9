<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreMrrDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_mrr_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_store_mrr_id');
            $table->unsignedBigInteger('trims_store_receive_id');
            $table->unsignedBigInteger('trims_store_receive_detail_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->text('item_description')->nullable();
            $table->string('size_id')->nullable();
            $table->string('size')->nullable();
            $table->string('planned_garments_qty')->nullable();
            $table->string('approval_shade_code')->nullable();
            $table->string('actual_consumption')->nullable();
            $table->string('total_consumption')->nullable();
            $table->string('actual_qty')->nullable();
            $table->string('total_delivered_qty')->nullable();
            $table->string('rate')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('trims_store_mrr_details');
    }
}
