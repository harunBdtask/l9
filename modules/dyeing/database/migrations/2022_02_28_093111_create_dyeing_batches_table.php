<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_batches', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('sub_dyeing_unit_id');
            $table->json('textile_orders_id')->nullable();
            $table->json('orders_no')->nullable();
            $table->date('batch_date');
            $table->unsignedInteger('color_range_id')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable();
            $table->string('total_batch_weight')->nullable();
            $table->string('total_machine_capacity')->nullable();
            $table->unsignedInteger('fabric_color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('finish_dia')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('dyeing_batches');
    }
}
