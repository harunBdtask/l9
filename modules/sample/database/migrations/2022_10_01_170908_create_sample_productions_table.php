<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_processing_id')->nullable();
            $table->unsignedBigInteger('sample_order_requisition_id')->nullable();
            $table->date('production_date')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->json('details')->nullable();
            $table->json('total_calculation')->nullable();
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
        Schema::dropIfExists('sample_productions');
    }
}
