<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareInstructionSymboolImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('care_instruction_symbool_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trims_accessory_detail_id');
            $table->string('image', 70)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('trims_accessory_detail_id')->references('id')->on('trims_accessory_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('care_instruction_symbool_images');
    }
}
