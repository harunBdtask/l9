<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleProcessingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_processings', function (Blueprint $table) {
            $table->id();
            $table->string('process_id', 50)->nullable();
            $table->unsignedBigInteger('sample_id')->nullable();
            $table->string('requisition_id', 50)->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('ready_for_approve', 50)->nullable();
            $table->string('order_qty', 50)->nullable();
            $table->json('details')->nullable();
            $table->json('total_calculation')->nullable();
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
        Schema::dropIfExists('sample_processings');
    }
}
