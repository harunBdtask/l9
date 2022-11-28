<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualCuttingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_cutting_productions', function (Blueprint $table) {
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
            $table->unsignedInteger('cutting_floor_id')->nullable();
            $table->unsignedInteger('cutting_table_id')->nullable();
            $table->unsignedBigInteger('sub_cutting_floor_id')->nullable();
            $table->unsignedBigInteger('sub_cutting_table_id')->nullable();
            $table->unsignedInteger('no_of_bundles')->default(0);
            $table->unsignedInteger('production_qty')->default(0);
            $table->unsignedInteger('rejection_qty')->default(0);
            $table->tinyInteger('produced_by')->default(1)->comment('1=Salary Base Worker, 2=Piece Base worker');
            $table->tinyInteger('reporting_hour')->default(1)->comment('1=12 Hour, 2=24Hour');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('factory_id');
            $table->index('production_date');
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('garments_item_id');
            $table->index(['color_id', 'size_id']);
            $table->index('cutting_floor_id');
            $table->index('cutting_table_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_cutting_productions');
    }
}
