<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanningInfoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        Schema::create('planning_info_details', function (Blueprint $table) {
            $table->id();
            $table->string('yarn_color')->nullable();
            $table->unsignedInteger('supplier_id');
            $table->string('total_qty')->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->unsignedInteger('planning_info_id')->nullable();
            $table->unsignedInteger('yarn_type_id')->nullable();
            $table->unsignedInteger('yarn_composition_id')->nullable();
            $table->unsignedInteger('composition_type_id')->nullable();
            $table->string('percentage',40)->nullable();
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
        Schema::dropIfExists('planning_info_details');
    }
}
