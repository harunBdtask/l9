<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleOrderFabricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_order_fabrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_order_requisition_id');
            $table->unsignedBigInteger('fabric_nature_id')->nullable();
            $table->unsignedBigInteger('fabric_source_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->date('delivery_date')->nullable();
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
        Schema::dropIfExists('sample_order_fabrics');
    }
}
