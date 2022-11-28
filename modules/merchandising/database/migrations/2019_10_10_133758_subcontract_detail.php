<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubcontractDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcontract_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subcontract_master_id');
            $table->unsignedInteger('color_id');
            $table->float('fabric_qty')->nullable();
            $table->integer('cutting_qty')->nullable();
            $table->integer('print_qty')->nullable();
            $table->integer('output_qty')->nullable();
            $table->integer('input_qty')->nullable();
            $table->integer('finishing_qty')->nullable();
            $table->integer('rejection_qty')->nullable();
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
        Schema::dropIfExists('subcontract_details');
    }
}
