<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramCollarCuffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_program_collar_cuffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('knitting_program_id');
            $table->unsignedInteger('gmt_color_id');
            $table->string('gmt_color', 100)->nullable();
            $table->unsignedInteger('size_id');
            $table->string('size', 100);
            $table->string('booking_item_size', 100)->nullable();
            $table->string('program_item_size', 100)->nullable();
            $table->string('booking_qty', 50)->nullable();
            $table->string('excess_percentage', 35)->nullable();
            $table->string('program_qty', 35)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('knitting_program_collar_cuffs');
    }
}
