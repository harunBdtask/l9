<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsAccessoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_accessory_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('trims_accessory_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->boolean('size_wise')->default(0)->comment('1=size wise, 0=others');
            $table->smallInteger('percentage')->nullable()->comment('size or color wise %');
            $table->string('color_hint', 40)->nullable();
            $table->string('style_description', 40)->nullable();
            $table->string('item_description', 40)->nullable();
            $table->string('vendor_code', 30)->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->string('production_batch_no', 40)->nullable();
            $table->string('care_instruction_symbol_image', 120)->nullable();
            $table->string('special_instruction', 40)->nullable();
            $table->integer('quantity')->default(0);
            $table->double('unit_price', 10, 2)->nullable();
            $table->string('remarks', 120)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('trims_accessory_details');
    }
}
