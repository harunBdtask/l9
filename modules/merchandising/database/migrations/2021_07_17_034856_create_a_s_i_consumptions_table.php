<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateASIConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('asi_consumptions', function (Blueprint $table) {
//            $table->id();
//            $table->string('unique_id')->nullable();
//            $table->unsignedInteger('factory_id')->nullable();
//            $table->unsignedInteger('buyer_id')->nullable();
//            $table->unsignedInteger('season_id')->nullable();
//            $table->string('style_name')->nullable();
//            $table->date('created_date')->nullable();
//            $table->date('updated_date')->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asi_consumptions');
    }
}
