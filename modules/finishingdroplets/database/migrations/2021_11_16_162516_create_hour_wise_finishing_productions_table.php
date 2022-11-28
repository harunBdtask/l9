<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourWiseFinishingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_wise_finishing_productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('finishing_floor_id');
            $table->unsignedInteger('finishing_table_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('po_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->string('production_type')->nullable()->comment('iron, poly, packing');
            $table->integer('hour_0')->default(0)->nullable();
            $table->integer('hour_1')->default(0)->nullable();
            $table->integer('hour_2')->default(0)->nullable();
            $table->integer('hour_3')->default(0)->nullable();
            $table->integer('hour_4')->default(0)->nullable();
            $table->integer('hour_5')->default(0)->nullable();
            $table->integer('hour_6')->default(0)->nullable();
            $table->integer('hour_7')->default(0)->nullable();
            $table->integer('hour_8')->default(0)->nullable();
            $table->integer('hour_9')->default(0)->nullable();
            $table->integer('hour_10')->default(0)->nullable();
            $table->integer('hour_11')->default(0)->nullable();
            $table->integer('hour_12')->default(0)->nullable();
            $table->integer('hour_13')->default(0)->nullable();
            $table->integer('hour_14')->default(0)->nullable();
            $table->integer('hour_15')->default(0)->nullable();
            $table->integer('hour_16')->default(0)->nullable();
            $table->integer('hour_17')->default(0)->nullable();
            $table->integer('hour_18')->default(0)->nullable();
            $table->integer('hour_19')->default(0)->nullable();
            $table->integer('hour_20')->default(0)->nullable();
            $table->integer('hour_21')->default(0)->nullable();
            $table->integer('hour_22')->default(0)->nullable();
            $table->integer('hour_23')->default(0)->nullable();
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
        Schema::dropIfExists('hour_wise_finishing_productions');
    }
}
