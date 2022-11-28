<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompactorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compactor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compactor_id');
            $table->unsignedBigInteger('textile_order_id')->nullable();
            $table->string('textile_order_no')->nullable();
            $table->unsignedBigInteger('textile_order_details_id')->nullable();
            $table->unsignedBigInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no')->nullable();
            $table->unsignedBigInteger('dyeing_batch_details_id')->nullable();
            $table->date('production_date');
            $table->text('fabric_description')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('req_order_qty')->nullable();
            $table->string('fin_no_of_roll')->nullable();
            $table->string('finish_qty')->nullable();
            $table->string('reject_roll_qty')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('total_cost')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compactor_details');
    }
}
