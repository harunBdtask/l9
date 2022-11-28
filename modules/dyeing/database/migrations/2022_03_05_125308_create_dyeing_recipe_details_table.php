<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingRecipeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_recipe_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_recipe_id');
            $table->unsignedBigInteger('recipe_operation_id');
            $table->unsignedBigInteger('recipe_function_id');
            $table->unsignedBigInteger('item_id')->comment('From Dyes Store Library');
            $table->unsignedBigInteger('unit_of_measurement_id');
            $table->string('percentage')->nullable();
            $table->string('g_per_ltr')->nullable();
            $table->string('plus_minus')->nullable();
            $table->string('additional')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('dyeing_recipe_details');
    }
}
