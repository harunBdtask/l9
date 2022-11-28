<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintEmbroideryQcInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_embroidery_qc_inventory_challans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25);
            $table->string('delivery_challan_no', 25)->nullable();
            $table->boolean('type')->default(0)->comment('0=tag, 1=challan');
            $table->boolean('operation_name')->default(0)->comment('0=print, 1=Embroidery');
            $table->boolean('delivery_status')->default(0)->comment('0=non delivered, 1=Delivered');
            $table->unsignedInteger('delivery_factory_id')->nullable();
            $table->string('remarks', 120)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('print_embroidery_qc_inventory_challans');
    }
}
