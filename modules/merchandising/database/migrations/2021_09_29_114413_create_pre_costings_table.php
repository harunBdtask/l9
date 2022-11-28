<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreCostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_costings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('season_id');
            $table->string('style');
            $table->string('customer');
            $table->unsignedInteger('item_id')->nullable();
            $table->date('create_date')->nullable();
            $table->date('revise_date')->nullable();
            $table->string('costing_status')->nullable();
            $table->string('tp_file')->nullable();
            $table->string('costing_file')->nullable();
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
        Schema::dropIfExists('pre_costings');
    }
}
