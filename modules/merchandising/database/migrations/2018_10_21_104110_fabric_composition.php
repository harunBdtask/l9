<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FabricComposition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_composition', function (Blueprint $table) {
            $table->increments('id');
            $table->string('yarn_composition');
            $table->unsignedInteger('factory_id');
            $table->tinyInteger('status_active')->default('1');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fabric_composition');
    }
}
