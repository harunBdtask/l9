<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTNASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_t_n_a_s', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 50)->nullable();
            $table->unsignedBigInteger('sample_id')->nullable();
            $table->string('requisition_id', 50)->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('control_ref_no')->nullable();
            $table->string('total_lead_time', 50)->nullable();
            $table->json('items')->nullable();
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
        Schema::dropIfExists('sample_t_n_a_s');
    }
}
