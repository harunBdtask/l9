<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_id', 30)->nullable();
            $table->string('sample_stage', 20);
            $table->date('req_date');
            $table->string('style_name', 30)->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedInteger('season_id');
            $table->unsignedBigInteger('dealing_merchant_id');
            $table->unsignedBigInteger('bh_merchant_id')->nullable();
            $table->unsignedBigInteger('product_department_id')->nullable();
            $table->string('buyer_ref')->nullable();
            $table->string('agent_name')->nullable();
            $table->date('est_ship_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('internal_ref')->nullable();
            $table->string('remarks')->nullable();
            $table->string('file')->nullable();
            $table->unsignedInteger('currency')->nullable();
            $table->unsignedTinyInteger('ready_for_approve')->nullable();
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
        Schema::dropIfExists('sample_requisitions');
    }
}
