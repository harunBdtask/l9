<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_requisition_id');
            $table->unsignedBigInteger('sample_id');
            $table->unsignedBigInteger('gmts_item_id');
            $table->string('smv')->nullable();
            $table->json('gmts_colors_id');
            $table->unsignedInteger('required_qty');
            $table->date('submission_date');
            $table->date('delivery_date');
            $table->json('details')->nullable();
            $table->json('calculation');
            $table->string('image_path')->nullable();
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
        Schema::dropIfExists('sample_requisition_details');
    }
}
