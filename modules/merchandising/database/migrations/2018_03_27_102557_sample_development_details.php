<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SampleDevelopmentDetails extends Migration
{
    public function up()
    {
        Schema::create('sample_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sample_id');
            $table->unsignedInteger('item_id');
            $table->string('item_description');
            $table->string('fabric_description');
            $table->text('fabrication');
            $table->unsignedInteger('composition_fabric_id');
            $table->string('gsm');
            $table->float('unit_price');
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['sample_id', 'item_id', 'factory_id']);
            $table->foreign('sample_id')->references('id')->on('samples')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('sample_details');
    }
}
