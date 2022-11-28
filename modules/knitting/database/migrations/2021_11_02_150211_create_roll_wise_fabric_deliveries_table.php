<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRollWiseFabricDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roll_wise_fabric_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('challan_no');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('booking_company_id');
            $table->unsignedInteger('buyer_id');
            $table->date('delivery_date')->nullable();
            $table->integer('delivery_qty')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_wise_fabric_deliveries');
    }
}
