<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_production_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_production_id');
            $table->unsignedBigInteger('sample_processing_id')->nullable();
            $table->unsignedBigInteger('sample_order_requisition_id')->nullable();
            $table->unsignedBigInteger('sample_sewing_line_id')->nullable();
            $table->unsignedBigInteger('gmts_color_id')->nullable();
            $table->unsignedBigInteger('gmts_size_id')->nullable();
            $table->json('details')->nullable();
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
        Schema::dropIfExists('sample_production_details');
    }
}
