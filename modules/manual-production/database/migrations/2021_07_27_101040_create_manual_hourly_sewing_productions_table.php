<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualHourlySewingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_hourly_sewing_productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->tinyInteger('source')->default(1)->comment('1=In House, 2=Out Bound');
            $table->unsignedInteger('factory_id');
            $table->unsignedBigInteger('subcontract_factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('line_id')->nullable();
            $table->unsignedBigInteger('sub_sewing_floor_id')->nullable();
            $table->unsignedBigInteger('sub_sewing_line_id')->nullable();
            $table->unsignedInteger('production_qty')->default(0);
            $table->unsignedInteger('rejection_qty')->default(0);
            $table->unsignedInteger('alter_qty')->default(0);
            $table->string('challan_no', 40)->nullable();
            $table->string('supervisor', 80)->nullable();
            $table->tinyInteger('produced_by')->default(1)->comment('1=Salary Base Worker, 2=Piece Base worker');
            $table->tinyInteger('reporting_hour')->default(1)->comment('1=12 Hour, 2=24Hour');
            $table->text('remarks')->nullable();
            $table->tinyInteger('entry_format')->default(1)->comment('1=Summary, 2=Hourly');
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
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('factory_id');
            $table->index('production_date');
            $table->index(['floor_id', 'line_id']);
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('garments_item_id');
            $table->index('challan_no');
            $table->index(['color_id', 'size_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_hourly_sewing_productions');
    }
}
