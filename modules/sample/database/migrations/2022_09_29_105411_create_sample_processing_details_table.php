<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleProcessingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_processing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_processing_id');
            $table->unsignedBigInteger('sample_order_requisition_id');
            $table->unsignedBigInteger('sample_id');
            $table->unsignedBigInteger('gmts_item_id');
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
        Schema::dropIfExists('sample_processing_details');
    }
}
