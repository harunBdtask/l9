<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualPolyPackingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_poly_packing_productions', function (Blueprint $table) {
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
            $table->unsignedInteger('finishing_floor_id')->nullable();
            $table->unsignedInteger('finishing_table_id')->nullable();
            $table->unsignedInteger('sub_finishing_floor_id')->nullable();
            $table->unsignedInteger('sub_finishing_table_id')->nullable();
            $table->unsignedInteger('production_qty')->default(0);
            $table->unsignedInteger('alter_qty')->default(0);
            $table->unsignedInteger('rejection_qty')->default(0);
            $table->unsignedInteger('carton_qty')->default(0);
            $table->tinyInteger('produced_by')->default(1)->comment('1=Salary Base Worker, 2=Piece Base worker');
            $table->tinyInteger('reporting_hour')->default(1)->comment('1=12 Hour, 2=24Hour');
            $table->string('challan_no', 40)->nullable();
            $table->string('supervisor', 80)->nullable();
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
        Schema::dropIfExists('manual_poly_packing_productions');
    }
}
