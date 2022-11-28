<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_uid')->nullable();
            $table->string('batch_no');
            $table->string('total_batch_weight')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('sub_dyeing_unit_id')->nullable();
            $table->json('sub_textile_order_ids')->nullable();
            $table->text('order_nos')->nullable();
            $table->date('batch_date')->nullable();
            $table->unsignedBigInteger('color_range_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->unsignedBigInteger('fabric_type_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->tinyInteger('dia_type_id')->nullable()->comment('1=Open,2=Tubular,3=Needle Open');
            $table->string('gsm')->nullable();
            $table->text('material_description')
                ->nullable()
                ->comment('fabric_composition + fabric_type +color + ld_no + color_type + finish_dia + dia_type + gsm');
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->string('fabric_color')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('total_machine_capacity', 40)->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_dyeing_batches');
    }
}
