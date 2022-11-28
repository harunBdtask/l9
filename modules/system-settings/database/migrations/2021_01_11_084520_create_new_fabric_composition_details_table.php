<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewFabricCompositionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_fabric_composition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('new_fab_comp_id')->comment = "new_fabric_compositions table id";
            $table->unsignedInteger('yarn_composition_id')->nullable();
            $table->string('percentage', 30)->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->unsignedInteger('composition_type_id')->nullable();
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
        Schema::dropIfExists('new_fabric_composition_details');
    }
}
