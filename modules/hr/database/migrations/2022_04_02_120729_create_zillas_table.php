<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zillas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("division_id")->nullable();
            $table->string("name")->unique();
            $table->string("name_bn")->nullable();
            $table->string("lat")->nullable();
            $table->string("long")->nullable();
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
        Schema::dropIfExists('zillas');
    }
}
