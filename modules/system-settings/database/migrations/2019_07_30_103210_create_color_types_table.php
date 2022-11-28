<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorTypesTable extends Migration
{
    public function up()
    {
        Schema::create('color_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('color_types');
            $table->unsignedInteger('factory_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('color_types');
    }
}
