<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsiConsumptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asi_consumption_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('asi_consumption_id')->nullable();
            $table->unsignedInteger('gmts_item_id')->nullable();
            $table->string('group_id')->nullable();
            $table->unsignedInteger('embl_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('fabrication_id')->nullable();
            $table->string('fabric_dia')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('cons_per_pcs')->nullable();
            $table->string('cons_per_dzn')->nullable();
            $table->string('efficiency')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('asi_consumption_details');
    }
}
