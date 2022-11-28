<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTqmSewingDhuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tqm_sewing_dhu_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tqm_sewing_dhu_id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('production_date');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->unsignedInteger('tqm_defect_id');
            $table->integer('hour_0')->default(0);
            $table->integer('hour_1')->default(0);
            $table->integer('hour_2')->default(0);
            $table->integer('hour_3')->default(0);
            $table->integer('hour_4')->default(0);
            $table->integer('hour_5')->default(0);
            $table->integer('hour_6')->default(0);
            $table->integer('hour_7')->default(0);
            $table->integer('hour_8')->default(0);
            $table->integer('hour_9')->default(0);
            $table->integer('hour_10')->default(0);
            $table->integer('hour_11')->default(0);
            $table->integer('hour_12')->default(0);
            $table->integer('hour_13')->default(0);
            $table->integer('hour_14')->default(0);
            $table->integer('hour_15')->default(0);
            $table->integer('hour_16')->default(0);
            $table->integer('hour_17')->default(0);
            $table->integer('hour_18')->default(0);
            $table->integer('hour_19')->default(0);
            $table->integer('hour_20')->default(0);
            $table->integer('hour_21')->default(0);
            $table->integer('hour_22')->default(0);
            $table->integer('hour_23')->default(0);
            $table->integer('total_defect')->default(0);
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
        Schema::dropIfExists('tqm_sewing_dhu_details');
    }
}
