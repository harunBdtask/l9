<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualShipmentProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_shipment_productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('production_qty')->default(0);
            $table->unsignedInteger('short_qty')->default(0);
            $table->unsignedInteger('carton_qty')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=Short Shipment, 2=Plus Shipment, 3=Partial Shipment');
            $table->string('responsible_person', 90)->nullable();
            $table->string('agent', 90)->nullable();
            $table->string('destination')->nullable();
            $table->string('vehicle_no', 50)->nullable();
            $table->string('driver', 90)->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_shipment_productions');
    }
}
