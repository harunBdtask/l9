<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleOrderFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_order_fabric_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_order_requisition_id');
            $table->unsignedBigInteger('sample_order_fabric_id');
            $table->unsignedBigInteger('body_part_id')->nullable();
            $table->json('details')->nullable();
            $table->json('calculations')->nullable();
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
        Schema::dropIfExists('sample_order_fabric_details');
    }
}
