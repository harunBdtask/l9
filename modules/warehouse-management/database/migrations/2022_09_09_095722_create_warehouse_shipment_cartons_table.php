<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseShipmentCartonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_shipment_cartons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no');
            $table->unsignedInteger('warehouse_carton_id')->index();
            $table->integer('challan_status')->default(0)->comment="0=scanned,1=challan";
            $table->unsignedInteger('factory_id')->index();
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
        Schema::dropIfExists('warehouse_shipment_cartons');
    }
}
