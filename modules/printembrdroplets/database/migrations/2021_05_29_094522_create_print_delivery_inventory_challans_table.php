<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintDeliveryInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_delivery_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25);
            $table->smallInteger('bag')->nullable();
            $table->unsignedInteger('part_id')->nullable();
            $table->unsignedInteger('print_factory_delivery_id')->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('print_delivery_inventory_challans');
    }
}
