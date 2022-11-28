<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnittingProgramCollarCuffProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knitting_program_collar_cuff_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knitting_program_id');
            $table->unsignedBigInteger('knitting_program_roll_id');
            $table->unsignedBigInteger('gmt_color_id')->nullable();
            $table->string('gmt_color')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('size')->nullable();
            $table->string('program_item_size')->nullable();
            $table->string('program_qty', 35)->nullable();
            $table->string('production_qty', 35)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('knitting_program_collar_cuff_productions');
    }
}
