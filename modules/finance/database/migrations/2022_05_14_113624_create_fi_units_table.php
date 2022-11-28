<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fi_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->json('user_ids')->nullable();
            $table->unsignedBigInteger('fi_project_id');
            $table->string('unit');
            $table->string('unit_head_name', 60)->nullable();
            $table->string('phone_no', 60)->nullable();
            $table->string('email', 60)->nullable();
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
        Schema::dropIfExists('fi_units');
    }
}
