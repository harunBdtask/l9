<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualCutToInputDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_cut_to_input_deliveries', function (Blueprint $table) {
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
            $table->unsignedInteger('production_qty')->default(0);
            $table->unsignedInteger('bundle_qty')->default(0);
            $table->string('challan_no', 40)->nullable();
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
        Schema::dropIfExists('manual_cut_to_input_deliveries');
    }
}